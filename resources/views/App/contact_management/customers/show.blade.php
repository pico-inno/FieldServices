@extends('App.main.navBar')
@section('styles')
@endsection
@section('contact_active','active')
@section('contact_active_show','active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Contact</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Contact</li>
    <li class="breadcrumb-item text-dark">Contact Details</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-10">
                    <div class="flex-grow-1">
                        <div class="row">
                            <div class="col-9">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex">
                                        <a href="#" class="text-gray-700 text-hover-primary fs-4 fw-bold me-1 mb-3">
                                            @if(in_array($contact->type, ['Both', 'Customer']) || in_array($contact->type, ['Both', 'Supplier']))                                            
                                                @if(!empty($contact->prefix))
                                                {{$contact->prefix}}
                                                @endif
                                                @if(!empty($contact->first_name))
                                                {{$contact->first_name}}
                                                @endif
                                                @if(!empty($contact->middle_name))
                                                {{$contact->middle_name}}
                                                @endif
                                                @if(!empty($contact->last_name))
                                                {{$contact->last_name}}
                                                @endif
                                                @if(!empty($contact->company_name))
                                                {{$contact->company_name}}
                                                @endif
                                            @endif
                                        </a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap fw-semibold fs-6 pe-2">
                                        @if(!empty($contact->address_line_1))
                                        <i class="fa-solid fa-location-dot me-2 mt-1"></i>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <!--begin::FontAwesome Icon-->
                                            <!--end::FontAwesome Icon-->
                                            {{$contact->address_line_1}}
                                        </a>
                                        @endif

                                        @if(!empty($contact->email))
                                        <i class="fa-solid fa-envelope me-2 mt-1"></i>
                                        <a href="#" class="d-flex text-gray-400 text-hover-primary me-5 mb-2">
                                            {{$contact->email}}
                                        </a>
                                        @endif

                                        @if(!empty($contact->mobile))
                                        <i class="fa-solid fa-phone-volume me-2 mt-1"></i>
                                        <a href="#" class="d-flex text-gray-400 text-hover-primary mb-2">
                                            {{$contact->mobile}}
                                        </a>
                                        @endif 
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </div>
                        </div>
                        <div class="row">
                        </div>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <ul id="myTab" class="nav nav-stretch nav-tabs  nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#profileinfoTab">Profile Information</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#ledgerTab">Ledger</a>
                    </li>

                    @if(in_array($contact->type, ['Both', 'Customer']))
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#salesTab">Sales</a>
                    </li>
                    @endif

                    @if(in_array($contact->type, ['Both', 'Supplier']))
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#purchaseTab">Purchases</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#subscriptionTab">Subscription</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#documentTab">Document & Note</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#paymentTab">Payment</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#activityTab">Activities</a>
                    </li>
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <div class="tab-content">
            <!--begin::profile info tab-->
            <div class="tab-pane fade active show" id="profileinfoTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title m-0">
                                <h4 class="fw-bold fs-4 m-0">Profile Details</h4>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Action-->
                            <a href="/contacts/customers" class="btn btn-sm btn-primary align-self-center">Edit</a>
                            <!--end::Action-->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Full Name</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->prefix))
                                                {{$contact->prefix}}
                                                @endif
                                                @if(!empty($contact->first_name))
                                                {{$contact->first_name}}
                                                @endif
                                                @if(!empty($contact->middle_name))
                                                {{$contact->middle_name}}
                                                @endif
                                                @if(!empty($contact->last_name))
                                                {{$contact->last_name}}
                                                @endif
                                                @if(!empty($contact->company_name))
                                                {{$contact->company_name}}
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Email</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->email))
                                                {{$contact->email}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Mobile</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->mobile))
                                                {{$contact->mobile}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Alternate Number</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->alternate_number))
                                                {{$contact->alternate_number}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Date of Birth</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->dob))
                                                {{$contact->dob}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Address Line 1</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->address_line_1))
                                                {{$contact->address_line_1}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Address Line 2</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->address_line_2))
                                                {{$contact->address_line_2}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">City</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->city))
                                                {{$contact->city}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">State</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->state))
                                                {{$contact->state}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Country</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->country))
                                                {{$contact->country}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Zip Code</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if(!empty($contact->zip_code))
                                                {{$contact->zip_code}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Contact No.</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                {{$contact->contact_id}}
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Pay Term</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if($contact->pay_term_number && $contact->pay_term_type)
                                                {{$contact->pay_term_number}} {{$contact->pay_term_type}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Credit Limit</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if($contact->credit_limit)
                                                {{$contact->credit_limit}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Shipping Address</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">
                                                @if($contact->shipping_address)
                                                {{$contact->shipping_address}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Custom Field 1</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">

                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Custom Field 2</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">

                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Custom Field 3</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">

                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-6 fw-semibold text-muted">Custom Field 4</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">

                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::profile info tab-->

            <!--begin::ledger tab-->
            <div class="tab-pane fade" id="ledgerTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-p-4 card-flush">
                        <div class="card-body">
                            <div class="row mb-9">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Date Range</label>
                                    <input class="form-control" placeholder="Pick date rage" id="kt_daterangepicker_4" />
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">Business Location</label>
                                    <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                        <option></option>
                                        @php
                                        $business_locations = \App\Models\settings\businessLocation::all();
                                        @endphp
                                        @foreach($business_locations as $business_location)
                                        <option value="{{$business_location->id}}">{{$business_location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-9">
                                <div class="col-md-6">
                                    <h3 class="text-gray-700">Pico Innovation</h3>
                                    <span class="text-gray-700">Mandalay, Mandalay , Mandalay, Myanmar, 0501</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="bg-secondary fw-bold text-gray-700 fs-6 p-3">To: </p>
                                    <p class="fw-bold text-gray-600">
                                        @if(!empty($contact->prefix))
                                        {{$contact->prefix}}
                                        @endif
                                        @if(!empty($contact->first_name))
                                        {{$contact->first_name}}
                                        @endif
                                        @if(!empty($contact->middle_name))
                                        {{$contact->middle_name}}
                                        @endif
                                        @if(!empty($contact->last_name))
                                        {{$contact->last_name}}
                                        @endif
                                        @if(!empty($contact->company_name))
                                        {{$contact->company_name}}
                                        @endif
                                    </p>

                                    @if(!empty($contact->address_line_1))
                                    <p class="fw-bold text-gray-600">
                                        {{$contact->address_line_1}}
                                    </p>
                                    @endif
                                    @if(!empty($contact->address_line_2))
                                    <p class="fw-bold text-gray-600">
                                        {{$contact->address_line_2}}
                                    </p>
                                    @endif
                                    @if(!empty($contact->city) || !empty($contact->state) || !empty($contact->country))
                                    <p class="fw-bold text-gray-600">
                                        {{$contact->city}} , {{$contact->state}}, {{$contact->country}}
                                    </p>
                                    @endif
                                    @if(!empty($contact->email))
                                    <p class="fw-bold text-gray-600">
                                        {{$contact->email}}
                                    </p>
                                    @endif
                                </div>
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                    <p class="bg-secondary fw-bold text-gray-700 fs-6 p-3">Account Summary:</p>
                                    <span class="text-gray-600 d-flex justify-content-end">30-5-2023 - 29-6-2023</span>
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_example">
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td>Opening Balance</td>
                                                    <td>0.0000</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Invoice</td>
                                                    <td>0.0000</td>
                                                </tr>
                                                <tr>
                                                    <td>Total paid</td>
                                                    <td>0.0000</td>
                                                </tr>
                                                <tr>
                                                    <td>Advance Balance</td>
                                                    <td>0.0000</td>
                                                </tr>
                                                <tr>
                                                    <td>Balance Due</td>
                                                    <td>0.0000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::ledger tab-->

            <!--begin::sales tab-->
            @if(in_array($contact->type, ['Both', 'Customer']))
            <div class="tab-pane fade" id="salesTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-p-4 card-flush">
                        <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                            <div class="card-toolbar d-flex justify-content-between ">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search....." />
                                </div>
                                <!--end::Search-->
                                <!--begin::Export buttons-->
                                <div id="kt_datatable_example_1_export" class="d-none"></div>
                                <!--end::Export buttons-->
                                <!--begin::Export dropdown-->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-light-primary btn-sm mx-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Export Report
                                    </button>
                                    <!--begin::Menu-->
                                    <div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                Copy to clipboard
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="excel">
                                                Export as Excel
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                Export as CSV
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                Export as PDF
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                    <!--end::Export dropdown-->

                                    <!--begin::Hide default export buttons-->
                                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                                    <!--end::Hide default export buttons-->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table align-middle rounded table-row-dashed fs-6 g-5" id="sales_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-125px">Action</th>
                                        <th class="min-w-125px">Date</th>
                                        <th class="min-w-125px">Invoice No.</th>
                                        <th class="min-w-125px">Customer Name</th>
                                        <th class="min-w-125px">Contact Number</th>
                                        <th class="min-w-125px">Location</th>
                                        <th class="min-w-125px">Payment Status</th>
                                        <th class="min-w-125px">Total Sale Amount</th>
                                        <th class="min-w-125px">Total Paid</th>
                                        <th class="min-w-125px">Total Balance</th>
                                        <th class="min-w-125px">Sold By</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    @if(isset($data['sales']))
                                    @foreach($data['sales'] as $s)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                                        <i class="fas fa-angle-down"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                                            <i class="fa-solid fa-trash me-3"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>{{$s->sold_at}}</td>
                                        <td>{{$s->sales_voucher_no}}</td>
                                        <td>
                                            {{$s->contact->prefix}}
                                            {{$s->contact->first_name}} 
                                            {{$s->contact->middle_name}}
                                            {{$s->contact->last_name}}
                                        </td>
                                        <td>{{$s->contact->mobile}}</td>
                                        <td>{{$s->businessLocation->name}}</td>
                                        <td>
                                            @if($s->payment_status == 'paid')
                                            <span class="badge badge-success">{{$s->payment_status}}</span>
                                            @elseif($s->payment_status == 'pending') 
                                            <span class="badge badge-warning">{{$s->payment_status}}</span>
                                            @else
                                            <span class="badge badge-primary">{{$s->payment_status}}</span>
                                            @endif
                                        </td>
                                        <td>{{$s->total_sale_amount}}</td>
                                        <td>{{$s->paid_amount}}</td>
                                        <td>{{$s->balance_amount}}</td>
                                        <td>{{$s->sold->username}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::sales tab-->

            <!--begin::purchase tab-->
            @if(in_array($contact->type, ['Both', 'Supplier']))
            <div class="tab-pane fade" id="purchaseTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-p-4 card-flush">
                        <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                            <div class="card-toolbar d-flex justify-content-between ">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search....." />
                                </div>
                                <!--end::Search-->
                                <!--begin::Export buttons-->
                                <div id="kt_datatable_example_1_export" class="d-none"></div>
                                <!--end::Export buttons-->
                                <!--begin::Export dropdown-->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-light-primary btn-sm mx-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Export Report
                                    </button>
                                    <!--begin::Menu-->
                                    <div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                Copy to clipboard
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="excel">
                                                Export as Excel
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                Export as CSV
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                Export as PDF
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                    <!--end::Export dropdown-->

                                    <!--begin::Hide default export buttons-->
                                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                                    <!--end::Hide default export buttons-->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table align-middle rounded table-row-dashed fs-6 g-5" id="purchase_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-125px">Action</th>
                                        <th class="min-w-125px">Date</th>
                                        <th class="min-w-125px">Purchase Voucher No.</th>
                                        <th class="min-w-125px">Location</th>
                                        <th class="min-w-125px">Supplier</th>
                                        <th class="min-w-125px">Purchase Status</th>
                                        <th class="min-w-125px">Payment Status</th>
                                        <th class="min-w-125px">Total Purchase Amount</th>
                                        <th class="min-w-125px">Total Paid</th>
                                        <th class="min-w-125px">Total Balance</th>
                                        <th class="min-w-125px">Purchased By</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    @if(isset($data['purchases']))
                                    @foreach($data['purchases'] as $p)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                                        <i class="fas fa-angle-down"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                                            <i class="fa-solid fa-trash me-3"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>{{$p->purchased_at}}</td>
                                        <td>{{$p->purchase_voucher_no}}</td>
                                        <td>{{$p->businessLocation->name}}</td>
                                        <td>
                                            {{$p->supplier->company_name}}
                                             
                                        </td>
                                        <td>
                                            @if($p->status == 'request')
                                            <span class="badge badge-primary">{{$p->status}}</span>
                                            @elseif($p->status == 'pending') 
                                            <span class="badge badge-danger">{{$p->status}}</span>
                                            @elseif($p->status == 'order')
                                            <span class="badge badge-secondary">{{$p->status}}</span>
                                            @elseif($p->status == 'partial')
                                            <span class="badge badge-warning">{{$p->status}}</span>
                                            @elseif($p->status == 'deliver')
                                            <span class="badge badge-info">{{$p->status}}</span>
                                            @else
                                            <span class="badge badge-success">{{$p->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->payment_status == 'paid')
                                            <span class="badge badge-success">{{$p->payment_status}}</span>
                                            @elseif($p->payment_status == 'pending') 
                                            <span class="badge badge-warning">{{$p->payment_status}}</span>
                                            @else
                                            <span class="badge badge-primary">{{$p->payment_status}}</span>
                                            @endif
                                        </td>
                                        <td>{{$p->total_purchase_amount}}</td>
                                        <td>{{$p->paid_amount}}</td>
                                        <td>{{$p->balance_amount}}</td>
                                        <td>{{$p->purchase_by->username}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::purchase tab-->

            <!--begin::subscription tab-->
            <div class="tab-pane fade" id="subscriptionTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">

                </div>
            </div>
            <!--end::subscription tab-->

            <!--begin::document tab-->
            <div class="tab-pane fade" id="documentTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-p-4 card-flush">
                        <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                            <div class="card-title d-flex flex-column">
                                <h4>All documents and notes</h4>
                            </div>
                            <div class="card-toolbar d-flex justify-content-between ">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search....." />
                                </div>
                                <!--end::Search-->
                                <!--begin::Export buttons-->
                                <div id="kt_datatable_example_1_export" class="d-none"></div>
                                <!--end::Export buttons-->
                                <!--begin::Export dropdown-->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-light-primary btn-sm mx-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Export Report
                                    </button>
                                    <!--begin::Menu-->
                                    <div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                Copy to clipboard
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="excel">
                                                Export as Excel
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                Export as CSV
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                Export as PDF
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                    <!--end::Export dropdown-->

                                    <!--begin::Hide default export buttons-->
                                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                                    <!--end::Hide default export buttons-->
                                    <a class="text-light btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#document_and_note_modal">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table align-middle rounded table-row-dashed fs-6 g-5" id="document_and_note_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-125px">Action</th>
                                        <th class="min-w-125px">Title</th>
                                        <th class="min-w-125px">Document</th>
                                        <th class="min-w-125px">Added By</th>
                                        <th class="min-w-125px">Created At</th>
                                        <th class="min-w-125px">Updated At</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                                        <i class="fas fa-angle-down"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="#" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                                            <i class="fa-solid fa-trash me-3"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>Document</td>
                                        <td>Document</td>
                                        <td>admin</td>
                                        <td>2023-7-14</td>
                                        <td>2023-7-15</td>
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::document tab-->

            <!--begin::payment tab-->
            <div class="tab-pane fade" id="paymentTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-p-4 card-flush">
                        <div class="card-body">
                            <table class="table align-middle rounded table-row-dashed fs-6 g-5" id="payment_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-125px">Paid On</th>
                                        <th class="min-w-125px">Payment Voucher No.</th>
                                        <th class="min-w-125px">Payment Amount</th>
                                        <th class="min-w-125px">Payment Method</th>
                                        <th class="min-w-125px">Payment For</th>
                                        <th class="min-w-125px">Action</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    @if(isset($data['payments']))
                                    @foreach($data['payments'] as $payment)
                                    @foreach($payment as $payment_tran)
                                    <tr>
                                        <td>{{$payment_tran->payment_date}}</td>
                                        <td>{{$payment_tran->payment_voucher_no}}</td>
                                        <td>{{$payment_tran->payment_amount}}</td>
                                        <td>{{$payment_tran->payment_method}}</td>
                                        <td>{{$payment_tran->transaction_ref_no}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                                        <i class="fas fa-angle-down"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a href="/home" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                                            <i class="fa-solid fa-trash me-3"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                    @endif
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::payment tab-->

            <!--begin::activity tab-->
            <div class="tab-pane fade" id="activityTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                </div>
            </div>
            <!--end::activity tab-->
        </div>
    </div>
    <!--end::container-->

    @include('App.contact_management.customers.addDocument&Note')
    

</div>
<!--end::Content-->
@endsection

@push('scripts')
<script>
    var start = moment().subtract(29, "days");
    var end = moment();

    function cb(start, end) {
        $("#kt_daterangepicker_4").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    }

    $("#kt_daterangepicker_4").daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            "Today": [moment(), moment()],
            "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
        }
    }, cb);

    cb(start, end);

    $("#document_and_note_table,#sales_table,#payment_table,#purchase_table").DataTable({
        "order": false,
        "paging": false,
        "info": false,
        "dom": "<'table-responsive'tr>"
    });

    var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
        url: "/contacts/customers", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        maxFiles: 10,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        accept: function(file, done) {
            if (file.name == "wow.jpg") {
                done("Naha, you don't.");
            } else {
                done();
            }
        }
    });
</script>
@endpush