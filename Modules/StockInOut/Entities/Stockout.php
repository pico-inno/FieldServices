<?php

namespace Modules\StockInOut\Entities;

use App\Models\BusinessUser;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockout extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'business_location_id',
        'stockout_voucher_no',
        'stockout_date',
        'stockout_person',
        'status',
        'noet',
        'created_by'
    ];

    public function businessLocation(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
    }
    public function stockoutdetails()
    {
        return $this->hasMany(StockoutDetail::class);
    }
    public function stockoutPerson()
    {
        return $this->belongsTo(BusinessUser::class, 'stockout_person');
    }

    public function created_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
    }
}
