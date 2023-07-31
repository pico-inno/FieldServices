<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    public function rooms() {
        return $this->hasMany(\Modules\RoomManagement\Entities\Room::class);
    }

    public function room_rates() {
        return $this->hasMany(\Modules\RoomManagement\Entities\RoomRate::class);
    }
}
