<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUsedProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_sale_detail_id',
        'service_id',
        'product_id',
        'variation_id',
        'quantity',
        'uom_id'
    ];
}
