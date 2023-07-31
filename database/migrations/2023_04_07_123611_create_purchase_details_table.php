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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
//             $table->foreignId('purchases_id')->constrained('purchases')->nullable();
            $table->unsignedBigInteger('purchases_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('purchase_uom_id')->nullable();
            $table->decimal('quantity', 22, 4);
            $table->decimal('uom_price', 22, 4)->nullable();
            $table->decimal('subtotal', 22, 4)->nullable();
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('per_item_discount', 22, 4)->nullable();
            $table->decimal('subtotal_with_discount', 22, 4)->nullable();
            $table->decimal('per_item_expense', 22, 4)->nullable();
            $table->decimal('expense', 22, 4)->nullable();
            $table->decimal('subtotal_with_expense', 22, 4)->nullable();
            $table->decimal('tax_amount', 22, 4)->nullable();
            $table->decimal('per_item_tax', 22, 4)->nullable();
            $table->decimal('subtotal_with_tax', 22, 4)->nullable();
            $table->unsignedBigInteger('ref_uom_id')->nullable();
            $table->unsignedBigInteger('per_ref_uom_price')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('received_quantity', 22, 4)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->unsignedBigInteger('updated_by');
            $table->boolean('is_delete')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
