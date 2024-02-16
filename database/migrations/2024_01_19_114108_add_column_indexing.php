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

        // Schema::table('contacts', function ($table) {
        //     $table->index('id');
        //     $table->index('first_name');
        //     $table->index('last_name');
        //     $table->index('middle_name');
        //     $table->index('company_name');
        // });


        Schema::table('business_locations', function ($table) {
            $table->index('id');
            $table->index('name');
        });


        Schema::table('business_settings', function ($table) {
            $table->index('id');
        });

        Schema::table('sales', function ($table) {
            $table->index('id');
            $table->index('contact_id');
            $table->index('business_location_id');

        });


        Schema::table('sale_details', function ($table) {
            $table->index('id');
            $table->index('sales_id');
            $table->index('product_id');
            $table->index('variation_id');
        });

        Schema::table('purchases', function ($table) {
            $table->index('id');
            $table->index('contact_id');
            $table->index('business_location_id');
        });


        Schema::table('purchase_details', function ($table) {
            $table->index('id');
            $table->index('purchases_id');
            $table->index('product_id');
            $table->index('variation_id');
        });


        Schema::table('uom', function ($table) {
            $table->index('id');
        });


        Schema::table('current_stock_balance', function ($table) {
            $table->index('id');
            $table->index('business_location_id');
            $table->index('variation_id');
            $table->index('transaction_type');
            $table->index('transaction_detail_id');
        });
        Schema::table('stock_histories', function ($table) {
            $table->index('id');
            $table->index('business_location_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('transaction_type');
            $table->index('transaction_details_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {



        Schema::table('sale_details', function ($table) {
            $table->dropIndex('sale_details_id_index');
            $table->dropIndex('sale_details_sales_id_index');
            $table->dropIndex('sale_details_product_id_index');
            $table->dropIndex('sale_details_variation_id_index');
        });
        Schema::table('sales', function ($table) {
            $table->dropIndex('sales_id_index');
            $table->dropIndex('sales_contact_id_index');
            $table->dropIndex('sales_business_location_id_index');
        });


        Schema::table('purchase_details', function ($table) {
            $table->dropIndex('purchase_details_id_index');
            $table->dropIndex('purchase_details_purchases_id_index');
            $table->dropIndex('purchase_details_product_id_index');
            $table->dropIndex('purchase_details_variation_id_index');
        });
        Schema::table('purchases', function ($table) {
            $table->dropIndex('purchases_id_index');
            $table->dropIndex('purchases_contact_id_index');
            $table->dropIndex('purchases_business_location_id_index');
        });


        Schema::table('uom', function ($table) {
            $table->dropIndex('uom_id_index');
        });

        Schema::table('current_stock_balance', function ($table) {
            $table->dropIndex('current_stock_balance_id_index');
            $table->dropIndex('current_stock_balance_business_location_id_index');
            $table->dropIndex('current_stock_balance_variation_id_index');
            $table->dropIndex('current_stock_balance_transaction_type_index');
            $table->dropIndex('current_stock_balance_transaction_detail_id_index');
        });


        Schema::table('business_locations', function ($table) {
            $table->dropIndex('business_locations_id_index');
            $table->dropIndex('business_locations_name_index');
        });
        Schema::table('stock_histories', function ($table) {
            $table->dropIndex('stock_histories_id_index');
            $table->dropIndex('stock_histories_business_location_id_index');
            $table->dropIndex('stock_histories_product_id_index');
            $table->dropIndex('stock_histories_variation_id_index');
            $table->dropIndex('stock_histories_transaction_type_index');
            $table->dropIndex('stock_histories_transaction_detail_id_index');
        });

        Schema::table('contacts', function ($table) {
            $table->dropIndex('contacts_id_index');
            $table->dropIndex('contacts_first_name_index');
            $table->dropIndex('contacts_last_name_index');
            $table->dropIndex('contacts_middle_name_index');
            $table->dropIndex('contacts_company_name_index');
        });

        Schema::table('business_settings', function ($table) {
            $table->dropIndex('business_settings_id_index');
        });
    }

};
