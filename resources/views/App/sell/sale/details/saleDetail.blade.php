@section('styles')
@endsection
@php
    $currencyDp=getSettingValue('currency_decimal_places');
    $quantityDp=getSettingValue('quantity_decimal_places');
@endphp
    <div class="modal-dialog modal-fullscreen-sm" id="printArea">
        <div class="modal-content">
              <form>
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
                                <div class="fw-bold fs-6 text-gray-800">{{businessLocationName($location)}}</div>
                                <!--end::Text-->
                                <!--end::Description-->
                                <div class="fw-semibold fs-7 text-gray-600">
                                    {!! addresss($address) !!}
                                </div>
                                <!--end::Description-->
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
                                        <tr class="fw-bold text-gray-700 fs-7 text-end">
                                            <td class="pt-6 text-start ps-2">{{$key+1}}</td>
                                            <td class="d-flex align-items-center pt-6">
                                                <div href="" class="symbol symbol-40px me-2">
                                                    <span class="symbol-label" style="background-image:url({{asset("
                                                        storage/product-image/arr($p,'image')")}});"></span>
                                                </div>
                                                {{arr($p,'name','','Unknown Product')}}
                                                @if(isset($product_variation['variation_template_value']))
                                                <span class="my-2 d-block">
                                                    ({{ $product_variation['variation_template_value']['name'] }})
                                                </span>
                                                @endif
                                            </td>
                                            <td class="pt-6">{{$sd->packagingTx ? round(arr($sd->packagingTx,'quantity') ,$quantityDp): ''}} {{$sd->packagingTx ? '('.$sd->packagingTx->packaging->packaging_name.')' : ''}}</td>
                                            <td class="pt-6">{{round($sd->quantity,$quantityDp)}}</td>
                                            @if ($sale['status']=='partial')
                                                <td class="min-w-100px">{{round($sd->delivered_quantity ?? 0,$currencyDp)}}</td>
                                            @endif
                                            <td class="pt-6">
                                               {{$sd['uom']['name']}}
                                            </td>
                                            <td class="pt-6">
                                                {{price($sd->uom_price ?? 0,$sd->currency_id)}}
                                            </td>
                                            <td class="pt-6 ">
                                                {{price($sd->subtotal,$sd->currency_id)}}
                                            </td>
                                            <td class="pt-6">
                                                {{$sd->discount_type}}
                                            </td>
                                            <td class="pt-6">
                                               {{price($sd->per_item_discount)}} &nbsp; {{
                                            $sd->discount_type=='percentage'?'%':$currency }}
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
                                            {{price($sale['extra_discount'] ?? 0)}} &nbsp;{{$sale['extra_discount_type']=='percentage'?'%':$currency}}
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
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Content-->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" id="print">Print</button> --}}
                </div>
            </form>
        </div>
    </div>
    <script src={{asset('customJs/general.js')}}></script>
    <script>
        clipboard()
    </script>


