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
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('price_list_detail_menu_link','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">{{ __('product/pricelist.add_pricelist') }}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('price-list-detail') }}" class="text-muted text-hover-primary">{{ __('product/pricelist.pricelist') }}</a>
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
        <form action="{{route('price-list-detail.create')}}" method="POST">
            @csrf
            <!--begin::Card-->
            <div class="card card-p-4 card-flush mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="" class="fs-5 form-label required">{{ __('product/product.name') }}</label>
                            <input type="text" class="form-control form-control-sm " name="name" placeholder="Name" value="">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label required">{{ __('product/pricelist.base_price') }}</label>
                            <select name="base_price" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Select Base Price">
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
                            <select name="currency_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
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
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table table-row-dashed fs-6 gy-4" id="room_added_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-600">
                                    <th class="min-w-150px">Type</th>
                                    <th class="min-w-150px">Product</th>
                                    <th class="min-w-100px">Min Quantity</th>
                                    <th class="min-w-100px">Price</th>
                                    <th class="min-w-150px">Start Date</th>
                                    <th class="min-w-150px">End Date</th>
                                    <th><i class="fa-solid fa-trash text-danger"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-700 x" id="price_list_body">
                                <tr class="price_list_row">
                                    <td>
                                        <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                                            <option></option>
                                            <option value="All">All</option>
                                            <option value="Category">Category</option>
                                            <option value="Product">Product</option>
                                            <option value="Variation">Variations</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-hide-search="false" data-placeholder="Please select">
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm rounded-0" name="price[]" value="00">
                                    </td>
                                    <td>
                                        <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <br>
                    <div class="row mb-8">
                        <div class="d-flex">
                            <button type="button" class="btn btn-light-primary btn-sm me-3" id="add_price_list_row"><i class="fa-solid fa-plus"></i></button>
                            {{-- <button type="button" class="btn btn-light-danger btn-sm disable delete_room_row" id="delete_room_row"><i class="fa-solid fa-trash"></i></button> --}}
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('price-list-detail') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('product/product.save') }}</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
{{-- <script src="{{asset('customJs/roomsale.js')}}"></script> --}}
    @include('App.product.PriceListDetail.js.price_list_detail_js');

@endpush
