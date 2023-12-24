<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLayout extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'data_text' => 'array',
        'table_text' => 'array',
    ];

}
