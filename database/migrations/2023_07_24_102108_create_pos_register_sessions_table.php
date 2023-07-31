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
        Schema::create('pos_register_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_register_id')->nullable();
            $table->enum('status',['open','close'])->nullable();
            $table->decimal('opening_amount',22,4)->nullable();
            $table->dateTime('opening_at')->nullable();
            $table->decimal('closing_amount')->nullable();
            $table->dateTime('closing_at')->nullable();
            $table->text('closing_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_sessions');
    }
};
