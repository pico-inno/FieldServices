<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Traits\Date;
use App\Models\Currencies;
use App\Models\Product\UOM;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\expenseReports;
use App\Models\paymentAccounts;
use App\Models\Product\Generic;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Helpers\generatorHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\expenseTransactions;
use App\Models\paymentsTransactions;
use App\Models\Product\Manufacturer;
use App\Models\Product\UnitCategory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product\VariationTemplates;

class expenseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:Expense')->only(['index']);
        $this->middleware('canCreate:Expense')->only(['create', 'store']);
        $this->middleware('canUpdate:Expense')->only(['edit', 'update']);
        $this->middleware('canDelete:Expense')->only(['destory']);
    }
    public function index(){
        return view('App.expense.list');
    }
    public function create(){
        $currencies=Currencies::get();
        return view('App.expense.create',compact('currencies'));
    }
    public function expenseProduct(Request $request){
        $q = $request->q;
        $products = Product::select('id', 'name', 'product_code', 'sku', 'product_type','uom_id', 'purchase_uom_id')
        ->where('can_expense',1)
        ->where('name', 'like', '%' . $q . '%')
        ->orWhere('sku', 'like', '%' . $q . '%')
        ->with([
            'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price')
                ->with([
                    'variationTemplateValue' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            },'uom'=>function($q){
                $q->with(['unit_category'=>function($q){
                    $q->with('uomByCategory');
                }]);
            }
        ]
        )->get()->toArray();
        return response()->json($products, 200);
    }
    public function productCreate(){
        $brands = Brand::all();
        $categories = Category::with('parentCategory', 'childCategory')->get();
        $manufacturers = Manufacturer::all();
        $generics = Generic::all();
        $uoms = UOM::all();
        $variations = VariationTemplates::all();
        $unitCategories = UnitCategory::all();

        return view('App.expense.expenseProductCreate', compact('brands', 'unitCategories', 'categories', 'manufacturers', 'generics', 'uoms', 'variations'));
    }
    public function report(){
        return view('App.expense.reportList');
    }


    public function store(Request $request){
        $expenseCount=expenseTransactions::select('id')->orderBy('id','DESC')->first()->id ?? 0;
        $data=request()->only(
            'expense_product_id',
            'quantity',
            'uom_id',
            'expense_on',
            'expense_amount',
            'paid_amount',
            'currency_id',
            'expense_description',
            'note',
            'documents'
        );
        $data['expense_voucher_no']=sprintf('EV-'."%0" . 6 . "d", ($expenseCount + 1));
        $data['balance_amount']=$data['expense_amount'] - $data['paid_amount'];
        if($data['expense_amount'] ==  $data['paid_amount']){
            $data['payment_status']='paid';
        }elseif($data['expense_amount'] ==  0){
            $data['payment_status']='due';
        }else{
            $data['payment_status']='partial';
        }
        $data['created_by']=Auth::user()->id;
        $data['updated_at']=null;
        // dd($data);
        $expense=expenseTransactions::create($data);
        $this->makePayment($expense,$request->toArray());
        return back()->with(['success'=>'successfully created']);
    }


    public function edit($id){
        $currencies=Currencies::get();
        $expense=expenseTransactions::where('id',$id)->with('variationProduct')->first();
        return view('App.expense.edit',compact('currencies','expense'));
    }

    public function update($id){
        $data=request()->only(
            'expense_product_id',
            'quantity',
            'uom_id',
            'expense_amount',
            'expense_on',
            'currency_id',
            'expense_description',
            'note',
            'documents'
        );
        $expense= expenseTransactions::where('id',$id)->first();
        if($data['expense_amount'] ==  $expense->paid_amount){
            $data['payment_status']='paid';
        }elseif($data['expense_amount'] ==  0){
            $data['payment_status']='due';
        }else{
            $data['payment_status']='partial';
        }
        $data['balance_amount']=$data['expense_amount'] - $expense->paid_amount;
        $data['updated_by']=Auth::user()->id;
        $data['updated_at']=now();
        $expense->update($data);
        return redirect()->route('expense.list')->with(['success'=>'successfully updated']);
    }
    public function updateFromReport(Request $request){
        $Requestdatas=$request->editPayment;
        foreach ($Requestdatas as $req) {
            # code...
            $expense=expenseTransactions::where('id',$req['expense_id'])->first();
            if($expense->expense_amount ==  $req['paid_amount']){
                $req['payment_status']='paid';
            }elseif($req['paid_amount'] ==  0){
                $req['payment_status']='due';
            }else{
                $req['payment_status']='partial';
            }

            $paymentTransactionQuery=paymentsTransactions::where('transaction_id',$expense->id)
                                    ->where('transaction_type','expense')
                                    ->where('payment_account_id',$req['payment_account_id'])
                                    ->orderBy('id','DESC');
            if($paymentTransactionQuery->exists()){
                $paymentTransaction=$paymentTransactionQuery->first();
                $paymentAccounts=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();

                if($req['paid_amount']>$expense['paid_amount']){
                    $diffAmt=$req['paid_amount']-$expense['paid_amount'];
                    $paymentTransaction->update([
                        'payment_amount'=>$paymentTransaction->payment_amount + $diffAmt
                    ]);
                    $current_balance=$paymentAccounts->current_balance-$diffAmt;
                    $paymentAccounts->update([
                        'current_balance'=>$current_balance,
                    ]);
                }else{
                    $diffAmt=$expense['paid_amount']-$req['paid_amount'];
                    $paymentTransaction->update([
                        'payment_amount'=>$paymentTransaction->payment_amount - $diffAmt
                    ]);
                    $current_balance=$paymentAccounts->current_balance+$diffAmt;
                    $paymentAccounts->update([
                        'current_balance'=>$current_balance,
                    ]);
                }
                $expense->update([
                    'payment_status'=>$req['payment_status'],
                    'payment_account_id'=>$req['payment_account_id'],
                    'paid_amount'=>$req['paid_amount'],
                    'balance_amount'=>$expense->expense_amount-$req['paid_amount'],
                ]);
            }else{
                $paid_amount=$req['paid_amount'];
                // if($req['paid_amount']>$expense['paid_amount']){
                //     $diffAmt=$req['paid_amount']-$expense['paid_amount'];
                //     $req['paid_amount']=$diffAmt;
                //     $this->makePayment($expense,$req);
                // }elseif($req['paid_amount']<$expense['paid_amount']){
                //     $diffAmtToRemove=$expense['paid_amount']-$req['paid_amount'];
                //     $paymentTransactions=paymentsTransactions::where('transaction_id',$expense->id)
                //                             ->where('transaction_type','expense')
                //                             ->orderBy('id','DESC')->get();
                //                             dd($paymentTransactions);
                //     foreach ($paymentTransactions as $paymentTransaction) {
                //         if($paymentTransaction->payment_amount >$diffAmtToRemove){
                //                 $paymentTransaction->update([
                //                     'payment_amount'=>$paymentTransaction->payment_amount - $diffAmtToRemove
                //                 ]);
                //                 $paymentAccounts=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();
                //                 $current_balance=$paymentAccounts->current_balance+$diffAmtToRemove;
                //                 $paymentAccounts->update([
                //                     'current_balance'=>$current_balance,
                //                 ]);
                //                 $diffAmtToRemove=0;
                //                 break;
                //         }elseif($paymentTransaction->payment_amount < $diffAmtToRemove){
                //             $paymentTransaction->update([
                //                 'payment_amount'=>$paymentTransaction->payment_amount - $diffAmtToRemove
                //             ]);
                //             $paymentAccounts=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();
                //             $current_balance=$paymentAccounts->current_balance+$diffAmtToRemove;
                //             $paymentAccounts->update([
                //                 'current_balance'=>$current_balance,
                //             ]);
                //             $diffAmtToRemove=0;
                //             break;
                //         }

                //     }


                // }
                $this->makePayment($expense,$req);
                $expense->update([
                    'payment_status'=>$req['payment_status'],
                    'payment_account_id'=>$req['payment_account_id'],
                    'paid_amount'=>$paid_amount,
                    'balance_amount'=>$expense->expense_amount-$req['paid_amount'],
                ]);
            }


        }
        return redirect()->back()->with(['success'=>'successfully updated']);
    }
    public function dataForList(){



        $expenses=expenseTransactions::with('variationProduct','createdBy')->whereNull('expense_report_id')->orderBy('id','DESC')->get();
        // dd($datas->toArray());
        return DataTables::of($expenses)
        ->addColumn('checkbox',function($e){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-price="'.$e->expense_amount.'" data-c="'.$e->currency_id.'" data-checked="delete" value='.$e->id.' />
                </div>
            ';
        })
        ->addColumn('expense_product',function($e){
            $variation_name=$e->variationProduct->toArray()['variation_template_value'] ;
            $variation_name_text=$variation_name ? '('. $variation_name['name'].')':' ';
            $finalText=$e->variationProduct->product->name.' '.$variation_name_text;
            // dd($finalText);
            return $finalText;
        })
        ->editColumn('payment_status',function($e){
            if($e->payment_status=='due'){
                return '<span class="badge badge-warning">Pending</span>';
            }elseif($e->payment_status=='partial'){
                return '<span class="badge badge-primary">Partial</span>';
            }elseif($e->payment_status=='paid'){
                return '<span class="badge badge-success">Paid</span>';
            }else{
                return '-';
            }
        })
        ->editColumn('expense_amount',function($e){
            return (number_format($e->expense_amount,2) ?? number_format(0.00,2)) .' '.$e->currency->symbol;
        })
        ->editColumn('paid_amount',function($e){
            return (number_format($e->paid_amount,2) ?? number_format(0.00,2)) .' '.$e->currency->symbol;
        })
        ->editColumn('created_by',function($e){
            return $e->createdBy->username ?? '';
        })
        ->addColumn('action', function ($e) {
            $viewPer = hasView('Expense');
            $deletePer = hasDelete('Expense');
            $editPer  = hasUpdate('Expense');
            // $editBtn= '<a href=" ' . route('exchangeRate_edit', $e->id) . ' " class="dropdown-item cursor-pointer" >Edit</a>';
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    if ($viewPer){ $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="view"   data-href="'.route('expense.view',$e->id).'">View</a>'; }
                    if ($editPer) {$html.='<a class="dropdown-item cursor-pointer fw-semibold" id="edit"   data-href="'.route('expense.edit',$e->id).'">Edit</a>'; }
                    if($e->balance_amount>0){
                        $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="paymentCreate"   data-href="'.route('paymentTransaction.createForExpense',['id' => $e->id,'currency_id'=>$e->currency_id]).'">Add Payment</a>';
                    }
                    $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="viewPayment"   data-href="'.route('paymentTransaction.viewForExpense',$e->id).'">View Payment</a>';
                    // $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="edit"   data-href="'.route('expense.edit',$e->id).'"></a>';
                    if ($deletePer) {$html.='<a class="dropdown-item cursor-pointer fw-semibold" id="delete" data-id="'.$e->id.'"  data-kt-expense-table="delete_row" data-href="'.route('paymentAcc.destory',$e->id).'">Delete</a>';}
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->rawColumns(['checkbox','action','payment_status'])
        ->make('true');


    }
    public function view($id){
        $currencies=Currencies::get();
        $expense=expenseTransactions::where('id',$id)->with('variationProduct')->first();
        return view('App.expense.view',compact('currencies','expense'));
    }
    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                expenseTransactions::where('id',$id)->first()->delete();
            }
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }



    protected function makePayment($transaction,$request){
        $data=[
            'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
            'payment_date'=>now(),
            'transaction_type'=>'expense',
            'transaction_id'=>$transaction->id,
            'transaction_ref_no'=>$transaction->expense_voucher_no,
            'payment_method'=>'card',
            'payment_account_id'=>$request['payment_account_id'],
            'payment_type'=>'credit',
            'payment_amount'=>$request['paid_amount'],
            'currency_id'=>$transaction->currency_id,
        ];
        // dd($data);
        paymentsTransactions::create($data);
        $accountInfo=paymentAccounts::where('id',$request['payment_account_id']);
        if($accountInfo->exists()){
            $currentBalanceFromDb=$accountInfo->first()->current_balance ;
            $finalCurrentBalance=$currentBalanceFromDb- $request['paid_amount'];
            $accountInfo->update([
                'current_balance'=>$finalCurrentBalance,
            ]);
        }
    }
}
