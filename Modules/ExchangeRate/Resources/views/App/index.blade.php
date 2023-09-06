


@extends('App.main.navBar')

@section('fa_active','active')
@section('ex_rate_active_show', 'active show')
@section('fa_active_show', 'active show')




@section('styles')
		<link href={{asset("assets/plugins/custom/datatables/datatables.bundle.css")}} rel="stylesheet" type="text/css" />
        <style>
            /* .billDiv tr td{
                padding: 8px 0 !important;
                }
            tr td:nth-child(6),tr td:nth-child(7)
             {
                text-align: end;
            } */

            /* tr td:nth-child(4),tr td:nth-child(5){
                text-align: center;
            } */
            /* tr td:last-child{
                text-align: center;
            } */
        </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Currency Rate</h1>
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
        <div class="accordion-collapse collapse" id="kt_accordion_1_body_2"  aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >

            </div>
        </div>
        <div class="card card-flush h-xl-100">
            <!--begin::Card header-->
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Exchange Rates</span>
                    {{-- <span class="text-gray-400 mt-1 fw-semibold fs-6">Total 2,356 Items in the Stock</span> --}}
                </h3>
                <!--end::Title-->
                <!--begin::Actions-->
                <div class="card-toolbar">
                    <div class="d-flex flex-stack flex-wrap gap-4" data-kt-exchangeRate-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm"  id="create_modal" data-href="{{route('exchangeRate.create')}}">Add</button>
                        {{-- <a href="#" class="btn btn-primary btn-sm">Add</a> --}}
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-exchangeRate-table-toolbar="selected">
                        <div class="fw-bold me-5">
                        <span class="me-2" data-kt-exchangeRate-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger btn-sm" data-kt-exchangeRate-table-select="delete_selected">Delete Selected</button>
                    </div>
                </div>

            </div>
            <!--end::Card header-->
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->

                <table class="table align-middle table-row-dashed fs-6 gy-3" id="exchangeRateTable">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll" type="checkbox" data-kt-check="true" data-kt-check-target="#exchangeRateTable .form-check-input" value="" />
                                </div>
                            </th>
                            <th class="  pe-3 min-w-100px">Actions</th>
                            <th class=" pe-3 min-w-100px">Currency</th>
                            <th class=" pe-3 min-w-100px">Rate</th>
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
<div class="modal modal-lg fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="editModal">

</div>
<div class="modal modal-lg fade" tabindex="-1" id="add_exchange_rates_modal">

</div>
{{-- @include('App.currency.exchangeRates.create') --}}
@endsection

@push('scripts')
<script>


let rates=@json($rates);
$(document).on('click', '#create_modal', function(e){
    e.preventDefault();
    loadingOn()
    $('#add_exchange_rates_modal').load($(this).data('href'), function() {
        loadingOff();
        $(this).modal('show');
    });
});


$(document).on('click', '#edit', function(e){
    e.preventDefault();
    $('#editModal').load($(this).data('href'), function() {
        $(this).modal('show');
    });
});
"use strict";




// Class definition
var KTCustomersList = function () {
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
                url: '/exchange-rate/get/list/',
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
                    name:'currency',
                    data:'currency'
                },
                {
                    name:'rate',
                    data:'rate',
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
                        url: `/exchange-rate/destory`,
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
                                    url: `/exchange-rate/destory`,
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
            table = document.querySelector('#exchangeRateTable');

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
    KTCustomersList.init();
});


</script>
@endpush




































