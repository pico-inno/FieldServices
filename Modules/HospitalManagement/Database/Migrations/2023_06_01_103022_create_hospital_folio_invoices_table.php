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
        Schema::create('hospital_folio_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('joint_folio_invoice_id')->nullable();
            $table->string('folio_invoice_code')->nullable();
            $table->unsignedBigInteger('billing_contact_id')->nullable();
            $table->integer('registration_id')->unsigned()->nullable();
            $table->datetime('confirm_at')->nullable();
            $table->integer('confirm_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->boolean('is_delete')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_folio_invoices');
    }
};
