<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    public function beds(){
        return $this->hasMany(\Modules\RoomManagement\Entities\Bed::class);
    }
}
