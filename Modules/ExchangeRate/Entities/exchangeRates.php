<?php

namespace Modules\ExchangeRate\Entities;

use App\Models\Currencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class exchangeRates extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'business_id',
        'currency_id',
        'default',
        'rate'
    ];
    public function currency(){
        return $this->hasOne(Currencies::class,'id','currency_id');
    }
}
