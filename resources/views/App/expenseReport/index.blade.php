@extends('App.main.navBar')

@section('expense_active', 'active')
@section('expense_report_active', 'active')
@section('expense_active_show', 'active show')


@section('styles')
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('expense.expense_report_list')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('expense.expense_report')}}</li>
        <li class="breadcrumb-item text-dark">LIst</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')

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
                            <div class="mb-5 col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">Bussiness Location:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">All</option>
                                    <option value="Analyst">Yangon</option>
                                    <option value="Developer">Mandalay</option>
                                    <option value="Support">YanKin</option>
                                    <option value="Trial">TarMwe</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold">Customer:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true">
                                    <option></option>
                                    <option value="Enabled">DE(202)</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <div class="mb-10 col-6 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">User:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="Location" data-hide-search="true">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="demo">Mg Mg</option>
                                    <option value="Yangon,">Kyaw Kyaw</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">

                            <!--begin::Input group-->
                            <div class="mb-10 col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">date range:</label>
                                <input class="form-control form-control-solid" placeholder="Pick date rage" id="kt_daterangepicker_4" data-dropdown-parent="#filter" />
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
            <div class="card-header border-0 pt-6">
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
                        <input type="text" data-kt-expense-report-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search {{__('expense.expense')}}" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-expense-report-table-toolbar="base">
                        <!--begin::Filter-->
                        <!--end::Filter-->
                        <!--end::Export-->
                        <!--begin::Add customer-->
                        {{-- <a  class="btn btn-primary btn-sm" href={{route('purchase_order_add')}}>Add</a> --}}
                        <!--end::Add customer-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-expense-report-table-toolbar="selected">
                        <div class="fw-bold me-5">
                        <span class="me-2" data-kt-expense-report-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger" data-kt-expense-report-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="expenseReportTable">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll" type="checkbox" data-kt-check="true"  data-kt-check-target="#expenseReportTable .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px text-start">Action</th>
                            <th class="min-w-125px">Expense Report No</th>
                            <th class="min-w-125px">Expense on</th>
                            <th class="min-w-125px">Total Expense Amount</th>
                            <th class="min-w-125px text-start">Report By</th>
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
        <div class="modal fade" id="kt_customers_export_modal" tabindex="-1" aria-hidden="true">
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
                        <div id="kt_customers_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <form id="kt_customers_export_form" class="form" action="#">
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
                                <button type="reset" id="kt_customers_export_cancel" class="btn btn-light me-3">Discard</button>
                                <button type="submit" id="kt_customers_export_submit" class="btn btn-primary">
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

<div class="modal modal-lg fade " tabindex="-1" id="modal">

</div>
@endsection

