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
        Schema::table('lot_serial_details', function (Blueprint $table) {
            $table->enum('stock_status', ['normal', 'prepare', 'reserve'])->default('normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lot_serial_details', function (Blueprint $table) {
            //
        });
    }
};
