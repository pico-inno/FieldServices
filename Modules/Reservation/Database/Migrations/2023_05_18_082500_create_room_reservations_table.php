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
        Schema::create('room_reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id')->nullable();
            $table->unsignedInteger('guest_id')->nullable();
            $table->unsignedInteger('room_type_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('room_rate_id')->nullable();
            $table->dateTime('room_check_in_date')->nullable();
            $table->dateTime('room_check_out_date')->nullable();
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
        Schema::dropIfExists('room_reservations');
    }
};
