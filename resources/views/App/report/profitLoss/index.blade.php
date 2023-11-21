@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('plReport_here_show', 'here show')
@section('plReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Profit / Loss</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="row align-items-stretch  align-self-stretch mb-5 g-5">
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-success"></i>
                        <span class="text-success">Net Profit</span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold ">
                        {{price($netProfit)}}
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">
                            (Total Sale Amount + Closing Stock Ammount) &minus;<br>(Total Purchase Amount + Total Opening Stock Amount+ Total Expense Amount)</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-primary"></i>
                        {{-- <i class="las la-wallet  fs-2 me-2"></i> --}}
                        <span class="text-primary">
                            Gross Profit
                        </span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold ">
                        {{price($grossProfit)}}
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">Total Sale Amount- Total Purchase Amount<br> &nbsp;</div>
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
                                    <td class="fw-bold fs-7">Total Opening Stock Amount</td>
                                    <td class="text-end fw-bold">{{price(totalOSAmount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Amount</td>
                                    <td class="text-end fw-bold">{{price(totalPurchaseAmount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Expense Amount</td>
                                    <td class="text-end fw-bold">{{price(totalExpenseAmount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold">{{price(totalExpenseAmount() + totalOSAmount() +totalPurchaseAmount())}}</td>
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
                                    <td class="fw-bold fs-7">Total Closing Stock Amount</td>
                                    <td class="text-end fw-bold">{{price(closingStocks())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Amount</td>
                                    <td class="text-end fw-bold">{{price(totalSaleAmount())}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Other Income Amount</td>
                                    <td class="text-end fw-bold">{{price(0)}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold">{{price(closingStocks() + totalSaleAmount() )}}</td>
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
