<?php

namespace Database\Seeders;

use App\Models\stock_history;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class dateTableSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run(): void
    {
        $sh=stock_history::where('transaction_type', 'purchase')
                    ->with('purchaseDetail')
                    ->get();
        foreach ($sh as $s) {
           if($s->purchaseDetail->is_delete==1){
                stock_history::find($s->id)->delete();
           }
        }

        DB::update("
            UPDATE stock_histories
            LEFT JOIN purchase_details ON stock_histories.transaction_details_id = purchase_details.id
            LEFT JOIN purchases ON purchase_details.purchases_id = purchases.id
            SET stock_histories.created_at = purchases.received_at
            WHERE stock_histories.transaction_type = 'purchases'
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
            LEFT JOIN opening_stock_details ON stock_histories.transaction_details_id = opening_stock_details.id
            LEFT JOIN opening_stocks ON opening_stock_details.opening_stock_id = opening_stocks.id
            SET stock_histories.created_at = opening_stocks.opening_date
            WHERE stock_histories.transaction_type = 'opening_stock'
        ");

        DB::update("
            UPDATE stock_histories
            LEFT JOIN stock_adjustment_details ON stock_histories.transaction_details_id = stock_adjustment_details.id
            LEFT JOIN stock_adjustments ON stock_adjustment_details.adjustment_id = stock_adjustments.id
            SET stock_histories.created_at = stock_adjustments.created_at
            WHERE stock_histories.transaction_type = 'adjustment'
        ");

        DB::update("
            UPDATE stock_histories
            LEFT JOIN transfer_details ON stock_histories.transaction_details_id = transfer_details.id
            LEFT JOIN transfers ON transfer_details.transfer_id = transfers.id
            SET stock_histories.created_at = transfers.created_at
            WHERE stock_histories.transaction_type = 'transfer'
        ")

        // DB::update("
        //     UPDATE stock_histories
        //     LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
        //     LEFT JOIN sales ON sale_details.sales_id = sales.id
        //     SET stock_histories.created_at = sales.sold_at
        //     WHERE stock_histories.transaction_type = 'sale'
        // ");




        // DB::update("
        //     UPDATE stock_histories
        //     LEFT JOIN purchase_details ON stock_histories.transaction_details_id = purchase_details.id
        //     LEFT JOIN purchases ON purchase_details.purchases_id = purchases.id
        //     SET stock_histories.created_at = purchases.purchased_at,
        //     stock_histories.ref_uom_price=purchase_details.per_ref_uom_price
        //     WHERE stock_histories.transaction_type = 'purchase'
        // ");

        // DB::update("
        //     UPDATE stock_histories
        //     LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
        //     LEFT JOIN sales ON sale_details.sales_id = sales.id
        //     LEFT JOIN lot_serial_details ON sale_details.id = lot_serial_details.transaction_detail_id
        //     LEFT JOIN current_stock_balance ON lot_serial_details.current_stock_balance_id = current_stock_balance.id
        //     SET stock_histories.created_at = sales.sold_at,
        //     stock_histories.ref_uom_price=current_stock_balance.ref_uom_price
        //     WHERE stock_histories.transaction_type = 'sale'
        // ");
    }
}
