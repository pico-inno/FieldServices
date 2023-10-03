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
        Schema::table('business_locations', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_location_id')->after('name')->nullable();
            $table->unsignedBigInteger('location_type')->after('parent_location_id')->nullable();
            $table->enum('inventory_flow',['fifo','lifo'])->after('location_type')->nullable();
            $table->unsignedBigInteger('invoice_layout')->default('1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            //
        });
    }
};
