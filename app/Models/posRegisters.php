<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class posRegisters extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'employee_id',
        'business_id',
        'payment_account_id',
        'status',
        'use_for_res',
        'printer_id',
        'invoice_layout_id'
    ];
    public function printer():HasOne{
        return $this->hasOne(printers::class,'id','printer_id');
    }
}
