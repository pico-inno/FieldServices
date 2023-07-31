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
        Schema::create('current_stock_balance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->enum('transaction_type', [
                'purchase',
                'sale',
                'transfer',
                'opening_stock',
                'stock_in',
                'stock_out',
                'adjustment'
            ])->nullable();
            $table->unsignedBigInteger('transaction_detail_id');
            $table->string('batch_no')->nullable();
            $table->string('lot_serial_no')->nullable();
            $table->date('expired_date')->nullable();
            $table->unsignedBigInteger('ref_uom_id')->nullable();
            $table->decimal('ref_uom_quantity', 22, 4)->nullable();
            $table->decimal('ref_uom_price', 22, 4)->nullable();
            $table->decimal('current_quantity', 22, 4)->nullable();
            $table->timestamps();

            // $table->foreign('business_location_id')->references('id')->on('business_locations');
            // $table->foreign('product_id')->references('id')->on('products');
            // $table->foreign('variation_id')->references('id')->on('variations');
            // $table->foreign('transaction_detail_id')->references('id')->on('transfer_details')->onDelete('cascade');
            // $table->foreign('transaction_detail_id')->references('id')->on('purchase_details')->onDelete('cascade');
            // $table->foreign('transaction_detail_id')->references('id')->on('stockin_details')->onDelete('cascade');
            // $table->foreign('uomset_id')->references('id')->on('uom_sets');
            // $table->foreign('smallest_unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_stock_balance');
    }
};
