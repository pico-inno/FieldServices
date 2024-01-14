<?php

namespace Modules\ComboKit\Entities;

use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\UOM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceipeOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rom_type',
        'product_id',
        'uom_id',
        'quantity',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function rom_details(){
        return $this->hasMany(ReceipeOfMaterialDetail::class, 'receipe_of_material_id', 'id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function product_variation(): HasOne
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'product_id');
    }

//    public function uom()
//    {
//        return $this->hasOne(UOM::class, 'id', 'uom_id');
//    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }

//    public function productVariation()
//    {
//        return $this->hasOne(ProductVariation::class,'id','product_id');
//    }


}
