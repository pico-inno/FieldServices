<?php

namespace App\Services\Stock;

use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;

class StockTransferServices
{

    public function completeStockTransfer()
    {

    }

    public function createStockTransfer()
    {

    }

    public function updateStockOutTransaction(
        $transaction_id,
        $transaction_type,
        $location_id ,
        $variation_id,
        $uom_id,
        $out_qty,
        $before_edit_qty,
        $lot_serial_no,
    )
    {

//        if ($lot_serial_no != null){
//            $out_qty = $lotDetail['lot_serial_qty'];
//            $before_edit_qty = $lotDetail['before_edit_lot_serial_qty'];
//        }else{
//            $out_qty = $stockOutDetail['out_quantity'];
//            $before_edit_qty = $stockOutDetail['before_edit_quantity'];
//        }

        if ($out_qty > $before_edit_qty){ // 10 - 7 = 3
            $out_able_qty = $out_qty - $before_edit_qty;
            $referenceInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($out_able_qty, $uom_id);
            $out_able_ref_qty = $referenceInfo['qtyByReferenceUom'];
        }elseif( $before_edit_qty > $out_qty){
            $out_able_qty = $before_edit_qty - $out_qty;
            $referenceInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($out_able_qty, $uom_id);
            $out_able_ref_qty = $referenceInfo['qtyByReferenceUom'];
        }




        if ($out_qty > $before_edit_qty) { //10 > 5


            $currentStockBalances = CurrentStockBalance::where('business_location_id', $location_id)
                ->where('variation_id', $variation_id)
                ->when($lot_serial_no != null, function ($query) use ($lot_serial_no) {
                    $query->where('lot_serial_no', $lot_serial_no);
                })
                ->where('current_quantity', '>', 0)
                ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                    return $query->orderBy('id');
                }, function ($query) {
                    return $query->orderByDesc('id');
                })
                ->get();


            foreach ($currentStockBalances as $balance) {
                if ($out_able_ref_qty == 0) {
                    break;
                }

                $balance_quantity = $balance->current_quantity;

                $lotSerialDetail = lotSerialDetails::where('current_stock_balance_id', $balance->id)
                    ->where('transaction_detail_id', $transaction_id)
                    ->where('transaction_type', $transaction_type)
                    ->first();


                if ($out_able_ref_qty >= $balance_quantity) {
                    $out_able_ref_qty -= $balance_quantity;

                    $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $stockOutDetail['uom_id'], $balance_quantity);
                    if ($lotSerialDetail) {
                        $lotSerialDetail->uom_quantity += $uom_balance_quantity;
                        $lotSerialDetail->ref_uom_quantity += $balance_quantity;
                        $lotSerialDetail->save();
                    } else {
                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockOutDetail['stockout_detail_id'],
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $balance['id'],
                            'lot_serial_numbers' => $balance['lot_serial_no'],
                            'expired_date' => $balance['expired_date'],
                            'uom_id' => $balance['ref_uom_id'],
                            'uom_quantity' => $uom_balance_quantity,
                            'ref_uom_quantity' => $balance_quantity,
                        ]);
                    }

                    $balance->decrement('current_quantity', $balance_quantity);

                } else {

                    $balance->decrement('current_quantity', $out_able_ref_qty);

                    $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $stockOutDetail['uom_id'], $out_able_ref_qty);

                    if ($lotSerialDetail) {
                        $lotSerialDetail->uom_quantity += $uom_balance_quantity;
                        $lotSerialDetail->ref_uom_quantity += $out_able_ref_qty;
                        $lotSerialDetail->save();
                    } else {
                        lotSerialDetails::create([
                            'transaction_detail_id' => $stockOutDetail['stockout_detail_id'],
                            'transaction_type' => 'stock_out',
                            'current_stock_balance_id' => $balance['id'],
                            'lot_serial_numbers' => $balance['lot_serial_no'],
                            'expired_date' => $balance['expired_date'],
                            'uom_id' => $balance['ref_uom_id'],
                            'uom_quantity' => $uom_balance_quantity,
                            'ref_uom_quantity' => $out_able_ref_qty,
                        ]);
                    }

                    $out_able_ref_qty = 0;
                }
            }

        }

        if ($before_edit_qty > $out_qty) { //10 > 3
            $lotSerialDetails = lotSerialDetails::where('transaction_detail_id', $transaction_id)
                ->where('transaction_type', $transaction_type)
                ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                    return $query->orderByDesc('current_stock_balance_id');
                }, function ($query) {
                    return $query->orderBy('current_stock_balance_id');
                })
                ->get();

            foreach ($lotSerialDetails as $lotSerialDetail) {
                if ($out_able_ref_qty == 0) {
                    break;
                }

                $balance_quantity = $lotSerialDetail->ref_uom_quantity;

                if ($out_able_ref_qty >= $balance_quantity) {//20 > 10 10 > 20
                    $out_able_ref_qty -= $balance_quantity; //10
                    $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                    $currentStockBalance->current_quantity += $balance_quantity;
                    $currentStockBalance->save();

                    $lotSerialDetail->delete();

                } else { //10 < 20
                    $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                    $currentStockBalance->current_quantity += $out_able_ref_qty;
                    $currentStockBalance->save();

                    $lotSerialDetail->uom_quantity -= $out_able_ref_qty;
                    $lotSerialDetail->save();
                }
            }
        }
    }

}
