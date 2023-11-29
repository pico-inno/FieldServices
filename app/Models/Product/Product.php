<?php

namespace App\Models\Product;

use App\Models\Product\UOM;
use App\Models\productPackaging;
use App\Models\CurrentStockBalance;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ComboKit\Entities\ReceipeOfMaterial;
use App\Models\Product\ProductVariationsTemplates;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'has_variation',
        'brand_id',
        'category_id',
        'sub_category_id',
        'manufacturer_id',
        'generic_id',
        'lot_count',
        'uom_id',
        'purchase_uom_id',
        'can_sale',
        'can_purchase',
        'can_expense',
        'can_expense',
        'is_recurring',
        'receipe_of_material_id',
        'product_custom_field1',
        'product_custom_field2',
        'product_custom_field3',
        'product_custom_field4',
        'image',
        'product_description',
        'is_inactive',
        'created_by',
        'updated_by',
        'deleted_by',


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
    public function product_variations():HasOne
    {
        return $this->hasOne(ProductVariation::class,'id', 'variation_id');
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
    public function purchaseUOM(): BelongsTo
    {
        return $this->belongsTo(UOM::class, 'purchase_uom_id','id');
    }

    public function stock()
    {
        return $this->hasMany(CurrentStockBalance::class);
    }
    public function variationTemplateValue(): BelongsTo
    {
        return $this->belongsTo(VariationTemplateValues::class, 'variation_template_value_id', 'id');
    }

    public function varPackaging(): HasOne
    {
        return $this->hasOne(productPackaging::class, 'product_variation_id', 'product_variations.id');
    }
    public function product_packaging(): HasOne
    {
        return $this->hasOne(productPackaging::class, 'product_id', 'id');
    }

    public function rom(): HasOne
    {
        return $this->hasOne(ReceipeOfMaterial::class, 'id', 'receipe_of_material_id');
    }

}
