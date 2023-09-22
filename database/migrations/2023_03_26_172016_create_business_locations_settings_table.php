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
            $table->string('location_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('location_code')->nullable();
            $table->string('name', 256)->nullable();
            $table->boolean('allow_purchase_order')->default(0);
            $table->boolean('allow_sale_order')->default(0);
            $table->string('price_lists_id')->nullable();
            $table->text('landmark')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->char('zip_code', 7)->nullable();
            $table->char('mobile',191)->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('featured_products')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->string('custom_field4')->nullable();
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
