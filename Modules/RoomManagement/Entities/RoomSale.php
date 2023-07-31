<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomSale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'transaction_type',
        'transaction_id',
        'room_sales_voucher_no',
        'business_location_id',
        'contact_id',
        'sale_amount',
        'total_item_discount',
        'total_sale_amount',
        'paid_amount',
        'balance_amount',
        'currency_id',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function reservation() {
        return $this->belongsTo(\Modules\Reservation\Entities\Reservation::class, 'transaction_id');
    }

    public function hospital_registration() {
        return $this->belongsTo(\App\Models\hospitalRegistrations::class, 'transaction_id');
    }

    public function business_location() {
        return $this->belongsTo(\App\Models\settings\businessLocation::class);
    }

    public function room_sale_details() {
        return $this->hasMany(\Modules\RoomManagement\Entities\RoomSaleDetail::class)->with('room_type','room_rate','room');
    }
    
}
