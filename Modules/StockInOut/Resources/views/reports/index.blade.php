@extends('App.main.navBar')

@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('inventory_reports_here_show', 'here show')
@section('stock_summary_active_show', 'active show')


@section('styles')
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('stockinout::stockinoutreport.stockinout_summary')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockinoutreport.reports')}}</li>
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockinoutreport.inventory_reports')}}</li>
        <li class="breadcrumb-item text-dark">{{__('stockinout::stockinoutreport.summary')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{__('stockinout::stockinoutreport.filters')}}</h2>
                        </div>
                    </div>
                    <div class="card-body filter-card">
                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-5 col-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold">{{__('stockinout::stockinoutreport.stock_reports_type')}}</label>
                                <select class="form-select form-select-sm  fw-bold filter_type" data-kt-select2="true"
                                        data-placeholder="{{__('stockinout::stockinoutreport.placeholder_select_option')}}" data-allow-clear="true" data-hide-search="true">
                                    <option></option>
                                    <option selected value="1">Stock In</option>
                                    <option value="2">Stock Out</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold">{{__('stockinout::stockinoutreport.business_location')}}</label>
                                <select class="form-select form-select-sm  fw-bold filter_locations" data-kt-select2="true"
                                        data-placeholder="{{__('stockinout::stockinoutreport.placeholder_select_option')}}" data-allow-clear="true" data-hide-search="true">
                                    <option></option>
                                    @if(count($locations) > 0)
                                        <option selected value="0">All</option>
                                        @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled value="null">{{__('stockinout::stockinoutreport.no_locations')}}</option>
                                    @endif
                                </select>
                            </div>
                            <!--end::Input group-->
                             <!--begin::Input group-->
                             <div class="mb-5 col-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold">{{__('stockinout::stockinoutreport.stock_person')}}</label>
                                <select class="form-select form-select-sm  fw-bold filter_person" data-kt-select2="true"
                                        data-placeholder="{{__('stockinout::stockinoutreport.placeholder_select_option')}}" data-allow-clear="true" data-hide-search="true">
                                    <option></option>
                                    @if(count($stocksperson) > 0)
                                        <option selected value="0">All</option>
                                        @foreach($stocksperson as $stockperson)
                                            <option value="{{$stockperson->id}}">{{$stockperson->username}}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled value="null">{{__('stockinout::stockinoutreport.no_person')}}</option>
                                    @endif
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold">{{__('stockinout::stockinoutreport.date')}}</label>
                                <input class="form-control form-control-sm filter_date" placeholder="Pick date rage"
                                       id="kt_daterangepicker_4" data-dropdown-parent="#filter"/>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>


            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                      transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"/>
                            </svg>
                        </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-customer-table-filter="search" id="search_input"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="{{__('stockinout::stockinoutreport.search')}}"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">

                                <!--begin::Export-->
                                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                        data-bs-target="#kt_customers_export_modal">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                                    <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1"
                                      transform="rotate(90 12.75 4.25)" fill="currentColor"/>
                                <path
                                    d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                    fill="currentColor"/>
                                <path opacity="0.3"
                                      d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                      fill="currentColor"/>
                            </svg>
                        </span>
                                    <!--end::Svg Icon-->{{__('stockinout::stockinoutreport.export')}}
                                </button>
                                <!--end::Export-->

                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                             data-kt-customer-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger"
                                    data-kt-customer-table-select="delete_selected">Delete Selected
                            </button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->



                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="stock_reports_table">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">{{__('stockinout::stockinoutreport.date')}}</th>
                            <th class="min-w-125px">{{__('stockinout::stockinoutreport.voucher_no')}}</th>
                            <th class="min-w-125px">{{__('stockinout::stockinoutreport.location')}}</th>
                            <th class="min-w-125px">
                                <span id="stock-person-header">
                                    {{__('stockinout::stockinoutreport.stock_person')}}
                                </span>
                            </th>
                            {{-- <th class="min-w-125px">Status</th> --}}
                            <th class="min-w-125px">{{__('stockinout::stockinoutreport.added_by')}}</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600" id="reports-data">

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
            <div class="modal fade" id="kt_customers_export_modal" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">{{__('stockinout::stockinoutreport.export_summary')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_customers_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                          transform="rotate(-45 6 17.3137)" fill="currentColor"/>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="currentColor"/>
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
                            <form id="kt_customers_export_form" class="form" action="#">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-semibold form-label mb-5">{{__('stockinout::stockinoutreport.select_export_format')}}:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div id="exportOptions" style="visibility: hidden;" class="w-1px h-1px"></div>
                                    <select id="exportFormat" name="exportFormat" class="form-select" data-control="select2" data-placeholder="Select a format"
                                            data-hide-search="true">
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                        <option value="pdf">PDF</option>
                                        <option value="print">Print</option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-semibold form-label mb-5">{{__('stockinout::stockinoutreport.select_date_range')}}:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid filter_date" placeholder="Pick date rage"
                                           id="kt_daterangepicker_5" data-dropdown-parent="#filter"/>
{{--                                    <input class="form-control form-control-solid" placeholder="Pick a date"--}}
{{--                                           name="date"/>--}}
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Row-->
                                <div class="row fv-row mb-15">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-semibold form-label mb-5">{{__('stockinout::stockinoutreport.export_column')}}:</label>
                                    <!--end::Label-->
                                    <!--begin::Radio group-->
                                    <div class="d-flex flex-column column_checkboxes">
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" id="chkAllColumns" checked="checked"/>
                                            <span class="form-check-label text-gray-600 fw-semibold">{{__('stockinout::stockinoutreport.all')}}</span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" checked="checked"
                                                   id="chkColumn0"/>
                                            <span class="form-check-label text-gray-600 fw-semibold">{{__('stockinout::stockinoutreport.date')}}</span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" checked="checked"
                                                   id="chkColumn1"/>
                                            <span class="form-check-label text-gray-600 fw-semibold">{{__('stockinout::stockinoutreport.voucher_no')}}</span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" checked="checked"
                                                   id="chkColumn2"/>
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">{{__('stockinout::stockinoutreport.location')}}</span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox"  checked="checked"
                                                   id="chkColumn3"/>
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold"><span id="stock-person-header-filter">{{__('stockinout::stockinoutreport.stock_person')}}</span></span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        {{-- <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox"  checked="checked"
                                                   id="chkColumn4"/>
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">Status</span>
                                        </label> --}}
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox"  checked="checked"
                                                   id="chkColumn5"/>
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">{{__('stockinout::stockinoutreport.added_by')}}</span>
                                        </label>
                                        <!--end::Radio button-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Actions-->
                                <div class="text-center">
                                    <button type="reset" id="kt_customers_export_cancel" class="btn btn-light me-3">
                                        {{__('stockinout::stockinoutreport.discard')}}
                                    </button>
                                    <button type="submit" id="kt_customers_export_submit" class="btn btn-primary">
                                        <span class="indicator-label">{{__('stockinout::stockinoutreport.submit')}}</span>
                                        <span class="indicator-progress">{{__('stockinout::stockinoutreport.please_wait')}}
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
    <!--end::Content-->
@endsection

@push('scripts')
    <script src="customJs/reports/inventory/stockSummaryExport.js"></script>
    <script src="customJs/reports/inventory/stockSummaryFilter.js"></script>
    <script src="customJs/toaster.js"></script>

        <script>
            $(document).ready(function() {
                var stockQtyHeader = $('#stock-person-header, #stock-person-header-filter');

                function updateStockQtyHeader() {
                    var selectedValue = $('.filter_type').val();
                    stockQtyHeader.text(selectedValue === '1' ? 'Stockin Person' : 'Stockout Person');
                }

                $('.filter_type').change(updateStockQtyHeader);

                updateStockQtyHeader(); // Set initial value on page load
            });

        </script>

@endpush
