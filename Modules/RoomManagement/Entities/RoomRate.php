<?php

namespace Modules\RoomManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'rate_name',
        'rate_amount',
        'start_date',
        'end_date',
        'booking_rule',
        'cancellation_rule',
        'min_stay',
        'max_stay',
        'created_by',
        'updated_by'
    ];

    public function room_type() {
        return $this->belongsTo(\Modules\RoomManagement\Entities\RoomType::class);
    }
}
