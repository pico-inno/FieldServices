<?php

namespace App\Models;

use App\Models\Product\UnitCategory;
use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class productPackaging extends Model
{
    use HasFactory;
    protected $table = 'product_packagings';

    protected $fillable = [
        'packaging_name',
        'product_id',
        'product_variation_id',
        'quantity',
        'uom_id',
        'package_barcode',
        'for_purchase',
        'for_sale',
        'package_barcode',
    ];

    public function uom()
    {
       return $this->hasOne(UOM::class,'id','uom_id');
    }
}
