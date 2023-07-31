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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->string('lot_serial_no')->nullable();
            $table->date('expired_date')->nullable();
            $table->enum('transaction_type',[
                'purchase',
                'sale',
                'transfer',
                'opening_stock',
                'stock_in',
                'stock_out',
                'adjustment'
            ])->nullable();
            $table->unsignedBigInteger('transaction_details_id')->nullable();
            $table->decimal('increase_qty',22,4)->nullable();
            $table->decimal('decrease_qty', 22, 4)->nullable();
            $table->unsignedBigInteger('ref_uom_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
