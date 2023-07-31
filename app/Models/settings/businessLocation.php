<?php

namespace App\Models\settings;

use App\Models\Product\PriceLists;
use App\Models\Stock\Stockin;
use App\Models\Stock\StockTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class businessLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'location_id',
        'name',
        'allow_purchase_order',
        'allow_sale_order',
        'price_lists_id',
        'landmark',
        'country',
        'state',
        'city',
        'zip_code',
        'mobile',
        'alternate_number',
        'email',
        'website',
        'featured_products',
        'is_active',
        'custom_field1',
        'custom_field2',
        'custom_field3',
        'custom_field4',

    ];

    // protected $casts = [
    //     'featured_products' => 'array',
    // ];

    protected $dates = ['deleted_at'];

    // public function business()
    // {
    //     return $this->belongsTo(Business::class);
    // }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    public function stockins()
    {
        return $this->hasMany(Stockin::class);
    }

    public function stocktransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }

    public function pricelist()
    {
        return $this->belongsTo(PriceLists::class, 'price_lists_id');
    }
}
