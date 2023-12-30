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
                <div class="card p-4">
                    <h4 class="text-muted p-3">Invoice Layouts</h4>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layouts as $layout)
                                <tr>
                                    <td><a href="{{ route('invoice.detail',$layout->id) }}"> {{ $layout->name }}</a></td>
                                    <td>{{ $layout->created_at->format('j-F-Y') }}</td>
                                    <td>
                                        <a href="{{ route('invoice.edit',$layout->id) }}" class="table-actions btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pencil-alt"></i>
                                        </a>
                                        <a href="" class="table-actions btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
@endpush
