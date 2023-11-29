<?php

namespace App\Actions\purchase;

use App\Models\purchases\purchases;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessSettings;
use App\Models\purchases\purchase_details;

class purchaseActions
{
    public $currency;
    public function __construct()
    {
        $settings = businessSettings::select('lot_control', 'currency_id', 'enable_line_discount_for_purchase')->with('currency')->first();
        $this->currency = $settings->currency;
    }

    public function create($purchases_data)
    {
        $purchases_data['purchase_voucher_no'] = purchaseVoucher();
        $purchases_data['purchased_by'] = Auth::user()->id;
        $purchases_data['confirm_at'] = $purchases_data['status'] === 'confirmed' ? now() : null;
        $purchases_data['confirm_by'] = $purchases_data['status'] === 'confirmed' ?  Auth::user()->id : null;
        return purchases::create($purchases_data);
    }

    public function update($id,$data){
        $data['updated_at'] = now();
        $data['updated_by'] = Auth::user()->id;
        if ($data['status'] === 'received') {
            $check = purchases::where('id', $id)->where('status', 'confirmed')->exists();
            if (!$check) {
                $data['confirm_at'] = now();
                $data['confirm_by'] = Auth::user()->id;
            }
        }


        // update  purchase data
        $selectPurchase = purchases::where('id', $id);
        $PurchaseDataBefUpdate = $selectPurchase->first();
        $selectPurchase->update($data);
        return [
            'befUpdateData'=>$PurchaseDataBefUpdate,
            'updatedData'=>$selectPurchase->first()
        ];
    }

}
