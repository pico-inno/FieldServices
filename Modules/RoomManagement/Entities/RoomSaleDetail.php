<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomSaleDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'room_sale_id',
        'room_type_id',
        'room_id',
        'contact_id',
        'room_rate_id',
        'check_in_date',
        'check_out_date',
        'qty',
        'uom_id',
        'room_fees',
        'subtotal',
        'discount_type',
        'per_item_discount',
        'tax_amount',
        'per_item_tax',
        'subtotal_with_tax',
        'currency_id',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function room_sale() {
        return $this->belongsTo(\Modules\RoomManagement\Entities\RoomSale::class, 'room_sale_id');
    }

    public function room_type() {
        return $this->belongsTo(\Modules\RoomManagement\Entities\RoomType::class, 'room_type_id');
    }

    public function room_rate() {
        return $this->belongsTo(\Modules\RoomManagement\Entities\RoomRate::class);
    }

    public function room(){
        return $this->belongsTo(\Modules\RoomManagement\Entities\Room::class);
    }
}
