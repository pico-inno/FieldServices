<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
