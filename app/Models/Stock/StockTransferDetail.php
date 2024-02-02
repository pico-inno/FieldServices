<?php

namespace App\Models\Stock;

use App\Models\Currencies;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\Unit;
use App\Models\Product\UOM;
use App\Models\Product\UOMSet;
use App\Models\Product\VariationTemplateValues;
use App\Models\productPackagingTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransferDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transfer_details';

    protected $fillable = [
        'transfer_id',
        'product_id',
        'variation_id',
        'uom_id',
        'uom_price',
        'quantity',
        'subtotal',
        'per_item_expense',
        'expense',
        'subtotal_with_expense',
        'ref_uom_id',
        'per_ref_uom_price',
        'currency_id',
        'remark',
        'confirm_at',
        'confirm_by',
        'created_by',
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
        return $this->hasOne(VariationTemplateValues::class,'id','variation_id');
    }

    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class,'id','variation_id');
    }


    public function stock()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }
    public function Currentstock()
    {
        return $this->hasOne(CurrentStockBalance::class, 'transaction_detail_id', 'id');
    }
    public function current_lot()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class,'transfer_id','id');
    }

    public function uom()
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }

    public function currency()
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }

    public function packagingTx() {
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'id')
            ->where('transaction_type','transfer');
    }

    public function lot_serial_details(): HasMany
    {
        return $this->hasMany(LotSerialDetails::class, 'transaction_detail_id', 'id')
            ->where('transaction_type', 'transfer');
    }

    public function current_stock()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }

}