@push('scripts')
		<script>
            $(document).on('click', '#paymentCreate', function(e){
                e.preventDefault();
                loadingOn();
                $('#modal').load($(this).data('href'), function() {
                    $(this).modal('show');
                    loadingOff();
                    $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
                    $('[data-control="select2-acc"]').select2();
                    $('[data-datepicker="datepicker"]').flatpickr({
                        dateFormat: "Y-m-d",
                    });
                });
            });
            $(document).on('click', '#viewPayment', function(e){
                e.preventDefault();
                loadingOn();
                $('#modal').load($(this).data('href'), function() {
                    $(this).modal('show');
                    loadingOff();
                    $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
                    $('[data-control="select2-acc"]').select2();
                    $('[data-datepicker="datepicker"]').flatpickr({
                        dateFormat: "Y-m-d",
                    });
                    handleDeletePayment();
                });
            });


            var handleDeletePayment = () => {
                // Select all delete buttons
                $(document).off('click', '[data-table="delete_payment"]').on('click','[data-table="delete_payment"]',function (e) {
                    e.preventDefault();
                    // Select parent row

                    const parent = $(this).closest('tr');
                    // Get expense name
                    const expenseName = parent.find('.voucher_no').text();
                    let id=$(this).getAttribute('data-id');
                    // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Are you sure to remove  " + expenseName + " from report?",
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

                            parent.remove();
                                $.ajax({
                                    url: `/remove/${id}/expense/report/`,
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(s) {
                                        datatable.ajax.reload();
                                        $('#total_expense_amount').text(s.total_expense_amount);
                                        $('#balance_amount').text(s.balance_amount);
                                        Swal.fire({
                                            text: "You have deleted " + expenseName + "!.",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            }
                                        }).then(function () {
                                            datatable.ajax.reload();
                                            success(s.success);
                                        });
                                    }
                                })
                        }
                    });
                })
                // Delete button on click
            }
            // Class definition
            var KTExpenseList = function () {
                // Define shared variables
                var table

                    // Private functions
                    var expenseList = function () {
                        datatable = $(table).DataTable({
                            pageLength: 30,
                            lengthMenu: [10, 20, 30, 50,70],
                            'columnDefs': [
                                { orderable: false, targets: 0 },
                            ],
                            order: [[0, ' ']],
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '/expense-report/list/data/',
                            },
                            columns: [
                                {
                                    data: 'checkbox',
                                    name: 'checkbox',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    searchable: false ,
                                    orderable: false,
                                },{

                                    data: 'expense_report_no',
                                    name: 'expense_report_no',
                                },
                                {
                                    name:'expense_on',
                                    data:'expense_on'
                                },
                                {
                                    name:'total_expense_amount',
                                    data:'total_expense_amount',
                                },
                                {
                                    name: 'reportBy',
                                    data: 'reportBy',
                                }

                            ]

                        });

                        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
                        datatable.on('draw', function () {
                            // handleBusinessLocationFilter();
                            function check(){
                                const toolbarBase = document.querySelector('[data-kt-expense-report-table-toolbar="base"]');
                                const toolbarSelected = document.querySelector('[data-kt-expense-report-table-toolbar="selected"]');
                                $('tbody [type="checkbox"]').each(function(){
                                if( checkList.length>0){
                                        checkList.forEach((val)=>{
                                            if(val==$(this).val()){
                                                $(this).attr('checked',true)
                                            }
                                        })


                                }else{

                                }
                                })
                            }
                            toggleToolbars();
                            initToggleToolbar();
                            handleDeleteRows();

                        });
                    }


                    // Init toggle toolbar
                    var initToggleToolbar = () => {
                        // Toggle selected action toolbar
                        // Select all checkboxes
                        const checkboxes = table.querySelectorAll('[data-checked="delete"]');
                        const selectAll = table.querySelector('#selectAll');
                        // Select elements
                        const deleteSelected = document.querySelector('[data-kt-expense-report-table-select="delete_selected"]');

                        // Toggle delete selected toolbar
                        checkboxes.forEach(c => {
                            // Checkbox on click event
                            c.addEventListener('click', function () {
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
                                text: "Are you sure you want to delete selected report?",
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
                                    let idForDelete=[];
                                    checkboxes.forEach(c => {
                                        if (c.checked) {
                                            idForDelete = [...idForDelete,c.value];
                                        }
                                    });
                                    $.ajax({
                                        url: `/expense-report/destory`,
                                        type: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            idForDelete,
                                        },
                                        success: function(s) {
                                            datatable.ajax.reload();
                                            Swal.fire({
                                                text: "You have successfully deleted selected expense!.",
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




                                const toolbarBase = document.querySelector('[data-kt-expense-report-table-toolbar="base"]');
                                const toolbarSelected = document.querySelector('[data-kt-expense-report-table-toolbar="selected"]');
                                toolbarBase.classList.remove('d-none');
                                toolbarSelected.classList.add('d-none');
                                $('[type="checkbox"]').prop('checked', false);
                            });
                        });
                    }


                    // Toggle toolbars
                    const toggleToolbars = () => {
                        // Define variables
                        const toolbarBase = document.querySelector('[data-kt-expense-report-table-toolbar="base"]');
                        const toolbarSelected = document.querySelector('[data-kt-expense-report-table-toolbar="selected"]');
                        const selectedCount = document.querySelector('[data-kt-expense-report-table-select="selected_count"]');

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
                            }else{
                                $('#selectAll').prop('checked',false);
                            }
                        });

                        // Toggle toolbars
                        if (checkedState) {
                            selectedCount.innerHTML = count;
                            toolbarBase.classList.add('d-none');
                            toolbarSelected.classList.remove('d-none');

                        }else{

                            toolbarBase.classList.remove('d-none');
                            toolbarSelected.classList.add('d-none');

                        }

                    }
                    var handleDeleteRows = () => {
                        // Select all delete buttons
                        const deleteButtons = document.querySelectorAll('[data-kt-expense-report-table="delete_row"]');
                        deleteButtons.forEach(d => {
                            // Delete button on click
                            d.addEventListener('click', function (e) {
                                e.preventDefault();
                                // Select parent row
                                const parent = e.target.closest('tr');
                                // Get expense name
                                const expenseName = parent.querySelectorAll('td')[2].innerText;

                                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Are you sure you want to delete " + expenseName + "?",
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
                                                url: `/expense-report/destory`,
                                                type: 'DELETE',
                                                data: {
                                                    idForDelete:[id],
                                                },
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function(s) {
                                                    datatable.ajax.reload();
                                                    Swal.fire({
                                                        text: "You have deleted " + expenseName + "!.",
                                                        icon: "success",
                                                        buttonsStyling: false,
                                                        confirmButtonText: "Ok, got it!",
                                                        customClass: {
                                                            confirmButton: "btn fw-bold btn-primary",
                                                        }
                                                    }).then(function () {
                                                        datatable.ajax.reload();
                                                        success(s.success);
                                                    });
                                                }
                                            })
                                    } else if (result.dismiss === 'cancel') {
                                        Swal.fire({
                                            text: expenseName + " was not deleted.",
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
                return {
                    init: function () {
                        table = document.querySelector('#expenseReportTable');

                        if (!table) {
                            return;
                        }
                        expenseList();
                        initToggleToolbar();
                        handleDeleteRows();
                    }
                }
            }();

            // On document ready
            KTUtil.onDOMContentLoaded(function () {
                KTExpenseList.init();
            });
        </script>
@endpush


