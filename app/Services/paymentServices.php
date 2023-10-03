<?php

namespace App\Services;

use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Helpers\generatorHelpers;
use App\Models\paymentsTransactions;
use App\Models\posRegisterTransactions;

class paymentServices
{
    public function multiPayment(Array $multiPayment,$data, $sale_data){
        foreach ($multiPayment as $mp) {
            $sale_data['paid_amount'] = $mp['payment_amount'];
            $payemntTransaction = $this->makePayment($sale_data, $mp['payment_account_id'] ?? null,'sale');
            posRegisterTransactions::create([
                'register_session_id' => $data['sessionId'],
                'payment_account_id' => $mp['payment_account_id'] ?? null,
                'transaction_type' => 'sale',
                'transaction_id' => $data['saleId'],
                'transaction_amount' => $mp['payment_amount'],
                'currency_id' => $data['currency_id'],
                'payment_transaction_id' => $payemntTransaction->id ?? null,
            ]);
        }
    }

    public function makePayment($transaction, $payment_account_id,$transactionType,$increasePayment = false, $increaseAmount = 0)
    {
        $paymentAmount = $increasePayment ? $increaseAmount : $transaction->paid_amount;
        if ($paymentAmount > 0) {
            $voucherNo='';
            if($transactionType=='sale'){
                $voucherNo=$transaction->sales_voucher_no;
                $payment_type='debit';
            }elseif($transactionType == 'purchase') {
                $voucherNo = $transaction->purchase_voucher_no;
                $payment_type='credit';
            }
            $data = [
                'payment_voucher_no' => generatorHelpers::paymentVoucher('sale'),
                'payment_date' => now(),
                'transaction_type' => $transactionType,
                'transaction_id' => $transaction->id,
                'transaction_ref_no' => $voucherNo,
                'payment_method' => 'card',
                'payment_account_id' => $payment_account_id ?? null,
                'payment_type' => $payment_type,
                'payment_amount' => $paymentAmount,
                'currency_id' => $transaction->currency_id,
            ];
            $paymentTransaction = paymentsTransactions::create($data);
            if ($payment_account_id) {
                $accountInfo = paymentAccounts::where('id', $payment_account_id);
                if ($accountInfo->exists()) {
                    $currentBalanceFromDb = $accountInfo->first()->current_balance;
                    if($transactionType=='sale'){
                        $finalCurrentBalance = $currentBalanceFromDb + $paymentAmount;
                    }else {
                        $finalCurrentBalance = $currentBalanceFromDb - $paymentAmount;
                    }
                    $accountInfo->update([
                        'current_balance' => $finalCurrentBalance,
                    ]);
                }
                $suppliers = Contact::where('id', $transaction->contact_id)->first();
                if ($transaction->balance_amount > 0) {
                    if($transactionType=='sale'){
                        $suppliers_receivable = $suppliers->receivable_amount;
                        $suppliers->update([
                            'receivable_amount' => $suppliers_receivable +  $transaction->balance_amount
                        ]);
                    }else{
                        $suppliers_payable = $suppliers->payable_amount;
                        $suppliers->update([
                            'payable_amount' => $suppliers_payable + $transaction->balance_amount
                        ]);
                    }
                } else if ($transaction->balance_amount < 0) {
                    if($transactionType=='sale'){
                        $suppliers_payable = $suppliers->receivable_amount;
                        $suppliers->update([
                            'payable_amount' => $suppliers_payable + $transaction->balance_amount
                        ]);
                    }else{
                        $suppliers_receivable = $suppliers->receivable_amount;
                        $suppliers->update([
                            'receivable_amount' => $suppliers_receivable + $transaction->receivable_amount
                        ]);
                    }
                }
            }

            return $paymentTransaction;
        }
        return null;
    }

}
