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
        // Schema::table('business_settings',function(Blueprint $table) {
        //     $table->integer('currency_rounded_method')->default(2)->nullable();
        //     $table->integer('quantity_rounded_method')->default(2)->nullable();
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
