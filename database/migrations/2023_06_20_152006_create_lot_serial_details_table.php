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
        Schema::create('lot_serial_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_detail_id')->nullable();
            $table->enum('transaction_type',['purchase','sale','stock_in','stock_out','transfer', 'adjustment'])->nullable();
            $table->unsignedBigInteger('current_stock_balance_id')->nullable();
            $table->string('lot_serial_numbers')->nullable();
            $table->datetime('expired_date')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->decimal('uom_quantity',22,4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_serial_details');
    }
};
