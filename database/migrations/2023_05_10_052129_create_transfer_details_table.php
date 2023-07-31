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
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('transfers')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variation_id');
            $table->integer('uom_id');
            $table->decimal('quantity', 22, 4);
            $table->decimal('uom_price', 22, 4);
            $table->decimal('subtotal', 22, 4);
            $table->decimal('per_item_expense', 22, 4);
            $table->decimal('expense', 22, 4);
            $table->decimal('subtotal_with_expense', 22, 4);
            $table->decimal('ref_uom_id', 22, 4);
            $table->decimal('per_ref_uom_price', 22, 4);
            $table->integer('currency_id');
            $table->string('remark')->nullable();
            $table->dateTime('created_at');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_details');
    }
};
