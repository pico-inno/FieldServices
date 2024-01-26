<?php

namespace App\Models;

use App\Models\sale\sale_details;
use Modules\Restaurant\Entities\table;
use Illuminate\Database\Eloquent\Model;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class resOrders extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'table_id',
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
    public function location(){
        return $this->hasOne(businessLocation::class,'id','location_id');
    }

    public function table()
    {
        return $this->hasOne(table::class, 'id', 'table_id');
    }
}
