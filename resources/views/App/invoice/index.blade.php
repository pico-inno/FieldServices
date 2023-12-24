@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')
@section('invoice_templates_active', 'active')

@section('styles')

@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Invoice Templates</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        {{-- <li class="breadcrumb-item text-muted">
            <a href="../../demo7/dist/index.html" class="text-muted">Home</a>
        </li> --}}
        <li class="breadcrumb-item text-muted">Invoices</li>
        <li class="breadcrumb-item text-dark">Templates</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="location">
            <div class="row">
                @foreach ($layouts as $layout)
                    <div class="col-4">
                        <div class="card shadow p-3">{{ $layout->name }}
                            <a href="{{ route('invoice.detail',$layout->id) }}" class="btn btn-success btn-sm">View</a>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@push('scripts')
@endpush
