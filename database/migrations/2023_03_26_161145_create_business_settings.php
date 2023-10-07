<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->nullable(true);
            $table->unsignedBigInteger('currency_id')->nullable(true);
            $table->enum('lot_control',['on','off'])->nullable(true);
            $table->boolean('use_paymentAccount')->nullable()->default(true);
            $table->date('start_date')->nullable(true);
            $table->double('default_profit_percent', 5, 2)->default(0.00)->nullable(true);
            $table->enum('finanical_year_start_month', [
                "january", "february", "march", "april", "may", "june",
                "july", "august", "september", "october", "november", "december"
            ])->nullable(true);



            $table->string('sale_prefix')->default('SL')->nullable();
            $table->string('purchase_prefix')->default('PC')->nullable();
            $table->string('stock_transfer_prefix')->default('ST')->nullable();
            $table->string('stock_adjustment_prefix')->default('SA')->nullable();
            $table->string('expense_prefix')->default('EP')->nullable();
            $table->string('purchase_payment_prefix')->default('PCP')->nullable();
            $table->string('expense_payment_prefix')->default('EPP')->nullable();
            $table->string('sale_payment_prefix')->default('SLP')->nullable();
            $table->string('expense_report_prefix')->default('EPR')->nullable();
            $table->string('business_location_prefix')->default('LOC')->nullable();
            // for sale
            $table->tinyInteger('enable_row')->unsigned()->notNull()->default(1);

            $table->unsignedBigInteger('owner_id')->nullable(true);
            $table->string('time_zone', 191)->default('Asia/Kolkata')->nullable(true);
            $table->tinyInteger('fy_start_month')->default(1)->nullable(true);
            $table->enum('accounting_method', ['fifo', 'lifo'])->default('fifo')->nullable(true);
            $table->decimal('default_sales_discount', 5, 2)->nullable(true);
            $table->enum('sell_price_tax', ['includes', 'excludes'])->default('includes')->nullable(true);
            $table->integer('currency_decimal_places')->default(2)->nullable();
            $table->integer('quantity_decimal_places')->default(2)->nullable();
            $table->string('logo', 191)->nullable(true);
            $table->string('sku_prefix', 191)->nullable(true);
            $table->tinyInteger('enable_product_expiry')->default(0)->nullable(true);
            $table->enum('expiry_type', ['add_expiry', 'on_expiry', 'before_expiry'])->default('add_expiry')->nullable(true);
            $table->enum('on_product_expiry', ['keep_selling', 'stop_selling'])->default('keep_selling')->nullable(true);
            $table->integer('stop_selling_before')->nullable(true)->comment('Stop selling expired item n days before expiry');
            $table->tinyInteger('enable_tooltip')->default(1)->nullable(true);
            $table->tinyInteger('purchase_in_diff_currency')->default(0)->nullable(true)->comment('Allow purchase to be in different currency then the business currency');
            $table->unsignedBigInteger('purchase_currency_id')->nullable(true);
            $table->decimal('p_exchange_rate', 20, 3)->default(1.000)->nullable(true);
            $table->integer('transaction_edit_days')->default(30)->nullable(true);
            $table->integer('stock_expiry_alert_days')->default(30)->nullable(true);
            $table->text('keyboard_shortcuts')->nullable(true);
            $table->text('pos_settings')->nullable(true);
            $table->longText('essentials_settings')->nullable(true);
            $table->text('weighing_scale_setting')->nullable(true)->comment('Used to store the configuration of weighing scale');
            $table->tinyInteger('enable_brand')->default(1)->nullable(true);
            $table->tinyInteger('enable_category')->default(1)->nullable(true);
            $table->tinyInteger('enable_sub_category')->default(1)->nullable(true);

            $table->tinyInteger('enable_price_tax')->unsigned()->notNull()->default(1);
            $table->tinyInteger('enable_line_discount_for_purchase')->unsigned()->default(0);
            $table->tinyInteger('enable_line_discount_for_sale')->unsigned()->default(0);
            $table->tinyInteger('enable_purchase_status')->unsigned()->default(1);
            $table->tinyInteger('enable_lot_number')->unsigned()->notNull()->default(0);
            $table->integer('default_unit')->nullable()->default(NULL);
            $table->tinyInteger('enable_sub_units')->unsigned()->notNull()->default(0);
            $table->tinyInteger('enable_racks')->unsigned()->notNull()->default(0);
            $table->tinyInteger('enable_position')->unsigned()->notNull()->default(0);
            $table->tinyInteger('enable_editing_product_from_purchase')->unsigned()->notNull()->default(1);
            $table->enum('sales_cmsn_agnt', ['value1', 'value2', 'value3'])->nullable()->default(NULL);
            $table->tinyInteger('item_addition_method')->unsigned()->notNull()->default(1);
            $table->tinyInteger('enable_inline_tax')->unsigned()->notNull()->default(1);
            $table->enum('currency_symbol_placement', ['before', 'after'])->notNull()->default('after');
            $table->text('enabled_modules')->nullable();
            $table->string('date_format', 191)->notNull()->default('m/d/Y');
            $table->enum('time_format', ['12', '24'])->notNull()->default('12');
            $table->text('ref_no_prefixes')->nullable();
            $table->char('theme_color', 20)->nullable()->default(NULL);
            $table->integer('created_by')->nullable()->default(NULL);
            $table->text('repair_settings')->nullable();
            $table->tinyInteger('enable_rp')->unsigned()->notNull()->default(0)->comment('rp is the short form of reward points');
            $table->string('rp_name', 191)->nullable()->default(NULL)->comment('rp is the short form of reward points');
            $table->decimal('amount_for_unit_rp', 22, 4)->unsigned()->notNull()->default(1.0000)->comment('rp is the short form of reward points');
            $table->decimal('min_order_total_for_rp', 22, 4)->unsigned()->notNull()->default(1.0000)->comment('rp is the short form of reward points');
            $table->integer('max_rp_per_order')->nullable()->default(NULL)->comment('rp is the short form of reward points');
            $table->decimal('redeem_amount_per_unit_rp', 22, 4)->unsigned()->notNull()->default(1.0000)->comment('rp is the short form of reward points');
            $table->decimal('min_order_total_for_redeem', 22, 4)->unsigned()->notNull()->default(1.0000)->comment('rp is the short form of reward points');
            $table->integer('min_redeem_point')->nullable()->default(NULL)->comment('rp is the short form of reward points');
            $table->integer('max_redeem_point')->nullable()->default(NULL)->comment('rp is the short form of reward points');
            $table->integer('rp_expiry_period')->nullable()->default(NULL)->comment('rp is the short form of reward points');

            $table->enum('rp_expiry_type', ['year', 'month'])->default('year')->nullable()->comment('rp is the short form of reward points');
            $table->text('email_settings')->nullable();
            $table->text('sms_settings')->nullable();
            $table->text('custom_labels')->nullable();
            $table->text('common_settings')->nullable();
            $table->boolean('is_active')->default(true)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bussiness_settings');
    }
};
