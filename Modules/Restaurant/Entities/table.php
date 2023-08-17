<?php

namespace Modules\Restaurant\Entities;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class table extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=[
        'description',
        'table_no',
        'seats',
        'appearence',
    ];
}
