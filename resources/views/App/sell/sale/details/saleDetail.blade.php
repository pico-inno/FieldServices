@section('styles')

@endsection
@php
    $currencyDp=getSettingValue('currency_decimal_places');
    $quantityDp=getSettingValue('quantity_decimal_places');
@endphp
    <div class="modal-dialog modal-fullscreen-sm" id="printArea">
        <div class="modal-content">

            <div>
                <div class="modal-header">
                    <h3 class="-title">sale Details (Voucher No : <span class=" " id="clipboard">{{$sale['sales_voucher_no']}} </span> )
                        <a type="button" class="btn btn-icon btn-sm p-0" data-clipboard-target="#clipboard">
                            <i class="fa-solid fa-copy fs-6 clipboard-icon ki-copy"></i>
                        </a></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="m-0">
                        <div class="row g-5 mb-10">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-6 text-gray-800">#{{$sale['sales_voucher_no']}}</div>
                                <div class="fw-semibold fs-7 text-gray-600">
                                    Status : <span class="badge badge-light-success">{{$sale['status']}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Sold Date:</div>
                                <!--end::Label-->
                                <!--end::Info-->
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                    <span class="pe-2">{{fDate($sale['sold_at'])}}</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row g-5 mb-11">
                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Customer:</div>
                                <!--end::Label-->
                                <!--end::Col-->
                                @if ($sale['customer'])
                                <address class="mt-3 fs-5 fw-semibold fs-7">
                                    <span class="fw-bold">{{$sale['customer']['first_name']}}</span>
                                    <br>
                                    <span class="text-gray-700 fs-7">
                                        {{$sale['customer']['mobile']}}
                                    </span>
                                    <br>
                                    <span class="text-gray-700 fs-7">
                                        {{$ecommerceOrderLocation ?? ''}}
                                    </span>
                                </address>
                                @endif
                                <!--end::Col-->
                            </div>
                            <!--end::Col-->
                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Business Location:</div>
                                <!--end::Label-->
                                <!--end::Text-->
                                @if ($location)

                                    <div class="fw-bold fs-6 text-gray-800">{{businessLocationName($location)}}</div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {!! address($address) !!}
                                    </div>
                                    <!--end::Description-->
                                @endif
                            </div>
                            <!--end::Col-->
                            <!--end::Col-->
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Content-->
                        <div class="flex-grow-1">
                            <!--begin::Table-->
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead class="">
                                        <tr class="border-bottom  border-primary fs-7 fw-bold  text-gray-500 x">
                                            <th class="min-w-10 text-start ps-2">#</th>
                                            <th class="min-w-175px pb-2 text-start">Description</th>
                                            <th class="min-w-100px text-end ">Quantity In Packaging</th>
                                            <th class="min-w-100px text-end">Quantity</th>
                                            @if ($sale['status']=='partial')
                                                <th class="min-w-100px text-end">Deivered Quantity</th>
                                            @endif
                                            <th class="min-w-100px text-end ">UOM</th>
                                            <th class="min-w-100px text-end ">UOM Price</th>
                                            <th class="min-w-100px text-end">
                                                Subtotal
                                            </th>
                                            <th class="min-w-100px text-end">
                                                Discount Type
                                            </th>
                                            <th class="min-w-100px text-end ">Per Item Discount Amount</th>
                                            <th class="min-w-100px text-end ">Subtotal</th>

                                            {{-- <th class="min-w-70px text-end pb-2">Hours</th>
                                            <th class="min-w-80px text-end pb-2">Rate</th>
                                            <th class="min-w-100px text-end pb-2">Amount</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sale_details as $key=>$sd)
                                        @php
                                            // dd($sd);
                                            $p=$sd->product;
                                            if($p){
                                                $product_variation =$sd->toArray()['product_variation'];
                                            }
                                            $currency=$sd->currency['symbol'] ?? ' ';
                                            $quantityDp=getSettingValue('quantity_decimal_places');
                                        @endphp
                                        <tr class="fw-bold text-gray-700 fs-7 text-start">
                                            <td class="pt-6 text-start ps-2">{{$key+1}}</td>
                                            <td class="d-flex align-items-center pt-6">
                                                <div href="" class="symbol symbol-40px me-2" >
                                                    @if ($p)
                                                        <div class="symbol-label" style="background-image:url({{asset(" storage/product-image/$p->image")}});"></div>
                                                    @endif
                                                </div>
                                                {{arr($p,'name','','Unknown Product')}}
                                                @if(isset($product_variation['variation_template_value']))
                                                <span class="my-2 d-block">
                                                    ({{ $product_variation['variation_template_value']['name'] }})
                                                </span>
                                                @endif
                                            </td>
                                            <td class="pt-6 text-end">{{$sd->packagingTx ? round(arr($sd->packagingTx,'quantity') ,$quantityDp): ''}} {{$sd->packagingTx ? '('.$sd->packagingTx->packaging->packaging_name.')' : ''}}</td>
                                            <td class="pt-6 text-end">{{round($sd->quantity,$quantityDp)}}</td>
                                            @if ($sale['status']=='partial')
                                                <td class="min-w-100px">{{round($sd->delivered_quantity ?? 0,$currencyDp)}}</td>
                                            @endif
                                            <td class="pt-6 text-end">
                                               {{$sd['uom']['name']}}
                                            </td>
                                            <td class="pt-6 text-end">
                                                {{price($sd->uom_price ?? 0,$sd->currency_id)}}
                                            </td>
                                            <td class="pt-6 text-end">
                                                {{price($sd->subtotal,$sd->currency_id)}}
                                            </td>
                                            <td class="pt-6 text-end">
                                                {{$sd->discount_type}}
                                            </td>
                                            <td class="pt-6 text-end">
                                               {{fprice($sd->per_item_discount)}} &nbsp; {{$sd->discount_type=='percentage'?'%':$currency }}
                                               @if ($sd->discount_type == 'percentage')
                                                    <br>
                                                    ({{price(calPercentage($sd->discount_type,$sd->per_item_discount,$sd->uom_price))}})
                                               @endif
                                            </td>
                                            <td class="pt-6 text-dark fw-bolder">
                                               {{price($sd->subtotal_with_discount,$sd->currency_id)}}
                                            </td>
                                            {{-- <td class="pt-6 text-dark fw-bolder">$3200.00</td> --}}
                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <!--end::Table-->
                            <!--begin::Container-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Section-->
                                <div class="mw-600px">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Sale Amount:</div>
                                        <!--end::Accountname-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                            {{price($sale['sale_amount'] ?? 0,$sale['currency_id'])}}
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Item Discount:</div>
                                        <!--end::Accountname-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                            {{price($sale['total_item_discount'] ?? 0,$sale['currency_id'])}}
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountnumber-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Extra Discount:</div>
                                        <!--end::Accountnumber-->
                                        <!--begin::Number-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                            @if($sale['extra_discount_type'] == 'percentage')
                                                {{formatNumberV2($sale['extra_discount_amount'] ?? 0)}} {{$sale['extra_discount_type']=='percentage'?'%':''}}
                                                <br/>
                                                ({{price(calPercentage($sale['extra_discount_type'],$sale['extra_discount_amount'] ?? 0,$sale['sale_amount']) ?? 0)}})
                                            @else
                                            {{price($sale['extra_discount_amount'] ?? 0)}}
                                            @endif
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Code-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Sale Amount:</div>
                                        <!--end::Code-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                            {{price($sale['total_sale_amount'],$sale['currency_id'])}}
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Code-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Paid Amount:</div>
                                        <!--end::Code-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                            {{price($sale['paid_amount'],$sale['currency_id'])}}
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <div class="d-flex flex-stack">
                                        <!--begin::Code-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">Balance Amount:</div>
                                        <!--end::Code-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                           {{price($sale['balance_amount'],$sale['currency_id'])}}
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                    @if ( hasModule('Ecommerce') && isEnableModule('Ecommerce'))
                                        <div class="d-flex flex-stack mt-10">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Payment Screenshot:</div>
                                            <!--end::Code-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">
                                                @if (isset($ecommerceOrder['screenshot']))
                                                    <!--end::Label-->
                                                    @php
                                                    // dd($ecommerceOrder);
                                                        $src = asset('/storage/payment-screenshot/'.$ecommerceOrder['screenshot']);
                                                    @endphp
                                                    <div class="w-auto min-h-65px d-flex justify-content-start">
                                                        <a class="d-block overlay w-50px h-65px" data-fslightbox="lightbox-basic-'{{$ecommerceOrder['id']}} " href="{{$src}}">
                                                            <div data-src="{{$src}}"
                                                                class="overlay-wrapper bgi-no-repeat bg-gray-300 bgi-position-center bg-secondary bgi-size-cover card-rounded  w-50px h-65px lazy-bg"
                                                                style="background-image:url('{{$src}}'); background-color:gray;">
                                                            </div>
                                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow ">
                                                                <i class="bi bi-eye-fill text-white fs-5"></i>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Container-->


                            @if ($sale['status'] !='order' && $sale['status']!='delivered' && $sale['status']!='partial' && hasModuleInstalled('Ecommerce'))
                                <div class="row g-5 mb-11 mt-10">
                                    <div class="col-4 text-start m-auto d-flex justify-content-center">
                                        <div class="w-auto">
                                            <button type="button" class="btn btn-primary"  id="confirmOrder" data-bs-stacked-modal="#kt_modal_stacked_{{$sale['id']}}">
                                                Confirm Order
                                            </button>
                                            {{-- <button class="btn btn-sm btn-primary" id="confirmOrder">
                                                Confirm Order
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="separator border-2 my-10"></div>
                            <!--begin::Table wrapper-->
                            <div class="table-responsive mt-2">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="sale_logs_table">
                                    <!--begin::Thead-->
                                    <thead class="border-gray-200 text-gray-700 fs-5 fw-semibold bg-lighten">
                                    <tr>
                                        <th class="min-w-175px">Timestamp</th>
                                        <th class="min-w-250px">Description</th>
                                        <th class="min-w-95px">Event</th>
                                        <th class="min-w-95px">Status</th>
                                        <th class="min-w-120px">By</th>
                                        <th class="min-w-100px">Note</th>
                                    </tr>
                                    </thead>
                                    <!--end::Thead-->
                                    <!--begin::Tbody-->
                                    <tbody class="fw-6 fw-semibold text-gray-600" style="vertical-align: text-top">
                                    </tbody>
                                    <!--end::Tbody-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table wrapper-->
                        </div>
                        <!--end::Content-->

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
                    {{-- <button type="button" class="btn btn-primary" id="print">Print</button> --}}
                </div>
            </div>
        </div>
    </div>
    @if (hasModuleInstalled('Ecommerce'))
    <div class="modal fade " tabindex="-1" id="kt_modal_stacked_{{$sale['id']}}">
        <div class="modal-dialog modal-dialog-centered w-md-500px">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h3 class="modal-title">Confirm Order</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="#" id="confirmForm_{{$sale['id']}}">
                    <div class="modal-body">
                            <div class="d-flex flex-stack mb-3">
                                <!--begin::Code-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Sale Amount:</div>
                                <!--end::Code-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-800">
                                    {{price($sale['total_sale_amount'],$sale['currency_id'])}}
                                </div>
                                <!--end::Label-->
                            </div>
                            <div class="d-flex flex-stack mb-3">
                                <!--begin::Code-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Paid Amount:</div>
                                <!--end::Code-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-800">
                                    {{price($sale['total_sale_amount'],$sale['currency_id'])}}
                                </div>
                                <!--end::Label-->
                            </div>
                            <div class="separator my-3"></div>
                            <div class="d-flex flex-stack mb-3" id="paymentConfirmation">
                                <!--begin::Code-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                    <label for="flexCheckChecked" class="user-select-none cursor-pointer"> Do You Also Want To Confirm Payment ?</label>
                                </div>
                                <!--end::Code-->
                                <!--begin::Label-->
                                <div class="form-check">
                                    <input class="form-check-input" name="confirm_payment" type="checkbox" value="true" id="flexCheckChecked" checked />
                                </div>
                            </div>
                            @if(hasModuleInstalled('Ecommerce'))
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Accept Account:</div>
                                    <div class="text-end fw-bold fs-6 text-gray-800">
                                        {{$paymentAccount['name'] ?? ''}}
                                    </div>
                                </div>
                            @endif
                            <div class="separator my-5"></div>

                            <div class="d-flex flex-stack mb-3" id="paymentConfirmation">
                                <!--begin::Code-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                    <label for="reserveLocation" class="user-select-none cursor-pointer">Is you want to move Reserve location ?</label>
                                </div>
                                <!--end::Code-->
                                <!--begin::Label-->
                                <div class="form-check">
                                    <input class="form-check-input" name="IsReserve" type="checkbox" value="true" id="reserveLocation" checked />
                                </div>
                            </div>
                            @if(hasModuleInstalled('Ecommerce'))
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Location:</div>
                                    <div class="text-end fw-bold fs-6 text-gray-800 col-6">
                                        <x-locationsselect name="location_id" placeholder="Select Reserve Location" className="form-select-sm"></x-locationsselect>
                                    </div>
                                </div>
                            @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancle</button>
                        <button type="submit" id="confirmBtn" class="btn btn-success">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endif

    <script src={{asset('customJs/general.js')}}></script>

    {{-- <script src={{asset('customJs/invoice/print.js')}}></script> --}}
    <script>
        clipboard();

    </script>


    <script src={{asset("assets/plugins/custom/fslightbox/fslightbox.bundle.js")}}></script>
    <script>

        "use strict";



        var KTCustomersList = function () {
            var datatable;
            var table;
            let sale_id = {{$sale['id']}};
            console.log(sale_id, 'ssssssssssss')
            var initLogsList = function () {

                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({

                    order: [[0, ' ']],
                    processing: true,
                    pageLength: 10,
                    lengthMenu: [10, 20, 30, 50,40,80],
                    serverSide: true,
                    ajax: {
                        url: `/logs/sale-transactions/all/${sale_id}`,
                    },
                    columns: [

                        { data: 'created_at' },
                        { data: 'description' },
                        { data: 'event' },
                        { data: 'status' },
                        { data: 'created_user' },
                        { data: 'properties' },

                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            render: function (data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    const dateTime = new Date(data);
                                    const formattedDateTime = dateTime.toLocaleString();
                                    return formattedDateTime;
                                }
                                return data;
                            }
                        },

                        {
                            targets: 3,
                            render: function (data, type, row) {
                                let badgeClass = 'badge-light-success';

                                if (data === 'fail') {
                                    badgeClass = 'badge-light-danger';
                                } else if (data === 'warn') {
                                    badgeClass = 'badge-light-warning';
                                }

                                return `<span class="badge ${badgeClass} fs-7 fw-bold">${data}</span>`;
                            }

                        },
                    ],

                });

            }

            return {
                init: function () {
                    table = document.querySelector('#sale_logs_table');

                    if (!table) {
                        return;
                    }
                    initLogsList()
                }
            }
        }();


        KTUtil.onDOMContentLoaded(function () {

            KTCustomersList.init();
            let statusChangeUri="{{route('sale.statusChange',$sale['id'])}}";


            $('#confirmForm_{{$sale['id']}}').submit((e)=>{
                e.preventDefault();
                $('#kt_modal_stacked_{{$sale['id']}}').modal("hide");
                $('#confirmBtn').text('Saving....');
                $("#confirmBtn").prop("disabled", true);
                let formData=deserialize($("#confirmForm_{{$sale['id']}}").serialize());
                $.ajax({
                    method: 'POST',
                    url:statusChangeUri,
                    dataType: 'json',
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    data:{
                        status:'order',
                        data:formData,
                    },
                    success: function(result) {

                        $('#confirmBtn').text('Confirm');// Disable the button
                        $("#confirmBtn").prop("disabled", false);
                        success('Successfully Updated');
                        $('.saleDetail').modal('hide');
                        let confirmBox = new bootstrap.Modal($('#kt_modal_stacked_{{$sale['id']}}'));
                            confirmBox.hide();
                        $('.confirmForm_{{$sale['id']}}').modal('hide');
                        $('#saleDetailModal').modal('hide');
                        Livewire.dispatch('reloadComponent');
                    },
                    error: function(result) {
                        $('#confirmBtn').text('Confirm');// Disable the button
                        $("#confirmBtn").prop("disabled", false);
                        toastr.error(result.responseJSON.errors,
                            'Something went wrong');
                    }
                });
            })
            $('#confirmOrder').off('click').on('click',()=>{
                let confirmBox = new bootstrap.Modal($('#kt_modal_stacked_{{$sale['id']}}'));
                confirmBox.show();





            })
        });
        function deserialize(serializedString) {
            var obj = {};
            var pairs = serializedString.split('&');
            for (var i = 0; i < pairs.length; i++) {
                var pair = pairs[i].split('=');
                obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
            }
            return obj;
        }

</script>


