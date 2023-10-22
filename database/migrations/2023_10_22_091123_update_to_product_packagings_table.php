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
        Schema::table('product_packagings', function (Blueprint $table) {
            $table->string('package_barcode')->nullable()->change();
            $table->boolean('for_purchase')->nullable()->change();
            $table->boolean('for_sale')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_packagings', function (Blueprint $table) {
            //
        });
    }
};
