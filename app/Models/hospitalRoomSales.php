<?php
namespace App\Models;

use App\Models\Contact\Contact;
use Modules\RoomManagement\Entities\Room;
use App\Models\hospitalRoomSaleDetails;
use Modules\RoomManagement\Entities\RoomRate;
use Modules\RoomManagement\Entities\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class hospitalRoomSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration_id',
        'room_sales_voucher_no',
        'business_location_id',
        'contact_id',
        'total_amount',
        'discount_type',
        'discount_amount',
        'total_sale_amount',
        'paid_amount',
        'balance_amount',
        'confirm_at',
        'confirm_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];
    public function roomRegistration():HasMany {
        return $this->hasMany(hospitalRoomRegistrations::class, 'registration_id', 'id')->where('is_delete', 0)->with('room_type','room','rate');
    }
}
