
    <div class="modal-dialog modal-fullscreen-sm" id="printArea">
        <div class="modal-content">
              <form>
                <div class="modal-header">
                    <h3 class="fs-4">{{__('adjustment.adjustment_details')}} ({{__('adjustment.voucher_no')}}: <span class=" " id="clipboard">{{$stockAdjustment->adjustment_voucher_no}}</span> )
                        <a type="button" class="btn btn-icon btn-sm p-0" data-clipboard-target="#clipboard">
                            <i class="fa-solid fa-copy fs-6 clipboard-icon ki-copy"></i>
                        </a></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="card-body py-20">
                        <!-- begin::Wrapper-->
                        <div class="mw-lg-950px mx-auto w-100">
                            <!-- begin::Header-->
                            <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                                <h4 class=" text-gray-800  pe-5 pb-7"></h4>
                                <!--end::Logo-->
                                <div class="text-sm-end">
                                    <a data-href="{{route('adjustment.print',$stockAdjustment->id)}}" class="btn btn-icon print-invoice btn-sm btn-active-light-primary ms-2" >
                                        <i class="fa-solid fa-print fs-2"></i>
                                    </a>

                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="pb-12">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column gap-7 gap-md-10">
                                    <!--begin::Order details-->
                                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment.voucher_no')}}</span>
                                            <span class="fs-6">{{$stockAdjustment->adjustment_voucher_no}}</span>
                                        </div>
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment.date')}}</span>
                                            <span class="fs-6">{{$stockAdjustment->created_at}}</span>
                                        </div>
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment.location')}}</span>
                                            <span class="fs-6">{{$stockAdjustment->businessLocation['name']}}</span>
                                        </div>
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment_status')}}</span>
                                            <span class="fs-6 {{$stockAdjustment->status == 'completed' ? 'text-success' : 'text-warning'}} ">{{$stockAdjustment->status}}</span>
                                        </div>
                                    </div>
                                    <!--end::Order details-->
                                    <!--begin::Billing & shipping-->
                                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted"> Condition </span>
                                            <span class="fs-6">{{$stockAdjustment->condition ?? ''}}</span>
                                        </div>
                                        <div class="flex-root d-flex flex-column"></div>
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment.increase_subtotal')}}</span>
                                            <span class="fs-6 text-success">{{$stockAdjustment->increase_subtotal ?? ''}}</span>
                                        </div>
                                        <div class="flex-root d-flex flex-column">
                                            <span class="text-muted">{{__('adjustment.decrease_subtotal')}}</span>
                                            <span class="fs-6 text-danger">{{$stockAdjustment->decrease_subtotal ?? ''}}</span>
                                        </div>
                                    </div>
                                    <!--end::Billing & shipping-->
                                    <!--begin:Order summary-->
                                    <div class="d-flex justify-content-between flex-column">
                                        <!--begin::Table-->
                                        <div class="table-responsive border-bottom mb-9">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                <thead>
                                                <tr class="border-bottom fs-6 fw-bold text-primary">
                                                    <th class="min-w-175px pb-2">{{__('adjustment.product')}}</th>
                                                    <th class="min-w-70px text-end pb-2">{{__('adjustment.uom')}}</th>
                                                    <th class="min-w-70px text-end pb-2">{{__('adjustment.total_balance')}}</th>
                                                    <th class="min-w-80px text-end pb-2">{{__('adjustment.on_ground_qty')}}</th>
                                                    <th class="min-w-80px text-end pb-2">{{__('adjustment.adjustment_qty')}}</th>
                                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.type')}}</th>
                                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.uom_price')}}</th>
                                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.total')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Products-->
                                                @foreach($stockAdjustmentDetails as $detail)
                                                    <tr>
                                                        <!--begin::Product-->
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!--begin::Thumbnail-->
                                                                {{--                                                        <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">--}}
                                                                {{--                                                            <span class="symbol-label" style="background-image:url(assets/media//stock/ecommerce/1.gif);"></span>--}}
                                                                {{--                                                        </a>--}}
                                                                <!--end::Thumbnail-->
                                                                <!--begin::Title-->
                                                                <div class="">
                                                                    <div
                                                                        class="fw-bold">{{$detail['product_name']}} <span class="text-gray-500 fw-semibold fs-5">{{ $detail['variation_name']??'' }}</span></div>
                                                                    {{--                                                                <div class="fs-7 text-muted">Expired--}}
                                                                    {{--                                                                    Date: {{$detail['expired_date']}}</div>--}}
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                        </td>
                                                        <!--end::Product-->
                                                        <!--begin::SKU-->
                                                        <td class="text-end">{{$detail['uom_name']}}</td>
                                                        <!--end::SKU-->
                                                        <!--begin::SKU-->
                                                        <td class="text-end">{{$detail['balance_quantity']}} {{$detail['uom_short_name'] ? '('.$detail['uom_short_name'].')' : ''}}</td>
                                                        <!--end::SKU-->
                                                        <!--begin::Quantity-->
                                                        <td class="text-end">{{$detail['gnd_quantity']}} {{$detail['uom_short_name'] ? '('.$detail['uom_short_name'].')' : ''}}</td>
                                                        <!--end::Quantity-->
                                                        <!--begin::Quantity-->
                                                        <td class="text-end {{$detail['gnd_quantity'] < 0 ? 'text-danger' : 'text-success'}}">{{$detail['adj_quantity']}} {{$detail['uom_short_name'] ? '('.$detail['uom_short_name'].')' : ''}}</td>
                                                        <!--end::Quantity-->
                                                        <td class="text-end">{{$detail['adjustment_type']}}</td>
                                                        <!--begin::Total-->
                                                        <td class="text-end">{{$detail['uom_price']}}</td>
                                                        <!--end::Total-->
                                                        <!--begin::Total-->
                                                        <td class="text-end">{{$detail['subtotal']}}</td>
                                                        <!--end::Total-->
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <!--end:Order summary-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Body-->
                            <!-- begin::Footer-->
{{--                            <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">--}}
                                <!-- begin::Actions-->
{{--                                <div class="my-1 me-5">--}}
                                    <!-- begin::Pint-->
{{--                                    <a href="{{route('stock-transfer.invoice.print',$stockAdjustment['id'])}}" type="button" class="btn btn-success my-1 me-12 print-invoice">Print--}}
{{--                                        Invoice--}}
{{--                                    </a>--}}
                                    <!-- end::Pint-->
                                    <!-- begin::Download-->
                                    {{--                                <button type="button" class="btn btn-light-success my-1">Download</button>--}}
                                    <!-- end::Download-->
{{--                                </div>--}}
                                <!-- end::Actions-->
                                <!-- begin::Action-->
                                {{--                            <a href="../../demo7/dist/apps/invoices/create.html" class="btn btn-primary my-1">Create--}}
                                {{--                                Invoice</a>--}}
                                <!-- end::Action-->
{{--                            </div>--}}
                            <!-- end::Footer-->
                        </div>
                        <!-- end::Wrapper-->
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

