<?php

namespace App\Models\Stock;

use App\Models\BusinessUser;
use App\Models\Currencies;
use App\Models\settings\businessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transfers';

    protected $fillable = [
        'transfer_voucher_no',
        'from_location',
        'to_location',
        'transfered_at',
        'transfered_person',
        'status',
        'received_at',
        'received_person',
        'remark',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by',
    ];


    public function stockTransferDetails()
    {
        return $this->hasMany(StockTransferDetail::class, 'transfer_id', 'id');
    }

    public function businessLocationFrom(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'from_location', 'id');
    }

    public function businessLocationTo(): BelongsTo
    {
        return $this->belongsTo(businessLocation::class, 'to_location', 'id');
    }

    public function stocktransferPerson()
    {
        return $this->belongsTo(BusinessUser::class, 'transfered_person');
    }

    public function stockreceivePerson()
    {
        return $this->belongsTo(BusinessUser::class, 'received_person');
    }

    public function created_by(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
    }

    public function currency()
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }
}
