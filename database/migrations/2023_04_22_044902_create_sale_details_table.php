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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->decimal('quantity', 22, 4)->nullable();
            $table->decimal('uom_price', 22, 4)->nullable();
            $table->decimal('subtotal', 22, 4)->nullable();
            $table->unsignedBigInteger('price_list_id')->nullable();
            $table->enum('discount_type', ['fixed', 'percentage','foc'])->nullable();
            $table->decimal('per_item_discount', 22, 4)->nullable();
            $table->decimal('subtotal_with_discount', 22, 4)->nullable();
            $table->decimal('tax_amount', 22, 4)->nullable();
            $table->decimal('per_item_tax', 22, 4)->nullable();
            $table->decimal('subtotal_with_tax', 22, 4)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('delivered_quantity', 22, 4)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('rest_order_id')->nullable();
            $table->text('note')->nullable();
            $table->enum('rest_order_status', ['order', 'preparing', 'ready', 'complete'])->nullable();
            $table->boolean('is_delete')->default(false)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
