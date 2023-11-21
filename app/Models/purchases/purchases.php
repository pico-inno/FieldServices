<?php

namespace App\Models\purchases;

use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use App\Models\Currencies;
use Illuminate\Database\Eloquent\Model;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class purchases extends Model
{
    use HasFactory;
    protected $fillable = [
        'business_location_id',
        'contact_id',
        'purchase_voucher_no',
        'status',
        'purchase_amount',
        'total_line_discount',
        'extra_discount_type',
        'extra_discount_amount',
        'totid_amount',
        'total_discount_amount',
        'purchase_expense',
        'total_purchase_amount',
        'balance_amount',
        'payment_status',
        'payment_account',
        'paid_amount',
        'purchased_at',
        'purchased_by',
        'confirm_at',
        'confirm_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'currency_id',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function business_location_id(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
    }

    public function businessLocation()
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id','id');
    }


    public function supplier(): HasOne
    {
        return $this->hasOne(Contact::class,'id','contact_id');
    }
    public function purchase_details(): HasMany
    {
       return $this->hasMany(purchase_details::class);
    }
    public function purchased_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class,'id', 'purchased_by');
    }
    public function purchase_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class,'id', 'purchased_by');
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
    public function currency():HasOne{
        return $this->hasOne(Currencies::class,'id','currency_id');
    }
}
