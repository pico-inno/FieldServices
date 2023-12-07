<?php

namespace App\Models\Product;

use App\Models\Currencies;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceLists extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_list_type',
        'business_id',
        'business_location_id',
        'currency_id',
        'name',
        'description'
    ];

    public function priceListDetails(): HasMany
    {
        return $this->hasMany(PriceListDetails::class, 'pricelist_id', 'id');
    }

    public function businessSetting(): BelongsTo
    {
        return $this->belongsTo(businessSettings::class, 'business_id');
    }

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id');
    }
}
