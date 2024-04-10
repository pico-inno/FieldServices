<?php

namespace App\Http\Controllers;

use App\Helpers\generatorHelpers;
use Illuminate\Http\Request;
use App\Models\paymentAccounts;
use App\Models\paymentsTransactions;
use App\Services\file\FileServices;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class paymentAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:Cash & Payment')->only(['index']);
        $this->middleware('canCreate:Cash & Payment')->only(['create', 'store']);
        $this->middleware('canUpdate:Cash & Payment')->only(['edit', 'update']);
        $this->middleware('canDelete:Cash & Payment')->only(['destory']);
    }
    public function index(){
        return view('App.paymentAccounts.index');
    }
    public function list(){
        $accouunts=paymentAccounts::OrderBy('id','desc')
        ->with('currency')
        ->get();
        return DataTables::of($accouunts)
        ->addColumn('checkbox',function($accouunt){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-checked="delete" value='.$accouunt->id.' />
                </div>
            ';
        })
        ->addColumn('action', function ($accouunt) {
            $viewPer = hasView('Cash & Payment');
            $editPer  = hasUpdate('Cash & Payment');
            $deletePer = hasDelete('Cash & Payment');
            $transferPer = hasTransfer('Cash & Payment');
            // $editBtn= '<a href=" ' . route('exchangeRate_edit', $accouunt->id) . ' " class="dropdown-item cursor-pointer" >Edit</a>';
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    if ($viewPer){ $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="view"   href="'.route('paymentAcc.view',$accouunt->id).'">View</a>'; }
                    if ($editPer){ $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="edit"   data-href="'.route('paymentAcc.edit',$accouunt->id).'">Edit</a>';}
                    if ($transferPer){ $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="transfer"   data-href="'.route('paymentTransaction.transfer',$accouunt->id).'">Transfer</a>';}
                    $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="deposit"   data-href="'.route('paymentTransaction.deposit',$accouunt->id).'">Deposit</a>';
                    $html.='<a class="dropdown-item cursor-pointer fw-semibold" id="withdrawl"   data-href="'.route('paymentTransaction.withdrawl',$accouunt->id).'">Withdrawl</a>';
                    if ($deletePer) {$html.='<a class="dropdown-item cursor-pointer fw-semibold" id="delete" data-id="'.$accouunt->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('paymentAcc.destory',$accouunt->id).'">Delete</a>';}
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })

        ->rawColumns(['action','currency','checkbox'])
        ->make(true);
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();
            $data=$this->getPaymentData($request);
            $payment=paymentAccounts::create($data);
            $this->createPaymentTransaction($payment);
            DB::commit();
            return redirect()->back()->with(['success'=>'successfully added']);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->back()->with(['error'=>$th->getMessage()]);

        }
    }

    public function edit($id){
        $account=paymentAccounts::where('id',$id)->first();
        return view('App.paymentAccounts.edit',compact('account'));
    }

    public function update($id,Request $request){
        try {
            DB::beginTransaction();
            $data = $this->getPaymentData($request);
            $pabeforeUpdated = paymentAccounts::where('id', $id)->first();
            $priceExcOa= $pabeforeUpdated['current_balance'] - $pabeforeUpdated['opening_amount'];
            $data['current_balance'] = $priceExcOa + $request['opening_amount'] ;
            paymentsTransactions::where('payment_account_id', $id)
            ->where('transaction_type', 'opening_amount')
            ->where('payment_type', 'debit')
            ->first()
            ->update([
                'payment_amount' => $request->opening_amount,
                'currency_id' => $request->currency_id,
            ]);
            $pabeforeUpdated->update($data);
            DB::commit();
            return redirect()->back()->with(['success' => 'successfully added']);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with(['error'=>'Something Wrong']);
            //throw $th;
        }
    }

    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                paymentAccounts::where('id',$id)->first()->delete();
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
    public function view($id){
        $account=paymentAccounts::where('id',$id)->first();
        return view('App.paymentAccounts.view',compact('account'));
    }

    private function getPaymentData($request){

        if((!$request['hasImage'] || $request['qrimage']) && $request->hasFile('qrimage')){
            $qrimage=$request->file('qrimage');
            $imageData = file_get_contents($qrimage->getRealPath());
        }else{
            $imageData=null;
        }
        $data=$request->only(
            'name',
            'account_type',
            'account_number',
            'opening_amount',
            'currency_id',
            'description');
        $data['current_balance']=$request['opening_amount'];
        $data['qrimage']=$imageData;
        return $data;
    }
    private function createPaymentTransaction($account){
        return paymentsTransactions::create([
            'payment_voucher_no'=>generatorHelpers::paymentVoucher('opening_amount'),
            'payment_date'=>now(),
            'transaction_type'=>'opening_amount',
            'payment_account_id'=>$account->id,
            'payment_type'=>'debit',
            'payment_amount'=>$account->opening_amount,
            'currency_id'=>$account->currency_id,
        ]);
    }

    public function getByCurrency($currency_id){
        $paymentAccs=paymentAccounts::where('currency_id',$currency_id)->get();
        $data=[
            'accounts'=>$paymentAccs
        ];
        return response()->json($data, 200);
    }
}
