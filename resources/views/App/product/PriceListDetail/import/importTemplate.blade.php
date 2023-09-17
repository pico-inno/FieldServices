@extends('App.main.navBar')
@section('styles')
<style>
    /* .custom-select2 {
        width: 135px;
    }

    #delete_room_row {
        cursor: not-allowed;
        opacity: 0.5;
    } */
</style>
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('import_price_list_detail_menu_link', 'active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">{{ __('product/pricelist.add_pricelist') }}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('price-list-detail') }}" class="text-muted text-hover-primary">{{
            __('product/pricelist.pricelist') }}</a>
    </li>
    <li class="breadcrumb-item text-dark">{{ __('product/product.add') }}</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="{{route('priceListImport')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!--begin::Card-->
            <div class="card card-p-4 card-flush mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="" class="fs-5 form-label required">{{ __('product/product.name') }}</label>
                            <input type="text" class="form-control form-control-sm " name="name" placeholder="Name"
                                value="">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror

                            {{-- being: hidden price list type --}}
                            <input type="hidden" name="price_list_type" value="product">
                            {{-- end: hidden price list type --}}
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label required">{{ __('product/pricelist.base_price') }}</label>
                            <select name="base_price" class="form-select form-select-sm fs-7" id="base_price"
                                data-control="select2" data-placeholder="Select Base Price">
                                <option></option>
                                <option value="0">{{ __('product/pricelist.cost') }}</option>
                                @foreach($price_lists as $price_list)
                                <option value="{{ $price_list->id }}">{{ $price_list->name }}</option>
                                @endforeach
                            </select>
                            @error('base_price')
                            <div class="text-danger my-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8 ">
                            <label for="" class="form-label required">{{ __('product/pricelist.currency') }}</label>
                            <select name="currency_id" id="currency_id" class="form-select form-select-sm fs-7"
                                data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" @selected($currency->id==
                                    $businessSetting->currency_id)>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                            @error('currency_id')
                            <div class="text-danger my-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-12">
                            <label for="" class="fs-5 form-label">{{ __('product/category.description') }}</label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>{{ __('product/pricelist.import_pricelist') }}</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row mb-5">
                        <div class="d-flex align-items-center gap-3 col-md-6 col-md-offset-3">
                            <div class="">
                                <label class=" form-label" for="formFileSm">{{ __('product/import-product.file_to_import')
                                    }}</label>

                                <input class="form-control form-control-sm" id="formFileSm" type="file"
                                    name="importPriceList" />
                                @error('import-products')

                                @enderror
                            </div>
                            <div class="">
                                <div class="mt-5">
                                    {{-- <a href="{{route('downloadPrceListExcel')}}" download class="btn btn-light-primary btn-sm">
                                        <i class="fas fa-download"></i>{{ __('product/import-product.download_template_file') }}
                                    </a> --}}
                                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        {{ __('product/import-product.download_template_file') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-success btn-sm">{{ __('product/import-product.submit') }}</button>
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
<!-- Vertically centered modal -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Choose Template</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap justify-content-center gap-5">
                    <div class="">
                        <a href="{{route('downloadPrceListExcel')}}" download class="btn btn-light-primary btn-sm">
                            <i class="fas fa-download"></i>{{ __('product/import-product.download_template_file') }}
                        </a>
                    </div>
                    <div class="">
                        <a href="{{route('downloadPrceListExcel')}}" download class="btn btn-light-success btn-sm">
                            <i class="fas fa-download"></i>Download Template ForPirce List By Product PriceList
                        </a>
                    </div>
                    <a href="{{route('downloadPrceListExcel')}}" download class="btn btn-light-warning btn-sm">
                        <i class="fas fa-download"></i>Download Template ForPirce List By Product Variation
                    </a>
                    <a href="{{route('downloadPrceListExcel')}}" download class="btn btn-light-danger btn-sm">
                        <i class="fas fa-download"></i>Download Template For Pirce List By Category
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    error( @json($error) )
</script>
@endforeach
@endif

@include('App.product.PriceListDetail.js.price_list_detail_js');
@endpush
