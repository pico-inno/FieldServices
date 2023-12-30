<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLayout extends Model
{
    use HasFactory;

    protected $fillable = ['name','paper_size','paper_type','data_text','header_text','footer_text','note','table_text'];
    protected $casts = [
        'data_text' => 'array',
        'table_text' => 'array',
    ];

}
