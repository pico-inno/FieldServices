@extends('App.main.navBar')
@section('barcode_active','active')
@section('barcode_active_show','active show')
@section('barcode_template_list_active_show','here show')
@section('location_add_nav','active')


@section('styles')
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<style>
    #table_card .table-responsive {
        min-height: 60vh;
    }
</style>
@endsection


@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Barcode Template</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Barcode</li>
    <li class="breadcrumb-item text-dark">Template List</li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
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
                        <input type="text" data-kt-barcode-table-filter="search"
                            class="form-control form-control-solid w-250px ps-15" placeholder="Search Template" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-barcode-table-toolbar="base">
                        <!--begin::Filter-->
                        <!--begin::Toolbar-->

                        <!--end::Toolbar-->
                        {{-- @if(hasCreate('purchase')) --}}
                        <!--begin::Add customer-->
                        <a href="{{route('barcode.create')}}">
                            <button class="btn btn-sm btn-primary">
                                Add New Template
                            </button>
                        </a>
                        <!--end::Add customer-->
                        {{-- @endif --}}


                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-barcode-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-barcode-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger"
                            data-kt-barcode-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0" id='table_card'>
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-7 fw-bold gy-2 pb-3 " id="kt_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2 d-none">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll"
                                        type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_table .form-check-input" value="" />
                                </div>
                            </th>
                            <th class="min-w-125px">{{__('table/label.actions')}}</th>
                            <th class="min-w-125px" id="Template Name">Name</th>
                            <th>Description</th>
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
@endsection

@push('scripts')
<script>
    "use strict";

var datatable;
// Class definition
var KTCustomersList = function () {

    var table;
    // Private functions
    var initBarcodeList = function () {
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            pageLength: 30,
            lengthMenu: [10, 20, 30, 50,40,80],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 1 }, // Disable ordering on column 6 (actions)

            ],
            // order: [[2, 'desc']],
            processing: true,
            serverSide: true,
               ajax: {
                url: '/barcode/list/data',
            },

            columns: [
                // {
                //     data: 'checkbox',
                //     name: 'checkbox',
                //     orderable: false,
                //     searchable: false,
                // },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false ,
                    orderable: false,
                },
                {
                    name:'name',
                    data:'name',
                },
                {
                    name:'description',
                    data:'description',
                },


            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();

        });
    }


    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-barcode-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }


        // Delete location
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-barcode-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('hello');
                // Select parent row
                const parent = e.target.closest('tr');

                // Get purchase name
                const purchaseName = parent.querySelectorAll('td')[2].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + purchaseName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        let id=d.getAttribute('data-id')
                            $.ajax({
                                url: `/barcode/${id}`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {
                                    datatable.ajax.reload();
                                    Swal.fire({
                                        text: "You have deleted " + purchaseName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        success(s.success);
                                    });
                                }
                            })
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: purchaseName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }


    // Toggle toolbars

        // Init toggle toolbar
        var initToggleToolbar = () => {
            // Toggle selected action toolbar
            // Select all checkboxes
            const checkboxes = table.querySelectorAll('[data-checked="delete"]');
            const selectAll = table.querySelector('#selectAll');
            // Select elements
            const deleteSelected = document.querySelector('[data-kt-barcode-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
                    console.log('click');
                    setTimeout(function () {
                        toggleToolbars();
                    }, 50);
                });
            });
            selectAll.addEventListener('click',function () {
                 setTimeout(function () {
                        toggleToolbars();
                    }, 50);
            })

            // Deleted selected rows
            deleteSelected.addEventListener('click', function () {
                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete selected locations?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        let data=[];
                        checkboxes.forEach(c => {
                            if (c.checked) {
                                console.log(c.value);
                                data = [...data,c.value];
                            }
                        });
                        $.ajax({
                            url: `purchase/softDelete`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                               data,
                            },
                            success: function(s) {
                                datatable.ajax.reload();
                                Swal.fire({
                                    text: "You have successfully deleted selected Purchase!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    //sth
                                    success(s.success);
                                });
                            }
                        })
                        // Swal.fire({
                        //     text: "You have deleted all selected Purchases!.",
                        //     icon: "success",
                        //     buttonsStyling: false,
                        //     confirmButtonText: "Ok, got it!",
                        //     customClass: {
                        //         confirmButton: "btn fw-bold btn-primary",
                        //     }
                        // }).then(function () {
                        //     // Remove all selected locations


                        //     // Remove header checked box
                        //     const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                        //     headerCheckbox.checked = false;
                        // });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Selected locations was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            });
        }


        // Toggle toolbars
        const toggleToolbars = () => {
            // Define variables
            const toolbarBase = document.querySelector('[data-kt-barcode-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-barcode-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-barcode-table-select="selected_count"]');

            // Select refreshed checkbox DOM elements
            const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                }
            });

            // Toggle toolbars
            if (checkedState) {
                selectedCount.innerHTML = count;
                toolbarBase.classList.add('d-none');
                toolbarSelected.classList.remove('d-none');
            } else {
                toolbarBase.classList.remove('d-none');
                toolbarSelected.classList.add('d-none');
            }
        }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_table');

            if (!table) {
                return;
            }

            initBarcodeList();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();

        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});

</script>
@endpush
