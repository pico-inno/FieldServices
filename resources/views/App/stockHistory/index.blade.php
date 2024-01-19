


@extends('App.main.navBar')

@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_history_active_show', 'active show')




@section('styles')
    <link href={{asset("assets/plugins/custom/datatables/datatables.bundle.css")}} rel="stylesheet" type="text/css" />
    <style>
        .billDiv tr td{
            padding: 8px 0 !important;
        }
        tr td:nth-child(6)
        {
            text-align: end;
        }
        tr td:nth-child(7)
        {
            text-align: end;
        }
        tr td:nth-child(8)
        {
            text-align: end;
        }


        /* tr td:nth-child(4),tr td:nth-child(5){
            text-align: center;
        } */
        tr td:last-child{
            text-align: center;
        }
    </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Stock History</h1>
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
                <div class="d-flex  flex-wrap flex-sm-nowrap col-12 pt-7 my-3 mx-5">
                    <div class="col-sm-2 col-6 me-sm-5 mb-3 mb-sm-0 ms-3">
                        <input type="text" class="form-control form-control-sm" placeholder="Search Product" data-filter="input">
                    </div>
                    <div class="col-sm-2 col-6 me-sm-5 mb-3">
                        <select name="locationfilter" id="locationFilter" class="form-select form-select-sm" data-control="select2" data-placeholder="Filter Location" data-filter="from-location" placeholder="Filter Location" data-allow-clear="true">
                            <option value="all" selected> All</option>
                            @foreach ($locations as $l)
                                <option value="{{$l->id}}">{{$l->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="stockHistoryTable">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th></th>
                            <th class="text-start min-w-100px">Date</th>
                            <th class="text-start pe-3 min-w-100px">Reference</th>
                            <th class="text-start pe-3 min-w-100px">Product</th>
                            <th class="text-start pe-3 min-w-100px">From</th>
                            <th class="text-start pe-3 min-w-100px">To</th>
                            <th class="text-end pe-3 min-w-100px">Increase Qty</th>
                            <th class="text-end pe-3 min-w-100px">Decrease Qty</th>
                            <th class="text-end pe-3 min-w-100px">Balance Qty</th>
                            <th class="text-center pe-3 min-w-100px">UOM</th>
                            {{-- <th class="text-end pe-0 min-w-25px">Qty</th> --}}
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600 text-start fs-7">
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

    <div class="modal fade purchaseDetail" tabindex="-1"></div>
    <div class="modal fade" tabindex="-1" id="folioPosting"></div>
@endsection

@push('scripts')
<script src="{{ asset('customJs/debounce.js') }}"></script>
    <script>

        "use strict";

        // Class definition
        var KTCustomersList = function () {
            // Define shared variables
            var datatable;
            var filterMonth;
            var filterPayment;
            var table

            // Private functions
            var initCustomerList = function () {
                // Set date data order
                const tableRows = table.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    const dateRow = row.querySelectorAll('td');
                    const realDate = moment(dateRow[5].innerHTML, "DD MMM YYYY, LT").format(); // select date from 5th column in table
                    dateRow[5].setAttribute('data-order', realDate);
                });
                var prevBalance = 0;

                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({
                    "ordering": false,
                    'columnDefs': [
                        // Disable ordering on column 0 (checkbox)
                        {
                            targets: [0], // Replace 0 with the index of the column you want to hide
                            visible: false,
                            searchable: true
                        }
                    ],
                    processing: true,
                    pageLength: 15,
                    lengthMenu: [15, 20, 30, 50,40,80],
                    serverSide: true,
                    ajax: {
                        url: '/stock-history/get/list/',
                    },
                    columns: [

                        {
                            name:'location',
                            data:'location'
                        },
                        {
                            name:'created_at',
                            data:'created_at',
                        },
                        {
                            data:'reference',
                            name: 'reference'
                        },
                        {
                            name:'product',
                            data:'product',
                        },
                        {
                            data: 'from',
                            name: 'from'
                        },{
                            data:'to',
                            name:'to'
                        },
                        {
                            data: 'increase_qty',
                            name: 'increase_qty'
                        },
                        {
                            data: 'decrease_qty',
                            name: 'decrease_qty'
                        },
                        {
                            data: 'balance_qty',
                            name: 'balance_qty',

                        },
                        {
                            data:'uom',
                            name: 'uom'
                        }


                    ],



                });

                // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
                datatable.on('draw', function () {

                    handleBusinessFromLocationFilter();
                    handleBusinessToLocationFilter();

                });
            }



            // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-filter="input"]');
                filterSearch.addEventListener('keyup', debounce(function (e) {
                    datatable.search(e.target.value).draw();
                }));
            }



            var handleBusinessFromLocationFilter = () => {
                const filterStatus = document.querySelector('[data-filter="from-location"]');
                $(filterStatus).on('change', e => {
                    let value = e.target.value;
                    if (value === 'all') {
                        value = '';
                    }
                    datatable.column(3).search(value).draw();

                });
            }
            var handleBusinessToLocationFilter = () => {
                const filterStatus = document.querySelector('[data-filter="from-location"]');
                $(filterStatus).on('change', e => {
                    let value = e.target.value;
                    if (value === 'all') {
                        value = '';
                    }
                    datatable.column(4).search(value).draw();

                });
            }


            // Public methods
            return {
                init: function () {
                    table = document.querySelector('#stockHistoryTable');

                    if (!table) {
                        return;
                    }

                    initCustomerList();
                    // initToggleToolbar();
                    handleSearchDatatable();
                    // handleDeleteRows();
                    // // handleStatusFilter();
                    handleBusinessFromLocationFilter();
                    handleBusinessToLocationFilter();
                    // DateRangeFilter();
                    // handleCustomerFilter();
                    // handleStatusFilter();
                    // handleDateFilterDatatable();
                }
            }
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTCustomersList.init();
        });


    </script>
@endpush




































