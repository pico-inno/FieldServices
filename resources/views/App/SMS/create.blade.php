@extends('App.main.navBar')

@section('sms_active_show', 'active show')
@section('sms_active', 'active ')
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Send SMS</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Send</li>
    {{-- <li class="breadcrumb-item text-muted">add</li> --}}
    <li class="breadcrumb-item text-dark">SMS </li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('styles')
<link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
<link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
<style>
    .data-table-body tr td {
        padding: 3px;
    }

    /* label{
            font-size: 50px !important ;
        } */
</style>
@endsection



@section('content')

<!--begin::Card-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xl" id="sale_container">
        <div class="card">
            <div class="card-header align-items-center">
                <div class="card-title">
                    <h2>Send Message</h2>
                </div>
            </div>
            <div class="card-body p-0">
                <!--begin::Form-->
                <form id="kt_inbox_compose_form" action="{{route('sms.send')}}" method="POST">
                    @csrf
                    <!--begin::Body-->
                    <div class="d-block">
                        <!--begin::To-->
                        <div class="d-flex align-items-center border-bottom px-8 min-h-50px">
                            <!--begin::Label-->
                            <div class="text-dark fw-bold w-100px">Phone No:</div>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-sm " name="sent_to" id="sent_to" value=""
                                 placeholder="Enter Contact Name" />
                            {{-- <input type="text" class="form-control form-control-sm " name="sent_to" id="sent_to" value=""
                                data-kt-inbox-form="tagify" placeholder="Enter Contact Name" /> --}}
                            <!--end::Input-->
                        </div>
                        <!--end::To-->
                        <div class="d-flex align-items-start border-bottom px-8 min-h-50px">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-100px">Message:</div>
                                <textarea class="form-control form-control- borer-0  min-h-45px mb-3" cols="30" rows="10" name="message"
                                    placeholder="Enter Message"></textarea>
                            </div>
                            <!--end::To-->
                        <!--begin::Subject-->
                        <div class="border-bottom col-12 ">
                        </div>
                        <!--end::Subject-->
                        <!--begin::Message-->
                        <!--end::Message-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top justify-content-center">
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center me-3">
                            <!--begin::Send-->
                            <div class="btn-group me-4 text-end">
                                <!--begin::Submit-->
                                <button type="submit" class="btn btn-sm btn-primary fs-bold px-6" data-kt-inbox-form="send">
                                    <span class="indicator-label">Send</span>
                                    {{-- <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span> --}}
                                </button>
                                <!--end::Submit-->
                            </div>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
</div>
    <!--end::Card-->
@endsection

@push('scripts')
{{-- <script src="assets/js/custom/apps/inbox/compose.js"></script> --}}
@endpush
