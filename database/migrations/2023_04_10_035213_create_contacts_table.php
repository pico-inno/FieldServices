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
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');
            $table->enum('type', ['Supplier', 'Customer', 'Both']);
            $table->unsignedBigInteger('pricelist_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_id')->nullable();
            $table->string('contact_status')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('township')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('zip_code')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('landline')->nullable();
            $table->string('alternate_number')->nullable();
            $table->integer('pay_term_number')->nullable();
            $table->enum('pay_term_type', ['Months', 'Days'])->nullable();
            $table->decimal('credit_limit',22,4)->nullable();
            $table->integer('created_by')->nullable();
            $table->decimal('receivable_amount',22,4)->nullable();
            $table->decimal('payable_amount',22,4)->nullable();
            $table->integer('total_rp')->nullable();
            $table->integer('total_rp_used')->nullable();
            $table->integer('total_rp_expired')->nullable();
            $table->boolean('is_default')->default(0)->nullable();
            $table->text('shipping_address')->nullable();
            $table->integer('customer_group_id')->nullable();
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('custom_field_3')->nullable();
            $table->string('custom_field_4')->nullable();
            $table->string('custom_field_5')->nullable();
            $table->string('custom_field_6')->nullable();
            $table->string('custom_field_7')->nullable();
            $table->string('custom_field_8')->nullable();
            $table->string('custom_field_9')->nullable();
            $table->string('custom_field_10')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
