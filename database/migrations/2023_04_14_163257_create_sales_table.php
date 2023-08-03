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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->string('sales_voucher_no')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->enum('status', [ 'quotation', 'draft','pending','order', 'partial', 'delivered'])->nullable();
            $table->unsignedBigInteger('pos_register_id')->nullable();
            $table->decimal('sale_amount', 22, 4)->nullable();
            $table->decimal('total_item_discount', 22, 4)->nullable();
            $table->enum('extra_discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('extra_discount_amount', 22, 4)->nullable();
            $table->decimal('total_sale_amount', 22, 4)->nullable();
            $table->decimal('paid_amount', 22, 4)->nullable();
            $table->decimal('balance_amount', 22, 4)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->enum('payment_status', [ 'due', 'partial', 'paid'])->nullable();
            $table->dateTime('sold_at')->nullable();
            $table->unsignedBigInteger('sold_by')->nullable();
            $table->dateTime('confirm_at')->nullable()->nullable();
            $table->unsignedBigInteger('confirm_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(false)->nullable();
            $table->dateTime('deleted_at')->nullable()->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // $table->foreign('business_location_id')->references('id')->on('business_locations');
            // $table->foreign('contact_id')->references('id')->on('contacts');
            // $table->foreign('sold_by')->references('id')->on('users');
            // $table->foreign('confirm_by')->references('id')->on('users');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
            // $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
