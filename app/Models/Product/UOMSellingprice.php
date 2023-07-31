<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UOMSellingprice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'uom_sellingprices';

    protected $fillable = [
        'product_variation_id',
        'uom_id',
        'pricegroup_id',
        'price_inc_tax'
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    public function pricegroup() : BelongsTo
    {
        return $this->belongsTo(PriceGroup::class, 'pricegroup_id', 'id');
    }

    public function uom() : BelongsTo
    {
        return $this->belongsTo(UOM::class, 'uom_id', 'id');
    }

    // scope
    public function scopeById($query, $id)
    {
        return $query->whereIn('id', $id);
    }
    
    public function scopeByProductVariationIds($query, $variationIds)
    {
        return $query->whereIn('product_variation_id', $variationIds);
    }

    public function scopeByUomIds($query, $uomIds)
    {
        return $query->whereIn('uom_id', $uomIds);
    }

    public function scopeByPriceGroupId($query, $pgId)
    {
        return $query->where('pricegroup_id', $pgId);
    }
}
