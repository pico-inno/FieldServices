<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary_product_id',
        'primary_product_variation_id',
        'additional_product_variation_id',
        'uom_id',
        'quantity',
    ];

    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class,'id','additional_product_variation_id');
    }
    public function uom()
    {
        return $this->hasOne(UOM::class,'id', 'uom_id');
    }
//    public function product(): HasOne
//    {
//        return $this->hasOne(Product::class, 'id', 'primary_product_id');
//    }
}
