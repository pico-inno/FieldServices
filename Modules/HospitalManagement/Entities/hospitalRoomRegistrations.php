<?php

namespace Modules\HospitalManagement\Entities;

use Modules\RoomManagement\Entities\Room;
use App\Models\hospitalRegistrations;
use Modules\RoomManagement\Entities\RoomRate;
use Modules\RoomManagement\Entities\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class hospitalRoomRegistrations extends Model
{
    use HasFactory;
    protected $fillable=[
        'registration_id',
        'patient_id',
        'room_type_id',
        'room_rate_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'created_at',
        'created_by',
        'update_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];

    public function room_type()
    {
        return $this->hasOne(RoomType::class, 'id', 'room_type_id');
    }
    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
    public function rate()
    {
        return $this->hasOne(RoomRate::class, 'id', 'room_rate_id');
    }
    public function registration()
    {
        return $this->hasOne(hospitalRegistrations::class, 'id', 'registration_id')->with('patient');
    }
}
