<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\sale\sales;
use Illuminate\Http\Request;
use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Helpers\generatorHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\expenseTransactions;
use App\Models\purchases\purchases;
use App\Models\paymentsTransactions;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Validator;

class paymentsTransactionsController extends Controller
{
    protected $currencyDp;
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->currencyDp=getSettingValue('currency_decimal_places');
    }
    public function list($id){
        $transactions=paymentsTransactions::where('payment_account_id',$id)->OrderBy('id','desc')
        ->with('currency','payment_account')
        ->get();
        return DataTables::of($transactions)
                ->editColumn('payment_type',function($transaction){
                    $html='';
                    $paymentType=$transaction->payment_type;
                    if($paymentType == 'withdrawl'){
                        $html.="<span class='badge badge-danger'>Withdrawl</span>";
                    }elseif($paymentType=='deposit'){
                        $html.="<span class='badge badge-primary'>Deposit</span>";
                    }elseif($paymentType=='debit'){
                        $html.="<span class='badge badge-success'>Debit</span>";
                    }elseif($paymentType=='credit'){
                        $html.="<span class='badge badge-danger'>Credit</span>";
                    }elseif($paymentType=='opening_amount'){
                        $html.="<span class='badge badge-info'>Opening Amount</span>";
                    }elseif($paymentType=='transfer'){
                        $html.="<span class='badge badge-secondary'>Transfer</span>";
                    }
                    return $html;
                })
                ->editColumn('payment_date',function($transaction){
                    $dateTime = DateTime::createFromFormat("Y-m-d H:i:s",$transaction->payment_date);
                    $formattedDate = $dateTime->format("m-d-Y " );
                    $formattedTime = $dateTime->format(" h:i A " );
                    return $formattedDate.'<br>'.'('.$formattedTime.')';
                })
                ->editColumn('payment_amount',function($transaction){
                    return price($transaction->payment_amount,$transaction->currency_id);
                })
                ->rawColumns(['payment_date','payment_type'])
                ->make(true);
    }



    public function withdrawl($id){
        $account=paymentAccounts::where('id',$id)->first();
        return view('App.paymentTransactions.withdrawl',compact('account'));
    }

    public function deposit($id){
        $account=paymentAccounts::where('id',$id)->first();
        return view('App.paymentTransactions.deposit',compact('account'));
    }


    public function storeWithdrawl($id,Request $request){
        $withdrawlAmount=$request->withdrawl_amount;

        $account=paymentAccounts::where('id',$id)->first();
        $currentBalance=$account->current_balance;
        Validator::make([
            'withdrawlAmount'=>$withdrawlAmount,
            'currentBalance'=>$currentBalance
        ],[
            'withdrawlAmount'=>'lte:currentBalance|required'
        ])->validate();
        paymentAccounts::where('id',$id)->update([
            'current_balance'=>$currentBalance-$withdrawlAmount
        ]);
        paymentsTransactions::create([
            'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
            'payment_date'=>now(),
            'payment_account_id'=>$account->id,
            'transaction_type'=>'withdrawl',
            'payment_type'=>'credit',
            'payment_amount'=>$withdrawlAmount,
            'currency_id'=>$account->currency_id,
        ]);
        return back()->with(['success'=>'Successfully withdrawls']);
    }


    public function depositStore($id,Request $request){
        $depositAmount=$request->deposit_amount;
        $account=paymentAccounts::where('id',$id)->first();
        $currentBalance=$account->current_balance;
        Validator::make([
            'depoist_amount'=>$depositAmount,
        ],[
            'depoist_amount'=>'required'
        ])->validate();
        paymentAccounts::where('id',$id)->update([
            'current_balance'=>$currentBalance+$depositAmount
        ]);
        paymentsTransactions::create([
            'payment_voucher_no'=>generatorHelpers::paymentVoucher(),
            'payment_date'=>now(),
            'payment_account_id'=>$account->id,
            'transaction_type'=>'deposit',
            'payment_type'=>'debit',
            'payment_amount'=>$depositAmount,
            'currency_id'=>$account->currency_id,
        ]);
        return back()->with(['success'=>'Successfully withdrawls']);
    }
    public function transferUi($id){
        $current_acc=paymentAccounts::where('id',$id)->with('currency')->first();
        $accounts=paymentAccounts::whereNot('id',$id)->with('currency')->get();
        $currencyDp=$this->currencyDp;
        return view('App.paymentTransactions.transfer',compact('current_acc','accounts','currencyDp'));
    }
    public function makeTransfer($paymentAccountId){
        $request=request();
        $rx_account_id=$request->rx_account_id;
        $tx_amount=$request->tx_amount;
        $rx_amount=$request->rx_amount;
        $this->transfer($paymentAccountId,$rx_account_id,$tx_amount,$rx_amount);
        return back()->with(['success'=>'Successfully transfer']);
    }
    public function transfer($tx_account_id,$rx_account_id,$tx_amount,$rx_amount,$status='transfer'){
        $tx_voucher_no=generatorHelpers::paymentVoucher();
        $tx=paymentAccounts::where('id',$tx_account_id);
        $tx_acc=$tx->first();
        $tx_acc_current_balance=$tx_acc->current_balance;


        $tx_left_amt=$tx_acc_current_balance-$tx_amount;
        $tx->update([
            'current_balance'=>$tx_left_amt
        ]);
        $tx_payment=paymentsTransactions::create([
            'payment_voucher_no'=>$tx_voucher_no,
            'payment_date'=>now(),
            'payment_account_id'=>$tx_account_id,
            'payment_type'=>'credit',
            'transaction_type'=>$status,
            'payment_amount'=>$tx_amount,
            'currency_id'=>$tx_acc->currency_id,
        ]);


        $rx=paymentAccounts::where('id',$rx_account_id);
        $rx_acc=$rx->first();
        $rx_acc_current_balance=$rx_acc->current_balance;



        $rx_result_amt=$rx_acc_current_balance+$rx_amount;
        $rx->update([
            'current_balance'=>$rx_result_amt
        ]);
        $rx_voucher_no=generatorHelpers::paymentVoucher();
        $tx_payment->update([
            'transaction_ref_no'=>$rx_voucher_no,
        ]);
        return paymentsTransactions::create([
            'payment_voucher_no'=>$rx_voucher_no,
            'payment_date'=>now(),
            'payment_account_id'=>$rx_account_id,
            'transaction_ref_no'=>$tx_voucher_no,
            'payment_type'=>'debit',
            'transaction_type'=>$status,
            'payment_amount'=>$tx_amount,
            'currency_id'=>$rx_acc->currency_id,
        ]);
    }

    public function createForExpense($id){
        $data=expenseTransactions::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',request()->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.create',compact('data','paymentAccounts'));
    }
    public function createForPurchase($id){
        $data=purchases::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',request()->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.purchase.create',compact('data','paymentAccounts'));
    }
    public function createForSale($id){
        $data=sales::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',request()->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.sell.create',compact('data','paymentAccounts'));
    }

    public function storeForExpense($id,Request $request){
        try {
            DB::beginTransaction();
            $expense=expenseTransactions::where('id',$id)->first();
            $paid_amount=$expense->paid_amount + $request->payment_amount;
            $balance_amount=($expense->expense_amount - $paid_amount);
            if($expense['expense_amount'] ==  $paid_amount){
                $payment_status='paid';
            }elseif($expense['expense_amount'] ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }
            $expense->update([
                'paid_amount'=>$paid_amount,
                'balance_amount'=>$balance_amount,
                'payment_status'=> $payment_status,
                'note'=>$request->note,
            ]);
            DB::commit();
            $this->makePayment($expense,$request);
            return back()->with(['success'=>'Successfully paid expense']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong!']);
        }
    }
    public function storeForPurchase($id,Request $request){
        try {
            DB::beginTransaction();
            // if($request->payment_account_id== '' || $request->payment_account_id==null){
            //     return back()->with(['warning'=>'Payment Account require!']);
            // }
            $purchase=purchases::where('id',$id)->first();
            $paid_amount=$purchase->paid_amount + $request->payment_amount;
            $balance_amount=($purchase->total_purchase_amount - $paid_amount);
            if($purchase->total_purchase_amount==  $paid_amount){
                $payment_status='paid';
            }elseif($purchase->total_purchase_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }

            $suppliers=Contact::where('id',$purchase->contact_id)->first();
            $suppliers_payable=$suppliers->payable_amount;
            $suppliers->update([
                'payable_amount'=>$suppliers_payable-$request->payment_amount
            ]);
            $purchase->update([
                'paid_amount'=>$paid_amount,
                'balance_amount'=>$balance_amount,
                'payment_status'=> $payment_status,
                'note'=>$request->note,
            ]);
            $this->makePayment($purchase,$request,'purchase');


            DB::commit();
            return back()->with(['success'=>'Successfully paid expense']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with(['warning'=>'Something Went Wrong!']);
        }
    }
    public function storeForSale($id,Request $request){
        try {
            DB::beginTransaction();
            $sale=sales::where('id',$id)->first();
            $paid_amount=$sale->paid_amount + $request->payment_amount;
            $balance_amount=($sale->total_sale_amount - $paid_amount);
            if($sale->total_sale_amount ==  $paid_amount){
                $payment_status='paid';
            }elseif($sale->total_sale_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }

            $suppliers=Contact::where('id',$sale->contact_id)->first();
            $suppliers_receivable=$suppliers->receivable_amount;
            $suppliers->update([
                'receivable_amount'=>$suppliers_receivable-$request->payment_amount
            ]);

            $sale->update([
                'paid_amount'=>$paid_amount,
                'balance_amount'=>$balance_amount,
                'payment_status'=> $payment_status,
                'note'=>$request->note,
            ]);

            $this->makePayment($sale,$request,'sale');

            DB::commit();
            return back()->with(['success'=>'Successfully paid expense']);
        } catch (\Throwable $th) {
            return back()->with(['warning'=>'Something Went Wrong!']);
            //throw $th;
        }

    }
    public function viewForExpense($id){

        $data=expenseTransactions::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::get();
        $transactions=paymentsTransactions::where('transaction_id',$id)->where('transaction_type','expense')->with('currency','payment_account')->get();
        return view('App.paymentTransactions.view',compact('data','paymentAccounts','transactions'));
    }
    public function viewForPurchase($id){
        $data=purchases::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::get();
        $transactions=paymentsTransactions::where('transaction_id',$id)->where('transaction_type','purchase')->with('currency','payment_account')->get();
        return view('App.paymentTransactions.purchase.view',compact('data','paymentAccounts','transactions'));
    }
    public function viewForSale($id){
        $data=sales::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::get();
        $transactions=paymentsTransactions::where('transaction_id',$id)->where('transaction_type','sale')->with('currency','payment_account')->get();
        return view('App.paymentTransactions.sell.view',compact('data','paymentAccounts','transactions'));
    }







    public function editForExpense($id){
        $data=paymentsTransactions::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',$data->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.edit',compact('data','paymentAccounts'));
    }
    public function editForPurchase($id){
        $data=paymentsTransactions::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',$data->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.purchase.edit',compact('data','paymentAccounts'));
    }
    public function editForSale($id){
        $data=paymentsTransactions::where('id',$id)->first();
        $paymentAccounts=paymentAccounts::where('currency_id',$data->currency_id)->with('currency')->get();
        return view('App.paymentTransactions.sell.edit',compact('data','paymentAccounts'));
    }

    public function updatetTransaction($paymentTransaciton_id,$transaction_type,Request $request){
       try {
            DB::beginTransaction();
            $data=paymentsTransactions::where('id',$paymentTransaciton_id)->first();
            if($transaction_type=='expense'){
                $transaction=expenseTransactions::where('id',$data->transaction_id)->first();
            }elseif($transaction_type == 'sale'){
                $transaction=sales::where('id',$data->transaction_id)->first();
            }elseif($transaction_type == 'purchase'){
                $transaction=purchases::where('id',$data->transaction_id)->first();
            }
            // the paymentAmount after reduce currenct transaction value  that befroe updated
            $oriPaymentAmount=($transaction->paid_amount - $data->payment_amount)+$request->payment_amount;
            if($oriPaymentAmount == $request->paid_amount){
                $payment_status='paid';
            }elseif($request->paid_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }
            if($transaction_type=='expense'){
                $transaction->update([
                    'payment_status'=>$payment_status,
                    'paid_amount'=> $oriPaymentAmount,
                    'balance_amount'=>$transaction->expense_amount- $oriPaymentAmount,
                    'note'=>$request->note,
                ]);
            }elseif($transaction_type == 'sale'){
                $suppliers=Contact::where('id',$transaction->contact_id)->first();
                $suppliers_receivable=$suppliers->receivable_amount;
                $suppliers->update([
                    'receivable_amount'=>$suppliers_receivable-($request->payment_amount-$transaction->paid_amount),
                ]);
                $transaction->update([
                    'payment_status'=>$payment_status,
                    'paid_amount'=> $oriPaymentAmount,
                    'balance_amount'=>$transaction->total_sale_amount-$oriPaymentAmount,
                    'note'=>$request->note,
                ]);
            }elseif($transaction_type == 'purchase'){
                $suppliers=Contact::where('id',$transaction->contact_id)->first();
                $suppliers_payable=$suppliers->payable_amount;
                $suppliers->update([
                    'payable_amount'=>$suppliers_payable-($request->payment_amount-$transaction->paid_amount),
                ]);
                $transaction->update([
                    'payment_status'=>$payment_status,
                    'paid_amount'=> $oriPaymentAmount,
                    'balance_amount' => $transaction->total_purchase_amount - $oriPaymentAmount,
                    'note'=>$request->note,
                ]);
            }
            // dd($transaction_type);
            $paymentAccounts=paymentAccounts::where('id',$data->payment_account_id)->first();
            if($data->payment_account_id == $request->payment_account_id){
                if($transaction_type == 'sale'){
                    $diffAmt =  $data->payment_amount -$request->payment_amount;
                }else{
                    $diffAmt = $request->payment_amount - $data->payment_amount;
                }
                $data->update([
                    'payment_amount' => $request->payment_amount
                ]);
                if($paymentAccounts){
                    $current_balance = $paymentAccounts->current_balance - $diffAmt;
                    $paymentAccounts->update([
                        'current_balance' => $current_balance,
                    ]);
                }
            }else{
                if($paymentAccounts){

                    if ($transaction_type == 'sale') {
                        $current_balance = $paymentAccounts->current_balance  -$data->payment_amount;
                    } else {
                        $current_balance = $paymentAccounts->current_balance  + $data->payment_amount;
                    }
                    $paymentAccounts->update([
                        'current_balance'=>$current_balance,
                    ]);
                }
                $this->makePayment($transaction,$request,$transaction_type);
                $data->delete();
            }
            DB::commit();
            return back()->with(['success'=>'Successfully updated']);
       } catch (\Throwable $th) {
        dd($th);
            DB::rollBack();
            return back()->with(['success' => 'Something Wrong']);
       }
    }






    // -------------------------------------------------- remove -----------------------------------------------------------
    public function removeForExpense($id){
        try {
            DB::beginTransaction();
            $paymentTransaction=paymentsTransactions::where('id',$id)->where('transaction_type','expense')->first();


            $expense=expenseTransactions::where('id',$paymentTransaction->transaction_id)->first();
            $paid_amount=$expense->paid_amount - $paymentTransaction->payment_amount;

            $balance_amount=($expense->expense_amount - $paid_amount);
            if($paid_amount ==  $expense->expense_amount){
                $payment_status='paid';
            }elseif($paid_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }


            $accountInfo=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb+ $paymentTransaction->payment_amount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
            $expense->update([
                    'paid_amount'=>$paid_amount,
                    'balance_amount'=>$balance_amount,
                    'payment_status'=> $payment_status
                ]);

            $paymentTransaction->delete();
            DB::commit();
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
    public function removeForPurchase($id){
        try {
            DB::beginTransaction();
            $paymentTransaction=paymentsTransactions::where('id',$id)->where('transaction_type','purchase')->first();
            $purchase=purchases::where('id',$paymentTransaction->transaction_id)->first();
            $paid_amount=$purchase->paid_amount - $paymentTransaction->payment_amount;

            $balance_amount=($purchase->total_purchase_amount - $paid_amount);
            if($paid_amount ==  $purchase->total_purchase_amount){
                $payment_status='paid';
            }elseif($paid_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }


            $accountInfo=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb+ $paymentTransaction->payment_amount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
            $purchase->update([
                    'paid_amount'=>$paid_amount,
                    'balance_amount'=>$balance_amount,
                    'payment_status'=> $payment_status
                ]);

            $paymentTransaction->delete();
            DB::commit();
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {
            logger($th);
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }
    public function removeForSale($id){
        try {
            DB::beginTransaction();
            $paymentTransaction=paymentsTransactions::where('id',$id)->where('transaction_type','sale')->first();


            $sale=sales::where('id',$paymentTransaction->transaction_id)->first();
            $paid_amount=$sale->paid_amount - $paymentTransaction->payment_amount;

            $balance_amount=($sale->total_sale_amount - $paid_amount);
            if($paid_amount ==  $sale->total_sale_amount){
                $payment_status='paid';
            }elseif($paid_amount ==  0){
                $payment_status='due';
            }else{
                $payment_status='partial';
            }


            $accountInfo=paymentAccounts::where('id',$paymentTransaction->payment_account_id)->first();
            if($accountInfo){
                $currentBalanceFromDb=$accountInfo->current_balance ;
                $finalCurrentBalance=$currentBalanceFromDb- $paymentTransaction->payment_amount;
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
            $sale->update([
                    'paid_amount'=>$paid_amount,
                    'balance_amount'=>$balance_amount,
                    'payment_status'=> $payment_status
                ]);

            $paymentTransaction->delete();
            DB::commit();
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            logger($th);
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }














    protected function makePayment($transaction,$request,$transaction_type="expense"){
        $data=[
            'payment_voucher_no'=>generatorHelpers::paymentVoucher($transaction_type),
            'payment_date'=>now(),
            'transaction_type'=>$transaction_type,
            'transaction_id'=>$transaction->id,
            'transaction_ref_no'=>$transaction->expense_voucher_no,
            'payment_method'=>'card',
            'payment_account_id'=>$request->payment_account_id ?? null,
            'payment_type'=> $transaction_type =='sale'?'debit':'credit',
            'payment_amount'=>$request->payment_amount,
            'currency_id'=>$transaction->currency_id,
            'note'=>$request->note,
        ];
        paymentsTransactions::create($data);
        if($request->payment_account_id){
            $accountInfo=paymentAccounts::where('id',$request->payment_account_id);
            if($accountInfo->exists()){
                $currentBalanceFromDb=$accountInfo->first()->current_balance ;
                if($transaction_type=='sale'){
                    $finalCurrentBalance=$currentBalanceFromDb + $request->payment_amount;
                }else{
                    $finalCurrentBalance=$currentBalanceFromDb - $request->payment_amount;
                }
                $accountInfo->update([
                    'current_balance'=>$finalCurrentBalance,
                ]);
            }
        }
    }
}

