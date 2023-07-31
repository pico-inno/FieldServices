<?php

namespace App\Models;

use App\Models\Currencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class paymentAccounts extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'name',
        'account_type',
        'account_number',
        'description',
        'opening_amount',
        'current_balance',
        'currency_id',
    ];
    public function currency(){
        return $this->hasOne(Currencies::class,'id','currency_id')->with('exchangeRate');
    }
}
