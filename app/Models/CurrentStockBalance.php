<?php

namespace App\Models;

use App\Models\settings\businessLocation;
use Carbon\Traits\Units;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrentStockBalance extends Model
{
    use HasFactory;
    protected $table = 'current_stock_balance';
    protected $fillable = [
        'business_location_id',
        'product_id',
        'variation_id',
        'transaction_type',
        'transaction_detail_id',
        'batch_no',
        'lot_serial_no',
        'expired_date',
        'ref_uom_id',
        'ref_uom_quantity',
        'ref_uom_price',
        'current_quantity',
        'created_at',
        'lot_serial_type',

    ];

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class, 'transaction_detail_id');
    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class, 'ref_uom_id', 'id');
    }

    public function smallest_unit()
    {
        return $this->hasOne(Unit::class,'id' , 'smallest_unit_id');
    }
    public function smallestUnit()
    {
        return $this->belongsTo(Unit::class, 'smallest_unit_id');
    }

    public function packagingtx(){
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'transaction_detail_id')
            ->where('transaction_type', 'purchase');
    }

    public function packaging_uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class, 'uom_id', 'id');
    }
}
