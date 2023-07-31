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
        Schema::create('expense_transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('expense_on')->nullable();
            $table->integer('expense_report_id')->nullable();
            $table->string('expense_voucher_no')->nullable();
            $table->integer('expense_product_id')->nullable();
            $table->string('expense_description')->nullable();
            $table->decimal('quantity',22,4)->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->decimal('expense_amount',22,4)->nullable();
            $table->decimal('paid_amount',22,4)->nullable();
            $table->decimal('balance_amount',22,4)->nullable();
            $table->enum('payment_status',['paid','partial','pending'])->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('note')->nullable();
            $table->string('documents')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_transactions');
    }
};
