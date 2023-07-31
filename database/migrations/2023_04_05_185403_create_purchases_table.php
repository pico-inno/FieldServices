<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
 * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_voucher_no')->nullable();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->enum('status', ['request', 'pending','order', 'partial', 'deliver', 'received'])->nullable();
            $table->decimal('purchase_amount', 22, 4)->nullable();
            $table->decimal('total_line_discount', 22, 4)->nullable();
            $table->enum('extra_discount_type', ['fixed','percentage'])->nullable();
            $table->decimal('extra_discount_amount', 22, 4)->nullable();
            $table->decimal('total_discount_amount', 22, 4)->nullable();
            $table->decimal('purchase_expense', 22, 4)->nullable();
            $table->decimal('total_purchase_amount', 22, 4)->nullable();
            $table->decimal('paid_amount', 22, 4)->nullable();
            $table->decimal('balance_amount', 22, 4)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->enum('payment_status', [ 'pending', 'partial', 'paid'])->nullable();
            $table->dateTime('purchased_at')->nullable();
            $table->unsignedBigInteger('purchased_by')->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->unsignedBigInteger('confirm_by')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
