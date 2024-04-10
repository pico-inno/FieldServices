<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentMethods extends Model
{
    use HasFactory;

    public $fillable=[
        'name','payment_account_id','note','currency_id','logo'
    ];

    public function paymentAccount(){
        return $this->hasOne(paymentAccounts::class,'id','payment_account_id');
    }
}
