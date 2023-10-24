<div class="modal-dialog modal-fullscreen-sm" id="printArea">
    <div class="modal-content">
        <div>
            <div class="modal-header">
                <h3 class="fs-4">Purchase Details -<span class=" "
                        id="clipboard">{{$purchase['purchase_voucher_no']}}</span>
                    <a type="button" class="btn btn-icon btn-sm p-0" data-clipboard-target="#clipboard">
                        <i class="fa-solid fa-copy fs-6 clipboard-icon ki-copy"></i>
                    </a>
                </h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fas fa-times fs-2"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="m-0">
                    <div class="row g-5 mb-10">
                        <div class="col-sm-6">
                            <div class="fw-bold fs-6 text-gray-800">#{{$purchase['purchase_voucher_no']}}</div>
                            <div class="fw-semibold fs-7 text-gray-600">
                                Status : <span class="badge badge-light-success">{{$purchase['status']}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Purchase Date:</div>
                            <!--end::Label-->
                            <!--end::Info-->
                            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                <span class="pe-2">{{fDate($purchase['purchased_at'])}}</span>
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
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Supplier:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            @if ($purchase['supplier'])
                            <address class="mt-3 fs-5 fw-semibold fs-7">
                                <span class="fw-bold">{{$purchase['supplier']['company_name']}}</span>
                                <br>
                                <span class="text-gray-700 fs-7">
                                    {{$purchase['supplier']['mobile']}}
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
                               {!! addresss($addresss) !!}
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
                                        <th class="min-w-100px text-end ">Purchase Quantity</th>
                                        <th class="min-w-100px text-end ">UOM</th>
                                        <th class="min-w-100px text-end ">UOM Price</th>
                                        <th
                                            class="min-w-100px text-end {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">
                                            Per Item Discount</th>
                                        <th
                                            class="min-w-100px text-end {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">
                                            Subtotal With Discount</th>
                                        <th class="min-w-100px text-end ">Per Item Expense</th>
                                        <th class="min-w-100px text-end ">Subtotal </th>

                                        {{-- <th class="min-w-70px text-end pb-2">Hours</th>
                                        <th class="min-w-80px text-end pb-2">Rate</th>
                                        <th class="min-w-100px text-end pb-2">Amount</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase_details as $key=>$pd)
                                    @php
                                    // dd($pd);
                                    $p=$pd->product;
                                    $product_variation =$pd->toArray()['product_variation'];
                                    // dd($pd->toArray());
                                    $pdCurrency=$pd->currency['symbol'] ?? ' ';

                                    $quantityDp=getSettingValue('quantity_decimal_places');
                                    @endphp
                                    <tr class="fw-bold text-gray-700 fs-7 text-end">
                                        <td class="pt-6 text-start ps-2">{{$key+1}}</td>
                                        <td class="d-flex align-items-center pt-6">
                                            <div href="" class="symbol symbol-40px me-2">
                                                <span class="symbol-label" style="background-image:url({{asset("
                                                    storage/product-image/$p->image")}});"></span>
                                            </div>
                                            {{$p->name}}
                                            @if(isset($product_variation['variation_template_value']))
                                            <span class="my-2 d-block">
                                                ({{ $product_variation['variation_template_value']['name'] }})
                                            </span>
                                            @endif
                                        </td>
                                        <td class="pt-6">{{round($pd->packagingTx->quantity,$quantityDp)}}({{$pd->packagingTx->packaging->packaging_name}})</td>
                                        <td class="pt-6">{{round($pd->quantity,$quantityDp)}}</td>
                                        <td class="pt-6">{{$pd->toArray()['purchase_uom']['name']}}</td>
                                        <td class="pt-6">{{price($pd->uom_price ?? 0,$pd->currency_id)}}</td>
                                        <td
                                            class="pt-6 {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">
                                            {{round($pd->per_item_discount ?? 0,2)}}&nbsp;{{
                                            $pd->discount_type=='percentage'?'%':$pdCurrency }}</td>
                                        <td
                                            class="pt-6 {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">
                                            {{price($pd->subtotal_with_discount,$pd->currency_id)}}</td>
                                        <td class="pt-6">{{price($pd->per_item_expense,$pd->currency_id)}}</td>
                                        <td class="pt-6 text-dark fw-bolder">
                                            {{price($pd->subtotal_with_tax,$pd->currency_id)}}</td>
                                        {{-- <td class="pt-6 text-dark fw-bolder">$3200.00</td> --}}
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                        <!--begin::Container-->
                        <div class="d-flex justify-content-end">
                            @php
                                $purchaseCurrency=$purchase['currency']['symbol'] ?? ''
                            @endphp
                            <!--begin::Section-->
                            <div class="mw-300px">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack mb-3">
                                    <!--begin::Accountname-->
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Net Total Amount:</div>
                                    <!--end::Accountname-->
                                    <!--begin::Label-->
                                    <div class="text-end fw-bold fs-6 text-gray-800">
                                        {{price($purchase['purchase_amount'] ?? 0,$pd->currency_id)}}</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex flex-stack mb-3">
                                    <!--begin::Accountname-->
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Discount</div>
                                    <!--end::Accountname-->
                                    <!--begin::Label-->
                                    <div class="text-end fw-bold fs-6 text-gray-800">
                                        {{round($purchase['extra_discount'] ?? 0,2)}}
                                        &nbsp;{{$purchase['extra_discount_type']=='percentage'?'%':$purchaseCurrency}}
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex flex-stack mb-3">
                                    <!--begin::Accountnumber-->
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Purchase Expense</div>
                                    <!--end::Accountnumber-->
                                    <!--begin::Number-->
                                    <div class="text-end fw-bold fs-6 text-gray-800">
                                        {{price($purchase['purchase_expense'],$pd->currency_id)}}</div>
                                    <!--end::Number-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Code-->
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Purchase Amount</div>
                                    <!--end::Code-->
                                    <!--begin::Label-->
                                    <div class="text-end fw-bold fs-6 text-gray-800">
                                        {{price($purchase['total_purchase_amount'],$pd->currency_id)}}</div>
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
                <div class="table-responsive mt-15 ">
                    <table class="table table-row-dashed fs-6 gy-2" id="kt_customers_table">
                        <!--begin::Table head-->
                        <thead class="bg-gray-200 bg-opacity-20">
                            <!--begin::Table row-->
                            <tr class=" fw-bold fs-6 text-gray-600 text-start text-uppercase fs-7 p-2">
                                <th class="min-w-60px ps-2">Date </th>
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
                                    {{fDate($purchase['purchased_at'])}}
                                </td>
                                <!--end::Name=-->
                                <!--begin::Email=-->
                                <td>
                                    <span class="badge badge-light-success">Creaete Purchase</span>
                                </td>
                                <td>
                                    {{$purchase['purchased_by']['username']??'-'}}
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
                            @if ($purchase['updated_by'])
                                <tr>
                                    <!--begin::Name=-->
                                    <td class="ps-2">
                                        {{fDate($purchase['updated_at'])}}
                                    </td>
                                    <!--end::Name=-->
                                    <!--begin::Email=-->
                                    <td>
                                        <span class="badge badge-light-warning">Updated</span>
                                    </td>
                                    <td>
                                        {{$purchase['updated_by']['username']??'-'}}
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
            </div>
        </div>
    </div>
</div>
<script src={{asset('customJs/general.js')}}></script>
<script>
    clipboard()
</script>
