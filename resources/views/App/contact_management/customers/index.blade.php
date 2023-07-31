@extends('App.main.navBar')
@section('contact_active','active')
@section('contact_active_show','active show')
@section('customer_here_show','here show')
@section('customer_list_active_show','active show')

@section('styles')

@endsection

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Customer</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Contact</li>
    <li class="breadcrumb-item text-dark">Customer</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Card-->
        <div class="card card-p-4 card-flush">
            <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
                <div class="card-title d-flex flex-column">
                    <h4>All customers</h4>
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
                        <a href="{{route('customers.create')}}" class="text-light btn btn-primary btn-sm">Add</a>
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
                            <th class="min-w-125px">Actions</th>
                            <th class="min-w-125px">Contact ID</th>
                            <th class="min-w-125px">Business Name</th>
                            <th class="min-w-125px">Customer Name</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Tax Number</th>
                            <th class="min-w-125px">Credit Limit</th>
                            <th class="min-w-125px">Pay Term</th>
                            <th class="min-w-125px">Address</th>
                            <th class="min-w-125px">Mobile</th>
                            <th class="min-w-125px">Custom Field 1</th>
                            <th class="min-w-125px">Custom Field 2</th>
                            <th class="min-w-125px">Custom Field 3</th>
                            <th class="min-w-125px">Custom Field 4</th>
                            <th class="min-w-125px">Custom Field 5</th>
                            <th class="min-w-125px">Custom Field 6</th>
                            <th class="min-w-125px">Custom Field 7</th>
                            <th class="min-w-125px">Custom Field 8</th>
                            <th class="min-w-125px">Custom Field 9</th>
                            <th class="min-w-125px">Custom Field 10</th>
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
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "info": false,
                'order': [],
                'pageLength': 10,

                ajax : {
                    url : '/contacts/customers',
                },

                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                    },
                ],
                columns: [
                    { data: 'action'},
                    { data: 'contact_id'},
                    { data: 'company_name'},
                    { data: 'first_name'},
                    { data: 'email'},
                    { data: 'tax_number'},
                    { data: 'credit_limit'},
                    { data: 'pay_term_number'},
                    { data: 'address_line_1'},
                    { data: 'mobile'},
                    { data: 'custom_field_1'},
                    { data: 'custom_field_2'},
                    { data: 'custom_field_3'},
                    { data: 'custom_field_4'},
                    { data: 'custom_field_5'},
                    { data: 'custom_field_6'},
                    { data: 'custom_field_7'},
                    { data: 'custom_field_8'},
                    { data: 'custom_field_9'},
                    { data: 'custom_field_10'},
                ],
                drawCallback: function(settings) {
                    adjustRowHeight();
                }
            });

            adjustRowHeight();
        }

        $(document).on("click", ".delete-btn", function(e) {
            e.preventDefault();
            var customerId = $(this).data("id");
            Swal.fire({
                title: "Are you sure",
                text: "This contact will be deleted",
                icon: "warning",
                showCancelButton: true,
                buttons: true,
                dangerMode: true,
            }).then(function (confirmed) {
                if (confirmed.value) {
                    $("#delete-form-" + customerId).submit();
                    }
                });
        });

        var adjustRowHeight = function() {
            var numRows = datatable.rows().count();
            //  console.log(numRows);
            var tableRows = $('#kt_datatable_example tbody tr');

            if (numRows === 1) {
                tableRows.css('height', '170px');
            } else {
                tableRows.css('height', '50px');
            }
        }

        // Hook export buttons
        var exportButtons = () => {
            const documentTitle = 'Customers Report';
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
