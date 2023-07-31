<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'initname',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'marital_status',
        'language',
        'blood_group',
        'alt_number',
        'contact_number',
        'family_number',
        'fb_link',
        'twitter_link',
        'social_media_1',
        'social_media_2',
        'custom_field_1',
        'custom_field_2',
        'custom_field_3',
        'custom_field_4',
        'guardian_name',
        'id_proof_name',
        'id_proof_number',
        'permanent_address',
        'current_address',
        'bank_details',
        'profile_photo',
    ];

    public function business_users(){
        return $this->hasMany(BusinessUser::class);
    }
}
