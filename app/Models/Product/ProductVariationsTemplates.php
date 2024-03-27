<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\VariationTemplates;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariationsTemplates extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_variations_tmplates';

    protected $fillable = [
        'product_id',
        'variation_template_id',
        'created_by'
    ];

    public function variationTemplate() : BelongsTo
    {
        return $this->belongsTo(VariationTemplates::class);
    }
}
