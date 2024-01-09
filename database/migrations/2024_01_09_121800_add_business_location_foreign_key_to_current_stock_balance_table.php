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
        Schema::table('current_stock_balance', function (Blueprint $table) {
            $table->foreign('business_location_id')
                ->references('id')->on('business_locations')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_stock_balance', function (Blueprint $table) {
            //
        });
    }
};
