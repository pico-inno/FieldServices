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
        Schema::create('additional_products', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_product_id');
            $table->integer('primary_product_variation_id');
            $table->integer('additional_product_variation_id');
            $table->integer('uom_id');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_products');
    }
};
