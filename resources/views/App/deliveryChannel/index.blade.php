@extends('App.main.navBar')
@section('contact_active','active')
@section('contact_active_show','active show')
@section('import_contacts_active','active')


@section('styles')
<style>
</style>
@endsection


@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Payment Accounts</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">sell</li>
    <li class="breadcrumb-item text-dark">all sales </li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2"
            data-bs-parent="#kt_accordion_1">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">

            </div>
        </div>
        <div class="card card-flush h-xl-100">
            <!--begin::Card header-->
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Payment Accounts</span>
                    {{-- <span class="text-gray-400 mt-1 fw-semibold fs-6">Total 2,356 Items in the Stock</span> --}}
                </h3>
                <!--end::Title-->
                <!--begin::Actions-->
                <div class="card-toolbar">
                    <div class="d-flex flex-stack flex-wrap gap-4" data-kt-exchangeRate-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
                            data-bs-target="#add_payment_acounts_modal">
                            Add
                        </button>
                        {{-- <a href="#" class="btn btn-primary btn-sm">Add</a> --}}
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-exchangeRate-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-exchangeRate-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger btn-sm"
                            data-kt-exchangeRate-table-select="delete_selected">Delete Selected</button>
                    </div>
                </div>

            </div>
            <!--end::Card header-->
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body ">
                <!--begin::Table-->
                @error('withdrawlAmount')
                <span class="text-danger require">{{$message}}</span>
                @enderror
                <table class="table align-middle table-row-dashed fs-6 gy-3 " id="paymentAccountsTable">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll"
                                        type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#paymentAccountsTable .form-check-input" value="" />
                                </div>
                            </th>
                            <th class="  pe-3 min-w-100px">Actions</th>
                            <th class=" pe-3 min-w-100px">Name</th>
                            {{-- <th class=" pe-3 min-w-100px">Account Type</th> --}}
                            <th class=" pe-3 min-w-100px">Account Number</th>
                            <th class=" pe-3 min-w-100px">Opening Amount</th>
                            <th class=" pe-3 min-w-100px">Current Amount</th>
                            <th class=" pe-3 min-w-100px">Currency</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-start text-gray-600  fs-7">

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <!--end::Container-->
</div>
@include('App.paymentAccounts.create')
<div class="modal modal-lg fade" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="editModal">

</div>
<div class="modal  fade " tabindex="-1" id="withdrawlModal">

</div>
<div class="modal  fade " tabindex="-1" id="depositModal">

</div>
<div class="modal  fade " tabindex="-1" id="transferModal">

</div>
@endsection

@push('scripts')
<script>
    // $(document).on('click', '#create_modal', function(e){
//     e.preventDefault();
//     $('#add_exchange_rates_modal').load($(this).data('href'), function() {
//         $(this).modal('show');
//     });
// });


$(document).on('click', '#edit', function(e){
    e.preventDefault();
    loadingOn();
    $('#editModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
    });
});
$(document).on('click', '#withdrawl', function(e){
    e.preventDefault();
    loadingOn();
    $('#withdrawlModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
    });
});
$(document).on('click', '#deposit', function(e){
    e.preventDefault();
    loadingOn();
    $('#depositModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
    });
});
$(document).on('click', '#transfer', function(e){

    loadingOn();
    e.preventDefault();
    $('#transferModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
    });
});
"use strict";




// Class definition
var paymentAccounts = function () {
    // Define shared variables
    var datatable;
    var table

    // Private functions
    var exchangeList = function () {
        datatable = $(table).DataTable({
            'columnDefs': [
                { orderable: false, targets: 0 },
            ],
            order: [[0, ' ']],
            processing: true,
            serverSide: true,
               ajax: {
                url: '/payment-account/get/list/',
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
                },
                {
                    name:'name',
                    data:'name'
                },
                // {
                //     name:'account_type',
                //     data:'account_type',
                // },
                {
                    data: 'account_number',
                    name: 'account_number',
                },
                {
                    name:'opening_amount',
                    data:'opening_amount'
                },
                {
                    name:'current_balance',
                    data:'current_balance',
                },
                {
                    name:'currency.name',
                    data:'currency.name'
                }

            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            // handleBusinessLocationFilter();
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
        const deleteSelected = document.querySelector('[data-kt-exchangeRate-table-select="delete_selected"]');

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
                    let idForDelete=[];
                    checkboxes.forEach(c => {
                        if (c.checked) {
                            idForDelete = [...idForDelete,c.value];
                        }
                    });
                    $.ajax({
                        url: `/payment-account/destory`,
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
                                text: "You have successfully deleted selected exchangeRate!.",
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




                const toolbarBase = document.querySelector('[data-kt-exchangeRate-table-toolbar="base"]');
                const toolbarSelected = document.querySelector('[data-kt-exchangeRate-table-toolbar="selected"]');
                toolbarBase.classList.remove('d-none');
                toolbarSelected.classList.add('d-none');
                $('[type="checkbox"]').prop('checked', false);
            });
        });
    }


    // Toggle toolbars
    const toggleToolbars = () => {
        // Define variables
        const toolbarBase = document.querySelector('[data-kt-exchangeRate-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-exchangeRate-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-exchangeRate-table-select="selected_count"]');

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
        var handleDeleteRows = () => {
            // Select all delete buttons
            const deleteButtons = document.querySelectorAll('[data-kt-exchangeRate-table="delete_row"]');
            deleteButtons.forEach(d => {
                // Delete button on click
                d.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Select parent row
                    const parent = e.target.closest('tr');
                    // Get exchangeRate name
                    const exchangeRateName = parent.querySelectorAll('td')[2].innerText;

                    // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Are you sure you want to delete " + exchangeRateName + "?",
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
                                    url: `/payment-account/destory`,
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
                                            text: "You have deleted " + exchangeRateName + "!.",
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
                                text: exchangeRateName + " was not deleted.",
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
            table = document.querySelector('#paymentAccountsTable');

            if (!table) {
                return;
            }
            exchangeList();
            initToggleToolbar();
            handleDeleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    paymentAccounts.init();
});


    $(document).ready(function(){
                // user update validation
        var paidAllValidator = function () {
            // Shared variables

            const element = document.getElementById("add_payment_acounts_modal");
            const form = element.querySelector("#add_payment_acounts");
            // let value={account->current_balance}};
            // Init add schedule modal
            var initPaidAll = () => {

                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/

                // Submit button handler
                const submitButton = element.querySelectorAll('#submit');

                submitButton.forEach((btn) => {
                    btn.addEventListener('click', function (e) {
                            var validator =validationField(form);
                            if (validator) {
                                validator.validate().then(function (status) {
                                    if (status == 'Valid') {
                                        e.currentTarget=true;
                                        btn.setAttribute('data-kt-indicator', 'on');
                                        return true;
                                    } else {
                                        e.preventDefault();

                                        btn.setAttribute('data-kt-indicator', 'off');
                                        // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                });
                            }

                    });
                })

            }

            // Select all handler

            return {
                // Public functions
                init: function () {
                    initPaidAll();
                }
            };
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            paidAllValidator.init();
        });

        function validationField(form) {
            $('.fv-plugins-message-container').remove();
            let accountId=$('#payment_account').val();
            let paidAmountValidator;

            var validationFields = {
                    name:{
                        validators: {
                            notEmpty: { message: "* Payment Account Name is required" }
                        },
                    },
                    currency_id:{
                        validators: {
                            notEmpty: { message: "* Currency is required" }
                        },
                    },
            };
            return  FormValidation.formValidation(
                form,
                {
                    fields:validationFields,
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    },

                }
            );
        }
    })
</script>
@endpush
