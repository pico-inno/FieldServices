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
        Schema::create('hospital_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('joint_registration_id')->nullable();
            $table->string('registration_code');
            $table->enum('registration_type', ['OPD', 'IPD']);
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->datetime('opd_check_in_date')->nullable();
            $table->datetime('ipd_check_in_date')->nullable();
            $table->datetime('check_out_date')->nullable();
            $table->enum('registration_status', ['pending','cancelled','confirmed','checkin','checkout'])->nullable();
            $table->datetime('booking_confirmed_at')->nullable();
            $table->unsignedBigInteger('booking_confirmed_by')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default('0')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
