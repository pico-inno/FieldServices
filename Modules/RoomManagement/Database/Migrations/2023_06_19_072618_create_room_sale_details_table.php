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
        Schema::create('room_sale_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_sale_id')->nullable();
            $table->unsignedInteger('room_type_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('room_rate_id')->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->dateTime('check_in_date')->nullable();
            $table->dateTime('check_out_date')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('uom_id')->nullable();
            $table->decimal('room_fees', 22, 4)->nullable();
            $table->decimal('subtotal', 22, 4)->nullable();
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('per_item_discount', 22, 4)->nullable();
            // $table->decimal('subtotal_with_discount', 22, 4)->nullable();
            $table->decimal('tax_amount', 22, 4)->nullable();
            $table->decimal('per_item_tax', 22, 4)->nullable();
            $table->decimal('subtotal_with_tax', 22, 4)->nullable();
            $table->unsignedInteger('currency_id')->nullable();
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
        Schema::dropIfExists('room_sale_details');
    }
};
