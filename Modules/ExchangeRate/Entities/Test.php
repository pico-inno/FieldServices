<?php

namespace Modules\ExchangeRate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class test extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ExchangeRate\Database\factories\TestFactory::new();
    }
}
