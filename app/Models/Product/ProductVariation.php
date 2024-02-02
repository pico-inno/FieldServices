<?php

namespace App\Models\Product;

use App\Models\CurrentStockBalance;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\AdditionalProduct;
use App\Models\productPackaging;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'variation_sku',
        'variation_template_value_id',
        'default_purchase_price',
        'profit_percent',
        'alert_quantity',
        'default_selling_price',
        'created_by',
        'updated_by'
    ];

    protected $guarded = ['id'];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function additionalProduct()
    {
        return $this->hasMany(AdditionalProduct::class, 'primary_product_variation_id','id');
    }

    public function variationTemplateValue() : BelongsTo
    {
        return $this->belongsTo(VariationTemplateValues::class, 'variation_template_value_id', 'id');
    }
    public function packaging(): HasMany
    {
        return $this->hasMany(productPackaging::class, 'product_variation_id', 'id');
    }
    // scope

    public function scopeByProductId($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function uomSellingPrice(){
        return $this->hasMany(UOMSellingprice::class, 'product_variation_id','id');
    }

    public function current_stock()
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'id');
    }


}
