<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseReports extends Model
{
    use HasFactory;
    protected $fillable=[
        'expense_title',
        'expense_report_no',
        'expense_on',
        'note',
        'documents',
        'created_by',
        'updated_by',
    ];

    public function reportBy(){
        return $this->hasOne(BusinessUser::class,'id','created_by');
    }
    public function currency(){
        return $this->hasOne(Currencies::class,'id','currency_id');
    }
}
