<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\deliveryChannels;
use Modules\Delivery\Entities\DeliveryOrder;
use Modules\Delivery\Entities\ShippingMethod;
use Modules\Delivery\Entities\DeliveryChannel;

class DeliveryInputsForm extends Component
{
    public $deliveryChannels,$shippingMethods,$deliveryOrderData=[];
    public $fee=0;
    public $saleId=null;

    public function mount()
    {
        $this->shippingMethods=ShippingMethod::get();
        if($this->saleId){
            $deliveryData=DeliveryOrder::where('transaction_type','sale')->where('transaction_id',$this->saleId)->first();
            $this->deliveryOrderData=$deliveryData;
            $this->deliveryChannels=deliveryChannels::query()
                                    ->select('delivery_channels.id','delivery_channels.name')
                                    ->rightJoin('shipping_methods','shipping_methods.delivery_channel_id','delivery_channels.id')
                                    ->get();
        }else{
            $this->deliveryChannels=[];
        }

    }
    public function render()
    {
        $deliveryOrderData=$this->deliveryOrderData ?? [];
        $shippingMethods=$this->shippingMethods;
        $deliveryChannels=$this->deliveryChannels;
        $fee=$this->fee;
        return view('livewire.delivery.delivery-inputs-form',compact('deliveryChannels','shippingMethods','fee','deliveryOrderData'));
    }

    #[On('selectedShippingMethod')]
    public function fetchDeliveryData($shippingId){
        $shipping=ShippingMethod::where('id',$shippingId)->first();
        $this->fee=$shipping['amount']??0;
        $this->deliveryChannels=DeliveryChannel::where('id',$shipping->delivery_channel_id)->get();
    }
    // #[On('selectedDeliveryChannel')]
    // public function selectedDeliveryChannel($deliveryChannelId){
    //     $deliveryChannel=deliveryChannels::where('id',$deliveryChannelId)->first();
    //     $this->dispatch('deliveryFee',fee:'Something Wrong');
    // }
}
