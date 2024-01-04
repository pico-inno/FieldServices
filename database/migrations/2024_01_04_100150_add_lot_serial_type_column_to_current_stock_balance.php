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
            $table->enum('lot_serial_type', ['lot', 'serial'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_stock_balance', function (Blueprint $table) {
            $table->enum('lot_serial_type', ['lot', 'serial'])->nullable();
        });
    }
};
