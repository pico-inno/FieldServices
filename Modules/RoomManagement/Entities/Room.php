<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_id',
        'room_type_id',
        'name',
        'description',
        'max_occupancy',
        'is_active',
        'status',
        'custom_field_1',
        'custom_field_2',
        'custom_field_3',
        'custom_field_4',
        'created_by',
        'updated_by'
    ];

    public function floor(){
        return $this->belongsTo(\App\Models\settings\Floor::class);
    }
        
    public function room_type(){
        return $this->belongsTo(\Modules\RoomManagement\Entities\RoomType::class);
    }

    public function beds(){
        return $this->hasMany(\Modules\RoomManagement\Entities\Bed::class);
    }

    public function room_reservations(){
        return $this->hasMany(\Modules\Reservation\Entities\RoomReservation::class);
    }
}
