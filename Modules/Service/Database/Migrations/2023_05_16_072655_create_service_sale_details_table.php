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
        Schema::create('service_sale_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_sale_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->decimal('quantity', 22, 4)->nullable();
            $table->decimal('sale_price_without_discount', 22, 4)->nullable();
            $table->enum('service_detail_discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 22, 4)->nullable();
            $table->decimal('sale_price', 22, 4)->nullable();
            $table->decimal('sale_price_inc_tax', 22, 4)->nullable();
            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_by')->nullable(); 
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_sale_details');
    }
};
