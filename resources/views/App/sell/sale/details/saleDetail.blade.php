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
                    <div class="row mb-10">
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-4">
                                Supplier:
                            </h3>
                            @if ($sale['customer'])
                                <address class="mt-3 fs-5">
                                    {{$sale['customer']['first_name']}} <br>
                                    Mobile:{{$sale['customer']['mobile']}}
                                </address>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-4">
                                Bussiness:
                            </h3>
                              <address class="mt-3 fs-5">
                                    {{$location['name']}}<br>
                                    {{$location['landmark']}}<br>
                                    {{$location['city'] ? $location['city']."," :''}}
                                    {{$location['state'] ? $location['state']."," :' '}}
                                    {{$location['country'] ? $location['country'].",":''}}
                                    {{$location['zip_code'] ? $location['zip_code']."," :''}}
                                </address>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-group">
                                {{-- <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    Reference No : <span class="text-gray-600"> {{$sale['reference_no']}}3</span>
                                </h3> --}}
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    Date : <span class="text-gray-600">{{$sale['sold_at']}}</span>
                                </h3>
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    sale Status : <span class="text-gray-600">{{$sale['status']}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-10">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-primary">
                                <!--begin::Table row-->
                                <tr class="bg-primary fw-bold fs-9 text-white text-center text-uppercase fs-7">
                                    <th class="min-w-30 text-center ps-5">#</th>
                                    <th class="min-w-100px">Product Name</th>
                                    <th class="min-w-100px">Quantity</th>
                                    @if ($sale['status']=='partial')
                                        <th class="min-w-100px">Deivered Quantity</th>
                                    @endif
                                    <th class="min-w-100px">UOM</th>
                                    <th class="min-w-100px">sale Price</th>
                                    <th class="min-w-100px">Discount Type</th>
                                    <th class="min-w-100px">Per Item Discount Amount</th>
                                    <th class="min-w-100px">Subtotal</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                           <tbody class="fw-semibold text-gray-800">
                            @foreach ($sale_details as $key=>$sd)
                                @php
                                    $p=$sd->product;
                                    $product_variation =$sd->toArray()['product_variation'];
                                    $currency=$sd->currency ? $sd->currency['symbol']:'';
                                @endphp

                                    <tr class="text-center fs-6">
                                        <!--begin::Name=-->
                                        <td class="text-center">
                                            {{$key+1}}
                                        </td>
                                        <!--end::Name=-->
                                        <!--begin::Email=-->
                                        <td>
                                            {{$p->name}}
                                            @if(isset($product_variation['variation_template_value']))
                                                <span class="my-2 d-block">
                                                    ({{ $product_variation['variation_template_value']['name'] }})
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{round($sd->quantity,$quantityDp)}}
                                        </td>
                                        @if ($sale['status']=='partial')
                                            <td class="min-w-100px">{{round($sd->delivered_quantity ?? 0,$currencyDp)}}</td>
                                        @endif
                                        <td>
                                            {{$sd['uom']['name']}}
                                        </td>
                                        <td>
                                            {{round($sd->subtotal,$currencyDp)}}&nbsp;{{$currency}}
                                        </td>
                                        <td>
                                            {{$sd->discount_type}}
                                        </td>
                                        <td>
                                            {{round($sd->per_item_discount,$currencyDp)}} &nbsp; {{ $sd->discount_type=='percentage'?'%':$currency }}
                                        </td>
                                        <td>
                                            {{round($sd->subtotal_with_tax,$currencyDp)}}&nbsp;{{$currency}}
                                        </td>


                                    </tr>

                            @endforeach
                        </tbody>
                            <!--end::Table body-->
                        </table>

                    </div>
                    <div class="row mt-5 justify-content-end">
                        {{-- <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="table-responsive mt-10">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                    <!--begin::Table head-->
                                    <thead class="bg-success">
                                        <!--begin::Table row-->
                                        <tr class="bg-success fw-bold fs-6 text-white text-start text-uppercase fs-7 ">
                                            <th class="ps-3">#</th>
                                            <th class="min-w-100px">Date</th>
                                            <th class="min-w-100px">Reference No</th>
                                            <th class="min-w-100px">Amount</th>
                                            <th class="min-w-100px">Payment mode</th>
                                            <th class="min-w-100px">Payment note</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <tbody class="fw-semibold text-gray-800">
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No payments found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                        <div class="col-md-6 col-sm-12 col-xs-12  me-10">
                            <div class="table-responsive mt-10">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-800 table-bordered billDiv">
                                        <tr>
                                            <td>
                                                Sale Amount
                                            </td>
                                            <td class="text-end">
                                                (=)
                                            </td>
                                            <td class="text-end">
                                                {{round($sale['sale_amount'] ?? 0,$currencyDp)}}&nbsp;{{$currency}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Item Discount:
                                            </td>
                                            <td class="text-end">
                                                (-)
                                            </td>
                                            <td class="text-end">
                                                {{round($sale['total_item_discount'] ?? 0,$currencyDp)}}&nbsp;{{$currency}}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            Extra Discount:
                                            </td>
                                            <td class="text-end">
                                                (-)
                                            </td>
                                            <td class="text-end">
                                                {{round($sale['extra_discount'] ?? 0,$currencyDp)}} &nbsp;{{ $sale['extra_discount_type']=='percentage'?'%':$currency}}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Sale Amount:
                                            </td>
                                            <td class="text-end">
                                                (=)
                                            </td>
                                            <td class="text-end">
                                            {{round($sale['total_sale_amount'],$currencyDp)}}&nbsp;{{$currency}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Paid Amount:
                                            </td>
                                            <td class="text-end">
                                                (-)
                                            </td>
                                            <td class="text-end">
                                                {{round($sale['paid_amount'],$currencyDp)}}&nbsp;{{$currency}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Balance Total:
                                            </td>
                                            <td class="text-end">
                                                (=)
                                            </td>
                                            <td class="text-end">
                                                {{round($sale['balance_amount'],$currencyDp)}}&nbsp;{{$currency}}
                                            </td>
                                        </tr>

                                    </tbody>
                                    <!--end::Table body-->
                                </table>

                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mt-5 mb-5">
                        <div class="col-md-6">
                            <h3 class="text-primary-emphasis fs-4">
                                Shipping Details:
                            </h3>
                            <div class="mt-3 fs-5">
                                <p>
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem ipsa iure adipisci culpa explicabo quaerat a in incidunt! Eius aliquid deserunt cum culpa saepe voluptate repudiandae vero. Minima, sequi.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-5">
                            <h3 class="text-primary-emphasis fs-4">
                                Additional Notes:
                            </h3>
                            <div class="mt-3 fs-5">
                                <p>
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem ipsa iure adipisci culpa explicabo quaerat a in incidunt! Eius aliquid deserunt cum culpa saepe voluptate repudiandae vero. Minima, sequi.
                                </p>
                            </div>
                        </div>
                    </div> --}}
                    <div class="table-responsive mt-10">
                        <table class="table table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-success">
                                <!--begin::Table row-->
                                <tr class="bg-secondary fw-bold fs-6 text-black text-start text-uppercase fs-7 p-2">
                                    <th class="min-w-60px ps-2">Date	</th>
                                    <th class="min-w-100px">Action</th>
                                    <th class="min-w-100px">By</th>
                                    {{-- <th class="min-w-100px">Note</th> --}}
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-800">

                                    <tr>
                                        <!--begin::Name=-->
                                        <td class="ps-2">
                                            {{$sale['sold_at']}}
                                        </td>
                                        <!--end::Name=-->
                                        <!--begin::Email=-->
                                        <td>
                                            <span class="badge badge-success">sale</span>
                                        </td>
                                        <td>
                                            {{$sale['saled_by']['username']??'-'}}
                                        </td>
                                        {{-- <td class="">
                                            <table class="no-border table table-slim mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td><span class="badge badge-light-success">Ordered</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td><span class="badge badge-light-success">Ks 0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Payment status:</th>
                                                        <td><span class="badge badge-light-success">Due</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td> --}}



                                    </tr>
                                    @if ($sale['updated_by'])
                                        <tr>
                                            <!--begin::Name=-->
                                            <td class="ps-2">
                                                {{$sale['updated_at']}}
                                            </td>
                                            <!--end::Name=-->
                                            <!--begin::Email=-->
                                            <td>
                                                <span class="badge badge-warning">Updated</span>
                                            </td>
                                            <td>
                                                {{$sale['updated_by']['username']??'-'}}
                                            </td>
                                            {{-- <td class="">
                                                <table class="no-border table table-slim mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td><span class="badge badge-light-success">Ordered</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <td><span class="badge badge-light-success">Ks 0</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payment status:</th>
                                                            <td><span class="badge badge-light-success">Due</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td> --}}



                                        </tr>
                                    @endif
                                    @if ($sale['confirm_by'])
                                        <tr>
                                            <!--begin::Name=-->
                                            <td class="ps-2">
                                                {{$sale['sold_at']}}
                                            </td>
                                            <!--end::Name=-->
                                            <!--begin::Email=-->
                                            <td>
                                                <span class="badge badge-primary">Confirmed</span>
                                            </td>
                                            <td>
                                                {{$sale['confirm_by']['username']??'-'}}
                                            </td>
                                            {{-- <td class="">
                                                <table class="no-border table table-slim mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td><span class="badge badge-light-success">Ordered</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <td><span class="badge badge-light-success">Ks 0</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payment status:</th>
                                                            <td><span class="badge badge-light-success">Due</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td> --}}



                                        </tr>
                                    @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>

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


