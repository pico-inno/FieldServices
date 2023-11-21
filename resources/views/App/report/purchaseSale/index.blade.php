@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('spReport_here_show', 'here show')
@section('spReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Purchase & Sale</li>
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
                        <i class="fa-solid fa-cart-shopping fs-6 me-2 text-success"></i>
                        <span class="text-success">Purchase</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Total Purchase Amount</div>
                        {{price(totalPurchaseAmount())}}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        {{-- <i class="fa-solid fa-shop fs-6 me-2 text-primary"></i> --}}
                        <i class="fa-solid fa-square-minus fs-6 me-2 text-info"></i>
                        {{-- <i class="las la-wallet  fs-2 me-2"></i> --}}
                        <span class="text-info">
                           (Sale - Purchase) Amount
                        </span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold mt-2">&nbsp;</div>
                        {{price(totalSaleAmount()-totalPurchaseAmount())}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        <i class="fa-solid fa-shop fs-6 me-2 text-primary"></i>
                        {{-- <i class="las la-wallet  fs-2 me-2"></i> --}}
                        <span class="text-primary">
                            Sale
                        </span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold mt-2">Total Sale Amount</div>
                        {{price(totalSaleAmount())}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch align-self-stretch mb-10 g-5">
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 ">
                    <div class="table-responsive">
                        <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Amount Without Discount</td>
                                    <td class="text-end fw-bold">{{price(totalPurchaseAmountWithoutDis())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total Purchase Discount</td>
                                    <td class="text-end fw-bold">{{price(totalPurchaseDiscountAmt())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total Purchase Amount</td>
                                    <td class="text-end fw-bold">{{price(totalPurchaseAmount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Due Amount</td>
                                    <td class="text-end fw-bold">{{price(totalPurchaseDueAmount())}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <div class="table-responsive">
                        <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Amount Without Discount</td>
                                    <td class="text-end fw-bold">{{price(totalSaleAmountWithoutDis())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Discount</td>
                                    <td class="text-end fw-bold">{{price(totalSaleDiscount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold">{{price( totalSaleAmount() )}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Due Amount</td>
                                    <td class="text-end fw-bold">{{price(totalSaleDueAmount())}}</td>
                                </tr>
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
@endpush
