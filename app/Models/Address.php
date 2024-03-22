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
        'township_id',
        'region_id',
        'postal_zip_code',
        'country',
        'phone',
        'is_default',
    ];

    public function township()
    {
        return $this->belongsTo(Township::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
