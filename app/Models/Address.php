<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state_province_region',
        'postal_zip_code',
        'country',
        'phone',
        'is_default',
    ];
}
