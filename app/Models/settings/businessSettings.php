<?php

namespace App\Models\settings;

use App\Models\BusinessUser;
use App\Models\Currencies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class businessSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'currency_id',
        'lot_control',
        'start_date',
        'default_profit_percent',
        'currency_decimal_places',
        'quantity_decimal_places',
        'currency_symbol_placement',
        'finanical_year_start_month',
        'owner_id',
        'time_zone',
        'fy_start_month',
        'accounting_method',
        'use_paymentAccount',
        'default_sales_discount',
        'sell_price_tax',
        'logo',
        'sku_prefix',
        'enable_product_expiry',
        'expiry_type',
        'on_product_expiry',
        'stop_selling_before',
        'enable_tooltip',
        'enable_line_discount_for_purchase',
        'enable_line_discount_for_sale',
        'purchase_in_diff_currency',
        'purchase_currency_id',
        'p_exchange_rate',
        'transaction_edit_days',
        'stock_expiry_alert_days',
        'keyboard_shortcuts',
        'pos_settings',
        'essentials_settings',
        'weighing_scale_setting',
        'enable_brand',
        'enable_category',
        'enable_sub_category',
        'enable_price_tax',
        'enable_purchase_status',
        'enable_lot_number',
        'default_unit',
        'enable_sub_units',
        'enable_racks',
        'enable_row',
        'enable_position',
        'enable_editing_product_from_purchase',
        'sales_cmsn_agnt',
        'item_addition_method',
        'enable_inline_tax',
        'currency_symbol_placement',
        'enabled_modules',
        'date_format',
        'time_format',
        'ref_no_prefixes',
        'theme_color',
        'created_by',
        'repair_settings',
        'enable_rp',
        'rp_name',
        'amount_for_unit_rp',
        'min_order_total_for_rp',
        'max_rp_per_order',
        'redeem_amount_per_unit_rp',
        'min_order_total_for_redeem',
        'min_redeem_point',
        'max_redeem_point',
        'rp_expiry_period',
        'rp_expiry_type',
        'email_settings',
        'sms_settings',
        'custom_labels',
        'common_settings',
        'is_active'

    ];

    public function currency(){
        return $this->hasOne(Currencies::class,'id','currency_id');
    }
    public function owner()
    {
        return $this->hasOne(BusinessUser::class, 'id', 'owner_id');
    }
}
