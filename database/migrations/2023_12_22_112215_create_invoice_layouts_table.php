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
        Schema::create('invoice_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('paper_size',['A4','A5','80mm','Legal'])->default('A4');
            $table->enum('paper_type',['classic','detailed','elegant']);
            $table->json('data_text')->nullable();
            $table->string('header_text');
            $table->string('footer_text');
            $table->longText('note');
            $table->json('table_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_layouts');
    }
};
