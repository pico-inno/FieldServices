<?php

namespace App\Helpers;



use App\Models\Product\UOM;
use App\Models\CurrentStockBalance;
use App\Models\paymentsTransactions;

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

    public static function paymentVoucher(){
        $paymentCount=paymentsTransactions::orderBy('id','DESC')->first()->id ?? 0;
        $numberCount = "%0" . 6 . "d";
        $voucherNo = sprintf('PMV-'.$numberCount, ($paymentCount + 1));
        return $voucherNo;
    }
}
