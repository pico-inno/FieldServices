@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')

@section('styles')
    <style>
        /* Add other styles as needed */
    </style>

    <!-- Include Bootstrap Stylesheet -->
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Invoice Template Detail</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">

        <li class="breadcrumb-item text-muted">Invoice Templates</li>
        <li class="breadcrumb-item text-dark">Detail</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="location">
            <div class="card">
                <div class="card-body">
                    <div class="container mb-5 mt-3">
                        <div class="row d-flex align-items-baseline">
                            <div class="col-8">
                                <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p>
                            </div>
                            <div class="col-4 float-end">
                                <a class="btn btn-light text-capitalize border-0" onclick="print()"
                                    data-mdb-ripple-color="dark"><i class="fas fa-print text-primary"></i> Print</a>
                                <a class="btn btn-light text-capitalize"
                                    onclick="convertToImage('print-section','TesingImage')" data-mdb-ripple-color="dark"><i
                                        class="fa-solid fa-image text-warning"></i>Generate
                                    Image</a>
                            </div>
                            <hr>
                        </div>
                        @if ($type === 'purchase')
                            <x-invoice.purchase-layout :layout="$layout" :tabletext="$table_text" :purchase="$purchase" :location="$location" />
                        @else
                            <x-invoice.sell-layout :layout="$layout" :tabletext="$table_text" :sale="$sale" :location="$location" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="{{ asset('customJs/invoice/print.js') }}"></script>
@endpush
