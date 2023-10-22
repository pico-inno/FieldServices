<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
