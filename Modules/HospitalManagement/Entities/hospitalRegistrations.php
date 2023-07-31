<?php

namespace Modules\HospitalManagement\Entities;

use App\Models\BusinessUser;
use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HospitalManagement\Entities\hospitalRoomRegistrations;

class hospitalRegistrations extends Model
{
    use HasFactory;
    protected $fillable = [
        'joint_registration_id',
        'registration_code',
        'registration_type',
        'patient_id',
        'company_id',
        'agency_id',
        'opd_check_in_date',
        'ipd_check_in_date',
        'check_out_date',
        'registration_status',
        'booking_confirmed_at',
        'booking_confirmed_by',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
        'patient',
    ];

    public function patient():HasOne
    {
        return $this->hasOne(Contact::class,'id','patient_id')->select('id','prefix','first_name','middle_name','last_name','dob','mobile');
    }
    public function company()
    {
       return $this->hasOne(Contact::class,'id','company_id')->select('id','first_name','middle_name','last_name','company_name');
    }
    public function agency()
    {
       return  $this->hasOne(Contact::class,'id','agency_id')->select('id','first_name','middle_name','last_name');
    }
    public function booking_by()
    {
       return  $this->hasOne(BusinessUser::class,'id','booking_confirmed_by')->select('id','username');
    }


    public function jointRegistration()
    {
        return  $this->hasOne(hospitalRegistrations::class, 'id', 'joint_registration_id')->select('id','registration_code','patient_id')->with('patient');
    }
    public function hospitalRoomRegistrations(){
        return $this->hasMany(hospitalRoomRegistrations::class, 'registration_id','id')->with('room_type','room','rate');
    }
}
