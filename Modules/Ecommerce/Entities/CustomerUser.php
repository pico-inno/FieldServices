<?php

namespace Modules\Ecommerce\Entities;

use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CustomerUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'customer';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'contact_id',
        'phone_verification_code',
        'phone_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'phone_verification_code',
        'remember_token',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

}
