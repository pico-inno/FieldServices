<?php

namespace App\Models;

use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use App\Models\Product\VariationTemplateValues;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class openingStockDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'opening_stock_id',
        'product_id',
        'variation_id',
        'lot_no',
        'expired_date',
        'uom_id',
        'subtotal',
        'quantity',
        'uom_price',
        'ref_uom_price',
        'ref_uom_id',
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
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function variationTempalteValues(): HasOne
    {
        return $this->hasOne(VariationTemplateValues::class, 'id', 'variation_id');
    }
    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }
    public function uom(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }
    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
    public function openingStock(){
        return $this->belongsTo(openingStocks::class);
    }
    public function packagingTx()
    {
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'id')
            ->where('transaction_type', 'opening_stock');
    }
}
