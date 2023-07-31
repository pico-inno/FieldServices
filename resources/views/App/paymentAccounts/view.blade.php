

@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>
    .loading{
        left: 50%;
        top: 40%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        color: #000000df;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        padding: 10px;
        position:absolute;
        font-size: 13px;
        font-weight: 300;
    }
    tr td:first-child{
    text-align: start;
}
</style>
@endsection

@section('fa_active','active')
{{-- @section('payment_account_active', 'active ') --}}
@section('fa_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Payment Account</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Paymnet</li>
    <li class="breadcrumb-item text-dark">Account</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-5 ">
                            <!--begin::User-->
                            <div class="d-flex my-2 justify-content-center align-items-center">
                                <!--begin::Name-->
                                <a  class="text-primary  fs-5 fw-bold me-1 d-block ">
                                    Payment Account
                                </a>
                            </div>
                            <!--end::User-->
                            <!--begin::Actions-->
                            <div class="d-flex my-2">
                                <a   class="btn btn-sm btn-primary me-2" id="edit" data-href="{{route('paymentAcc.edit',$account->id)}}">Edit</a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <div class="row g-10 mb-3 gap-2 justify-content-between">
                               <div class="col-4">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Account Name:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$account->name}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Account Type:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$account->account_type}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Account Number:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$account->account_number}}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                    </table>
                               </div>
                                <div class="col-4">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Opening Amount:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800">{{number_format($account->opening_amount,2,'.')}} {{$account->currency->symbol}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Current Balance:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800">{{number_format($account->current_balance,2,'.')}} {{$account->currency->symbol}}</span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                    </table>
                                </div>
                                <div class="col-3 mt-13">
                                    <div class="">
                                        <div class="fw-semibold fs-6 text-gray-600 mb-3">Description :</div>
                                        <div class="fw-semibold fs-7 text-gray-800">{{$account->description}}</div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="d-flex justify-content-between align-items-start flex-wrap mx-10 mt-5">
                <!--begin::User-->
                <div class="d-flex  justify-content-center align-items-center">
                    <!--begin::Name-->
                    <a  class="text-primary  fs-5 fw-bold me-1 d-block ">
                        Transaction History
                    </a>
                </div>
                <!--end::User-->
                <!--begin::Actions-->
                <div class="d-flex ">
                    <input type="text" class="form-control form-control-sm" placeholder="Search " data-filter="input">
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="paymentTransaction">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-capitalize gs-0">
                            <th class="text-start min-w-100px">Payment Voucher No</th>
                            <th class="text-end pe-3 min-w-100px">Pay Date</th>
                            <th class="text-end pe-3 min-w-100px">Transaction Type</th>
                            <th class="text-end pe-3 min-w-100px">Transaction Reference No</th>
                            <th class="text-end pe-3 min-w-100px">Paymet Method</th>
                            <th class="text-end pe-3 min-w-100px">Paymet Account</th>
                            <th class="text-end pe-3 min-w-100px">Paymet Type</th>
                            <th class="text-end pe-3 min-w-100px">Paymet Amount</th>
                            <th class="text-end pe-3 min-w-100px">note</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600 text-start fs-7 text-end">
                        <tr>
                            <!--begin::Product ID-->
                            <td class="text-start">#XGY-356</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">02 Apr, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">Opening</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                SVN-0005
                            </td>
                            <td class="text-end">
                                Cash
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">KBZ Acc</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class=" fw-bold text-danger">Credit</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">50,000.00 $</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">Note </span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Product ID-->
                            <td class="text-start">#XGY-357</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">02 Apr, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">Sale</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                SVN-0008
                            </td>
                            <td class="text-end">
                                Wave
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">Wave ACC</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class=" fw-bold text-success">Debit</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">50,000.00 $</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">Note </span>
                            </td>
                            <!--end::Qty-->
                        </tr>
                        <tr>
                            <!--begin::Product ID-->
                            <td class="text-start">#XGY-358</td>
                            <!--end::Product ID-->
                            <!--begin::Date added-->
                            <td class="text-end">03 Apr, 2023</td>
                            <!--end::Date added-->
                            <!--begin::Price-->
                            <td class="text-end">Purchase</td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td class="text-end">
                                PVN-0005
                            </td>
                            <td class="text-end">
                                Cash
                            </td>
                            <!--end::Status-->
                            <!--begin::Qty-->
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">KBZ Acc</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class=" fw-bold text-danger">Credit</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">50,000.00 $</span>
                            </td>
                            <td class="text-end" data-order="58">
                                <span class="text-dark fw-bold">Note </span>
                            </td>
                            <!--end::Qty-->
                        </tr>

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>


    </div>
    <!--end::container-->
</div>
<!--end::Content-->



<div class="modal modal-lg fade" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="editModal">

</div>

@endsection
@push('scripts')

<script>
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
    var filterMonth;
    var filterPayment;
    var table

    // Private functions
    var initCustomerList = function () {
        let render=function(data){
                return data ?? '-';
            }
        datatable = $(table).DataTable({
            'columnDefs': [
                { orderable: false, targets: 0 },
            ],
            order: [[0, ' ']],
            processing: true,
            serverSide: true,
               ajax: {
                url: '/payment-transactions/{{$account->id}}/get/list/',
            },
            columns: [

                {
                    name:'payment_voucher_no',
                    data:'payment_voucher_no'
                },
                {
                    name:'payment_date',
                    data:'payment_date',
                    render,

                },
                {
                    name:'transaction_type',
                    data:'transaction_type',
                    render,
                },
                {
                    name: 'transaction_ref_no',
                    data: 'transaction_ref_no',
                    render,
                },{
                    name:'payment_method',
                    data:'payment_method',
                    render,
                },

                {
                    name:'payment_account.name',
                    data: 'payment_account.name',
                    render,
                },
                {
                    name: 'payment_type',
                    data: 'payment_type',
                    render,
                },
                {
                    name: 'payment_amount',
                    data: 'payment_amount',
                    render,
                },
                {
                    name:'note',
                    data: 'note',
                }

            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-filter="input"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
            // datatable.search(e.target.value).draw();
        });
    }


    // Public methods
    return {
        init: function () {
            table = document.querySelector('#paymentTransaction');

            if (!table) {
                return;
            }

            initCustomerList();
            // initToggleToolbar();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});


</script>
@endpush







