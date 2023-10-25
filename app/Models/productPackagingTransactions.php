<?php

namespace App\Models;

use App\Models\productPackaging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class productPackagingTransactions extends Model
{
    use HasFactory;
    protected $fillable=[
        'transaction_type' ,
        'transaction_details_id' ,
        'product_packaging_id' ,
        'quantity',

        'created_at' ,
        'created_by' ,
        'updated_at' ,
        'updated_by' ,
        'is_delete' ,
        'deleted_at' ,
        'deleted_by' ,
    ];

    public function packaging(){
        return $this->hasOne(productPackaging::class,'id', 'product_packaging_id');
    }
}
