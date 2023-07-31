<?php

namespace App\Models;

use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class printers extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'name',
        'printer_type',
        'ip_address',
        'product_category_id',
    ];
    public function category(){
        return $this->hasOne(Category::class,'id','product_category_id');
    }
}
