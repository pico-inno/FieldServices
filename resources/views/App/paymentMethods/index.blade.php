


@extends('App.main.navBar')

@section('fa_active','active')
@section('payment_method_active', 'active ')
@section('fa_active_show', 'active show')




@section('styles')
        <style>
        </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Payment Methods</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Payment</li>
        <li class="breadcrumb-item text-dark">Methods </li>
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

        <livewire:Payment.PaymentMethodTable />
    </div>
    <!--end::Container-->
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush




































