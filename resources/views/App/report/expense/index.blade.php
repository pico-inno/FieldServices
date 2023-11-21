@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('expenseReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Expense Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Expense</li>
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
                        <i class="fa-solid fa-hand-holding-dollar fs-6 me-2"></i>
                        <span class="">Expense</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Total Expense Amount</div>
                        {{price(totalExpenseAmount())}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-hand-holding-dollar fs-6 me-2"></i>
                        <span class="">Expense due</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Total Expense Due Amount</div>
                        {{price(totalExpenseDueAmount())}}
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
