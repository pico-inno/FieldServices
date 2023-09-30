@extends('App.main.navBar')
@section('styles')
<style>
    /* .custom-select2 {
        width: 135px;
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
@section('price_list_detail_menu_link', 'active')

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
        <form action="{{route('price-list-detail.create')}}" id="priceList_form" method="POST">
            @csrf
            @php
                // dd(session('noti'));
            @endphp
            <!--begin::Card-->
            <div class="card card-p-4 card-flush mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label for="" class="fs-5 form-label required">{{ __('product/product.name') }}</label>
                            <input type="text" class="form-control form-control-sm " name="name" placeholder="Name" value="{{$priceListData['name'] ?? '' }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                           {{-- being: hidden price list type --}}
                           <input type="hidden" name="price_list_type" value="product">
                           {{-- end: hidden price list type --}}
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label class="form-label required">{{ __('product/pricelist.base_price') }}</label>
                            <select name="base_price" class="form-select form-select-sm fs-7" id="base_price" data-control="select2" data-placeholder="Select Base Price">
                                <option></option>
                                <option value="0" selected>{{ __('product/pricelist.cost') }}</option>
                                @foreach($price_lists as $price_list)
                                    <option value="{{ $price_list->id }}" @selected($priceListData ? $priceListData['base_price'] == $price_list->id:false)>{{ $price_list->name }}</option>
                                @endforeach
                            </select>
                            @error('base_price')
                                <div class="text-danger my-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8 ">
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label for="" class="form-label required">{{ __('product/pricelist.currency') }}</label>
                            <select name="currency_id" id="currency_id"  class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" @selected(isset($priceListData['currency_id']) ? $priceListData['currency_id'] == $currency->id:false) >{{ $currency->name }}</option>
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
                                    <th class="min-w-150px required">Apply Type</th>
                                    <th class="min-w-150px required">Apply Value</th>
                                    <th class="min-w-100px required">Min Quantity</th>
                                    <th class="min-w-100px required">Cal Type</th>
                                    <th class="min-w-100px required">Cal Value</th>
                                    <th class="min-w-150px">Start Date</th>
                                    <th class="min-w-150px">End Date</th>
                                    <th><i class="fa-solid fa-trash text-danger"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-700 x" id="price_list_body">
                                {{-- This Condition is purpose for the dat
                                a that will come from excel import --}}
                                @if ($PriceListDetaildata)
                                    @foreach ($PriceListDetaildata as $pl)
                                    {!! App\Http\Controllers\Product\UI\PriceListDetailsUI::detailsUI($pl) !!}

                                    {{-- <tr class="price_list_row">
                                        <td>
                                            <div class="fv-row">
                                                <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="true" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="All">All</option>
                                                    <option value="Category" @selected($pl['applied_type']=='Category')>Category</option>
                                                    <option value="Product" @selected($pl['applied_type']=='Product')>Product</option>
                                                    <option value="Variation" @selected($pl['applied_type']=='Variation')>Variations</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                @php
                                                    if($pl['applied_type']=='Category'){
                                                        $applyValues=App\Models\Product\Category::get();
                                                    }else if($pl['applied_type']=='Product'){
                                                        $applyValues=App\Models\Product\Product::get();
                                                    }else if($pl['applied_type']=='Variation'){
                                                        $applyValues=App\Http\Controllers\Product\PriceListDetailController::getVariationOptions();
                                                    }
                                                @endphp
                                                <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="false" data-placeholder="Please select">
                                                    @if($pl['applied_type']=='Variation')
                                                        @foreach ($applyValues as $av)
                                                            <option value="{{$av->id}}" @selected($av->id==$pl['applied_value'])>{{$av->product_variation_name}}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($applyValues as $av)
                                                            <option value="{{$av->id}}" @selected($av->id==$pl['applied_value'])>{{$av->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="{{$pl['min_qty']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="true" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="fixed" @selected($pl['cal_type']=='fixed' ||$pl['cal_type']=='fix' )>Fix</option>
                                                    <option value="percentage" @selected($pl['cal_type']=='percentage')>Percentage</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]" value="{{$pl['cal_value']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
                                                placeholder="Select date" autocomplete="off" />
                                        </td>
                                        <td>
                                            <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
                                                placeholder="Select date" autocomplete="off" />
                                        </td>
                                        <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i
                                                    class="fa-solid fa-trash"></i></button></td>
                                    </tr> --}}
                                    @endforeach
                                @else
                                    <tr class="price_list_row">
                                        <td>
                                            <div class="fv-row">
                                                <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="true" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="All">All</option>
                                                    <option value="Category">Category</option>
                                                    <option value="Product">Product</option>
                                                    <option value="Variation">Variations</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="false" data-placeholder="Please select">

                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                                    data-hide-search="true" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="fixed">Fix</option>
                                                    <option value="percentage" selected>Percentage</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]" value="">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
                                        </td>
                                        <td>
                                            <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
                                        </td>
                                        <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i class="fa-solid fa-trash"></i></button></td>
                                    </tr>
                                @endif

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
                    <button type="submit" class="btn btn-primary" id="submit">{{ __('product/product.save') }}</button>
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
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>


    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                error( @json($error) )
            </script>
        @endforeach
    @endif
    <script>
        let priceLists=@json(request()['data'] ?? []);
    </script>

    @include('App.product.PriceListDetail.js.price_list_detail_js');
@endpush
