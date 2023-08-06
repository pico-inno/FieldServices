<?php

namespace Modules\orderDisplay\Entities;

use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDisplay extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'name',
        'location_id',
        'pos_register_id',
        'product_category_id'
    ];
    public function location(){
        return $this->hasOne(businessLocation::class,'id','location_id');
    }
}
