
    <div class="modal-dialog modal-fullscreen-sm" id="printArea">
        <div class="modal-content">
              <form>
                <div class="modal-header">
                    <h3 class="fs-4">Purchase Details (Purchase Voucher No: <span class=" " id="clipboard">{{$purchase['purchase_voucher_no']}}</span> )
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
                            <h3 class="text-primary-emphasis fs-6">
                                Supplier:
                            </h3>
                            @if ($purchase['supplier'])
                            <address class="mt-3 fs-5 fw-semibold">
                                {{$purchase['supplier']['company_name']}}<br>
                                Mobile:{{$purchase['supplier']['mobile']}}
                            </address>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-6">
                                Bussiness:
                            </h3>
                              <address class="mt-3 fs-6 fw-semibold">
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
                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                    Voucher No : <span class="text-gray-600 fw-semibold"> {{$purchase['purchase_voucher_no']}}3</span>
                                </h3>
                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                    Date : <span class="text-gray-600 fw-semibold">{{$purchase['purchased_at']}}</span>
                                </h3>
                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                    Purchase Status : <span class="text-gray-600 fw-semibold">{{$purchase['status']}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-10">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-primary">
                                <!--begin::Table row-->
                                <tr class="bg-primary fw-bold fs-6 text-white text-start text-uppercase fs-8">
                                    <th class="min-w-30 text-center ps-5">#</th>
                                    <th class="min-w-100px text-center ">Product Name</th>
                                    <th class="min-w-100px text-center ">Purchase Quantity</th>
                                    <th class="min-w-100px text-center ">UOM</th>
                                    <th class="min-w-100px text-center ">UOM Price</th>
                                    <th class="min-w-100px text-center ">Per Item Discount</th>
                                    <th class="min-w-100px text-center ">Subtotal With Discount</th>
                                    <th class="min-w-100px text-center ">Per Item Expense</th>
                                    <th class="min-w-100px text-center ">Subtotal </th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                           <tbody class="fw-semibold text-gray-800 fs-6">
                            @foreach ($purchase_details as $key=>$pd)
                                @php
                                    $p=$pd->product;
                                    $product_variation =$pd->toArray()['product_variation'];
                                    // dd($pd->toArray());
                                    $pdCurrency=$pd->currency['symbol'] ?? ' ';

                                    $quantityDp=getSettingValue('quantity_decimal_places');
                                @endphp
                                <tr class="text-center">
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
                                        {{round($pd->quantity,$quantityDp)}}
                                    </td>
                                    <td>
                                        {{$pd->toArray()['purchase_uom']['name']}}
                                    </td>
                                    <td>
                                        {{price($pd->uom_price ?? 0,$pd->currency_id)}}
                                    </td>
                                    <td>
                                        {{round($pd->per_item_discount ?? 0,2)}}&nbsp;{{ $pd->discount_type=='percentage'?'%':$pdCurrency }}
                                    </td>
                                    <td>
                                        {{price($pd->subtotal_with_discount,$pd->currency_id)}}
                                    </td>
                                    <td>
                                        {{price($pd->per_item_expense,$pd->currency_id)}}
                                    </td>
                                    <td>
                                        {{price($pd->subtotal_with_tax,$pd->currency_id)}}
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
                        <div class="col-md-6 col-sm-12 col-xs-12  ">
                            <div class="table-responsive mt-10">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                    <!--begin::Table body-->
                                    @php
                                        $purchaseCurrency=$purchase['currency']['symbol'] ?? ''
                                    @endphp
                                   <tbody class="fw-semibold text-gray-800 table-bordere">
                                    <tr>
                                        <td>
                                            Net Total Amount:
                                        </td>
                                        <td class="text-end">
                                        </td>
                                        <td class="text-end">
                                            {{price($purchase['purchase_amount'] ?? 0,$pd->currency_id)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Discount:
                                        </td>
                                        <td class="text-end">
                                            (-)
                                        </td>
                                        <td class="text-end">
                                            {{round($purchase['extra_discount'] ?? 0,2)}} &nbsp;{{ $purchase['extra_discount_type']=='percentage'?'%':$purchaseCurrency}}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Purchase Expense:
                                        </td>
                                        <td class="text-end">
                                            (+)
                                        </td>
                                        <td class="text-end">
                                            {{price($purchase['purchase_expense'],$pd->currency_id)}}
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Additional Shipping charges:	</td>
                                        <td class="text-end">
                                            (+)
                                        </td>
                                        <td class="text-end">
                                            0
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td>
                                            Purchase Total:
                                        </td>
                                        <td class="text-end">
                                            (=)
                                        </td>
                                        <td class="text-end">
                                        {{price($purchase['total_purchase_amount'],$pd->currency_id)}}
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
                                            {{$purchase['purchased_at']}}
                                        </td>
                                        <!--end::Name=-->
                                        <!--begin::Email=-->
                                        <td>
                                            <span class="badge badge-success">Purchase</span>
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
                                                {{$purchase['updated_at']}}
                                            </td>
                                            <!--end::Name=-->
                                            <!--begin::Email=-->
                                            <td>
                                                <span class="badge badge-warning">Updated</span>
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
                    {{-- <button type="button" class="btn btn-primary" id="print">Print</button> --}}
                </div>
            </form>
        </div>
    </div>
    <script src={{asset('customJs/general.js')}}></script>
    <script>
        clipboard()
    </script>

