<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('parent_location_id')->nullable();
            $table->unsignedBigInteger('location_type')->nullable();
            $table->string('location_code')->nullable();
            $table->string('name', 256)->nullable();
            $table->boolean('allow_purchase_order')->default(0);
            $table->boolean('allow_sale_order')->default(0);
            $table->string('price_lists_id')->nullable();
            // $table->text('landmark')->nullable();
            // $table->string('country', 100)->nullable();
            // $table->string('state', 100)->nullable();
            // $takble->string('city', 100)->nullable();
            // $table->char('zip_code', 7)->nullable();
            // $table->char('mobile',191)->nullable();
            // $table->string('alternate_number')->nullable();
            // $table->string('email')->nullable();
            // $table->string('website')->nullable();
            $table->enum('inventory_flow', ['fifo', 'lifo'])->nullable();
            $table->unsignedBigInteger('invoice_layout')->default('1')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_location_settings');
    }
};
