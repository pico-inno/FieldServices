<?php

namespace Modules\Barcode\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Barcode\Database\factories\BarcodeTemplateFactory;

class BarcodeTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name','description', 'template_data'];

}
