<?php

namespace App\Models\Product;

use App\Models\Product\UOM;
use App\Models\CurrentStockBalance;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product\ProductVariationsTemplates;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'product_code',
        'sku',
        'product_type',
        'brand_id',
        'category_id',
        'sub_category_id',
        'manufacturer_id',
        'generic_id',
        'lot_count',
        'uom_id',
        'purchase_uom_id',
        'product_custom_field1',
        'product_custom_field2',
        'product_custom_field3',
        'product_custom_field4',
        'image',
        'product_description',
        'is_inactive',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function getVariationName(){
        
    }

    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function productVariationTemplates() : HasMany
    {
        return $this->hasMany(ProductVariationsTemplates::class);
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function generic(): BelongsTo
    {
        return $this->belongsTo(Generic::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }

    public function stock()
    {
        return $this->hasMany(CurrentStockBalance::class);
    }
}
