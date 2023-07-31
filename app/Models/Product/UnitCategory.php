<?php

namespace App\Models\Product;

use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];

    public function uomByCategory(){
       return $this->hasMany(UOM::class, 'unit_category_id','id');
    }
}
