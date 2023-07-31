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
        Schema::create('room_sales', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['registration', 'reservation'])->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->string('room_sales_voucher_no')->nullable();
            $table->unsignedInteger('business_location_id')->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->decimal('sale_amount', 22, 4)->nullable();
            $table->decimal('total_item_discount', 22, 4)->nullable();
            $table->decimal('total_sale_amount', 22, 4)->nullable();
            $table->decimal('paid_amount', 22, 4)->nullable();
            $table->decimal('balance_amount', 22, 4)->nullable();
            $table->decimal('currency_id', 22, 4)->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->unsignedInteger('confirm_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_sales');
    }
};
