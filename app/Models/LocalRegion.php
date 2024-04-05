<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'mm_name',
        'en_name'
    ];
}
