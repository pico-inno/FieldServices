<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable =[
        'log_name',
        'description',
        'event',
        'status',
        'properties',
        'created_by',
        'updated_by'
    ];

    public function created_user():BelongsTo
    {
        return $this->belongsTo(BusinessUser::class, 'created_by')->select('id','username');
    }

}
