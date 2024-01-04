<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BusinessUser extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table = 'business_users';

    protected $fillable = [
        'username',
        'role_id',
        'business_id',
        'default_location_id',
        'enable_line_discount_for_sale',
        'enable_line_discount_for_purchase',
        'access_location_ids',
        'email',
        'password',
        'personal_info_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function personal_info(){
        return $this->belongsTo(PersonalInfo::class);
    }
}
