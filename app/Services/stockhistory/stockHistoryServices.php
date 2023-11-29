<?php

namespace App\Services\stockhistory;

use App\Models\stock_history;

class stockHistoryServices
{

    /**
     * stock history creation
     *
     * @param  array $data
     * @param  integer $transaction detail id
     * @param  integer $quantity
     * @param  mixed $tx_type(transaction type)
     * @param  date stock created date
     * @param  string $change(decrease or increase)
     * @return void
     */
    public function create($data,$txDetailId,$qty,$createDate, string $tx_type,string $change='increase'){
        return stock_history::create([
            'business_location_id' => $data['business_location_id'],
            'product_id' => $data['product_id'],
            'variation_id' => $data['variation_id'],
            'batch_no' => $data['batch_no'],
            'expired_date' => $data['expired_date'],
            'transaction_type' => $tx_type,
            'transaction_details_id' => $txDetailId,
            'increase_qty' => $change == 'increase' ? $qty :0,
            'decrease_qty' => $change == 'decrease' ? $qty : 0,
            'ref_uom_id' => $data['ref_uom_id'],
            "created_at" => $createDate,
        ]);
    }

}
