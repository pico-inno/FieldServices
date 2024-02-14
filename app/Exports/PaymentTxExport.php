<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\paymentsTransactions;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentTxExport implements FromView,ShouldAutoSize
{
    public $paymentAccount;
    public function __construct($paymentAccount)
    {

        $this->paymentAccount=$paymentAccount;
    }
    public function view(): View
    {

        $paymentAccount=$this->paymentAccount;
        $id=$paymentAccount->id;
        $datas=paymentsTransactions::query()
        ->where('payment_account_id',$id)
        ->OrderBy('id','desc')
        ->with('currency','payment_account')->get();
        $beforeCreditPayment=paymentsTransactions::where('id','>',$datas[0]['id'])
                                            ->where('payment_account_id',$id)
                                            ->where('payment_type','credit')
                                            ->sum('payment_amount');
        $beforeDebitPayment=paymentsTransactions::where('id','>',$datas[0]['id'])
                                            ->where('payment_account_id',$id)
                                            ->where('payment_type','debit')
                                            ->sum('payment_amount');
        $beforeBalanceAmt= $beforeCreditPayment-$beforeDebitPayment;
        $balanceAmount=$paymentAccount->current_balance+$beforeBalanceAmt;
        return view('App.paymentTransactions.export.paymentTxExport',compact('datas','balanceAmount'));
    }
}
