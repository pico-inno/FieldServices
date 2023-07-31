@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
@endsection
@section('registration_icon','active')
@section('hospital_registration_show','active show')
@section('registration_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Registration</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Hospital</li>
    <li class="breadcrumb-item text-dark">Registration</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Reservation-->
        <!--begin::Card-->
        <div class="card card-p-4 card-flush" id="listAgency">
            <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                <div class="card-title d-flex flex-column">
                    <h4>All Registration</h4>
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
                        <input type="text" data-registration="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search....." />
                    </div>
                    <!--end::Search-->
                    <!--begin::Export buttons-->
                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                    <!--end::Export buttons-->
                    <!--begin::Export dropdown-->
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-light-primary mx-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
                        <a href="{{route('registration_create') }}" class="text-light btn btn-primary btn-sm">Add</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- <div class="table-responsive"> -->
                    <table class="table align-middle rounded table-row-dashed fs-6 g-5" id="kt_registration_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-gray-400 fs-7 fw-bold text-uppercase ">
                                <th class="min-w-125px">Action</th>
                                <th class="min-w-80px">patient type</th>
                                <th class="min-w-150px">Registration Id</th>
                                <th class="min-w-100px">patient</th>
                                <th class="min-w-100px">Company</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-150px">OPD check In Date</th>
                                <th class="min-w-150px">IPD check In Date</th>
                                <th class="min-w-150px">Check Out Date</th>
                                <th class="min-w-100px">Remark</th>
                                <th class="min-w-100px">Confirm At</th>
                                <th class="min-w-100px">Confirm By</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold">
                          
                        </tbody>
                        <!--end::Table body-->
                    </table>
                <!-- </div> -->
            </div>
        </div>
        <!--end::Card-->
        <!--end::Reservation-->
    </div>
    <!--end::container-->
</div>
<!--end::Content-->



<!--begin::Modal-->
<div class="modal fade" tabindex="-1" id="joinTo">

</div>
<!--end::Modal-->
@endsection

@push('scripts')
    <script src="{{asset('customJs/hospital/list.js')}}"></script>
    <script>
            // $(document).on('click', '.view_detail', function(){
            //     $url=$(this).data('href');
            //     console.log($url);
            //     $('.purchaseDetail').load($url, function() {
            //         $(this).modal('show');
            //     });
            // });


            $(document).on('click', '.joinRegistration', function(e){
                e.preventDefault();
                $('#joinTo').load($(this).data('href'), function() {
                    $('.joinSelect').select2();
                    $(this).modal('show');
                    $('form#joinRegistrationForm').submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var data = form.serialize();
                        $.ajax({
                            method: 'PUT',
                            url: $(this).attr('action'),
                            dataType: 'json',
                            data: data,

                            success: function(result) {
                                if (result.success == true) {
                                    $('#joinTo').modal('hide');
                                    toastr.success(result.msg);
                                    datatable.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                            error: function(result) {
                                toastr.error(result.responseJSON.errors, 'Something went wrong');
                            }
                        });
                    });
                });
            });
            $('[data-select="test"]').select2();
    </script>
@endpush
