<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
