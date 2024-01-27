
@extends('App.main.navBar')
@section('setting_active','active')
@section('setting_active_show','active show')
@section('location_here_show','here show')


@section('styles')
		<link href="{{asset('customCss/bussingessSetting.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Bussiness Location Setting</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Setting</li>
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted text-hover-primary">Bussiness Location</a>
        </li>
        <li class="breadcrumb-item text-dark">Bussiness Location Setting</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin:::Tabs-->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#receipt_setting">Receipt Settings</a>
            </li>
            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" id="receipt_setting" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Receipt Settings <span class="fs-7 fw-light text-gray-600">All receipt related settings for this location</span></h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <div class="row fv-row row-cols flex-wrap">
                                <div class="col-md-12  mb-7  col-lg-5">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="auto_print_invoice">
                                        <span class="required">Auto print invoice after finalizing:</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7 text-success" data-bs-toggle="tooltip" title="Enable or Disable auto-printing of invoice on finalizing."></i>
                                    </label>
                                    <!--end::Label-->
                                     <div class="input-group flex-nowrap">
                                        <span class="input-group-text">
                                           <i class="fa-solid fa-file"></i>
                                        </span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="auto_print_invoice" id="auto_print_invoice" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Yes</option>
                                                <option value="2" >None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12  mb-7  col-lg-5">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="receipt_printer_type">
                                        <span class="required">Receipt Printer Type:</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7 text-success" data-bs-toggle="tooltip"  data-bs-html="true"  title="<i>Browser Based Printing</i>: Show Browser Print Dialog Box with Invoice Preview<br/><br/> <i>Use Configured Receipt Printer</i>: Select a Configured Receipt / Thermal printer for printing"></i>
                                    </label>
                                    <!--end::Label-->
                                     <div class="input-group flex-nowrap">
                                        <span class="input-group-text">
                                           <i class="fa-solid fa-print"></i>
                                        </span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="receipt_printer_type" id="receipt_printer_type" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Browser Based Printing</option>
                                                <option value="2" >Use Configured Receipt Printer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row fv-row row-cols flex-wrap">
                                <div class="col-md-12  mb-7  col-lg-5">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="invoice_layout">
                                        <span class="required">Invoice layout:</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7 text-success" data-bs-html="true" data-bs-toggle="tooltip" title="Invoice Layout to be used for this business location<br><small class='text-muted'>(<i>You can add new <b>Invoice Layout</b> in <b>Invoice Settings<b></i>)</small>"></i>
                                    </label>
                                    <!--end::Label-->
                                     <div class="input-group flex-nowrap">
                                        <span class="input-group-text">
                                           <i class="fa-solid fa-circle-info"></i>
                                        </span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="invoice_layout" id="invoice_layout" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Default</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12  mb-7  col-lg-5">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="invoice_layout">
                                        <span class="required">Invoice Layout:</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7 text-success" data-bs-html="true" data-bs-toggle="tooltip" title="Invoice Scheme means invoice numbering format. Select the scheme to be used for this business location<br><small class='text-muted'><i>You can add new Invoice Scheme</b> in Invoice Settings</i></small>"></i>
                                    </label>
                                    <!--end::Label-->
                                     <div class="input-group flex-nowrap">
                                        <span class="input-group-text">
                                           <i class="fa-solid fa-circle-info"></i>
                                        </span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="invoice_layout" id="invoice_layout" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Default Template</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                </div>
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Main column-->

    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')
		<script src="{{asset('assets/js/custom/apps/ecommerce/customers/listing/add.js')}}"></script>
@endpush
