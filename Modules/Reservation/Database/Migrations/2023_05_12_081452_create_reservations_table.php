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
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('joint_reservation_id')->nullable();
            $table->string('reservation_code')->nullable();
            $table->unsignedInteger('guest_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date');
            $table->enum('reservation_status', ['Pending', 'Cancelled', 'Confirmed', 'No_Show', 'Checkin', 'Checkout']);
            $table->dateTime('booking_confirmed_at')->nullable();
            $table->unsignedInteger('booking_confirmed_by')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('reservations');
    }
};
