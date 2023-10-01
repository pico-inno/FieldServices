

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
.wallet{
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1000' height='400' preserveAspectRatio='none' viewBox='0 0 1000 400'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1514%26quot%3b)' fill='none'%3e%3crect width='1000' height='400' x='0' y='0' fill='url(%26quot%3b%23SvgjsLinearGradient1515%26quot%3b)'%3e%3c/rect%3e%3cpath d='M1000 0L501.35 0L1000 55.01z' fill='rgba(255%2c 255%2c 255%2c .1)'%3e%3c/path%3e%3cpath d='M501.35 0L1000 55.01L1000 189.54L358.95000000000005 0z' fill='rgba(255%2c 255%2c 255%2c .075)'%3e%3c/path%3e%3cpath d='M358.95000000000005 0L1000 189.54L1000 282.14L159.37000000000003 0z' fill='rgba(255%2c 255%2c 255%2c .05)'%3e%3c/path%3e%3cpath d='M159.37 0L1000 282.14L1000 302.81L74.7 0z' fill='rgba(255%2c 255%2c 255%2c .025)'%3e%3c/path%3e%3cpath d='M0 400L64.11 400L0 256.78999999999996z' fill='rgba(0%2c 0%2c 0%2c .1)'%3e%3c/path%3e%3cpath d='M0 256.78999999999996L64.11 400L462.43 400L0 164.37999999999997z' fill='rgba(0%2c 0%2c 0%2c .075)'%3e%3c/path%3e%3cpath d='M0 164.38L462.43 400L769.84 400L0 158.04999999999998z' fill='rgba(0%2c 0%2c 0%2c .05)'%3e%3c/path%3e%3cpath d='M0 158.04999999999998L769.84 400L908.8100000000001 400L0 145.79z' fill='rgba(0%2c 0%2c 0%2c .025)'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1514'%3e%3crect width='1000' height='400' fill='white'%3e%3c/rect%3e%3c/mask%3e%3clinearGradient x1='85%25' y1='137.5%25' x2='15%25' y2='-37.5%25' gradientUnits='userSpaceOnUse' id='SvgjsLinearGradient1515'%3e%3cstop stop-color='rgba(14%2c 42%2c 71%2c 1)' offset='0'%3e%3c/stop%3e%3cstop stop-color='rgba(0%2c 158%2c 247%2c 1)' offset='1'%3e%3c/stop%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e");
    /* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1000' height='400' preserveAspectRatio='none' viewBox='0 0 600 300'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1036%26quot%3b)' fill='none'%3e%3crect width='600' height='300' x='0' y='0' fill='url(%26quot%3b%23SvgjsLinearGradient1037%26quot%3b)'%3e%3c/rect%3e%3cpath d='M600 0L573.4 0L600 81.1z' fill='rgba(255%2c 255%2c 255%2c .1)'%3e%3c/path%3e%3cpath d='M573.4 0L600 81.1L600 129.1L256.96 0z' fill='rgba(255%2c 255%2c 255%2c .075)'%3e%3c/path%3e%3cpath d='M256.96 0L600 129.1L600 229.92L251.39 0z' fill='rgba(255%2c 255%2c 255%2c .05)'%3e%3c/path%3e%3cpath d='M251.39 0L600 229.92L600 278.90999999999997L126.02999999999999 0z' fill='rgba(255%2c 255%2c 255%2c .025)'%3e%3c/path%3e%3cpath d='M0 300L245.81 300L0 205.22z' fill='rgba(0%2c 0%2c 0%2c .1)'%3e%3c/path%3e%3cpath d='M0 205.22L245.81 300L321.89 300L0 99.42999999999999z' fill='rgba(0%2c 0%2c 0%2c .075)'%3e%3c/path%3e%3cpath d='M0 99.43L321.89 300L352.95 300L0 72.32000000000001z' fill='rgba(0%2c 0%2c 0%2c .05)'%3e%3c/path%3e%3cpath d='M0 72.32L352.95 300L440.91999999999996 300L0 32.13999999999999z' fill='rgba(0%2c 0%2c 0%2c .025)'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1036'%3e%3crect width='600' height='300' fill='white'%3e%3c/rect%3e%3c/mask%3e%3clinearGradient x1='100%25' y1='50%25' x2='0%25' y2='50%25' gradientUnits='userSpaceOnUse' id='SvgjsLinearGradient1037'%3e%3cstop stop-color='%230e2a47' offset='0'%3e%3c/stop%3e%3cstop stop-color='rgba(0%2c 158%2c 247%2c 1)' offset='1'%3e%3c/stop%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e"); */
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
    <li class="breadcrumb-item text-muted">Payment</li>
    <li class="breadcrumb-item text-dark">Account</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="row align-items-stretch align-self-stretch mb-10">
            <div class="col-12 col-lg-5">
               <div class="card py-2 px-5 bg-primary wallet">
                    <span class="text-center fw-bold mt-3 text-white">
                        <i class="las la-wallet text-white fs-2 me-2"></i>
                        {{$account->name}}
                    </span>
                    <div class="fs-2hx mt-5  fw-bold text-white">
                         <div class=" fs-7 fw-semibold me-4">Balance :</div>
                         {{price($account->current_balance,$account->currency->id)}}
                    </div>
                    <div class="action_btn  mt-10 text-start mb-5">
                        <button class="btn btn-light btn-sm px-4 py-2 fs-8 mt-2" id="transfer" data-href="{{route('paymentTransaction.transfer',$account->id)}}" ><i class="fa-solid fa-right-left fs-6 me-1 "></i> Transfer </button>
                        <button class="btn btn-light btn-sm px-4 py-2 fs-8 mt-2" id="withdrawl" data-href="{{route('paymentTransaction.withdrawl',$account->id)}}"><i class="fa-solid fa-upload fs-6 me-1"></i> WithDrawl </button>
                        <button class="btn btn-light btn-sm px-4 py-2 fs-8 mt-2" id="deposit" data-href="{{route('paymentTransaction.deposit',$account->id)}}"><i class="fa-solid fa-download fs-6 me-1"></i> Deposit </button>
                    </div>
               </div>
            </div>
            <div class="col-12  col-lg-7 align-self-stretch">
                <div class="card h-100">
                    <div class="card-body pt-9 pb-0">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-5 d-none">
                                    <!--begin::User-->
                                    <div class="d-flex my-2 justify-content-center align-items-center">
                                        <!--begin::Name-->
                                        <a class="text-primary  fs-5 fw-bold me-1 d-block ">
                                            Payment Account
                                        </a>
                                    </div>
                                    <!--end::User-->
                                    <!--begin::Actions-->
                                    <div class="d-flex my-2">
                                        <a class="btn btn-sm btn-primary me-2" id="edit"
                                            data-href="{{route('paymentAcc.edit',$account->id)}}">Edit</a>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Title-->
                                <div class="row g-10 mb-3 justify-content-between">
                                    <div class="col-12 col-md-6">
                                        <div class="group fs-7 text-gray-600 fw-semibold">
                                            <div>
                                                Account Number
                                            </div>
                                            <div class="d-block fw-bold fs-7  mt-3 mb-5">
                                                <span>
                                                    <i class="fa-solid fa-hashtag fs-9"></i>
                                                </span>
                                                <span class="text-gray-700">
                                                    {{$account->account_number}}
                                                </span>
                                            </div>
                                            <div class="d-block fw-bold fs-7 text-gray-800 mt-3">
                                                <div class="text-gray-600 mb-4">
                                                   Description:
                                                </div>
                                                <span class="text-gray-700 mt-3 d-block">
                                                    {{$account->description}}
                                                </span>
                                            </div>
                                        </div>
                                        <table class="table  table-layout-fixed  table-row-bordered d-none">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                        <span class="fw-semibold fs-7 text-gray-600">Account Number:</span>
                                                    </th>
                                                    <td class="text-end">
                                                        <span class="fw-bold fs-7 text-gray-800">{{$account->account_number}}</span>
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <th class="text-start">
                                                        <span class="fw-semibold fs-7 text-gray-600">Account Type:</span>
                                                    </th>
                                                    <td class="text-end">
                                                        <span class="fw-bold fs-7 text-gray-800">{{$account->account_type}}</span>
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <th class="text-start">
                                                        <span class="fw-semibold fs-7 text-gray-600">Opening Amount:</span>
                                                    </th>
                                                    <td class="text-end">
                                                        <span class="fw-bold fs-7 text-gray-800">{{price($account->opening_amount,$account->currency->id)}}</span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 col-md-6 text-end">
                                        <div class="">
                                            <a class="btn btn-sm btn-primary me-2 px-4 py-2 fs-8 mt-2" id="edit"
                                                data-href="{{route('paymentAcc.edit',$account->id)}}">Edit</a>
                                        </div>
                                        {{-- <div class="d-flex justify-content-between d-none">
                                            <div class="fw-semibold fs-6 text-gray-600 mb-3">Description :</div>
                                            <div class="">
                                                <a class="btn btn-sm btn-primary me-2 px-4 py-2 fs-8 mt-2" id="edit" data-href="{{route('paymentAcc.edit',$account->id)}}">Edit</a>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold fs-7 text-gray-800">{{$account->description}}</div>
                                        </div> --}}
                                    </div>
                                    <div class="col-12 col-md-6 d-none">
                                        <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                        <span class="fw-semibold fs-7 text-gray-600">Opening Amount:</span>
                                                    </th>
                                                    <td class="text-end">
                                                        <span
                                                            class="fw-bold fs-7 text-gray-800">{{price($account->opening_amount,$account->currency->id)}}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                        <span class="fw-semibold fs-7 text-gray-600">Current Balance:</span>
                                                    </th>
                                                    <td class="text-end">
                                                        <span
                                                            class="fw-bold fs-7 text-gray-800">{{price($account->current_balance,$account->currency->id)}}
                                                        </span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

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


<div class="modal  fade " tabindex="-1" id="withdrawlModal">

</div>
<div class="modal  fade " tabindex="-1" id="depositModal">

</div>
<div class="modal  fade " tabindex="-1" id="transferModal">

</div>
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







