<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product\VariationTemplateValues;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariationTemplates extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function variationTemplateValues()
    {
        return $this->hasMany(VariationTemplateValues::class, 'variation_template_id', 'id');
    }

    protected $fillable = [
        'name',
        'business_id',
        'created_by'
    ];
}
