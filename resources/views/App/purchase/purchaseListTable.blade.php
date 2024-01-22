@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')
@section('purchases_list_active_show', 'active ')


@section('styles')
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<style>
    #purchase_table_card .table-responsive {
        min-height: 60vh;
    }

</style>
@livewireStyles
@endsection


@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-sm-3 fs-8">{{__('purchase.list_purchase')}}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{__('purchase.purchase')}}</li>
    <li class="breadcrumb-item text-dark">{{__('purchase.list_purchase')}} </li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')

{{-- <div class="container">
    <!--begin::Container-->
    <div class="accordion" id="kt_accordion_1">
        <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">

            </h2>
            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show"
                aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                <div class="accordion-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis nihil dolore quas assumenda eius
                    repudiandae, error iste exercitationem. Hic natus doloribus molestiae porro incidunt dolor modi!
                    Quas nostrum perspiciatis quia!
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2"
            data-bs-parent="#kt_accordion_1">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                <div class="card">
                    <div class="card-header p-5 p-sm-7">
                        <div class="card-title">
                            <h2>Filters</h2>
                        </div>
                    </div>
                    <div class="card-body p-5 p-sm-7">
                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-3 ">
                                <label class="form-label fs-6 fw-semibold">Bussiness Location:</label>
                                <select class="form-select form-select-sm fw-bold" data-kt-select2="true"
                                    data-placeholder="Select option" data-allow-clear="true"
                                    data-kt-business-location-filter="locations" data-hide-search="true">
                                    <option></option>
                                    <option value="all">All</option>
                                    @foreach ($locations as $l)
                                    <option value="{{$l->name}}">{{businessLocationName($l)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold">Supplier:</label>
                                <select class="form-select form-select-sm fw-bold" data-kt-select2="true"
                                    data-placeholder="Select option" data-allow-clear="true"
                                    data-kt-supplier-table-filter="supplier" data-hide-search="true">
                                    <option></option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{$supplier->supplier_business_name??$supplier->first_name}}">
                                        {{$supplier->supplier_business_name??$supplier->first_name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                <select class="form-select form-select-sm  fw-bold" data-kt-select2="true"
                                    data-placeholder="Select option" data-allow-clear="true"
                                    data-kt-status-table-filter="status" data-hide-search="true">
                                    <option value="all">All</option>
                                    <option value="request">Request</option>
                                    <option value="order">order</option>
                                    <option value="pending">Pending</option>
                                    <option value="deliver">Deliver</option>
                                    <option value="received">Received</option>
                                    <option value="confirmed">Confirmed</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            {{-- <div class="mb-10 col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">Shipping Status:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true"
                                    data-placeholder="Select option" data-allow-clear="true"
                                    data-kt-user-table-filter="two-step" data-hide-search="true">
                                    <option></option>
                                    <option value="Enabled">All</option>
                                </select>
                            </div> --}}
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-12 col-md-3 ">
                                <label class="form-label  fs-6 fw-semibold">date:</label>
                                <input class="form-control form-select-sm " placeholder="Pick date rage"
                                    id="kt_daterangepicker_4" data-kt-date-filter="date"
                                    data-dropdown-parent="#filter" />
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 px-5 px-sm-7 pt-6 pb-5">
                <!--begin::Card title-->
                <div class="card-title ">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1 me-2">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-sm-1 svg-icon-5 position-absolute m-3 ms-sm-6">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-purchase-table-filter="search"
                            class="form-control fs-8 fs-sm-base form-control-sm form-control-solid w-100 w-sm-250px ps-10 ps-sm-15 "
                            placeholder="Search Purchase" />
                    </div>

                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-purchase-table-toolbar="base">
                        <!--begin::Filter-->
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                            <!--begin::Filter-->
                            {{-- <button class="accordion-button fs-4 fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true"
                                aria-controls="kt_accordion_1_body_1">
                                Accordion Item #1
                            </button> --}}
                            <button type="button" class="btn btn-sm btn-light-primary me-3 collapsed fs-8 fs-sm-7"
                                type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2"
                                aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                Filter
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-lg-600px w-md-450px" tabindex="-1"
                                id="filter" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                    <div class="d-flex flex-wrap justify-content-around">


                                    </div>
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset"
                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                            data-kt-menu-dismiss="true"
                                            data-kt-user-table-filter="filter">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>

                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                        <!--end::Toolbar-->
                        @if(hasCreate('purchase'))
                        <!--begin::Add customer-->
                        <a href="{{route('purchase_add')}}">
                            <button class="btn btn-sm btn-primary fs-9 fs-sm-7">
                                Add Purchase
                            </button>
                        </a>
                        <!--end::Add customer-->
                        @endif


                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-purchase-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-purchase-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger"
                            data-kt-purchase-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0 px-5 px-sm-7" id='purchase_table_card'>
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-7 fw-bold gy-2 pb-3 " id="kt_purchase_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll"
                                        type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_purchase_table .form-check-input" value="" />
                                </div>
                            </th>
                            <th class="min-w-125px">{{__('table/label.actions')}}</th>
                            <th class="min-w-125px">Date</th>
                            <th>Purchase at</th>
                            <th class="min-w-125px">{{__('table/label.voucher_no')}}</th>
                            <th class="min-w-125px">{{__('table/label.location')}}</th>
                            <th class="min-w-125px">Supplier</th>
                            <th class="min-w-125px">{{__('table/label.received_at')}}</th>
                            <th class="min-w-125px">{{__('table/label.status')}}</th>
                            <th class="min-w-125px">{{__('table/label.total_purchase_amount')}}</th>
                            <th class="min-w-125px">{{__('table/label.paymnet_status')}}</th>
                            {{-- <th class="min-w-125px">Quantity Remaining</th>
                            <th class="min-w-125px">Shipping Status</th>
                            <th class="min-w-125px">Added By</th> --}}
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600">

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--begin::Modals-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
</div>
<div class="modal fade purchaseDetail" tabindex="-1">

</div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="modal"></div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="paymentEditModal"></div>
{{-- @include('App.purchase.DetailView.purchaseDetail') --}}
@livewireScripts
@endsection

@push('scripts')
<script src="{{ asset('customJs/debounce.js') }}"></script>
<script src={{asset('customJs/Purchases/purchaseTable.js')}}></script>
<script src="{{asset('customJs/Purchases/payment/payment.js')}}"></script>
<script src={{asset('customJs/toastrAlert/alert.js')}}></script>
<script>
    $(document).ready(function(){
            let printId="{{session('print')}}";


            const layoutIdSelectBox = $('#layoutIdBox');
            let layoutId = 1;
            layoutIdSelectBox.change(function(e){
                layoutId = e.target.value;
            })

            if(printId){
                let url=`purchase/print/${printId}/Invoice`;
                loadingOn();
                ajaxPrint(url);
            }
            $(document).on('click', '.print-invoice', function(e) {
                e.preventDefault();
                loadingOn();
                var url = $(this).data('href');
                ajaxPrint(url);

            });
            function ajaxPrint(url){
                $.ajax({
                    url: url,
                    data : {
                        'layoutId' : layoutId
                    },
                    success: function(response) {
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
                        console.log(response.html);
                        // Write the invoice HTML and styles to the iframe document
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        iframeDoc.open();
                        iframeDoc.write(response.html);
                        iframeDoc.close();

                        // Trigger the print dialog
                        iframe.contentWindow.focus();
                        loadingOff();
                        setTimeout(() => {
                            loadingOff();
                            iframe.contentWindow.print();
                        }, 500);
                    }
                });
            }
            $(document).on('click', '.view_detail', function(){

                loadingOn();
                $url=$(this).data('href');
                $('.purchaseDetail').load($url, function() {
                    $(this).modal('show');
                    loadingOff();
                });
            });
          })
</script>
@endpush
