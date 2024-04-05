<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalTownship extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'mm_name',
        'en_name'
    ];
}
