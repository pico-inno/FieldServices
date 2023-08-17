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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_code')->nullable();
            $table->string('sku')->nullable();
            $table->enum('product_type', ['consumeable', 'storable', 'service',])->default('storable');
            $table->enum('has_variation', ['single', 'variable'])->default('single');
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('manufacturer_id')->nullable();
            $table->integer('generic_id')->nullable();
            $table->integer('lot_count')->nullable();
            $table->integer('uom_id')->nullable();
            $table->integer('purchase_uom_id')->nullable();
            $table->string('product_custom_field1')->nullable();
            $table->string('product_custom_field2')->nullable();
            $table->string('product_custom_field3')->nullable();
            $table->string('product_custom_field4')->nullable();
            $table->string('image')->nullable();
            $table->longText('product_description')->nullable();
            $table->boolean('can_sale')->default(0);
            $table->boolean('can_purchase')->default(0);
            $table->boolean('can_expense')->default(0);
            $table->boolean('is_recurring')->default(0);
            $table->boolean('is_inactive')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
