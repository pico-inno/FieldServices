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
        Schema::create('product_packagings', function (Blueprint $table) {
            $table->id();
            $table->string('packaging_name');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variation_id');
            $table->decimal('quantity', 22, 4);
            $table->unsignedBigInteger('uom_id');
            $table->string('package_barcode');
            $table->boolean('for_purchase');
            $table->boolean('for_sale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packagings');
    }
};
