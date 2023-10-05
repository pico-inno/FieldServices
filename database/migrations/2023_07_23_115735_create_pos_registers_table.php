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
        Schema::create('pos_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->json('employee_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->json('payment_account_id')->nullable();
            $table->enum('status',['open','close'])->nullable();
            $table->integer('printer_id')->nullable();
            $table->unsignedBigInteger('use_for_res')->before('printer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_registers');
    }
};
