<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExchangeRate\Entities\exchangeRates;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currencies extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'business_id',
        'currency_type',
        'name',
        'country',
        'code',
        'symbol',
        'thoundsand_seprator',
        'decimal_separator',
    ];
    public function exchangeRate(){
        if(hasModule('ExchangeRate') && isEnableModule('ExchangeRate')){
            return $this->belongsTo(exchangeRates::class,'id','currency_id');
        }
        return $this->hasMany(Currencies::class,'id','id');
    }
}
