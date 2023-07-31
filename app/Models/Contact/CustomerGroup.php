<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'amount',
        'price_calculation_type',
        'selling_price_group_id',
        'created_by'
    ];
}
