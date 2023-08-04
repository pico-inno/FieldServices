@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection
@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stock_adjustment_here_show', 'here show')
@section('stock_adjustment_active_show', 'active ')

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('adjustment.adjustment')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">
            <a href="{{route('stock-adjustment.index')}}" class="text-muted text-hover-primary">{{__('adjustment.list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">View ({{$stockAdjustment->adjustment_voucher_no}})</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!-- begin::Invoice 3-->
            <div class="card">
                <!-- begin::Body-->
                <div class="card-body py-20">
                    <!-- begin::Wrapper-->
                    <div class="mw-lg-950px mx-auto w-100">
                        <!-- begin::Header-->
                        <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                            <h4 class=" text-gray-800 fs-2qx pe-5 pb-7">{{__('adjustment.adjustment_details')}}</h4>
                            <!--end::Logo-->
                            <div class="text-sm-end">

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
                                        <span class="text-muted">{{__('adjustment.coucher_no')}}</span>
                                        <span class="fs-6">{{$stockAdjustment->adjustment_voucher_no}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('adjustment.date')}}</span>
                                        <span class="fs-6">{{$stockAdjustment->created_at}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('adjustment.location')}}</span>
                                        <span class="fs-6">fasd</span>
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
                                                    <td class="text-end {{$detail['adj_quantity'] < 0 ? 'text-danger' : 'text-success'}}">{{$detail['adj_quantity']}} {{$detail['uom_short_name'] ? '('.$detail['uom_short_name'].')' : ''}}</td>
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
                                            <!--begin::Subtotal-->
                                            {{--                                            <tr>--}}
                                            {{--                                                <td colspan="3" class="text-end">Subtotal</td>--}}
                                            {{--                                                <td class="text-end">$264.00</td>--}}
                                            {{--                                            </tr>--}}
                                            <!--end::Subtotal-->
                                            <!--begin::VAT-->
                                            {{--                                            <tr>--}}
                                            {{--                                                <td colspan="3" class="text-end">VAT (0%)</td>--}}
                                            {{--                                                <td class="text-end">$0.00</td>--}}
                                            {{--                                            </tr>--}}
                                            <!--end::VAT-->
                                            <!--begin::Shipping-->
                                            {{--                                            <tr>--}}
                                            {{--                                                <td colspan="3" class="text-end">Shipping Rate</td>--}}
                                            {{--                                                <td class="text-end">$5.00</td>--}}
                                            {{--                                            </tr>--}}
                                            <!--end::Shipping-->
                                            <!--begin::Grand total-->
                                            {{--                                            <tr>--}}
                                            {{--                                                <td colspan="3" class="fs-3 text-dark fw-bold text-end">Grand Total</td>--}}
                                            {{--                                                <td class="text-dark fs-3 fw-bolder text-end">$269.00</td>--}}
                                            {{--                                            </tr>--}}
                                            <!--end::Grand total-->
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
                        <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">
                            <!-- begin::Actions-->
                            <div class="my-1 me-5">
                                <!-- begin::Pint-->
                                <a href="{{route('stock-transfer.invoice.print',$stockAdjustment['id'])}}" type="button" class="btn btn-success my-1 me-12 print-invoice">Print
                                    Invoice
                                </a>
                                <!-- end::Pint-->
                                <!-- begin::Download-->
{{--                                <button type="button" class="btn btn-light-success my-1">Download</button>--}}
                                <!-- end::Download-->
                            </div>
                            <!-- end::Actions-->
                            <!-- begin::Action-->
                            {{--                            <a href="../../demo7/dist/apps/invoices/create.html" class="btn btn-primary my-1">Create--}}
                            {{--                                Invoice</a>--}}
                            <!-- end::Action-->
                        </div>
                        <!-- end::Footer-->
                    </div>
                    <!-- end::Wrapper-->
                </div>
                <!-- end::Body-->
            </div>
            <!-- end::Invoice 1-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script>

        // print invoice
        $(document).on('click', '.print-invoice', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            $.ajax({
                url: url,
                success: function (response) {
                    // Open a new window with the invoice HTML and styles
                    // Create a hidden iframe element and append it to the body
                    var iframe = $('<iframe>', {
                        'height': '0px',
                        'width': '0px',
                        'frameborder': '0',
                        'css': {
                            'display': 'none'
                        }
                    }).appendTo('body')[0];
                    console.log(response);
                    // Write the invoice HTML and styles to the iframe document
                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write(response.html);
                    iframeDoc.close();

                    // Trigger the print dialog
                    iframe.contentWindow.focus();
                    setTimeout(() => {
                        iframe.contentWindow.print();
                        console.log('hello');
                    }, 500);
                }
            });
        });
    </script>
@endpush
