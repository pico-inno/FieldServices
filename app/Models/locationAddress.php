<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class locationAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'location_id',
        'mobile',
        'alternate_number',
        'email',
        'address',
        'country',
        'state',
        'city',
        'zip_postal_code',
    ];
}
