
<div class="modal-dialog modal-fullscreen-sm" id="printArea">
    <div class="modal-content">
        <form>
            <div class="modal-header">
                <h3 class="fs-4">{{__('transfer.transfer_details')}} ({{__('transfer.voucher_no')}}: <span class=" " id="clipboard">{{$stockTransfer->transfer_voucher_no}}</span> )
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
                <div class="card-body py-15">
                    <!-- begin::Wrapper-->
                    <div class="mw-lg-950px mx-auto w-100">
                        <!-- begin::Header-->
                        <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                            <h4 class=" text-gray-800  pe-5 pb-7"></h4>
                            <!--end::Logo-->
                            <div class="text-sm-end">
                                <a data-href="{{route('transfer.print',$stockTransfer->id)}}" class="btn btn-icon print-invoice btn-sm btn-active-light-primary ms-2" >
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
                                        <span class="text-muted">Voucher No</span>
                                        <span class="fs-5">{{$stockTransfer->transfer_voucher_no}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Transfer Date</span>
                                        <span class="fs-5">{{$stockTransfer->transfered_at}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Location (From)</span>
                                        <span class="fs-5">{{$stockTransfer->from_location_name}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Location (To)</span>
                                        <span class="fs-5">{{$stockTransfer->to_location_name}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Status</span>
                                        <span class="fs-5
                                        @if($stockTransfer->status == 'completed')
                                        text-success
                                        @elseif($stockTransfer->status == 'in_transit')
                                        text-warning
                                        @else
                                        text-primary
                                        @endif
                                        ">{{$stockTransfer->status}}</span>
                                    </div>
                                </div>
                                <!--end::Order details-->
                                <!--begin::Billing & shipping-->
{{--                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">--}}
{{--                                    <div class="flex-root d-flex flex-column">--}}
{{--                                        <span class="text-muted">{{__('adjustment.increase_subtotal')}}</span>--}}
{{--                                        --}}{{--                                        <span class="fs-6 text-success">{{$stockAdjustment->increase_subtotal ?? ''}}</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="flex-root d-flex flex-column">--}}
{{--                                        <span class="text-muted">{{__('adjustment.decrease_subtotal')}}</span>--}}
{{--                                        --}}{{--                                        <span class="fs-6 text-danger">{{$stockAdjustment->decrease_subtotal ?? ''}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <!--end::Billing & shipping-->
                                <!--begin:Order summary-->
                                <div class="d-flex justify-content-between flex-column">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-primary">
                                                <th class="min-w-175px pb-2">Products</th>
                                                <th class="min-w-80px text-end pb-2">Transfer Qty</th>
                                                <th class="min-w-80px text-end pb-2">UOM</th>
                                                <th class="min-w-100px text-end pb-2">Remark</th>
                                            </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                            <!--begin::Products-->
                                            @foreach($stockTransferDetails as $detail)
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
                                                            <div class="ms-5">
                                                                <div
                                                                    class="fw-bold">{{$detail['product_name']}} <span class="text-gray-500 fw-semibold fs-5">{{ $detail['variation_name']??'' }}</span></div>
                                                                {{-- <div class="fs-7 text-muted">Expired
                                                                    Date: {{$detail['expired_date']}}</div> --}}
                                                            </div>
                                                            <!--end::Title-->
                                                        </div>
                                                    </td>
                                                    <!--end::Product-->
                                                    <!--begin::SKU-->
                                                    {{-- <td class="text-end">{{$detail['batch_no']}}</td> --}}
                                                    <!--end::SKU-->
                                                    <!--begin::Quantity-->
                                                    <td class="text-end">{{$detail['quantity']}} {{ $detail['uom_short_name'] }}</td>
                                                    <!--end::Quantity-->
                                                    <!--begin::Quantity-->
                                                    <td class="text-end">{{$detail['uom_name']}}</td>
                                                    <!--end::Quantity-->
                                                    <!--begin::Total-->
                                                    <td class="text-end">{{$detail['remark']}}</td>
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

                    </div>
                    <!-- end::Wrapper-->
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
<script src={{asset('customJs/general.js')}}></script>
<script>
    clipboard()
</script>

