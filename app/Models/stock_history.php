<?php

namespace App\Models;

use App\Models\Product\UOM;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use App\Models\purchases\purchase_details;
use App\Models\sale\sale_details;
use App\Models\sale\sales;
use App\Models\settings\businessLocation;
use App\Models\Stock\StockAdjustmentDetail;
//use App\Models\Stock\StockinDetail;
//use App\Models\Stock\StockoutDetail;
use App\Models\Stock\StockTransferDetail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\StockInOut\Entities\StockinDetail;
use Modules\StockInOut\Entities\StockoutDetail;

class stock_history extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'ref_uom_price',
        'business_location_id',
        'product_id',
        'variation_id',
        'lot_serial_no',
        'expired_date',
        'transaction_type',
        'transaction_details_id',
        'increase_qty',
        'decrease_qty',
        'ref_uom_id',
        'balance_quantity',
        'created_at',
    ];
    public function business_location()
    {
        return $this->hasOne(businessLocation::class, 'id', 'business_location_id');
    }
    public function uom()
    {
        return $this->hasOne(UOM::class, 'id', 'ref_uom_id');
    }
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }
    public function saleDetail()
    {
        return $this->hasOne(sale_details::class, 'id', 'transaction_details_id');
    }
    public function purchaseDetail()
    {
        return $this->hasOne(purchase_details::class, 'id', 'transaction_details_id');
    }
    public function stockInDetail()
    {
        return $this->hasOne(StockinDetail::class, 'id', 'transaction_details_id');
    }
    public function stockOutDetail()
    {
        return $this->hasOne(StockoutDetail::class, 'id', 'transaction_details_id');
    }
    public function openingStockDetail()
    {
        return $this->hasOne(openingStockDetails::class, 'id', 'transaction_details_id');
    }
    public function adjustmentDetail()
    {
        return $this->hasOne(StockAdjustmentDetail::class, 'id', 'transaction_details_id');
    }
    public function StockTransferDetail()
    {
        return $this->hasOne(StockTransferDetail::class, 'id', 'transaction_details_id');
    }
    public function transaction()
    {
        return $this->morphTo('transaction', 'transaction_type', 'transaction_details_id');
    }
}
