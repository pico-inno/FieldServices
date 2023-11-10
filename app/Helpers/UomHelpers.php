<?php

namespace App\Helpers;



use App\Models\Product\UOM;
use App\Models\CurrentStockBalance;

class UomHelper
{
    public static function getReferenceUomInfoByCurrentUnitQty($qty, $currentUnitId)
    {
        $currentUnit = UOM::where('id', $currentUnitId)->with(['unit_category' => function ($q) {
            $q->with('uomByCategory');
        }])->first();
        $currentUnitType = $currentUnit->unit_type;
        $unitsByCategory = $currentUnit->unit_category['uomByCategory'];
        $referenceUnit = $unitsByCategory->first(function ($item) {
            return $item['unit_type'] === 'reference';
        });
        $referenceUomId = $referenceUnit->id;
        $referenceRoundedAmount = $referenceUnit->rounded_amount ?? 1;
        $referenceValue = $referenceUnit->value;
        $currentUnitValue = $currentUnit->value;
        if ($currentUnitType == "reference") {
            $result = $qty * $referenceValue;
        } elseif ($currentUnitType == "bigger") {
            $result = ($qty * $currentUnitValue);
        } elseif ($currentUnitType == 'smaller') {
            // dd(($qty)/$currentUnitValue,$qty,$currentUnitValue);
            $result = ($qty / $currentUnitValue);
        } else {
            $result = $qty;
        }
        return [
            "qtyByReferenceUom" => $result,
            "referenceUomId" => $referenceUomId,
            "qtyRelation" => $currentUnitValue
        ];
    }
    public static function changeQtyOnUom($currentUom, $newUom, $currentQty)
    {
        $currentRefQty = self::getReferenceUomInfoByCurrentUnitQty($currentQty, $currentUom)['qtyByReferenceUom'];
        // $newRefUomQty = self::getReferenceUomInfoByCurrentUnitQty(1, $newUom)['qtyByReferenceUom'];
        $newUomInfo = UOM::where('id', $newUom)->first();
        $currentUomInfo = UOM::where('id', $currentUom)->first();
        $currentUomType = $currentUomInfo->unit_type;
        $newUomType = $newUomInfo->unit_type;
        $newUomRounded = $newUomInfo->rounded_amount ?? 1;
        if ($currentUomType == "smaller" && $newUomType == "bigger") {
            $result = $currentRefQty /   $newUomInfo->value;
        } elseif ($currentUomType == "bigger" && $newUomType == "smaller") {
            $result = $currentRefQty *  $newUomInfo->value;
        } elseif ($currentUomType == "reference" && $newUomType == "bigger") {
            $result = $currentRefQty /  $newUomInfo->value;
        } elseif ($currentUomType == "reference" && $newUomType == "smaller") {
            $result = $currentRefQty *  $newUomInfo->value;
        } elseif ($currentUomType == "bigger" &&  $newUomType == "bigger") {
            $result = $currentRefQty /  $newUomInfo->value;
        } elseif ($currentUomType == "smaller" &&  $newUomType == "smaller") {
            $result = $currentRefQty *  $newUomInfo->value;
        } else {
            // dd('here');
            $result = $currentQty;
        }
        // dd($result, $currentUomType, $newUomInfo->unit_type);
        return round($result, $newUomRounded);
    }

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
}
