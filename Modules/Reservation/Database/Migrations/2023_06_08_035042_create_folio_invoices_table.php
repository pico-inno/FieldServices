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
        Schema::create('folio_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('joint_folio_invoice_id')->nullable();
            $table->string('folio_invoice_code')->nullable();
            $table->unsignedInteger('billing_contact_id')->nullable();
            $table->unsignedInteger('reservation_id')->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->unsignedInteger('confirm_by')->nullable();
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
        Schema::dropIfExists('folio_invoices');
    }
};
