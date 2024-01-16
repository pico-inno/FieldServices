<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class locationProduct extends Model
{
    use HasFactory;
    public $table= 'location_product';
    public $timestamps=false;
    protected $fillable=[
            'location_id',
            'product_id'
    ];
}
