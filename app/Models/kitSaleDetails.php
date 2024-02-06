<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class kitSaleDetails extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'sale_details_id',
        'product_id',
        'variation_id',
        'uom_id',
        'quantity'
    ];
    public function product():HasOne {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function productVariation(): HasOne
    {
        return $this->hasOne(ProductVariation::class,'id','variation_id');
    }

    public function uom(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }
}
