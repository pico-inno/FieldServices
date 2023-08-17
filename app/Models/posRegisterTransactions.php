<?php

namespace App\Models;

use App\Models\sale\sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class posRegisterTransactions extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'register_session_id',
        'payment_account_id',
        'transaction_type',
        'transaction_id',
        'transaction_amount',
        'currency_id',
        'payment_transaction_id'
    ];

    public function sale(){
        return $this->hasOne(sales::class,'id','transaction_id');
    }

    public function paymentTransaction():HasOne{
        return $this->hasOne(paymentsTransactions::class,'id','payment_transaction_id');
    }

    public function paymentAccount():HasOne{
        return $this->hasOne(paymentAccounts::class,'id','payment_account_id');
    }
}
