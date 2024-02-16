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
        // Schema::create('personal_infos', function (Blueprint $table) {
        //     $table->id();
        //     $table->char('initname', 10)->nullable();
        //     $table->string('first_name', 191)->nullable();
        //     $table->string('last_name', 191)->nullable();
        //     $table->char('language', 7)->nullable();
        //     $table->boolean('allow_login')->default(1)->nullable();
        //     $table->enum('status', ['online', 'offline'])->default('offline')->nullable();
        //     $table->date('dob')->nullable();
        //     $table->boolean('gender')->nullable();
        //     $table->enum('marital_status', ['Married', 'Unmarried', 'Divorced'])->nullable();
        //     $table->char('blood_group', 10)->nullable();
        //     $table->char('contact_number', 20)->nullable();
        //     $table->string('alt_number', 191)->nullable();
        //     $table->string('family_number', 191)->nullable();
        //     $table->string('fb_link', 191)->nullable();
        //     $table->string('twitter_link', 191)->nullable();
        //     $table->string('social_media_1', 191)->nullable();
        //     $table->string('social_media_2', 191)->nullable();
        //     $table->text('permanent_address')->nullable();
        //     $table->text('current_address')->nullable();
        //     $table->string('guardian_name', 191)->nullable();
        //     $table->string('custom_field_1', 191)->nullable();
        //     $table->string('custom_field_2', 191)->nullable();
        //     $table->string('custom_field_3', 191)->nullable();
        //     $table->string('custom_field_4', 191)->nullable();
        //     $table->longText('bank_details')->nullable();
        //     $table->string('id_proof_name', 191)->nullable();
        //     $table->string('id_proof_number', 191)->nullable();
        //     $table->string('profile_photo')->nullable();
        //     $table->softDeletes();
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
        Schema::dropIfExists('personal_infos');
    }
};
