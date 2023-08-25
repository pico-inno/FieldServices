@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stock_out_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('stockinout::stockout.stockout_details')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockout.stockout')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-out.index')}}">{{__('stockinout::stockout.list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{$stockout->stockout_voucher_no}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    {{-- css file for this page --}}
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
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="pb-12">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column gap-7 gap-md-10">
                                <!--begin::Order details-->
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockout.voucher_no')}}</span>
                                        <span class="fs-5">{{$stockout->stockout_voucher_no}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockout.date')}}</span>
                                        <span class="fs-5">{{$stockout->stockout_date}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockout.business_location')}}</span>
                                        <span class="fs-5">{{$location}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockout.stockout_person')}}</span>
                                        <span class="fs-5">{{$stockout_person}}</span>
                                    </div>
                                </div>
                                <!--end::Order details-->
                                <!--begin:Order summary-->
                                <div class="d-flex justify-content-between flex-column">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="min-w-175px pb-2">{{__('stockinout::stockout.products')}}</th>
                                                <th class="min-w-70px text-end pb-2">{{__('stockinout::stockout.delivered_qty')}}</th>
                                                <th class="min-w-80px text-end pb-2">{{__('stockinout::stockout.uom')}}</th>
                                                <th class="min-w-100px text-end pb-2">{{__('stockinout::stockout.remark')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                            <!--begin::Products-->
                                            @foreach($stockout_details as $detail)

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
                                                                <div class="fw-bold">{{$detail->product->name}}
                                                                    @if ($detail->product_variation && $detail->product_variation->variation_template_value != null)
                                                                        <span class="text-gray-500 fw-semibold fs-5">{{ $detail->product_variation->variation_template_value }}</span>
                                                                    @endif
                                                                </div>

                                                                <div class="fs-7 text-muted">Expired
                                                                    Date: {{$detail->expired_date}}</div>
                                                            </div>
                                                            <!--end::Title-->
                                                        </div>
                                                    </td>
                                                    <!--end::Product-->
                                                    <!--begin::SKU-->
                                                    <td class="text-end">{{$detail->quantity}}</td>
                                                    <!--end::SKU-->
                                                    <!--begin::Quantity-->
                                                    <td class="text-end">{{$detail->uom->name}} ({{$detail->uom->short_name}}}</td>
                                                    <!--end::Quantity-->
                                                    <!--begin::Total-->
                                                    <td class="text-end">{{$detail->remark}}</td>
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
                        <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">
                            <!-- begin::Actions-->
                            <div class="my-1 me-5">
                                <!-- begin::Pint-->
                                <a href="{{route('stock-out.invoice.print', $stockout->id)}}" type="button" class="btn btn-success my-1 me-12 print-invoice">Print
                                    {{__('stockinout::stockout.invoice')}}
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
