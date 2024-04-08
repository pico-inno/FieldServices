<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\deliveryChannels;
use Modules\Delivery\Entities\DeliveryOrder;
use Modules\Delivery\Entities\ShippingMethod;
use Modules\Delivery\Entities\DeliveryChannel;
use Modules\Ecommerce\Entities\EcommerceOrder;

class DeliveryInputsForm extends Component
{
    public $deliveryChannels,$shippingMethods,$deliveryOrderData=[];
    public $fee=0;
    public $saleId=null;
    public $saleType='sale';
    public $deliveryChannelId=null;

    public function mount()
    {
        $saleType=$this->saleType;
        $this->shippingMethods=ShippingMethod::get();
        $this->deliveryChannels=deliveryChannels::query()
                                ->select('delivery_channels.id','delivery_channels.name')
                                ->rightJoin('shipping_methods','shipping_methods.delivery_channel_id','delivery_channels.id')
                                ->get();
        if($this->saleId){
            if($saleType=='sale'){
                $deliveryData=DeliveryOrder::where('transaction_type','sale')->where('transaction_id',$this->saleId)->first();
                $this->deliveryOrderData=$deliveryData;
            }else{
                $ecommerceOrder=EcommerceOrder::where('sale_id',$this->saleId)->first();
                $shippingMethod=ShippingMethod::where('id',$ecommerceOrder['shipping_method_id'])->first();
                $this->deliveryOrderData=[
                    'delivery_channel_id'=>$shippingMethod['delivery_channel_id'],
                    'shipping_method_id'=>$ecommerceOrder['shipping_method_id']
                ];
            }
        }
    }
    public function render()
    {
        $deliveryOrderData=$this->deliveryOrderData ?? [];
        $shippingMethods=$this->shippingMethods;
        $deliveryChannels=$this->deliveryChannels;
        $fee=$this->fee;
        $deliveryChannelId=$this->deliveryChannelId;
        return view('livewire.delivery.delivery-inputs-form',compact('deliveryChannels','deliveryChannelId','shippingMethods','fee','deliveryOrderData'));
    }

    #[On('selectedShippingMethod')]
    public function fetchDeliveryData($shippingId){
        $shipping=ShippingMethod::where('id',$shippingId)->first();
        $this->fee=$shipping['amount']??0;
        $this->deliveryChannelId=$shipping['delivery_channel_id'];
    }
    // #[On('selectedDeliveryChannel')]
    // public function selectedDeliveryChannel($deliveryChannelId){
    //     $deliveryChannel=deliveryChannels::where('id',$deliveryChannelId)->first();
    //     $this->dispatch('deliveryFee',fee:'Something Wrong');
    // }
}
