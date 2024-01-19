<?php

namespace App\Models\sale;

use App\Models\Currencies;
use App\Models\Product\UOM;
use App\Models\BusinessUser;
use App\Models\Product\Unit;
use App\Models\kitSaleDetails;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use App\Models\lotSerialDetails;
use App\Models\productPackaging;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use App\Models\productPackagingTransactions;
use App\Models\Product\VariationTemplateValues;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Currency;

class sale_details extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_id',
        'product_id',
        'variation_id',
        'parent_id',
        'rest_order_id',
        'rest_order_status',
        'uom_id',
        'quantity',
        'uom_price',
        'subtotal',
        'discount_type',
        'per_item_discount',
        'subtotal_with_discount',
        'price_list_id',
        'line_subtotal_discount',
        'tax_amount',
        'per_item_tax',
        'subtotal_with_tax',
        'currency_id',
        'note',
        'delivered_quantity',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];
    public function purchase()
    {
        return $this->belongsTo(purchases::class, 'purchases_id');
    }
    public function sale(){
        return $this->hasOne(sales::class, 'id', 'sales_id');
    }
    public function saleWithTable(){
        return $this->hasOne(sales::class, 'id', 'sales_id')->with('table');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class,'id','variation_id')->with('variationTemplateValue');
    }
    // public function uomSet(): HasOne
    // {
    //     return $this->hasOne(UOMSet::class, 'id', 'uomset_id');
    // }
    // public function unit(): HasOne
    // {
    //     return $this->hasOne(Unit::class, 'id', 'unit_id');
    // }
    public function uoms(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'uoms_id');
    }
    public function uom(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }

    public function stock()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }
    public function Currentstock()
    {
        return $this->hasOne(CurrentStockBalance::class, 'id', 'current_stock_balance_id');
    }
    public function current_lot()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'variation_id');
    }
    public function currency()
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }

    public function packagingTx()
    {
        return $this->hasOne(productPackagingTransactions::class, 'transaction_details_id', 'id')
            ->where('transaction_type', 'sale');
    }

    public function product_packaging(): HasOne
    {
        return $this->hasOne(productPackaging::class, 'product_id', 'id');
    }
    public function kitSaleDetails():HasMany{
        return $this->hasMany(kitSaleDetails::class, 'sale_details_id','id');
    }

    public function lotSerialDetail()
    {
        return $this->hasMany(lotSerialDetails::class, 'transaction_detail_id', 'id')->where("transaction_type", 'sale');
    }
}

