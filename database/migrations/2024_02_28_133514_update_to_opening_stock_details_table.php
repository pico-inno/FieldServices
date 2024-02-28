<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opening_stock_details', function (Blueprint $table) {
            $table->dateTime('expired_date')->nullable();
            $table->string('lot_serial_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opening_stocks', function (Blueprint $table) {
            //
        });
    }
};
