@extends('App.main.navBar')

@section('products_icon', 'active')
@section('products_show', 'active show')
@section('combokit_menu_link', 'active')

@section('styles')

@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{__('combokit::combokit.edit_combo_kit')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('combokit::combokit.products')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{route('combokit.index')}}" class="text-muted text-hover-primary">{{__('combokit::combokit.combo_kit_list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{__('combokit::combokit.edit')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection



@section('styles')

@endsection



@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="rom_container">
        <div class="container-xxl">
            <form action="{{route('combokit.update', $update_id)}}" method="POST" id="rom_form">
                @csrf
                @method('PUT')
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card">
                        <div class="card-body">
                            @error('details')
                            <div class="alert-danger alert">
                                {{__('combokit::combokit.at_least_one_consume_product_is_required_to_complete_recipe_of_material')}}
                            </div>
                            @enderror

                            <div class="row mb-3 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required" for="">
                                        {{__('combokit::combokit.name')}}
                                    </label>
                                    <div class="fv-row">
                                        <input type="text" class="form-control form-select-sm" id="name" name="name"  value="{{$rom->name}}" placeholder="Combo/Kit Name">
                                    </div>
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-3 fv-row">
                                    <label class="form-label fs-6 fw-semibold required">{{__('combokit::combokit.product')}}</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="product_id" class="form-select form-select-sm rounded-start-0 fw-bold product_name" @if($rom->product !== null) disabled @endif data-kt-select2="true" data-hide-search="false" data-placeholder="Select option" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="true" >
                                                <option></option>
                                                @foreach($products as $product)
                                                    <option value="{{$product['id']}}" data-rom="{{json_encode($product['rom'])}}" data-template_uom_id="{{ $product['uom_id'] }}" @selected(old('product_and_variation_id', $rom->product_id) == $product['id']) data-product="{{ $product['purchase_uom_id'] }}" @if(old('product_id') == $product['id']) selected @endif>{{$product['name']}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="product_id" value="{{$product['id']}}">
                                        </div>

                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required" for="">
                                        {{__('combokit::combokit.rom_type')}}
                                    </label>
                                    <div class="fv-row">
                                        <select name="rom_type" class="form-select form-select-sm fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option></option>
                                            <option value="kit" @selected($rom->rom_type == 'kit')>Kit</option>
                                            <option value="manufacture"  @selected($rom->rom_type == 'manufacture')>Manufacture this product</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="templateUom" name="uom_id" value="{{$rom->uom_id}}">
{{--                                <div class="mb-7 mt-3 col-12 col-md-3">--}}
{{--                                    <label class="form-label fs-6 fw-semibold required" for="">--}}
{{--                                        Quantity--}}
{{--                                    </label>--}}
{{--                                    <div class="fv-row">--}}
{{--                                        <input type="text" class="form-control form-select-sm" id="quantity" name="quantity" value="{{$rom->quantity}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="mb-7 mt-3 col-12 col-md-3">--}}
{{--                                    <label class="form-label fs-7 mb-3 fw-semibold required" for="">--}}
{{--                                        UOM--}}
{{--                                    </label>--}}
{{--                                    <select class="form-select form-select-sm" name="uom_id" id="uom_unit" data-control="select2" data-hide-search="true" data-placeholder="Select purchase UoM">--}}

{{--                                    </select>--}}
{{--                                </div>--}}

                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label mb-4 fs-6 fw-semibold required" for="">
                                        {{__('combokit::combokit.make_as')}}
                                    </label>
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input type="checkbox" name="default_template" value="1"  @if($rom->status != null) checked @endif   class="form-check-input me-3" id="default_template">
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_add_is_active">
                                                <div class="fw-bold">{{__('combokit::combokit.default_template')}}</div>

                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                </div>
                                <!--end::Input group-->

                            </div>
                        </div>
                    </div>
                    <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">
                        <div class="card-body px-5">
                            <div class="row align-items-center mb-8">
                                <div class="col-12">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control form-control-sm rounded-end-3" id="searchInput" placeholder="{{__('combokit::combokit.search')}}">
                                        <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card z-index-1 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-rounded table-striped border gy-4 gs-4" id="rom_consume_table">
                                    <!--begin::Table head-->
                                    <thead class="bg-light rounded-3">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase">
                                        <th class="min-w-125px" style="max-width: 125px">{{__('combokit::combokit.product')}}</th>
                                        <th class="w-200px">{{__('combokit::combokit.quantity')}}</th>
                                        <th class="w-300px">{{__('combokit::combokit.uom')}}</th>
                                        <th class="w-300px">{{__('combokit::combokit.apply_on_variants')}}</th>
                                        <th class="text-center" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600 data-table-body rom-table">
                                    @foreach($romdetails as $key=>$pd)
                                        @php
//                                            $product = $product_variation=$pd->toArray()['product_variation']['product'];
                                            $product_variation=$pd->toArray()['product_variation'];
                                            $product = $product_variation['product'];
                                            $uom_price=$pd->uom_price;
                                        @endphp
                                        <tr class='cal-gp'>
                                            <td class="d-none">
                                                <input type="hidden" value="{{$pd->id}}" name="consume_details[{{$key}}][rom_detail_id]">
                                                <input type="hidden" value="{{$pd->component_variation_id}}" name="consume_details[{{$key}}][component_variation_id]">
                                                <input type="hidden" value="{{$pd->applied_variation_id}}" name="consume_details[{{$key}}][applied_variation_id]">
                                            </td>
                                            <td>
                                                <span  class="text-gray-600 text-hover-primary">   {{$product['name']}}</span>
                                                @if(isset($product_variation['variation_template_value']))
                                                    <span class="my-2 d-block">
                                                            ({{$product_variation['variation_template_value']['name']}})
                                                        </span>
                                                @endif
                                            </td>
                                            <td class="fv-row">
                                                <input type="text" class="form-control form-control-sm mb-1 purchase_quantity input_number" placeholder="Quantity" name="consume_details[{{$key}}][quantity]" value="{{number_format($pd->quantity, 2)}}">
                                            </td>
                                            <td>
                                                <select  name="consume_details[{{$key}}][uom_id]" class="form-select form-select-sm unit_id " data-kt-repeater="uom_select" data-hide-search="false" data-placeholder="Select unit">
                                                    @foreach ($product['uom']['unit_category']['uom_by_category'] as $unit)
                                                        <option value="{{$unit['id']}}" @selected($unit['id']==$pd['uom_id'])>{{$unit['name']}}</option>
                                                    @endforeach
                                                </select>

                                            </td>
                                            <td id="applied_variation_column">
                                                <select  name="consume_details[{{$key}}][applied_variation_id]" class="form-select form-select-sm applied-variation" data-kt-repeater="applied_variation"  data-kt-repeater="select2" data-hide-search="false" data-allow-clear="true"  data-placeholder="Select Option">
                                                    <option></option>
                                                    @if($applied_variation_data !== null)
                                                    @if($applied_variation_data['has_variation'] != 'single')
                                                    @foreach ($applied_variation_data['product_variations'] as $item)
                                                        <option value="{{$item['id']}}" @selected($item['id'] == $pd['applied_variation_id']) >{{$item['variation_template_value']['variation_template']['name']}}: {{$item['variation_template_value']['name']}}</option>
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                </select>
                                            </td>

                                            <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow" ></i></th>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-12 text-center mt-5 mb-5">
                    <button type="submit" class="btn btn-primary btn" data-kt-purchase-action="submit" name="save" value='save'>{{__('combokit::combokit.update')}}</button>
                </div>
            </form>
        </div>
        <!--end::Container-->
    </div>

    <div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
@endsection

@push('scripts')
    <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
    @include('combokit::JS.calBoM')
    <script>

        $(document).ready(function (){
            $('[data-kt-repeater="uom_select"]').select2();
        });
        $(document).ready(function (){
            $('[data-kt-repeater="applied_variation"]').select2();
        });

        //Quick Add New Product
        $(document).on('click', '.productQuickAdd', function(){
            $url=$(this).data('href');

            loadingOn();
            $('#quick_add_product_modal').load($url, function() {
                $(this).modal('show');
                loadingOff();
            });
        });
        //Quick Add New Product
    </script>
@endpush


