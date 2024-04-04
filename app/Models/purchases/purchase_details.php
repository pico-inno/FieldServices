<?php

namespace App\Models\purchases;

use App\Models\Currencies;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use App\Models\Product\VariationValue;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use App\Models\Product\VariationTemplateValues;
use App\Models\productPackaging;
use App\Models\productPackagingTransactions;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class purchase_details extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchases_id',
        'product_id',
        'variation_id',
        'purchase_uom_id',
        'quantity',
        'uom_price',
        'subtotal',
        'discount_type',
        'per_item_discount',
        'subtotal_with_discount',
        'per_item_expense',
        'expense',
        'subtotal_with_expense',
        'tax_amount',
        'per_item_tax',
        'subtotal_with_tax',
        'ref_uom_id',
        'per_ref_uom_price',
        'currency_id',
        'received_quantity',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function purchase(){
        return $this->hasOne(purchases::class, 'id','purchases_id',);
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
    public function purchaseUom(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'purchase_uom_id');
    }
    public function currency(): HasOne
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }
    public function packagingTx() {
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'id')
                ->where('transaction_type','purchase');
    }

    public function product_packaging(): HasOne
    {
        return $this->hasOne(productPackaging::class, 'product_id', 'id');
    }

    public function variation_values() : HasMany
    {
        return $this->hasMany(VariationValue::class, 'product_variation_id', 'variation_id');
    }

}

