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
        Schema::create('hospital_room_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->string('room_sales_voucher_no')->nullable();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->decimal('total_amount', 22, 4)->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_amount', 22, 4)->nullable();
            $table->decimal('total_sale_amount', 22, 4)->nullable();
            $table->decimal('paid_amount', 22, 4)->nullable();
            $table->decimal('balance_amount', 22, 4)->nullable();
            $table->datetime('confirm_at')->nullable();
            $table->unsignedBigInteger('confirm_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(0)->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
