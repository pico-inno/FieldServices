<?php

namespace Modules\StockInOut\Entities;


use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use App\Models\Product\VariationTemplateValues;
use App\Models\purchases\purchase_details;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\StockInOut\Entities\Stockin;


class StockinDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'stockin_id',
        'product_id',
        'variation_id',
        'transaction_type',
        'transaction_detail_id',
        'uom_id',
        'quantity',
        'remark',
        'created_by',
    ];

    public function stockin(){
        return $this->belongsTo(Stockin::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function purchaseDetail(){
        return $this->hasOne(purchase_details::class, 'id', 'transaction_detail_id');
    }

    public function variationTempalteValues(): HasOne
    {
        return $this->hasOne(VariationTemplateValues::class,'id','variation_id');
    }
    public function productVariation()
    {
        return $this->hasOne(ProductVariation::class,'id','variation_id');
    }


    public function uom(): HasOne
    {
        return $this->hasOne(UOM::class, 'id', 'uom_id');
    }

}
