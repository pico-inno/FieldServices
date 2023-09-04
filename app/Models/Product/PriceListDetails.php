<?php

namespace App\Models\Product;

use App\Models\Product\PriceLists;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceListDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricelist_id',
        'applied_type',
        'applied_value',
        'min_qty',
        'from_date',
        'to_date',
        'cal_type',
        'cal_value',
        'base_price',
        'cal_profit_discount',
        'rounding_method',
        'extra_price',
        'margin_type',
        'min_margin',
        'max_margin'
    ];

        // Define the parent relationship
        public function currentPrice()
        {
            return $this->hasMany(PriceListDetails::class, 'base_price');
        }

        // Define the children relationship
        public function base_price_data()
        {
            return $this->belongsTo(PriceListDetails::class, 'base_price');
        }
        public function base_price_list()
        {
            return $this->belongsTo(PriceLists::class, 'base_price','id');
        }
}
