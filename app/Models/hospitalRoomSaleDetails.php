<?php
namespace App\Models;

use App\Models\Contact\Contact;
use Modules\RoomManagement\Entities\Room;
use Modules\RoomManagement\Entities\RoomRate;
use Modules\RoomManagement\Entities\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class hospitalRoomSaleDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_sales_id',
        'room_type_id',
        'room_id',
        'patient_id',
        'room_rate_id',
        'check_in_date',
        'check_out_date',
        'qty',
        'before_discount_amount',
        'discount_type',
        'discount_amount',
        'after_discount_amount',
        'amount_inc_tax',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];

    public function room_type()
    {
        return $this->hasOne(RoomType::class,'id','room_type_id');
    }
    public function room()
    {
        return $this->hasOne(Room::class,'id','room_id');
    }
    public function rate()
    {
        return $this->hasOne(RoomRate::class,'id','room_rate_id');
    }
    public function registration()
    {
        return $this->hasOne(hospitalRegistrations::class, 'id', 'registration_id')->with('patient');
    }
}
