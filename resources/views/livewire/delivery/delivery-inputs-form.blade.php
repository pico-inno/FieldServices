<div class="">
    <div class="card mt-5" >
        <div class="card-header"  wire:ignore>
            <div class="card-title fw-bold fs-4">
                <i class="fa-solid fa-truck-fast me-3 "></i> Delivery
            </div>
        </div>
        <div class="card-body" >
            <div class="row justify-content-start mb-3 d-flex" wire:ignore.self>
                {{-- <form class="form" class="d-flex" action=""> --}}
                    <div class="col-12 col-sm-6 col-md-3" wire:ignore>
                        <label for="" class="form-label" >
                            Shipping Methods
                        </label>
                        <select name="delivery[shipping_method_id]"   id="shipping_method" class="form-select form-select-sm" data-control="select2" data-kt-placeholder="Please Select Shipping Method" placeholder="Please Select Shipping Method">
                            <option selected disabled value="">Please Select Shipping Method</option>
                            @foreach ($shippingMethods as $sm)
                                <option value="{{$sm['id']}}" @selected(isset($deliveryOrderData['shipping_method_id']) && $sm['id']==$deliveryOrderData['shipping_method_id'])>{{$sm['name']}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12 col-sm-6 col-md-3"   >
                        <label for="" class="form-label">
                            Shipping Fees
                        </label>
                        <input type="text"  name="delivery[shipping_fee]" value="{{$fee}}" class="form-control form-control-sm" placeholder="Shipping Fees" value="{{$fee}}">
                    </div>


                    <div class="col-12 col-sm-6 col-md-3"   wire:ignore>
                        <label for="" class="form-label">
                            Delivery Channel
                        </label>
                        <select name="delivery[delivery_channel_id]" id="delivery_channel" class="form-select form-select-sm" data-control="select2">
                            <option selected disabled value="" >Please Select Delivery Channel</option>
                            @foreach ($deliveryChannels as $dcs)
                                <option value="{{$dcs['id']}}"  @selected(isset($deliveryOrderData['delivery_channel_id']) && $dcs['id']==$deliveryOrderData['delivery_channel_id'])>{{$dcs['name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3"   wire:ignore>
                        <label for="" class="form-label">
                            Shipping Status
                        </label>
                        <select name="delivery[status]" id="shipping_status" class="form-select form-select-sm" data-control="select2">
                            <option value="pending" @selected( isset($deliveryOrderData['status']) && $deliveryOrderData['status']=='pending')>Pending</option>
                            <option value="shipped" @selected(isset($deliveryOrderData['status']) && $deliveryOrderData['status']=='shipped')>Shipping</option>
                            <option value="delivered"  @selected(isset($deliveryOrderData['status']) &&  $deliveryOrderData['status']=='delivered')>Delivered</option>
                        </select>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
    @script

    <script>

        __init();
        Livewire.hook('morph.updated', ({ el, component }) => {
            console.log($(el).attr('id'));
            if($(el).attr('id') == 'delivery_channel'){
                $('#delivery_channel').select2();
            }
        });
        function __init(){
            $('#shipping_status').select2();
            $('#shipping_method').select2().on('select2:select', function (e) {
                $wire.dispatch('selectedShippingMethod',{'shippingId':$('#shipping_method').select2("val")});
            });
            $('#delivery_channel').select2().on('select2:select', function (e) {
                $wire.dispatch('selectedDeliveryChannel',{'deliveryChannelId':$('#shipping_method').select2("val")});
            });;
        }

    </script>
    @endscript
</div>
