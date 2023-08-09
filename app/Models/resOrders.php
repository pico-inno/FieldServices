<?php

namespace App\Models;

use App\Models\sale\sale_details;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resOrders extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'order_voucher_no',
        'order_status',
        'location_id',
        'services',
        'pos_register_id',
    ];
    public function saleDetail(){
        return $this->hasMany(sale_details::class,'rest_order_id','id')
                    ->with('saleWithTable');
    }
}
