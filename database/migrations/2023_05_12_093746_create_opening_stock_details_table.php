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
        Schema::create('opening_stock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opening_stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('uom_id');
            $table->decimal('quantity', 22, 4);
            $table->decimal('uom_price', 22, 4);
            $table->decimal('subtotal', 22, 4);
            $table->unsignedBigInteger('ref_uom_id');
            $table->unsignedBigInteger('ref_uom_price');
            $table->string('remark')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_stock_details');
    }
};
