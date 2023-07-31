<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'joint_reservation_id',
        'reservation_code',
        'guest_id',
        'company_id',
        'agency_id',
        'check_in_date',
        'check_out_date',
        'reservation_status',
        'booking_confirmed_at',
        'booking_confirmed_by',
        'remark',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];

    public function joint_reservations() {
        return $this->hasMany(\Modules\Reservation\Entities\Reservation::class, 'joint_reservation_id', 'id');
    }

    public function parent_reservation() {
        return $this->belongsTo(\Modules\Reservation\Entities\Reservation::class, 'joint_reservation_id', 'id');
    }

    public function room_reservations() {
        return $this->hasMany(\Modules\Reservation\Entities\RoomReservation::class);
    }
    
    public function room_sales() {
        return $this->hasMany(\Modules\RoomManagement\Entities\RoomSale::class, 'transaction_id');   
    }

    public function sales() {
        return $this->hasMany(\App\Models\sale\sales::class, 'transaction_id');
    }
   
    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact\Contact::class, 'guest_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Contact\Contact::class, 'company_id');
    }
}
