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
        Schema::create('opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('business_location_id')->nullable();
            $table->string('opening_stock_voucher_no')->nullable();
            $table->dateTime('opening_date')->nullable();
            $table->integer('opening_person')->nullable();
            $table->decimal('total_opening_amount',22,4)->nullable();
            $table->string('note')->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->integer('confirm_by')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->boolean('is_delete')->default(0);
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
        Schema::dropIfExists('opening_stocks');
    }
};
