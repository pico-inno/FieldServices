<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceSaleDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'service_sale_id',
        'service_id',
        'uom_id',
        'quantity',
        'sale_price_without_discount',
        'service_detail_discount_type',
        'discount_amount',
        'sale_price',
        'sale_price_inc_tax',
        'is_delete',
        'created_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function serviceSale() : BelongsTo
    {
        return $this->belongsTo(ServiceSale::class);
    }

    public function service() : BelongsTo
    {
        return $this->belongsTo(Services::class);
    }

    public function serviceUsedProducts() : HasMany
    {
        return $this->hasMany(ServiceUsedProducts::class);
    }
}
