<?php

namespace Modules\Service\Entities;

use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Model;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceSale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_location_id',
        'contact_id',
        'service_voucher_no',
        'service_status',
        'sale_amount',
        'service_discount_type',
        'discount_amount',
        'total_sale_amount',
        'paid_amount',
        'balance',
        'remark',
        'is_delete',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function businessLocation() : BelongsTo
    {
        return $this->belongsTo(businessLocation::class);
    }

    public function contact() : BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function serviceSaleDetails() : HasMany
    {
        return $this->hasMany(ServiceSaleDetail::class);
    }
}
