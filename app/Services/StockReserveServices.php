<?php

namespace App\Services;

use App\Helpers\UomHelper;
use App\Models\lotSerialDetails;
use App\Repositories\CurrentStockBalanceRepository;

class StockReserveServices
{
    protected $csb;
    public function __construct()
    {
        $this->csb = new CurrentStockBalanceRepository ();
    }

    public static function make()
    {
        return new self();
    }

    public function reserve(
        $business_location_id,
        $product_id,
        $variation_id,
        $uom_id,
        $remove_quantity,
        $transaction_type,
        $transaction_id
    )
    {
        $currentStockBalances = $this->csb->query()
                ->where('business_location_id', $business_location_id)
                ->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('current_quantity', '>', 0)
                ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                    return $query->orderBy('id');
                }, function ($query) {
                    return $query->orderByDesc('id');
                })
                ->get();

                $uominfo =  UomHelper::getReferenceUomInfoByCurrentUnitQty($remove_quantity,$uom_id);

                 $remove_ref_quantity =  $uominfo['qtyByReferenceUom'];

              foreach ($currentStockBalances as $balance) {

                    $balance_quantity = $balance->current_quantity;

                    if ($remove_ref_quantity == 0){
                        break;
                    }
                    if ($remove_ref_quantity >= $balance_quantity) {

                        $balance->decrement('current_quantity', $balance_quantity);



                        $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $uom_id, $balance_quantity);

                        $old_lot_serial_detail = lotSerialDetails::where('transaction_detail_id', $transaction_id)
                            ->where('transaction_type', $transaction_type)
                            ->where('current_stock_balance_id', $balance['id'])->first();

                        if ($old_lot_serial_detail){
                            $old_lot_serial_detail->uom_quantity = $uom_balance_quantity;
                            $old_lot_serial_detail->ref_uom_quantity = $remove_ref_quantity;
                            $old_lot_serial_detail->uom_id = $balance['ref_uom_id'];
                            $old_lot_serial_detail->stock_status = 'reserve';
                            $old_lot_serial_detail->save();

                        }else{
                            lotSerialDetails::create([
                                'transaction_detail_id' => $transaction_id,
                                'transaction_type' => $transaction_type,
                                'current_stock_balance_id' => $balance['id'],
                                'lot_serial_numbers' => $balance['lot_serial_no'],
                                'expired_date' => $balance['expired_date'],
                                'uom_id' => $balance['ref_uom_id'],
                                'uom_quantity' => $uom_balance_quantity,
                                'ref_uom_quantity' => $balance_quantity,
                                'stock_status' => 'reserve',
                            ]);
                        }

                        $remove_ref_quantity -= $balance_quantity;
                    } else {


                        $balance->decrement('current_quantity', $remove_ref_quantity);

                        $uom_balance_quantity = UomHelper::changeQtyOnUom($balance['ref_uom_id'], $uom_id, $remove_ref_quantity);

                        $old_lot_serial_detail = lotSerialDetails::where('transaction_detail_id', $transaction_id)
                            ->where('transaction_type', $transaction_type)
                            ->where('current_stock_balance_id', $balance['id'])->first();

                        if ($old_lot_serial_detail){
                            $old_lot_serial_detail->uom_quantity = $uom_balance_quantity;
                            $old_lot_serial_detail->ref_uom_quantity = $remove_ref_quantity;
                            $old_lot_serial_detail->uom_id = $balance['ref_uom_id'];
                            $old_lot_serial_detail->stock_status = 'reserve';
                            $old_lot_serial_detail->save();

                        }else {
                            lotSerialDetails::create([
                                'transaction_detail_id' => $transaction_id,
                                'transaction_type' => $transaction_type,
                                'current_stock_balance_id' => $balance['id'],
                                'lot_serial_numbers' => $balance['lot_serial_no'],
                                'expired_date' => $balance['expired_date'],
                                'uom_id' => $balance['ref_uom_id'],
                                'uom_quantity' => $uom_balance_quantity,
                                'ref_uom_quantity' => $remove_ref_quantity,
                                'stock_status' => 'reserve',
                            ]);
                        }

                        $remove_ref_quantity = 0;
                    }
        }


    }
}
