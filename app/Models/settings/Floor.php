<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'business_location_id',
        'name',
        'created_by',
        'updated_by'
    ];

    public function building(){
        return $this->belongsTo(\App\Models\settings\Building::class);
    }

    public function business_location(){
        return $this->belongsTo(\App\Models\settings\businessLocation::class);
    }

    public function rooms(){
        return $this->hasMany(\Modules\RoomManagement\Entities\Room::class);
    }
}
