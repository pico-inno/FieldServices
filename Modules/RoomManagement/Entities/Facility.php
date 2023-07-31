<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function building(){
        return $this->belongsTo(\App\Models\settings\Building::class);
    }
}
