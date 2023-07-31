<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UOM extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'uom';

    protected $fillable = [
        'name',
        'short_name',
        'unit_category_id',
        'unit_type',
        'value',
        'rounded_amount',
        'created_by',
        'updated_by'
    ];

    public function unit_category(): BelongsTo
    {
        return $this->belongsTo(UnitCategory::class);
    }
}
