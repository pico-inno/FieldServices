<?php

namespace Modules\HospitalManagement\Entities;
use App\Models\hospitalRoomSales;
use Modules\RoomManagement\Entities\RoomSale;
use App\Models\sale\sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class hospitalFolioInvoiceDetails extends Model
{
    use HasFactory;
    protected $fillable=[
            'folio_invoice_id',
            'transaction_type',
            'transaction_id',
            'transaction_description',
            'confirm_at',
            'confirm_by',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'is_delete',
            'deleted_at',
            'deleted_by',
    ];
    // public function transaction()
    // {
    //     return $this->morphTo(null, 'transaction_type', 'transaction_id');
    // }
    public function roomSales(){
        return $this->hasOne(RoomSale::class,'id','transaction_id')->with('room_sale_details')->where('is_delete', 0);
    }
    public function sales()
    {
        return $this->hasOne(sales::class, 'id', 'transaction_id')->with('saleDetails')->where('is_delete',0);
    }



}
