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
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->enum('channel_type',['sale','pos','campaign'])->default('sale');
        });
        Schema::table('sale_details', function (Blueprint $table) {
            $table->enum('discount_type', ['fixed', 'percentage','foc','present'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
};
