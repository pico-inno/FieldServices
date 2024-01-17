<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class locationProduct extends Model
{
    use HasFactory;
    public $table= 'location_product';
    public $timestamps=false;
    protected $fillable=[
            'location_id',
            'product_id'
    ];

    public function location()
    {
        return $this->belongsTo(businessLocation::class, 'location_id');
    }

    public function location_name()
    {
        return $this->belongsTo(businessLocation::class, 'location_id')->name;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
