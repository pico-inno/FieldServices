<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FolioInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'joint_folio_invoice_id',
        'folio_invoice_code',
        'billing_contact_id',
        'reservation_id',
        'net_amount',
        'discount_amount',
        'transaction_discount_total',
        'total_discount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'confirm_at',
        'confirm_by',
        'created_by',
        'updated_by',
        'is_delete',
        'deleted_at',
        'deleted_by'
    ];

    public function folio_invoice_details() {
        return $this->hasMany(\Modules\Reservation\Entities\FolioInvoiceDetail::class);
    }

    public function joint_folio_invoices() {
        return $this->hasMany(\Modules\Reservation\Entities\FolioInvoice::class, 'joint_folio_invoice_id', 'id');
    }

    public function parent_folio() {
        return $this->belongsTo(\Modules\Reservation\Entities\FolioInvoice::class, 'joint_folio_invoice_id', 'id');
    }
}
