<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use Illuminate\Http\Request;
use App\Models\expenseReports;
use App\Models\paymentAccounts;
use App\Helpers\generatorHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\expenseTransactions;
use App\Models\paymentsTransactions;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class expenseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        return view('App.expenseReport.index');
    }

    public function create(){
        $currencies=Currencies::get();
        return view('App.expenseReport.reportCreate',compact('currencies'));
    }
    public function edit($id){
        $expenseReport=expenseReports::where('id',$id)->first();
        $currencies=Currencies::get();
        return view('App.expenseReport.edit',compact('currencies','expenseReport'));
    }
    public function store(Request $request){
        $expenseReportData = $request->data;
        $expenseReportIdList = $request->expenseIds;
        parse_str($expenseReportData, $expenseReportDataArray);
        $expenseReportsCount=expenseReports::count();
        $expenseReport=expenseReports::create(
            [
                'expense_title'=>$expenseReportDataArray['expense_title'],
                'expense_report_no'=> expenseReportVoucherNo($expenseReportsCount),
                'expense_on'=>date_create($expenseReportDataArray['expense_on']),
                'note'=>$expenseReportDataArray['note'],
                'created_by'=>Auth::user()->id,
            ]
        );
        foreach ($expenseReportIdList as $id) {
             expenseTransactions::where('id',$id)->update([
                'expense_report_id'=>$expenseReport->id
             ]);
        }

        return response()->json([
            'status'=>'200',
            'success'=>'successfully deleted'
        ], 200);


    }
    public function update($id,Request $request){
        $expenseReport=expenseReports::where('id',$id)->update(
            [
                'expense_title'=>$request->expense_title,
                'expense_on'=>$request->expense_on,
                'note'=>$request->note,
                'created_by'=>Auth::user()->id,
            ]
        );
        return back()->with(['success'=>'successfully updated']);


    }
    public function dataForList(){
        $expenses=expenseReports::orderBy('id','DESC')->get();
        // dd($datas->toArray());
        return DataTables::of($expenses)
        ->addColumn('checkbox',function($e){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-price="'.$e->expense_amount.'" data-checked="delete" value='.$e->id.' />
                </div>
            ';
        })

        ->editColumn('reportBy',function($e){
            return $e->reportBy->username ?? '';
        })
        ->addColumn('total_expense_amount',function($e){
            $expenseTransaction=expenseTransactions::where('expense_report_id',$e->id)->sum('expense_amount');
            return $expenseTransaction;
        })
        ->addColumn('action', function ($e) {
            // $editBtn= '<a href=" ' . route('exchangeRate_edit', $e->id) . ' " class="dropdown-item cursor-pointer" >Edit</a>';
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="view"   href="'.route('expenseReport.view',$e->id).'">View & Edit</a>';
                    if($e->balance_amount>0){
                        $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="paymentCreate"   data-href="'.route('paymentTransaction.createForExpenseReport',$e->id).'">Add Payment</a>';
                    }
                    // $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="viewPayment"   data-href="'.route('paymentTransaction.viewForExpense',$e->id).'">View Payment</a>';
                    // $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="edit"   data-href="'.route('expense.edit',$e->id).'"></a>';
                    $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="delete" data-id="'.$e->id.'"  data-kt-expense-report-table="delete_row" data-href="'.route('paymentAcc.destory',$e->id).'">Delete</a>';
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->rawColumns(['checkbox','action'])
        ->make('true');


    }

    public function view($id){
        $report=expenseReports::where('id',$id)->first();
        $expenseTransactionQuery=expenseTransactions::where('expense_report_id',$id);
        $total_expense_amount=$expenseTransactionQuery->sum('expense_amount');
        $total_paid_amount=$expenseTransactionQuery->sum('paid_amount');
        $total_balance_amount=$expenseTransactionQuery->sum('balance_amount');
        $expenseTransactions=$expenseTransactionQuery->get();
        return view('App.expenseReport.view',compact(
            'report',
            'total_expense_amount',
            'total_paid_amount',
            'total_balance_amount',
            'expenseTransactions',

        ));
    }

    public function expenseData($id){
        $expenses=expenseTransactions::with('variationProduct')
        ->where('expense_report_id',$id)->orderBy('id','DESC')->get();
        return DataTables::of($expenses)
        ->addColumn('action', function ($e) {
                $html='<a class="btn btn-sm btn-light" id="view"   data-href="'.route('expense.view',$e->id).'">View</a>';
                return $html;
        })
        ->addColumn('expense_product',function($e){
            $variation_name=$e->variationProduct->toArray()['variation_template_value'] ;
            $variation_name_text=$variation_name ? '('. $variation_name['name'].')':' ';
            $finalText=$e->variationProduct->product->name.' '.$variation_name_text;
            return $finalText;
        })
        ->addColumn('remove', function ($e) {
            $html='<button type="button" class="btn btn-sm btn-light-danger btn-sm" data-id="'.$e->id.'" data-table="delete_row"><i class="fa-solid fa-trash"></i></button>';
            return $html;
        })
        ->rawColumns(['checkbox','action','remove'])
        ->make('true');
    }

    public function removeFromReport($id){
        try {
            $expense=expenseTransactions::where('id',$id)->first();
            $expenseReport=expenseReports::where('id',$expense->expense_report_id)->first();
            $total_expense_amount=$expenseReport->total_expense_amount -$expense->expense_amount;
            $balance_amount=$total_expense_amount-$expenseReport->paid_amount;

            $expenseReport->update([
                'total_expense_amount'=>$total_expense_amount,
                'balance_amount'=>$balance_amount,
            ]);
            $expense->update(
                ['expense_report_id'=>null]
            );

            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted',
                'total_expense_amount'=>$total_expense_amount,
                'balance_amount'=>$balance_amount,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }

    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                $expense=expenseTransactions::where('expense_report_id',$id)->first();
                if($expense){
                    $expense->update(
                        ['expense_report_id'=>null]
                    );
                }
                expenseReports::where('id',$id)->first()->delete();
            }
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {logger($th);
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }

    public function paidAll(Request $request){
        $paymentAccountId=$request->payment_account_id;
        $total_paid_amount=$request->total_paid_amount;
        $expense_ids=$request->expense;
        $paymentAccount=paymentAccounts::where('id',$paymentAccountId)->first();
        if($paymentAccount->current_balance < $total_paid_amount){
            return back()->with(['warning'=>'something is wrong']);
        }else{
            // dd($request->toArray());
            $paymentAccount->update([
                'current_balance'=>$paymentAccount->current_balance-$total_paid_amount
            ]);
            foreach($expense_ids as $id){
                $expense=expenseTransactions::where('id',$id)->first();
                $data=[
                    'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
                    'payment_date'=>now(),
                    'transaction_type'=>'expense',
                    'transaction_id'=>$expense->id,
                    'transaction_ref_no'=>$expense->expense_voucher_no,
                    'payment_method'=>'card',
                    'payment_account_id'=>$paymentAccountId,
                    'payment_type'=>'credit',
                    'payment_amount'=>$expense->balance_amount,
                    'currency_id'=>$expense->currency_id,
                ];
                $expense->update([
                    'paid_amount'=>$expense->expense_amount,
                    'balance_amount'=>0,
                ]);
                paymentsTransactions::create($data);
            }

        }
        return back();
    }
}
