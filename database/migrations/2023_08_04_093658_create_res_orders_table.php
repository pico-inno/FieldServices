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
        Schema::create('res_orders', function (Blueprint $table) {
            $table->id();
            $table->text('order_voucher_no');
            $table->enum('order_status',['order','preparing','ready','complete']);
            $table->enum('services',['dine_in','take_away','delivery']);
            $table->unsignedBigInteger('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_orders');
    }
};
