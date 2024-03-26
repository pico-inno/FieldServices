<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Html\Editor\Fields\BelongsTo;

class VariationValue extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_id',
      'product_variation_id',
      'variation_template_value_id',
    ];

    public function variation_template_value()
    {
        return $this->belongsTo(VariationTemplateValues::class);
    }
}
