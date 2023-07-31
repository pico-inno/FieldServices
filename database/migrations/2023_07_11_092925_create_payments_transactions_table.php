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
        Schema::create('payments_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('payment_voucher_no')->nullable();
            $table->dateTime('payment_date');
            $table->enum('transaction_type',['sale','purchase','stockIn','stockOut','opening_amount','withdrawl','deposit','transfer','expense'])->nullable();
            // $table->enum('',['due','partial','paid','opening'])->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('transaction_ref_no')->nullable();
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('payment_account_id')->nullable();
            $table->enum('payment_type',['credit','debit'])->nullable();
            $table->decimal('payment_amount',22,4)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_transactions');
    }
};
