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
        Schema::create('service_used_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_sale_detail_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_used_products');
    }
};
