<?php

namespace Modules\Service\Entities;

use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Services extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'service_code',
        'service_type_id',
        'active',
        'uom_id',
        'price'
    ];

    public function serviceType() : BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }
}
