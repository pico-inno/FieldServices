<?php

namespace Modules\ComboKit\Entities;

use App\Models\CurrentStockBalance;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceipeOfMaterialDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipe_of_material_id',
        'component_variation_id',
        'applied_variation_id',
        'quantity',
        'uom_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function rom() : BelongsTo
    {
        return $this->belongsTo(ReceipeOfMaterial::class, 'receipe_of_material_id', 'id');
    }


    public function productVariation(): HasOne
    {
        return $this->hasOne(ProductVariation::class,'id','component_variation_id');
    }

    public function applied_variation(): HasOne
    {
        return $this->hasOne(ProductVariation::class,'id','applied_variation_id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'component_variation_id');
    }

//    public function uom()
//    {
//        return $this->hasOne(UOM::class, 'id', 'uom_id');
//    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }

    public function csbrecord() : HasMany
    {
        return $this->hasMany(CurrentStockBalance::class, 'variation_id', 'component_variation_id');
    }
}
