@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_adjustment_here_show', 'here show')
@section('stock_adjustment_active_show', 'active show')


@section('styles')
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <style>

            #adjustment_table_card .table-responsive{
                    min-height: 60vh;
                }
        </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('adjustment.adjustment')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('adjustment.adjustment')}}</li>
        <li class="breadcrumb-item text-dark">{{__('adjustment.list')}}</li>
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
                <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis nihil dolore quas assumenda eius repudiandae, error iste exercitationem. Hic natus doloribus molestiae porro incidunt dolor modi! Quas nostrum perspiciatis quia!
                    </div>
                </div>
            </div>
        </div>
</div> --}}

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="accordion-collapse collapse" id="kt_accordion_1_body_2"  aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Filters</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-5 col-6 col-md-3 ">
                                <label class="form-label fs-6 fw-semibold">{{__('adjustment.location')}}</label>
                                <select class="form-select form-select-sm fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-business-location-filter="locations" data-hide-search="true">
                                    <option></option>
                                    <option value="all" selected>All</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->name}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold">{{__('adjustment.status')}}</label>
                                <select class="form-select form-select-sm fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-table-filter="status" data-hide-search="true">
                                    <option></option>
                                    <option value="all" selected>All</option>
                                    <option value="prepared">prepared</option>
                                    <option value="completed">completed</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-6 col-md-3 ">
                                <label class="form-label  fs-6 fw-semibold">{{__('adjustment.date')}}</label>
                                <input class="form-control form-select-sm " placeholder="Pick date rage" id="kt_daterangepicker_4" data-kt-date-filter="date" data-dropdown-parent="#filter" />
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
            <div class="card-header border-0 pt-6 pb-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-purchase-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-adjustment-table-toolbar="base">
                        <!--begin::Filter-->
                       <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Filter-->
                            {{-- <button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                                Accordion Item #1
                            </button> --}}
                            <button type="button" class="btn btn-sm btn-light-primary me-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->{{__('adjustment.filter')}}</button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-lg-600px w-md-450px" tabindex="-1" id="filter" data-kt-menu="true">
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
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                        <!--end::Toolbar-->
                        <!--end::Filter-->
                        @if(hasExport('stock transfer'))
                        <!--begin::Export-->
                        <button type="button" class="btn btn-sm btn-light-primary me-3 " data-bs-toggle="modal" data-bs-target="#adjustment_export_modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor" />
                                <path opacity="0.3" d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->{{__('adjustment.export')}}</button>
                        <!--end::Export-->
                        @endif
                        @if(hasCreate('stock transfer'))
                            <!--begin::Add customer-->
                            <button onclick="window.location.href='{{route('stock-adjustment.create')}}'" type="button"
                                    class="btn btn-sm btn-primary">{{__('adjustment.create')}}
                            </button>
                            <!--end::Add customer-->
                        @endif


                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-adjustment-table-toolbar="selected">
                        <div class="fw-bold me-5">
                        <span class="me-2" data-adjustment-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger" data-adjustment-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0" id='adjustment_table_card'>
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-7 fw-bold gy-2 pb-3 " id="stockadjustment_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
{{--                            <th class="w-10px pe-2">--}}
{{--                                <div class="form-check form-check-sm form-check-custom  me-3">--}}
{{--                                    <input class="form-check-input" data-checked="selectAll" id="selectAll" type="checkbox" data-kt-check="true" data-kt-check-target="#stockadjustment_table .form-check-input" value="" />--}}
{{--                                </div>--}}
{{--                            </th>--}}
                            <th class="min-w-1250px text-center">{{__('adjustment.actions')}}</th>
                            <th class="min-w-100px">{{__('adjustment.date')}}</th>
                            <th class="min-w-125px">{{__('adjustment.voucher_no')}}</th>
                            <th class="min-w-125px">{{__('adjustment.location')}}</th>
                            <th class="min-w-100px">{{__('adjustment.increase_subtotal')}}</th>
                            <th class="min-w-100px">{{__('adjustment.decrease_subtotal')}}</th>
                            <th class="min-w-100px">{{__('adjustment.status')}}</th>
                            <th class="min-w-100px">{{__('adjustment.created_by')}}</th>

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
        <!--begin::Modal - Adjust Balance-->
        <div class="modal fade" id="adjustment_export_modal" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Export Customers</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_purchase_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_purchase_export_form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="fs-5 fw-semibold form-label mb-5">Select Export Format:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select data-control="select2" data-placeholder="Select a format" data-hide-search="true" name="format" class="form-select ">
                                    <option value="excell">Excel</option>
                                    <option value="pdf">PDF</option>
                                    <option value="cvs">CVS</option>
                                    <option value="zip">ZIP</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="fs-5 fw-semibold form-label mb-5">Select Date Range:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Pick a date" name="date" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Row-->
                            <div class="row fv-row mb-15">
                                <!--begin::Label-->
                                <label class="fs-5 fw-semibold form-label mb-5">Payment Type:</label>
                                <!--end::Label-->
                                <!--begin::Radio group-->
                                <div class="d-flex flex-column">
                                    <!--begin::Radio button-->
                                    <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" checked="checked" name="payment_type" />
                                        <span class="form-check-label text-gray-600 fw-semibold">All</span>
                                    </label>
                                    <!--end::Radio button-->
                                    <!--begin::Radio button-->
                                    <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="2" checked="checked" name="payment_type" />
                                        <span class="form-check-label text-gray-600 fw-semibold">Visa</span>
                                    </label>
                                    <!--end::Radio button-->
                                    <!--begin::Radio button-->
                                    <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="3" name="payment_type" />
                                        <span class="form-check-label text-gray-600 fw-semibold">Mastercard</span>
                                    </label>
                                    <!--end::Radio button-->
                                    <!--begin::Radio button-->
                                    <label class="form-check form-check-custom form-check-sm form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="4" name="payment_type" />
                                        <span class="form-check-label text-gray-600 fw-semibold">American Express</span>
                                    </label>
                                    <!--end::Radio button-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Actions-->
                            <div class="text-center">
                                <button type="reset" id="kt_purchase_export_cancel" class="btn btn-light me-3">Discard</button>
                                <button type="submit" id="kt_purchase_export_submit" class="btn btn-primary">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - New Card-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
</div>
<div class="modal fade adjustmentDetail" tabindex="-1">

</div>
<div class="modal modal-lg fade " tabindex="-1" id="modal"></div>
{{--<div class="modal modal-lg fade " tabindex="-1" id="paymentEditModal"></div>--}}
{{-- @include('App.purchase.DetailView.adjustmentDetail') --}}
@endsection

@push('scripts')
        <script src={{asset('customJs/stock/adjustmentTable.js')}}></script>
        <script src={{asset('customJs/toastrAlert/alert.js')}}></script>
        <script>


          $(document).ready(function(){
            let printId="{{session('print')}}";
            if(printId){
                let url=`adjustment/print/${printId}/invoice`;
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
                $('.adjustmentDetail').load($url, function() {
                    $(this).modal('show');
                    loadingOff();
                });
            });
          })
        </script>
@endpush


