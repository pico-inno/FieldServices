<?php
//
//namespace App\Models\Stock;
//
//use App\Models\Product\Product;
//use App\Models\Product\ProductVariation;
//use App\Models\Product\Unit;
//use App\Models\Product\UOM;
//use App\Models\Product\UOMSet;
//use App\Models\Product\VariationTemplateValues;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\HasMany;
//use Illuminate\Database\Eloquent\Relations\HasOne;
//use Illuminate\Database\Eloquent\SoftDeletes;
//
//class StockoutDetail extends Model
//{
//    use HasFactory;
//    use SoftDeletes;
//    public $timestamps = true;
//
//    protected $fillable = [
//        'stockout_id',
//        'product_id',
//        'variation_id',
//        'transaction_type',
//        'transaction_detail_id',
//        'lot_no',
//        'current_stock_balance_id',
//        'purchase_price',
//        'expired_date',
//        'uom_id',
//        'unit_id',
//        'quantity',
//        'remark',
//        'created_by',
//    ];
//
//
//    public function product(): HasOne
//    {
//        return $this->hasOne(Product::class, 'id', 'product_id');
//    }
//
//    public function variationTempalteValues(): HasOne
//    {
//        return $this->hasOne(VariationTemplateValues::class,'id','variation_id');
//    }
//    public function productVariation()
//    {
//        return $this->hasOne(ProductVariation::class,'id','variation_id');
//    }
//
//    public function uomSet(): HasOne
//    {
//        return $this->hasOne(UOMSet::class, 'id', 'uomset_id');
//    }
//    public function uom(): HasOne
//    {
//        return $this->hasOne(UOM::class, 'id', 'uom_id');
//    }
//    public function unit(): HasOne
//    {
//        return $this->hasOne(Unit::class, 'id', 'unit_id');
//    }
//    public function stockOut(){
//        return $this->hasOne(Stockout::class,'id','stockout_id');
//    }
//}
