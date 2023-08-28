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
        Schema::create('price_list_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricelist_id')->nullable();
            $table->enum('applied_type', ['All', 'Category', 'Product', 'Variation', 'Room_Type', 'Room'])->nullable();
            $table->integer('applied_value')->nullable();
            $table->decimal('min_qty', 22, 4)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->enum('cal_type', ['fixed', 'percentage', 'formula'])->nullable();
            $table->decimal('cal_value', 22,4)->nullable();
            $table->integer('base_price')->nullable();
            $table->decimal('cal_profit_discount', 22, 4)->nullable();
            $table->decimal('rounding_method')->nullable();
            $table->decimal('extra_price', 22, 4)->nullable();
            $table->enum('margin_type', ['fixed', 'percent'])->nullable();
            $table->decimal('min_margin', 22, 4)->nullable();
            $table->decimal('max_margin', 22, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_details');
    }
};
