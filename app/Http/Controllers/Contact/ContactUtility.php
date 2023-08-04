<?php
namespace App\Http\Controllers\Contact;

use App\Models\Contact\Contact;
use App\Models\sale\sales;
use App\Models\purchases\purchases;
use App\Models\paymentsTransactions;

trait ContactUtility
{
    public function getSalesAndPurchases($id)
    {
        $sales = sales::where('contact_id', $id)->get();
        $purchases = purchases::where('contact_id', $id)->get();

        $payments = [];
        if ($sales) {
            foreach ($sales as $sale) {
                $payment = paymentsTransactions::where('transaction_id', $sale->id)
                    ->where('transaction_type', 'sale')
                    ->get();

                if ($payment) {
                    $payments[] = $payment;
                }
            }
        }

        if ($purchases) {
            foreach ($purchases as $purchase) {
                $payment = paymentsTransactions::where('transaction_id', $purchase->id)
                    ->where('transaction_type', 'purchase')
                    ->get();

                if ($payment) {
                    $payments[] = $payment;
                }
            }
        }

        return compact('sales', 'purchases', 'payments');
    }
}
