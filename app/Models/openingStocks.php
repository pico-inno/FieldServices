<?php

namespace App\Models;

use App\Models\BusinessUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class openingStocks extends Model
{
    use HasFactory;
    protected $fillable = [
        'business_location_id',
        'opening_stock_voucher_no',
        'expired_date',
        'opening_date',
        'opening_person',
        'status',
        'note',
        'total_opening_amount',
        'confirm_at',
        'confirm_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];
    public function business_location_id(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
    }
    public function businessLocation(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
    }
    public function opening_person(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'opening_person');
    }
    public function openingPerson(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'opening_person');
    }
    public function confirm_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'confirm_by');
    }
    public function created_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
    }
    public function updated_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'updated_by');
    }

}
