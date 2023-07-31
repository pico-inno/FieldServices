<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FolioInvoiceDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'folio_invoice_id',
        'transaction_type',
        'transaction_id',
        'transaction_description',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function folio_invoice() {
        return $this->belongsTo(\Modules\Reservation\Entities\FolioInvoice::class);
    }
}
