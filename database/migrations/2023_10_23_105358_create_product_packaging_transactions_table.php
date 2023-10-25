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
        Schema::create('product_packaging_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', [
                'purchase',
                'sale',
                'transfer',
                'opening_stock',
                'stock_in',
                'stock_out',
                'adjustment'
            ])->nullable();
            $table->unsignedBigInteger('transaction_details_id')->nullable();
            $table->unsignedBigInteger('product_packaging_id')->nullable();
            $table->decimal('quantity',22,4)->nullable();

            $table->dateTime('created_at')->default(now());
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(false)->nullable();
            $table->dateTime('deleted_at')->nullable()->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packaging_transactions');
    }
};
