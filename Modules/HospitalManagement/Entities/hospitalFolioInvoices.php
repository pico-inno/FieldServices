<?php

namespace Modules\HospitalManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class hospitalFolioInvoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'joint_folio_invoice_id',
        'folio_invoice_code',
        'billing_contact_id',
        'registration_id',
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

    public function folioInvoiceDetail():HasMany{
            return $this->hasMany(hospitalFolioInvoiceDetails::class, 'folio_invoice_id','id');
    }
}
