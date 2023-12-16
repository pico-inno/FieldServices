<?php

namespace App\Models\Stock;

use App\Models\BusinessUser;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'adjustment_voucher_no',
        'business_location',
        'condition',
        'status',
        'increase_subtotal',
        'decrease_subtotal',
        'adjustmented_at',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];


    public function businessLocation(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'business_location', 'id');
    }

    public function adjustmentDetails(): HasMany
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'adjustment_id', 'id');
    }
    public function created_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
    }

    public function createdPerson(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
    }
}
