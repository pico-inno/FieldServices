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
        Schema::create('hospital_folio_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folio_invoice_id')->nullable();
            $table->enum('transaction_type',['room','sale','services'])->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->text('transaction_descritpion')->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->unsignedBigInteger('confirm_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default('0');
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_folio_invoice_details');
    }
};
