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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_voucher_no');
            $table->unsignedBigInteger('from_location');
            $table->unsignedBigInteger('to_location');
            $table->date('transfered_at');
            $table->unsignedBigInteger('transfered_person');
            $table->enum('status', ['prepared', 'pending', 'in_transit', 'completed'])->default('prepared');
            $table->date('received_at')->nullable();
            $table->unsignedBigInteger('received_person')->nullable();
            $table->dateTime('created_at');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
