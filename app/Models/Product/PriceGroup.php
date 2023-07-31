<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function uom_sellingprices(): HasMany
    {
        return $this->hasMany(UOMSellingprice::class);
    }
}
