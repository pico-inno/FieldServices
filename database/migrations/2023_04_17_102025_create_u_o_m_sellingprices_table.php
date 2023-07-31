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
        Schema::create('u_o_m_sellingprices', function (Blueprint $table) {
            $table->id();
            $table->integer('product_variation_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->integer('pricegroup_id')->nullable();
            $table->decimal('price_inc_tax', 10, 2)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_o_m_sellingprices');
    }
};
