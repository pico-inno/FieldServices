<?php

use App\Models\stock_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use function Laravel\Prompts\error;
use function Laravel\Prompts\progress;

return new class extends Migration
{

    private $migration = false;
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('stock_histories', function (Blueprint $table) {
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('lot_serial_details', function (Blueprint $table) {
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dateTime('received_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });


        Schema::table('sales', function (Blueprint $table) {
            $table->dateTime('delivered_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });


        // info("\n\n\n-------------------- successfull console ---------------------") ;

        // DB::update("
        //     UPDATE stock_histories
        //     LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
        //     LEFT JOIN sales ON sale_details.sales_id = sales.id
        //     SET stock_histories.created_at = sales.sold_at
        // ");
        //     DB::update("
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

        // $stockHistoriesToUpdate = stock_history::select('stock_histories.*')
        // ->where('transaction_type', '=', 'sale')
        //     ->leftJoin('sale_details', 'stock_histories.transaction_details_id', '=', 'sale_details.id')
        //     ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
        //     ->get();

        // foreach ($stockHistoriesToUpdate as $Index=>$stockHistory) {

        //     // Update each record individually using Eloquent models
        //     $salesSoldAt = $stockHistory->saleDetail->sale->sold_at;
        //     $stockHistory->created_at = $salesSoldAt;
        //     $stockHistory->save();

        // }

    }

    // 2023_11_24_152527_update_to_stock_histories_table
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            //
        });
    }
};
