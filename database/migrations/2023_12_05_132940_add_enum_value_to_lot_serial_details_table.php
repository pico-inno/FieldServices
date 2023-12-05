<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lot_serial_details', function (Blueprint $table) {
            $table->enum('transaction_type', ['purchase', 'sale', 'stock_in', 'stock_out', 'transfer', 'adjustment', 'service_sale', 'kit_service_used_product_detail','kit_sale_detail', 'kit_stock_out_detail'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lot_serial_details', function (Blueprint $table) {
            //
        });
    }
};
