<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        // DB::statement('UPDATE product_variations SET default_purchase_price = REPLACE(default_purchase_price, ",", "")');
        // DB::statement('UPDATE product_variations SET default_selling_price = REPLACE(default_selling_price, ",", "")');



        // Schema::table('product_variations', function (Blueprint $table) {
        //     $table->decimal('default_purchase_price', 22,4)->nullable()->change();
        //     $table->decimal('profit_percent', 22,4)->nullable()->change();
        //     $table->decimal('default_selling_price', 22,4)->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
