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
        // prefix
        Schema::table('business_settings',function(Blueprint $table) {
            $table->string('sale_prefix')->default('S')->nullable();
            $table->string('purchase_prefix')->default('P')->nullable();
            $table->string('stock_transfer_prefix')->default('ST')->nullable();
            $table->string('stock_adjustment_prefix')->default('SA')->nullable();
            $table->string('expense_prefix')->default('E')->nullable();
            $table->string('purchase_payment_prefix')->default('PMV')->nullable();
            $table->string('expense_payment_prefix')->default('PMV')->nullable();
            $table->string('sale_payment_prefix')->default('PMV')->nullable();
            $table->string('expense_report_prefix')->default('ER')->nullable();
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
