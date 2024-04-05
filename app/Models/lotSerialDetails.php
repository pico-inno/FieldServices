<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lotSerialDetails extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable=[
        'transaction_type',
        'transaction_detail_id',
        'current_stock_balance_id',
        'lot_serial_numbers',
        'expired_date',
        'uom_id',
        'uom_quantity',
        'ref_uom_quantity',
        'is_prepare',
        'stock_status'
    ];



    public function current_stock_balance(){
        return $this->belongsTo(CurrentStockBalance::class, 'current_stock_balance_id');
    }
}
