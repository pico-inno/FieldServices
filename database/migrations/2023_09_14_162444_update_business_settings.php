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
        // prefix
        Schema::table('business_settings',function(Blueprint $table) {
            $table->string('sale_prefix')->default('S')->nullable();
            $table->string('purchase_prefix')->default('P')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
