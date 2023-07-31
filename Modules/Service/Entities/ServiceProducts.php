<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'product_id',
        'variation_id',
        'uom_id',
        'quantity'
    ];

    public function service() : BelongsTo
    {
        return $this->belongsTo(Services::class);
    }
}
