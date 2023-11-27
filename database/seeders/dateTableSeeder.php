<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class dateTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        DB::update("
            UPDATE stock_histories
            LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
            LEFT JOIN sales ON sale_details.sales_id = sales.id
            SET stock_histories.created_at = sales.sold_at
        ");
            DB::update("
            UPDATE stock_histories
            LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
            LEFT JOIN sales ON sale_details.sales_id = sales.id
            SET stock_histories.created_at = sales.sold_at
            WHERE stock_histories.transaction_type = 'sale'
        ");

        DB::update("
            UPDATE stock_histories
            LEFT JOIN purchase_details ON stock_histories.transaction_details_id = purchase_details.id
            LEFT JOIN purchases ON purchase_details.purchases_id = purchases.id
            SET stock_histories.created_at = purchases.purchased_at,
            stock_histories.ref_uom_price=purchase_details.per_ref_uom_price
            WHERE stock_histories.transaction_type = 'purchase'
        ");

        DB::update("
            UPDATE stock_histories
            LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
            LEFT JOIN sales ON sale_details.sales_id = sales.id
            LEFT JOIN lot_serial_details ON sale_details.id = lot_serial_details.transaction_detail_id
            LEFT JOIN current_stock_balance ON lot_serial_details.current_stock_balance_id = current_stock_balance.id
            SET stock_histories.created_at = sales.sold_at,
            stock_histories.ref_uom_price=current_stock_balance.ref_uom_price
            WHERE stock_histories.transaction_type = 'sale'
        ");
    }
}
