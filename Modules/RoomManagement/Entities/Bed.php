<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bed_type_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function bed_type(){
        return $this->belongsTo(\Modules\RoomManagement\Entities\BedType::class);
    }

    public function room(){
        return $this->belongsTo(\Modules\RoomManagement\Entities\Room::class);
    }
}
