@extends('App.main.navBar')
@section('dashboard_active','active')
@section('dashboard_show', 'active show')
@section('dashboard_active_show', 'active ')
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">SMS POH</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">SMS</li>
    {{-- <li class="breadcrumb-item text-muted">add</li> --}}
    <li class="breadcrumb-item text-dark">Dashboard </li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('styles')
<style>
    .current-stock-balance-table-card .table-responsive {
        min-height: 283px;
    }
</style>
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">

        <div class="row g-5 g-xl-8  d-none">
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a class="card bg-body hoverable card-xl-stretch mb-xl-8" id="sale_order_click">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                        <dib class="d-flex">
                            <i class="fa-solid fa-paper-plane fs-1 me-5"></i>
                            <div class="fw-semibold text-gray-600 fs-5">Total Sent Message</div>
                        </dib>
                        <!--end::Svg Icon-->
                        <div class="text-gray-900 fw-bold fs-2x mb-2 mt-5" id="total_sale_order_widget">0</div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a class="card bg-dark hoverable card-xl-stretch mb-xl-8" id="purchase_order_click">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                        <span class="svg-icon svg-icon-gray-100 svg-icon-3x ms-n1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M18 21.6C16.3 21.6 15 20.3 15 18.6V2.50001C15 2.20001 14.6 1.99996 14.3 2.19996L13 3.59999L11.7 2.3C11.3 1.9 10.7 1.9 10.3 2.3L9 3.59999L7.70001 2.3C7.30001 1.9 6.69999 1.9 6.29999 2.3L5 3.59999L3.70001 2.3C3.50001 2.1 3 2.20001 3 3.50001V18.6C3 20.3 4.3 21.6 6 21.6H18Z"
                                    fill="currentColor" />
                                <path
                                    d="M12 12.6H11C10.4 12.6 10 12.2 10 11.6C10 11 10.4 10.6 11 10.6H12C12.6 10.6 13 11 13 11.6C13 12.2 12.6 12.6 12 12.6ZM9 11.6C9 11 8.6 10.6 8 10.6H6C5.4 10.6 5 11 5 11.6C5 12.2 5.4 12.6 6 12.6H8C8.6 12.6 9 12.2 9 11.6ZM9 7.59998C9 6.99998 8.6 6.59998 8 6.59998H6C5.4 6.59998 5 6.99998 5 7.59998C5 8.19998 5.4 8.59998 6 8.59998H8C8.6 8.59998 9 8.19998 9 7.59998ZM13 7.59998C13 6.99998 12.6 6.59998 12 6.59998H11C10.4 6.59998 10 6.99998 10 7.59998C10 8.19998 10.4 8.59998 11 8.59998H12C12.6 8.59998 13 8.19998 13 7.59998ZM13 15.6C13 15 12.6 14.6 12 14.6H10C9.4 14.6 9 15 9 15.6C9 16.2 9.4 16.6 10 16.6H12C12.6 16.6 13 16.2 13 15.6Z"
                                    fill="currentColor" />
                                <path
                                    d="M15 18.6C15 20.3 16.3 21.6 18 21.6C19.7 21.6 21 20.3 21 18.6V12.5C21 12.2 20.6 12 20.3 12.2L19 13.6L17.7 12.3C17.3 11.9 16.7 11.9 16.3 12.3L15 13.6V18.6Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="text-gray-100 fw-bold fs-2x mb-2 mt-5" id="total_purcahse_order_widget">0</div>
                        <div class="fw-semibold text-gray-100">Total Purchase Order</div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{route('suppliers.index')}}" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="text-white fw-bold fs-2x mb-2x mt-5" id="total_this_month_supplier_widget">0</div>
                        <div class="fw-semibold text-white">New Suppliers in This Months</div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{route('customers.index')}}" class="card bg-info hoverable card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Svg Icon | path: icons/duotune/graphs/gra007.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M10.9607 12.9128H18.8607C19.4607 12.9128 19.9607 13.4128 19.8607 14.0128C19.2607 19.0128 14.4607 22.7128 9.26068 21.7128C5.66068 21.0128 2.86071 18.2128 2.16071 14.6128C1.16071 9.31284 4.96069 4.61281 9.86069 4.01281C10.4607 3.91281 10.9607 4.41281 10.9607 5.01281V12.9128Z"
                                    fill="currentColor" />
                                <path
                                    d="M12.9607 10.9128V3.01281C12.9607 2.41281 13.4607 1.91281 14.0607 2.01281C16.0607 2.21281 17.8607 3.11284 19.2607 4.61284C20.6607 6.01284 21.5607 7.91285 21.8607 9.81285C21.9607 10.4129 21.4607 10.9128 20.8607 10.9128H12.9607Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="text-white fw-bold fs-2x mb-2 mt-5" id="total_this_month_customer_widget">0</div>
                        <div class="fw-semibold text-white">New Customers In This Months</div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
        </div>


        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Actions-->
                <div class="d-flex flex-wrap gap-2">
                    <!--begin::Checkbox-->
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-4 me-lg-7">
                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                            data-kt-check-target="#kt_inbox_listing .form-check-input" value="1" />
                    </div>
                    <!--end::Checkbox-->
                    <!--begin::Reload-->
                    <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="tooltip"
                        data-bs-dismiss="click" data-bs-placement="top" title="Reload">
                        <i class="ki-outline ki-arrows-circle fs-2"></i>
                    </a>
                    <!--end::Reload-->
                    <!--begin::Archive-->
                    <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="tooltip"
                        data-bs-dismiss="click" data-bs-placement="top" title="Archive">
                        <i class="ki-outline ki-sms fs-2"></i>
                    </a>
                    <!--end::Archive-->
                    <!--begin::Delete-->
                    <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="tooltip"
                        data-bs-dismiss="click" data-bs-placement="top" title="Delete">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </a>
                    <!--end::Delete-->
                    <!--begin::Filter-->
                    <div>
                        <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-start">
                            <i class="ki-outline ki-down fs-2"></i>
                        </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="show_all">All</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="show_read">Read</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="show_unread">Unread</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="show_starred">Starred</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="show_unstarred">Unstarred</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Filter-->
                    <!--begin::Sort-->
                    <span>
                        <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="tooltip"
                            data-bs-dismiss="click" data-bs-placement="top" title="Sort">
                            <i class="ki-outline ki-dots-square fs-3 m-0"></i>
                        </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="filter_newest">Newest</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="filter_oldest">Oldest</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-inbox-listing-filter="filter_unread">Unread</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </span>
                    <!--end::Sort-->
                </div>
                <!--end::Actions-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                        <input type="text" data-kt-inbox-listing-filter="search"
                            class="form-control form-control-sm form-control-solid mw-100 min-w-125px min-w-lg-150px min-w-xxl-200px ps-11"
                            placeholder="Search inbox" />
                    </div>
                    <!--end::Search-->
                    <!--begin::Toggle-->
                    <a href="#" class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none"
                        data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" title="Toggle inbox menu"
                        id="kt_inbox_aside_toggle">
                        <i class="ki-outline ki-burger-menu-2 fs-3 m-0"></i>
                    </a>
                    <!--end::Toggle-->
                </div>
                <!--end::Actions-->
            </div>
            <div class="card-body p-0">
                <!--begin::Table-->
                <table class="table table-hover table-row-dashed fs-6 gy-5 my-0" id="kt_inbox_listing">
                    <thead class="d-none">
                        <tr>
                            <th class="mw-10px">Checkbox</th>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smsDatas as $sms)
                            <tr>
                                <td class="ps-9">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid mt-3">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td class="text-start ">
                                    <a href="../../demo55/dist/apps/inbox/reply.html" class="d-flex align-items-center text-dark">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px me-3">
                                            <div class="symbol-label bg-light-danger">
                                                <span class="text-danger">M</span>
                                            </div>
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::Name-->
                                        <span class="fw-semibold">+{{$sms['message_to']}}</span>
                                        <!--end::Name-->
                                    </a>
                                </td>
                                <td>
                                    <div class="text-dark gap-1 pt-2">
                                        <!--begin::Heading-->
                                        <a href="../../demo55/dist/apps/inbox/reply.html" class="text-dark">
                                            <span class="fw-bold">{{$sms['message_text']}}</span>
                                            <span class="fw-bold d-none d-md-inine">-</span>
                                            <span class="d-none d-md-inine text-muted">Thank you for ordering UFC 240 Holloway vs
                                                Edgar Alternate camera angles...</span>
                                        </a>
                                        <!--end::Heading-->
                                    </div>
                                    <!--begin::Badges-->
                                    <div class="badge badge-light-primary">inbox</div>
                                    <div class="badge badge-light-warning">task</div>
                                    <!--end::Badges-->
                                </td>
                                <td class="w-100px text-end fs-7 pe-9">
                                    <span class="fw-semibold">{{date($sms['create_at'])}}</span>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                <!--end::Table-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
@endsection

@push('scripts')
<script src="customJs/reports/dashboard/currentBalanceFilter.js"></script>
<script src="customJs/reports/dashboard/widgetFilter.js"></script>
<script src="{{asset("assets/js/custom/apps/inbox/listing.js")}}"></script>
<script src={{asset("assets/js/custom/utilities/modals/users-search.js")}}></script>
@endpush

