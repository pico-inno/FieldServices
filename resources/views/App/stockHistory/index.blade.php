


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
            tr td:nth-child(6),tr td:nth-child(7)
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
                <div class="col-sm-2 col-6">
                    <select name="locationfilter" id="locationFilter" class="form-select form-select-sm" data-control="select2" data-placeholder="Filter Location" data-filter="location" placeholder="Filter Location" data-allow-clear="true">
                        <option></option>
                        @foreach ($locations as $l)
                            <option value="{{$l->name}}">{{$l->name}}</option>
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
                            <th class="text-start pe-3 min-w-100px">Product</th>
                            <th class="text-start pe-3 min-w-100px">From</th>
                            <th class="text-start pe-3 min-w-100px">To</th>
                            <th class="text-start pe-3 min-w-100px">Reference</th>
                            <th class="text-end pe-3 min-w-100px">Increase Qty</th>
                            <th class="text-end pe-3 min-w-100px">Decrease Qty</th>
                            <th class="text-end pe-3 min-w-100px">Balance</th>
                            <th class="text-center pe-3 min-w-100px">UOM</th>
                            {{-- <th class="text-end pe-0 min-w-25px">Qty</th> --}}
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600 text-start fs-7">
                        {{-- <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Macbook Air M1</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#XGY-356</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">02 Apr, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$1,230</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">58 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Surface Laptop 4</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#YHD-047</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">01 Apr, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$1,060</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="0">
                                <span class="text-dark fw-bold">0 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Logitech MX 250</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#SRR-678</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">24 Mar, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$64</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="290">
                                <span class="text-dark fw-bold">290 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">AudioEngine HD3</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#PXF-578</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">24 Mar, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$1,060</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="46">
                                <span class="text-dark fw-bold">46 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">HP Hyper LTR</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#PXF-778</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">16 Jan, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$4500</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="78">
                                <span class="text-dark fw-bold">78 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Dell 32 UltraSharp</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#XGY-356</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">22 Dec, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$1,060</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-warning">Low Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="8">
                                <span class="text-dark fw-bold">8 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Item-->
                            <td>
                                <a href="../../demo7/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Google Pixel 6 Pro</a>
                            </td>
                            <!--end::Item-->
                            <!--begin::Product ID-->
                            <td class="text-end">#XVR-425</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">27 Dec, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">$1,060</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="124">
                                <span class="text-dark fw-bold">124 PCS</span>
                            </td>
                            <!--end::Qty-->
                        </tr> --}}
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

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            'columnDefs': [
               // Disable ordering on column 0 (checkbox)
                {
                    targets: [0], // Replace 0 with the index of the column you want to hide
                    visible: false,
                    searchable: true
                },
                { orderable: false, targets: 0 },
            ],
            order: [[0, ' ']],
            processing: true,
            serverSide: true,
               ajax: {
                url: '/stock-history/get/list/',
            },
            columns: [
                // {
                //     data: 'checkbox',
                //     name: 'checkbox',
                //     orderable: false,
                //     searchable: false
                // },
                // {
                //     data: 'action',
                //     name: 'action',
                //     searchable: false ,
                //     orderable: false,
                // },
                {
                    name:'location',
                    data:'location'
                },
                {
                    name:'date',
                    data:'date',
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
                    data:'reference',
                    name: 'reference'
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
                  data: 'balance_quantity',
                  name: 'balance_quantity',
                },
                {
                    data:'uom',
                    name: 'uom'
                }
                // ,{
                //     data: 'sale_amount',
                //     name: 'Sale Amount'
                // },
                // {
                //     data: "business_location_id.name",
                //     name: "business_location_id.name"
                // },


            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {

            handleBusinessLocationFilter();

        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-filter="input"]');
        filterSearch.addEventListener('keyup', function (e) {

            datatable.column(2).search(e.target.value).draw();
            // datatable.search(e.target.value).draw();
        });
    }

    var handleBusinessLocationFilter = () => {
        const filterStatus = document.querySelector('[data-filter="location"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            console.log(value);
            if (value === 'all') {
                value = '';
            }
            datatable.column(0).search(value).draw();

            // datatable.search(value).draw();

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
            handleBusinessLocationFilter();
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




































