@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('itemReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Items Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Items</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="row align-items-stretch  align-self-stretch mb-5 g-5">
            <div class="col-12 col-lg-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-boxes-stacked fs-6 me-2"></i>
                        <span class="">Items Count</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Products Count (Including Its Variation)</div>
                        {{$productCount}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-boxes-stacked fs-6 me-2"></i>
                        <span class="">Items Count without Variation</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Products Count (Excluding Its Variation)</div>
                        {{$productCountExcVaria}}
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-rounded table-striped border gy-5 gs-5" id="itemReportTable">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-175px">Product Sku</th>
                                    <th class="min-w-175px">Purchase Date</th>
                                    <th class="min-w-175px">Purchase Voucher No</th>
                                    <th class="min-w-175px">Supplier</th>
                                    <th class="min-w-175px">Purchase Price</th>
                                    <th class="min-w-175px">Customer</th>
                                    <th class="min-w-175px">Location</th>
                                    <th class="min-w-175px">Sell Qty</th>
                                    <th class="min-w-175px">Selling price</th>
                                    <th class="min-w-175px">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div>
    <!--end::container-->
</div>
<!--end::Content-->






@endsection
@push('scripts')

<script>
    "use strict";

// Class definition
    var datatable;
    var KTCustomersList = function () {
        // Define shared variables
        var filterMonth;
        var filterPayment;
        var table

        // Private functions
        var initCustomerList = function () {
            // Set date data order
            const tableRows = table.querySelectorAll('tbody tr');
            // Init datatable --- more info on datatables: https://datatables.net/manual/
            let columns = [
                {
                    data: 'product',
                    name: 'product',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'product_sku',
                    name: 'product_sku',
                    // searchable: false,
                    // orderable: false,
                },
                {
                    name: 'purchase_date',
                    data: 'purchase_date',
                },
                {
                    data: 'purchase_voucher_no',
                    name: 'purchase_voucher_no'
                },{
                    data: 'supplier',
                    name: 'supplier'
                },
                {
                    data: 'purchase_price',
                    name: 'purchase_price'
                },
                {
                    data: "customer_name",
                    name: "customer_name"
                },

                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'sell_qty',
                    name: 'sell_qty'
                },

                {
                    data: 'selling_price',
                    name: 'selling_price'
                },

                {
                    data: 'sale_subtotal',
                    name: 'sale_subtotal'
                }

            ];

            datatable = $(table).DataTable({
                pageLength: 30,
                lengthMenu: [10, 20, 30, 50,40,80],
                order: [[1, 'desc']],
                processing: true,
                serverSide: true,
                columns,
                ajax: {
                    url: '/items/report/data'
                }
            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {
            });
        }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#itemReportTable');

            if (!table) {
                return;
            }

            initCustomerList();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});



</script>
@endpush
