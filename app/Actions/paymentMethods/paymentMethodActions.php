<?php

namespace App\Actions\paymentMethods;

use App\Models\paymentMethods;
use Ramsey\Uuid\Type\Integer;

class paymentMethodActions
{


    /**
     * Create a new payment method.
     *
     * @param array $data An associative array containing the data for the new payment method.
     * The array should have the following structure:
     *                    [
     *                        'name' => string, // The name of the payment method.
     *                        'payment_account_id' => int, // The ID of the payment account.
     *                    ]
     * @return \App\Models\PaymentMethod The newly created payment method instance.
     */
    public function create(Array $data):paymentMethods{
        return paymentMethods::create($data);
    }

    public function update($id,Array $data){
        return paymentMethods::where('id',$id)->first()->update($data);
    }


}
