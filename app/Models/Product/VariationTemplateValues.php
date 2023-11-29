<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\VariationTemplates;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariationTemplateValues extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'variation_template_id',
        'created_by'
    ];

    public function variationTemplate()
    {
        return $this->belongsTo(VariationTemplates::class, 'variation_template_id', 'id');
    }

    public function getName()
    {
        return $this->name;
    }
}
