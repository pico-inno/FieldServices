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
        Schema::create('pos_register_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('register_session_id')->nullable();
            $table->unsignedBigInteger('payment_account_id')->nullable();
            $table->enum('transaction_type',['opening_amount','sale'])->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->decimal('transaction_amount',22,2)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('payment_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_register_transactions');
    }
};
