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
        Schema::table('product_variations', function ($table) {
            $table->index('product_id');
            $table->index('variation_sku');
        });

        Schema::table('products', function ($table) {
            $table->index('id');
            $table->index('name');
            $table->index('product_code');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
