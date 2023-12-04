<?php

namespace App\Helpers;



use App\Models\Product\UOM;
use App\Models\CurrentStockBalance;
use App\Models\paymentsTransactions;
use App\Models\resOrders;
use App\Models\sale\sales;

class generatorHelpers
{

    public static function generateBatchNo($variation_id, $prefix = "", $count = 6)
    {
        $lastCurrentStockCount = CurrentStockBalance::select('id','batch_no','variation_id')->where('variation_id',$variation_id)->OrderBy('id', 'DESC')->first()->batch_no ?? 0;

        $numberCount = "%0" . $count . "d";
        $seperator=$prefix ? '-' :'';
        $exploded=explode('-',$lastCurrentStockCount);
        $lastNo=intval(end($exploded));
        $batchNo = sprintf($prefix.$seperator. $numberCount, ($lastNo + 1));
        return $batchNo;
    }

    public static function paymentVoucher($transaction_type=''){
        if($transaction_type=='expense'){
            $prefix = getSettingValue('expense_payment_prefix');
        }else if($transaction_type == 'sale') {
            $prefix = getSettingValue('sale_payment_prefix');
        } else if ($transaction_type == 'purchase') {
            $prefix = getSettingValue('purchase_payment_prefix');
        } else if ($transaction_type == 'opening_amount') {
            $prefix = 'PM';
        }
        else{
            $prefix='PM';
        }
        $paymentCount=paymentsTransactions::orderBy('id','DESC')->first()->id ?? 0;
        $numberCount = "%0" . 6 . "d";
        $voucherNo = sprintf($prefix.'-'.$numberCount, ($paymentCount + 1));
        return $voucherNo;
    }
    public static function resOrderVoucherNo(){
        $paymentCount=resOrders::orderBy('id','DESC')->first()->id ?? 0;
        $numberCount = "%0" . 6 . "d";
        $voucherNo = sprintf('ROV-'.$numberCount, ($paymentCount + 1));
        return $voucherNo;
    }


    /**
     * generateVoucher
     *
     * @param  mixed $prefix
     * @param  mixed $uniqueCount
     * @param  mixed $voucherCount
     * @return void
     */
    public static function generateVoucher($prefix,$uniqueCount,$voucherCount=6)
    {
        $numberCount = "%0" . $voucherCount . "d";
        $prefixTxt= $prefix ? $prefix.'-' : '';
        $voucherNo = sprintf($prefixTxt. $numberCount, ($uniqueCount + 1));
        return $voucherNo;
    }
}
