
    <div class="modal-dialog modal-fullscreen-sm" id="printArea">
        <div class="modal-content">
              <form>
                <div class="modal-header">
                    <h3 class="-title">Opening Stock voucher (Voucher No: {{$openingStock['opening_stock_voucher_no']}} )</h3>

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
                            <address class="mt-3 fs-5">
                                {{$openingStock['opening_person']['username']}}<br>
                                {{-- Mobile:{{$openingStock['opening_person']['mobile']}} --}}
                            </address>
                        </div>
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-4">
                                Bussiness:
                            </h3>
                              <address class="mt-3 fs-5">
                                    {{businessLocationName($location)}}
                                    {!! address($address)!!}
                                </address>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-group">
                                {{-- <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    Reference No : <span class="text-gray-600"> {{$sock['reference_no']}}3</span>
                                </h3> --}}
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    Date : <span class="text-gray-600">{{$openingStock['opening_date']}}</span>
                                </h3>
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    {{-- sock Status : <span class="text-gray-600">{{$openingStock['status']}}</span> --}}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-sm-6">
                            <h3 class="text-primary-emphasis fs-4">
                                Note:
                            </h3>
                            <p class="mt-3 fs-5 pt-5">
                                   {{$openingStock['note']}}
                            </p>
                        </div>
                    </div>
                    <div class="table-responsive mt-10">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-primary">
                                <!--begin::Table row-->
                                <tr class="bg-primary fw-bold fs-6 text-white text-center text-uppercase fs-7">
                                    <th class="min-w-30 text-center ps-5">#</th>
                                    <th class="min-w-150px">Product Name</th>
                                    <th class="min-w-100px">UOM</th>
                                    <th class="min-w-100px">Quantity</th>
                                    <th class="min-w-100px"> Price </th>
                                    <th class="min-w-100px"> Subtotal </th>
                                    <th class="min-w-100px "> EXP date</th>
                                    <th class="min-w-75px ">Remark</th>
                                    <th class="min-w-75px pe-4">Updated At</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                           <tbody class="fw-semibold text-gray-800">
                                @foreach ($openingStockDetails as $key=>$osd)
                                    @php
                                        $p=$osd->product;
                                        if($p){
                                            $product_variation =$osd->toArray()['product_variation'];
                                        }
                                    @endphp
                                    <tr class="text-center">
                                        {{-- <!--begin::Name=--> --}}
                                        <td class="text-center">
                                            {{$key+1}}
                                        </td>
                                        {{-- <!--end::Name=-->
                                        <!--begin::Email=--> --}}
                                        <td>
                                            {{arr($p,'name','','Unknown Product')}}
                                            @if(isset($product_variation['variation_template_value']))
                                                <span class="my-2 d-block">
                                                    ({{ $product_variation['variation_template_value']['name'] }})
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$osd->uom->name}}
                                        </td>
                                        <td>
                                            {{round($osd->quantity,2)}}
                                        </td>
                                        <td>
                                            {{round($osd->uom_price,2)}}
                                        </td>
                                        <td>
                                            {{round($osd->subtotal,2)}}
                                        </td>
                                        <td>
                                            {{$osd->expired_date}}
                                        </td>
                                        <td class="">
                                            <p class="max-w-150px">
                                                {{$osd->remark}}
                                            </p>

                                        </td>
                                        <td>
                                            {{$osd->updated_at}}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <div class="col-sm-3 col-12 float-end mt-3">
                            <table>
                                <tbody>
                                        <tr class="">
                                            <td class="text-end fw-bold pe-4">Total Opening Amount :</td>
                                            <td class="text-end fw-bold ">
                                                {{$openingStock['total_opening_amount']}}
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                            {{$openingStock['opening_date']}}
                                        </td>
                                        <!--end::Name=-->
                                        <!--begin::Email=-->
                                        <td>
                                            <span class="badge badge-success">Opening Date</span>
                                        </td>
                                        <td>
                                            {{$openingStock['opening_person']['username']??'-'}}
                                        </td>
                                    </tr>
                                     <tr>
                                        <!--begin::Name=-->
                                        <td class="ps-2">
                                            {{$openingStock['created_at']}}
                                        </td>
                                        <!--end::Name=-->
                                        <!--begin::Email=-->
                                        <td>
                                            <span class="badge badge-primary">Created By</span>
                                        </td>
                                        <td>
                                            {{$openingStock['created_by']['username']??'-'}}
                                        </td>
                                    </tr>
                                    @if ($openingStock['updated_by'])
                                        <tr>
                                            <!--begin::Name=-->
                                            <td class="ps-2">
                                                {{$openingStock['updated_at']}}
                                            </td>
                                            <!--end::Name=-->
                                            <!--begin::Email=-->
                                            <td>
                                                <span class="badge badge-warning">Updated</span>
                                            </td>
                                            <td>
                                                {{$openingStock['updated_by']['username']??'-'}}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($openingStock['confirm_by'])
                                        <tr>
                                            <!--begin::Name=-->
                                            <td class="ps-2">
                                                {{$openingStock['purchased_at']}}
                                            </td>
                                            <!--end::Name=-->
                                            <!--begin::Email=-->
                                            <td>
                                                <span class="badge badge-primary">Confirmed</span>
                                            </td>
                                            <td>
                                                {{$openingStock['confirm_by']['username']??'-'}}
                                            </td>
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


