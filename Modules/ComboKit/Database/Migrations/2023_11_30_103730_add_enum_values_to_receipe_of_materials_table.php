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
        Schema::table('receipe_of_materials', function (Blueprint $table) {
            $table->enum('rom_type', ['manufacture', 'kit', 'service'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipe_of_materials', function (Blueprint $table) {

        });
    }
};
