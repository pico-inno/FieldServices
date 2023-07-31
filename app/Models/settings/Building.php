<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    public function floors(){
        return $this->hasMany(\App\Models\settings\Floor::class);
    }

    public function facilities(){
        return $this->hasMany(\Modules\RoomManagement\Entities\Facility::class);
    }
}
