<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RoomReservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'reservation_id',
        'room_type_id',
        'room_id',
        'guest_id',
        'room_rate_id',
        'room_check_in_date',
        'room_check_out_date',
        'remark',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function reservation() {
        return $this->belongsTo(\Modules\Reservation\Entities\Reservation::class);
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

    public function contact() {
        return $this->belongsTo(\App\Models\Contact\Contact::class);
    }
}
