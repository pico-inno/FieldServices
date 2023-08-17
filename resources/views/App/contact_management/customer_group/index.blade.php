@extends('App.main.navBar')
@section('contact_active','active')
@section('contact_active_show','active show')
@section('customer_group_here_show','here show')
@section('customer_group_list_active_show','active show')

@section('styles')
<style>
    #kt_datatable_example {
        min-height: 40vh;
    }
</style>
@endsection

@section('title')

<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Customer Group</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Contact</li>
    <li class="breadcrumb-item text-dark">Customer Group</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        @if(session('error'))
        <div class="alert alert-dismissible bg-light-danger d-flex align-items-center flex-sm-row p-5 mb-10">
            <span class="text-danger">{{ session('error') }}</span>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif
        <!--begin::Card-->
        <div class="card card-p-4 card-flush">
            <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                <div class="card-title d-flex flex-column">
                    <h4>All Customer Groups</h4>
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
                        <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search....." />
                    </div>
                    <!--end::Search-->
                    <!--begin::Export buttons-->
                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                    <!--end::Export buttons-->
                    <!--begin::Export dropdown-->
                    <div class="mt-2">
                        @if(hasExport('customer'))
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
                        @endif
                        @if(hasCreate('customer'))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#add_customer_group_modal" data-href="{{ url('customerGroup/addCustomerGroup')}}"
                        >
                            Add
                        </button>
                            @endif
                    </div>
                </div>
            </div>
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_example">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                            <th class="min-w-125px">Customer Group Name</th>
                            <th class="min-w-125px">Calculation Percentage(%)</th>
                            <th class="min-w-125px">Selling Price Group</th>
                            <th class="min-w-125px">Actions</th>
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
    </div>
    <!--end::Container-->
    <!--begin::Modal-->
    @include('App.contact_management.customer_group.create')

   <div class="modal modal-lg fade" tabindex="-1" role="dialog" id="edit_customer_group_modal">
   </div>
   <!--end::Modal-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script>
    "use strict";

    // Class definition
    var KTDatatablesExample = function() {
        // Shared variables
        var table;
        var datatable;

        // Private functions
        var initDatatable = function() {
            // Set date data order
            const tableRows = table.querySelectorAll('tbody tr');

            // Init datatable --- more info on datatables: https://datatables.net/manual/
            datatable = $(table).DataTable({
                processing : true,
                serverSide : true,
                "info": false,
                'order': [],
                'pageLength': 10,

                ajax: {
                    url: '/customer-group'
                },

                columnDefs: [
                    {
                        targets: 3,
                        orderable: false,
                        searchable: false,
                    },
                ],
                columns: [
                    { data: 'name'},
                    { data: 'amount'},
                    { data: 'selling_price_group_id'},
                    { data: 'action'},
                ],
            });
        }

        $(document).on('click', 'button.edit-btn', function(){
            $('#edit_customer_group_modal').load($(this).data('href'), function() {
                $(this).modal('show');

                $('form#edit_customer_group_form').submit(function(e) {
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
                                $('#edit_customer_group_modal').modal('hide');
                                toastr.success(result.msg);
                                datatable.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                        error: function(result) {
                            toastr.error(result.responseJSON.errors, 'Error');
                        }
                    });
                });
            });
        });

        $(document).on("click", ".delete-btn", function(e) {
            e.preventDefault();
            var customerGroupId = $(this).data("id");
            Swal.fire({
                title: "Are you sure",
                text: "This customer group will be deleted",
                icon: "warning",
                showCancelButton: true,
                buttons: true,
                dangerMode: true,
            }).then(function (confirmed) {
                if (confirmed.value) {
                    $("#delete-form-" + customerGroupId).submit();
                    }
                });
        });

        // Hook export buttons
        var exportButtons = () => {
            const documentTitle = 'Customer Groups Report';
            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [{
                        extend: 'copyHtml5',
                        title: documentTitle
                    },
                    {
                        extend: 'excelHtml5',
                        title: documentTitle
                    },
                    {
                        extend: 'csvHtml5',
                        title: documentTitle
                    },
                    {
                        extend: 'pdfHtml5',
                        title: documentTitle
                    }
                ]
            }).container().appendTo($('#kt_datatable_example_buttons'));

            // Hook dropdown menu click event to datatable export buttons
            const exportButtons = document.querySelectorAll('#kt_datatable_example_export_menu [data-kt-export]');
            exportButtons.forEach(exportButton => {
                exportButton.addEventListener('click', e => {
                    e.preventDefault();

                    // Get clicked export value
                    const exportValue = e.target.getAttribute('data-kt-export');
                    const target = document.querySelector('.dt-buttons .buttons-' + exportValue);

                    // Trigger click event on hidden datatable export buttons
                    target.click();
                });
            });
        }

        // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
        var handleSearchDatatable = () => {
            const filterSearch = document.querySelector('[data-kt-filter="search"]');
            filterSearch.addEventListener('keyup', function(e) {
                datatable.search(e.target.value).draw();
            });
        }

        // Public methods
        return {
            init: function() {
                table = document.querySelector('#kt_datatable_example');

                if (!table) {
                    return;
                }

                initDatatable();
                exportButtons();
                handleSearchDatatable();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        KTDatatablesExample.init();
    });
</script>

@endpush
