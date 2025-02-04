


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

@livewireStyles
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
            <livewire:stock.stockHistoryTable/>
        </div>
        <!--end::Container-->
    </div>

    <div class="modal fade purchaseDetail" tabindex="-1"></div>
    <div class="modal fade" tabindex="-1" id="folioPosting"></div>
@endsection

@push('scripts')

@livewireScripts
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




































