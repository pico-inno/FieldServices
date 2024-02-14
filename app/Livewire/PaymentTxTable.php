<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\paymentAccounts;
use App\Exports\PaymentTxExport;
use App\Jobs\ExportPaymentTxJob;
use App\Models\paymentsTransactions;
use Maatwebsite\Excel\Facades\Excel;

class PaymentTxTable extends Component
{
    use WithPagination,datatable;
    public $id;
    public function export(){

        $date=now()->format('d-M-y');
        $id=$this->id;
        $paymentAccount=paymentAccounts::where('id',$id)->first();
        $fileName='paymentTxFor_'.$paymentAccount->name.$date.'.xlsx';
        return Excel::download(new PaymentTxExport($paymentAccount), $fileName);
    }
    public function render()
    {
        $id=$this->id;
        $paymentAccount=paymentAccounts::where('id',$id)->first();
        $datas=paymentsTransactions::where('payment_account_id',$id)->OrderBy('id','desc')
        ->with('currency','payment_account')->paginate($this->perPage);
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
        return view('livewire.payment-tx-table',compact('datas','balanceAmount'));
    }
}
