<?php

namespace App\Models;

use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseTransactions extends Model
{
    use HasFactory;
    protected $fillable=[
        'expense_report_id',
        'expense_voucher_no',
        'expense_product_id',
        'expense_description',
        'quantity',
        'uom_id',
        'expense_amount',
        'paid_amount',
        'status',
        'balance_amount',
        'payment_status',
        'currency_id',
        'note',
        'documents',
        'expense_on',
        'created_by',
        'updated_by'
    ];
    public function variationProduct(){
        return $this->hasOne(ProductVariation::class,'id','expense_product_id')->with('product','variationTemplateValue');
    }
    public function currency(){
        return $this->hasOne(Currencies::class,'id','currency_id');
    }

    public function createdBy(){
        return $this->hasOne(BusinessUser::class,'id','created_by');
    }
}
