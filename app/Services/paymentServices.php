<?php

namespace App\Services;

use App\Models\Contact\Contact;
use App\Models\paymentAccounts;
use App\Helpers\generatorHelpers;
use App\Models\paymentsTransactions;
use App\Models\posRegisterTransactions;

class paymentServices
{
    public function multiPayment(Array $multiPayment,$data){
        foreach ($multiPayment as $mp) {
            $sale_data['paid_amount'] = $mp['payment_amount'];
            $payemntTransaction = $this->makePayment($sale_data, $mp['payment_account_id'] ?? null);
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

    public function makePayment($sale, $payment_account_id, $increatePayment = false, $increaseAmount = 0)
    {
        $paymentAmount = $increatePayment ? $increaseAmount : $sale->paid_amount;
        if ($paymentAmount > 0) {
            $data = [
                'payment_voucher_no' => generatorHelpers::paymentVoucher('sale'),
                'payment_date' => now(),
                'transaction_type' => 'sale',
                'transaction_id' => $sale->id,
                'transaction_ref_no' => $sale->sales_voucher_no,
                'payment_method' => 'card',
                'payment_account_id' => $payment_account_id ?? null,
                'payment_type' => 'debit',
                'payment_amount' => $paymentAmount,
                'currency_id' => $sale->currency_id,
            ];
            $paymentTransaction = paymentsTransactions::create($data);
            if ($payment_account_id) {
                $accountInfo = paymentAccounts::where('id', $payment_account_id);
                if ($accountInfo->exists()) {
                    $currentBalanceFromDb = $accountInfo->first()->current_balance;
                    $finalCurrentBalance = $currentBalanceFromDb + $paymentAmount;
                    $accountInfo->update([
                        'current_balance' => $finalCurrentBalance,
                    ]);
                }
                $suppliers = Contact::where('id', $sale->contact_id)->first();
                if ($sale->balance_amount > 0) {
                    $suppliers_receivable = $suppliers->receivable_amount;
                    $suppliers->update([
                        'receivable_amount' => $suppliers_receivable +  $sale->balance_amount
                    ]);
                } else if ($sale->balance_amount < 0) {
                    $suppliers_payable = $suppliers->receivable_amount;
                    $suppliers->update([
                        'payable_amount' => $suppliers_payable + $sale->balance_amount
                    ]);
                }
            }

            return $paymentTransaction;
        }
        return null;
    }
}
