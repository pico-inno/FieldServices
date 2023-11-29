<?php

namespace App\Models\Stock;

use App\Models\CurrentStockBalance;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use App\Models\Product\VariationTemplateValues;
use App\Models\productPackagingTransactions;
use App\Models\Stock\StockAdjustment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjustmentDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'adjustment_id',
        'product_id',
        'variation_id',
        'uom_id',
        'balance_quantity',
        'gnd_quantity',
        'adjustment_type',
        'adj_quantity',
        'uom_price',
        'subtotal',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class,'adjustment_id','id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function variationTempalteValues(): HasOne
    {
        return $this->hasOne(VariationTemplateValues::class,'id','variation_id');
    }

    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class,'id','variation_id');
    }

    public function uom()
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }

    public function stock()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }
    public function Currentstock()
    {
        return $this->hasOne(CurrentStockBalance::class, 'transaction_detail_id', 'id');
    }

    public function packagingTx() {
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'id')
            ->where('transaction_type','adjustment');
    }
}
