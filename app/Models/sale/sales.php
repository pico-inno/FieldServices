<?php

namespace App\Models\sale;

use App\Models\Currencies;
use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Model;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Restaurant\Entities\table;

class sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_voucher_no',
        'business_location_id',
        'contact_id',
        'status',
        'pos_register_id',
        'table_id',
        'sale_amount',
        'total_line_discount',
        'extra_discount_type',
        'extra_discount_amount',
        'total_sale_amount',
        'paid_amount',
        'balance_amount',
        'total_item_discount',
        'payment_status',
        'currency_id',
        'sold_at',
        'sold_by',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
        'delivered_at',

        'channel_type',
        'channel_id'
    ];


    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class);
    }
    public function customer()
    {
        return $this->hasOne(Contact::class,'id','contact_id');
    }
    public function table()
    {
        return $this->hasOne(table::class,'id','table_id');
    }
    public function business_location_id()
    {
        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

   public function sold_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class,'id', 'sold_by');
    }
    public function sold(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'sold_by');
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
    public function currency()
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }

    public function sale_details(): HasMany
    {
        return $this->hasMany(sale_details::class);
    }
    public function saleDetails(): HasMany{
        return $this->hasMany(sale_details::class)->with('product', 'productVariation', 'uom');
    }
    public function stockHistory()
    {
        return $this->morphMany(StockHistory::class, 'transaction');
    }
}

