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
        // Schema::create('business_users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('username')->unique();
        //     $table->integer('role_id');
        //     $table->integer('business_id');
        //     $table->integer('default_location_id');
        //     $table->text('access_location_ids');
        //     $table->string('email')->unique()->nullable();
        //     $table->string('password');
        //     $table->integer('personal_info_id');
        //     $table->boolean('is_active')->default(1);
        //     $table->string('remember_token', 100)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_users');
    }
};
