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
        Schema::create('service_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('service_voucher_no')->nullable();
            $table->enum('service_status', ['request', 'draft', 'suspend', 'final', 'confirm'])->nullable();
            $table->decimal('sale_amount', 22, 4)->nullable();
            $table->enum('service_discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 22, 4)->nullable();
            $table->decimal('total_sale_amount', 22, 4)->nullable();
            $table->decimal('paid_amount', 22, 4)->nullable();
            $table->decimal('balance', 22, 4)->nullable();
            $table->string('remark')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('confirm_at')->nullable();
            $table->unsignedBigInteger('confirm_by')->nullable();
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
        Schema::dropIfExists('service_sales');
    }
};
