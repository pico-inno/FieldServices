<?php

namespace App\Services\Stock;

use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;

class CurrentStockBalanceServices
{

    public function removeQuantityFromCsb(
        $is_prepare,
        $transaction_id,
        $transaction_type,
        $location_id ,
        $variation_id,
        $uom_id,
        $remove_ref_quantity,
        $lot_serial_no,
    )
    {
        $currentStockBalances = CurrentStockBalance::where('business_location_id', $location_id)
            ->where('variation_id', $variation_id)
            ->when($lot_serial_no != null, function ($query) use ($lot_serial_no){
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

            $balance_quantity = $balance->current_quantity;

            if ($remove_ref_quantity == 0){
                break;
            }
            if ($remove_ref_quantity >= $balance_quantity) {
                $remove_ref_quantity -= $balance_quantity;

                if ($is_prepare !== 'prepare'){
                    $balance->decrement('current_quantity', $balance_quantity);
                }


                $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $uom_id, $balance_quantity);
                lotSerialDetails::create([
                    'transaction_detail_id' => $transaction_id,
                    'transaction_type' => $transaction_type,
                    'current_stock_balance_id' => $balance['id'],
                    'lot_serial_numbers' => $balance['lot_serial_no'],
                    'expired_date' => $balance['expired_date'],
                    'uom_id' => $balance['ref_uom_id'],
                    'uom_quantity' => $uom_balance_quantity,
                    'ref_uom_quantity' => $balance_quantity,
                    'is_prepare' => $is_prepare !== 'prepare' ? false : true,
                ]);
            } else {

                if ($is_prepare !== 'prepare') {
                    $balance->decrement('current_quantity', $remove_ref_quantity);
                }
                $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $uom_id, $remove_ref_quantity);
                lotSerialDetails::create([
                    'transaction_detail_id' => $transaction_id,
                    'transaction_type' => $transaction_type,
                    'current_stock_balance_id' => $balance['id'],
                    'lot_serial_numbers' => $balance['lot_serial_no'],
                    'expired_date' => $balance['expired_date'],
                    'uom_id' => $balance['ref_uom_id'],
                    'uom_quantity' => $uom_balance_quantity,
                    'ref_uom_quantity' => $remove_ref_quantity,
                    'is_prepare' => $is_prepare !== 'prepare' ? false : true,
                ]);

                $remove_ref_quantity = 0;
            }
        }

    }

    public function duplicateCsbTransaction(
        $csb_id,
        $location_id = null,
        $transaction_id = null,
        $transaction_type = null,
        $ref_quantity = null,
        $ref_price = null,
    )
    {
        $currentStockDetail = CurrentStockBalance::where('id', $csb_id)->first();

        CurrentStockBalance::create([
            'business_location_id' => $location_id ?? $currentStockDetail->business_location_id,
            'product_id' => $currentStockDetail->product_id,
            'variation_id' => $currentStockDetail->variation_id,
            'transaction_type' => $transaction_type ?? $currentStockDetail->transaction_type,
            'transaction_detail_id' => $transaction_id ?? $currentStockDetail->transaction_detail_id,
            'expired_date' => $currentStockDetail->expired_date,
            'batch_no' => $currentStockDetail->batch_no,
            'lot_serial_no' => $currentStockDetail->lot_serial_no,
            'ref_uom_id' => $currentStockDetail->ref_uom_id,
            'ref_uom_quantity' => $ref_quantity ?? $currentStockDetail->ref_uom_quantity,
            'ref_uom_price' => $ref_price ?? $currentStockDetail->ref_uom_price,
            'current_quantity' => $ref_quantity ?? $currentStockDetail->current_quantity,
            'created_at' => now(),
        ]);
    }


    public function updateStockOutTransaction(
        $is_prepare,
        $transaction_id,
        $transaction_type,
        $location_id,
        $variation_id,
        $uom_id,
        $out_qty,
        $before_edit_qty,
        $lot_serial_no
    )
    {


        if ($out_qty > $before_edit_qty){ //2 > 1
            $out_able_qty = $out_qty - $before_edit_qty;
            $referenceInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($out_able_qty, $uom_id);
            $out_able_ref_qty = $referenceInfo['qtyByReferenceUom'];
        }elseif( $before_edit_qty > $out_qty){ //20 > 10
            $out_able_qty = $before_edit_qty - $out_qty;
            $referenceInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($out_able_qty, $uom_id);
            $out_able_ref_qty = $referenceInfo['qtyByReferenceUom'];
        }

        if ($out_qty > $before_edit_qty) {

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
                    ->when($is_prepare === 'prepare', function ($query){
                        return $query->where('is_prepare', 1);
                    })
                    ->first();


                if ($out_able_ref_qty >= $balance_quantity) {
                    $out_able_ref_qty -= $balance_quantity;

                    $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'],$uom_id, $balance_quantity);
                    if ($lotSerialDetail) {
                        $lotSerialDetail->uom_quantity += $uom_balance_quantity;
                        $lotSerialDetail->ref_uom_quantity += $balance_quantity;

                        if ($is_prepare !== 'prepare'){
                            $lotSerialDetail->is_prepare = 0;
                        }

                        $lotSerialDetail->save();
                    } else {
                        lotSerialDetails::create([
                            'transaction_detail_id' => $transaction_id,
                            'transaction_type' => $transaction_type,
                            'current_stock_balance_id' => $balance['id'],
                            'lot_serial_numbers' => $balance['lot_serial_no'],
                            'expired_date' => $balance['expired_date'],
                            'uom_id' => $balance['ref_uom_id'],
                            'uom_quantity' => $uom_balance_quantity,
                            'ref_uom_quantity' => $balance_quantity,
                            'is_prepare' => $is_prepare !== 'prepare' ? false : true,
                        ]);
                    }

                    if ($is_prepare !== 'prepare'){
                        $balance->decrement('current_quantity', $balance_quantity);
                    }

                } else {

                    if ($is_prepare !== 'prepare'){
                        $balance->decrement('current_quantity', $out_able_ref_qty);
                    }

                    $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $uom_id, $out_able_ref_qty);

                    if ($lotSerialDetail) {
                        $lotSerialDetail->uom_quantity += $uom_balance_quantity;
                        $lotSerialDetail->ref_uom_quantity += $out_able_ref_qty;

                        if ($is_prepare !== 'prepare'){
                            $lotSerialDetail->is_prepare = 0;
                        }

                        $lotSerialDetail->save();
                    } else {
                        lotSerialDetails::create([
                            'transaction_detail_id' => $transaction_id,
                            'transaction_type' => $transaction_type,
                            'current_stock_balance_id' => $balance['id'],
                            'lot_serial_numbers' => $balance['lot_serial_no'],
                            'expired_date' => $balance['expired_date'],
                            'uom_id' => $balance['ref_uom_id'],
                            'uom_quantity' => $uom_balance_quantity,
                            'ref_uom_quantity' => $out_able_ref_qty,
                            'is_prepare' => $is_prepare !== 'prepare' ? false : true,
                        ]);
                    }

                    $out_able_ref_qty = 0;
                }
            }

        }

        if ($before_edit_qty > $out_qty) {
            $lotSerialDetails = lotSerialDetails::where('transaction_detail_id', $transaction_id)
                ->where('transaction_type', $transaction_type)
                ->when($lot_serial_no != null, function ($query) use ($lot_serial_no) {
                    $query->where('lot_serial_numbers', $lot_serial_no);
                })
                ->when($is_prepare === 'prepare', function ($query){
                    return $query->where('is_prepare', 1);
                })
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

                if ($out_able_ref_qty >= $balance_quantity) {
                    $out_able_ref_qty -= $balance_quantity;

                    if ($is_prepare !== 'prepare'){
                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                        $currentStockBalance->current_quantity += $balance_quantity;
                        $currentStockBalance->save();
                    }

                    $lotSerialDetail->delete();

                } else { //10 < 20
                    if ($is_prepare !== 'prepare'){
                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                        $currentStockBalance->current_quantity += $out_able_ref_qty;
                        $currentStockBalance->save();
                    }

                    $uom_balance_quantity = UomHelper::changeQtyOnUom($lotSerialDetail->uom_id ,$uom_id, $out_able_ref_qty);
                    $lotSerialDetail->ref_uom_quantity -= $out_able_ref_qty;
                    $lotSerialDetail->uom_quantity -= $uom_balance_quantity;
                    $lotSerialDetail->save();
                }
            }
        }
    }


}
