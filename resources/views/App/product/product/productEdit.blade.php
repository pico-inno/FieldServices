@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
 <style>
    .box {
        display: none;
    }
 </style>
@endsection

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/product.edit_product') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('products') }}" class="text-muted text-hover-primary">{{ __('product/product.product') }}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{ __('product/product.edit') }}</li>
    </ul>
<!--end::Breadcrumb-->
@endsection
{{-- @section('products_icon', 'active') --}}
@section('products_show', 'active show')
{{-- @section('add_product_menu_link', 'active') --}}

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Form-->
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/products.html">
                @csrf
                @method('PUT')
                <!--begin::Aside column-->
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        {{-- Product Image --}}
                                        <div class="mb-10 fv-row">
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label d-block">{{ __('product/product.product_image') }}</label>
        
                                                    <style>
                                                        [data-bs-theme="dark"] .image-input-placeholder {
                                                            @if ($product->image)
        
                                                            background-image: url('{{ asset("storage/product-image/$product->image") }}');
                                                            @endif
                                                        }
                                                    </style>
                                                    <!--end::Image input placeholder-->
                                                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-150px h-150px"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Remove-->
                                                        <div class="text-muted fs-7">Max File Size: 5MB</div>
                                                        <div class="text-muted fs-7">Aspect ratio should be 1:1</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_sale">
                                                            <input class="form-check-input" name="can_sale" type="checkbox" value="1" id="can_sale" @checked($product->can_sale === 1)/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_sale') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_purchase">
                                                            <input class="form-check-input" name="can_purchase" type="checkbox" value="1" id="can_purchase" @checked($product->can_purchase === 1)/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_purchase') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_expense">
                                                            <input class="form-check-input" name="can_expense" type="checkbox" value="1" id="can_expense" @checked($product->can_expense === 1)/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_expense') }}</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Image input-->
                                        </div>
                                        <!--begin::Input group-->

                                        <div class="mb-10 fv-row">
                                            <div class="row mb-5">
                                                <div class="btn btn-sm btn-light-info w-200px" id="advance_toggle">
                                                    <span class="show_advance"><i class="fa-solid fa-eye-slash me-5"></i>Show Advance</span>
                                                    <span class="hide_advance d-none"><i class="fa-solid fa-eye me-5"></i>Hide Advance</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4 mb-5">
                                                    <label class="required form-label">{{ __('product/product.product_name') }}</label>
                                                    <input type="text" name="product_name" class="form-control form-control-sm mb-2" placeholder="Product name" value="{{old('product_name',$product->name)}}" />
                                                    @error('product_name')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <label class="form-label">
                                                            Product Code
                                                        </label>
                                                        <input type="text" name="product_code" class="form-control form-control-sm mb-2" placeholder="Product code" value="{{old('product_code',$product->product_code)}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5  advance-toggle-class d-none">
                                                    <div class="fv-row">
                                                        <label class="form-label">
                                                            SKU <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                            title="Unique product id or Stock Keeping Unit <br/><br/> Keep it blank to automatically generate sku.<br/><span class='text-muted'>You can modify sku prefix in Business settings.</span>"></i>
                                                        </label>
                                                        <input type="text" name="sku" class="form-control form-control-sm mb-2" placeholder="SKU Number" value="{{old('sku',$product->sku)}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">{{ __('product/product.brand') }}</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="brand" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select brand">
                                                                <option></option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}" @selected($brand->id === $product->brand_id)>{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" data-bs-toggle="modal" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_brand">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
    
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">
                                                        {{ __('product/product.category') }}
                                                    </label>
                                                    <select id="categorySelect" name="category" class="form-select form-select-sm" data-control="select2" data-placeholder="Select category">
                                                        <option></option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @selected($category->id === $product->category_id)>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <label for="" class="form-label">
                                                        Sub Category
                                                    </label>
                                                    <select class="form-select form-select-sm" name="sub_category" id="subCategorySelect" data-control="select2" data-hide-search="true" data-placeholder="Select sub category">
    
                                                    </select>
                                                </div>
                                            </div>                                           
                                            <div class="row mb-5  advance-toggle-class d-none">
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Manufacturer</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="manufacturer" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select manufacturer">
                                                                <option></option>
                                                                @foreach ($manufacturers as $manufacturer)
                                                                    <option value="{{ $manufacturer->id }}" @selected($manufacturer->id === $product->manufacturer_id)>{{ $manufacturer->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_manufacturer">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Generic</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="generic" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select generic">
                                                                <option></option>
                                                                @foreach ($generics as $generic)
                                                                    <option value="{{ $generic->id }}" @selected($generic->id === $product->generic_id)>{{ $generic->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_generic">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                {{-- <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label">
                                                            Lot
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="text" name="lot_count" class="form-control form-control-sm mb-2" placeholder="Product code" value="{{old('lot_count',$product->lot_count)}}" />
                                                        <!--end::Input-->
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label required">
                                                            {{ __('product/product.uom') }}
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="uom_id" class="form-select form-select-sm " data-control="select2" data-placeholder="Select unit">
                                                                    <option></option>
                                                                    @foreach ($uoms as $uom)
                                                                        <option value="{{ $uom->id }}" @selected($uom->id === $product->uom_id)>{{ $uom->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('uom_id')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label required">
                                                            {{ __('product/product.purchase_uom') }}
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="purchase_uom_id" id="unitOfUom" class="form-select form-select-sm" data-control="select2" data-placeholder="Select purchase UoM">
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('purchase_uom_id')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row advance-toggle-class">
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.purchase_price') }}</label>
                                                    <input type="text" name="purchase_price_for_single" class="form-control form-control-sm mb-2" placeholder="Purchase price" value="" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.profit_margin') }}</label>
                                                    <input type="text" name="profit_margin_for_single" class="form-control form-control-sm mb-2" placeholder="Profit mergin (%)" value="" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.sell_price') }}</label>
                                                    <input type="text" name="sell_price_for_single" class="form-control form-control-sm mb-2" placeholder="Sell price" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Product Type  --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3 col-md-offset-4">
                                                <label for="" class="form-label required">
                                                    {{ __('product/product.product_type') }}
                                                </label>
                                                <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                    title="<div class='text-start'><strong>Single product: </strong> Product with no variations. <br/>
                                                            <strong>Variable product: </strong> Product with variations such as size, color etc. <br/>
                                                            <strong>Combo product: </strong> A combination of multiple products, also called bundle product.</div>"></i>
                                                <div class="mb-3">
                                                    <select class="form-select form-select" name="product_type" id="product_type" data-hide-search="true" disabled>
                                                        <option value="single" @selected($product->product_type === "single")>Single</option>
                                                        <option value="variable" @selected($product->product_type === "variable")>Variable</option>
                                                    </select>
                                                    <input type="hidden" name="product_type_hidden" value="{{ $product->product_type }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="single_box" class="box advance-toggle-class d-none">

                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-secondary fw-bold fs-6 text-gray-800 text-center">
                                                            <th>Default Purchase Price</th>
                                                            <th>
                                                                x Margin(%) <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                title="Default profit margin for the product.<br/>
                                                                       <i class='text-muted'>You can manage default profit margin in Business Settings.</i>"></i>
                                                            </th>
                                                            <th>Default Selling Price</th>
                                                            <th>Product image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="" class="required form-label">Exc. tax</label>
                                                                        <input type="text" name="single_exc" class="form-control" placeholder="Exc. tax">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="required form-label">Inc. tax</label>
                                                                        <input type="text" name="single_inc" class="form-control" placeholder="Inc. tax">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for=""></label>
                                                                <input type="text" name="single_profit" class="form-control" value="">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Exc. Tax</label>
                                                                <input type="text" name="single_selling" class="form-control" placeholder="Exc. tax">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Product image</label>
                                                                <input type="file" name="" id="" class="form-control">
                                                                <div class="text-muted">
                                                                    Max File size: 5MB <br/>
                                                                    Aspect ration should be 1:1
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="variable_box" class="box advance-toggle-class d-none">
                                            <span class="required fs-2">
                                                Add Variation
                                            </span>

                                            <div class="my-3 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="fw-bold fs-3 text-gray-800 text-start bg-gray-300">
                                                            <th class="text-center">Variation</th>
                                                            <th>Variation Values</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="repeater" >
                                                        <tr >
                                                            <td class="min-w-200px">
                                                                <select name="variation_name" disabled id="variationSelect" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                                                                    <option></option>
                                                                    @foreach ($variations as $variation)
                                                                        <option value="{{ $variation->id }}"
                                                                            @selected($product->productVariationTemplates()->first()->variation_template_id === $variation->id) >
                                                                            {{ $variation->name }}
                                                                        </option>
                                                                    @endforeach
                                                                    <input type="hidden" name="variation_template_id_hidden" value="{{ $product->productVariationTemplates()->first()->variation_template_id }}">
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-bordered" id="variation-table">
                                                                        <thead>
                                                                            <tr class="fw-bold fs-6 text-gray-800 text-start bg-gray-500">
                                                                                <th class="text-center min-w-100px">
                                                                                    SKU <i class="fas fa-exclamation-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                                    title="SKU is optional. <br/> <br/>
                                                                                           Keep it blank to automatically generate sku."></i>
                                                                                </th>
                                                                                <th class="min-w-100px">Value</th>
                                                                                <th class="min-w-200px">
                                                                                    Default Purchase Price <br/>
                                                                                    <i>Exc. tax Inc. tax</i>
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    x Margin(%)
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    Default Selling Price <br/>
                                                                                    <i>Exc. Tax</i>
                                                                                </th>
                                                                                <th class="min-w-200px">Variation Images</th>
                                                                                <th class=" min-w-50px">
                                                                                    <span id="child-repeater" name="add" data-repeater-create class="svg-icon svg-icon-primary svg-icon-4 cursor-pointer add-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody data-repeater-list="variation_lists" id="variation-row" class="variation-row-class">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="separator border-dark my-10 "></div>

                                        {{-- Custom Fields  --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-4 mb-8">
                                                <div class="fv-row">
                                                    
                                                    <label class="form-label">{{ __('product/product.custom_field_1') }}</label>
                                                    
                                                    <input type="text" name="custom_field1" class="form-control form-control-sm mb-2" placeholder="Custom field1" value="{{old('custom_field1',$product->product_custom_field1)}}" />
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                
                                                <label class="form-label">{{ __('product/product.custom_field_2') }}</label>
                                              
                                                <input type="text" name="custom_field2" class="form-control form-control-sm mb-2" placeholder="Custom field2" value="{{old('custom_field2',$product->product_custom_field2)}}" />
                                                
                                            </div>
                                            <div class="col-md-4 mb-3">
                                               
                                                <label class="form-label">{{ __('product/product.custom_field_3') }}</label>
                                                
                                                <input type="text" name="custom_field3" class="form-control form-control-sm mb-2" placeholder="Custom field3" value="{{old('custom_field3',$product->product_custom_field3)}}" />
                                                
                                            </div>
                                            
                                        </div>
                                        <div class="row mb-5 advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3">
                                                
                                                <label class="form-label">{{ __('product/product.custom_field_4') }}</label>
                                                
                                                <input type="text" name="custom_field4" class="form-control form-control-sm mb-2" placeholder="Custom field4" value="{{old('custom_field4',$product->product_custom_field4)}}" />
                                                
                                            </div>
                                        </div>

                                        {{-- Product Description  --}}
                                        <div class="mb-8">
                                            <!--begin::Label-->
                                            <h3>{{ __('product/product.product_description') }}</h3>
                                            <!--end::Label-->
                                            <!--begin::Editor-->
                                            <div id="kt_docs_quill_basic" name="product_desc" class="min-h-100px mb-2">

                                            </div>
                                            <input type="hidden" name="quill_data" value="{{ old('quill_data', $quillData ?? '') }}">
                                            <!--end::Editor-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                            <!--end::Description-->
                                        </div>

                                        {{-- Product Disable  --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-3 mb-8 col-md-offset-4">
                                                <div class="form-check form-check-custom form-check-solid mt-8">
                                                    <label class="" for="tab2_check1">
                                                        <input class="form-check-input" name="product_inactive" type="checkbox" value="" id="tab2_check1" @checked($product->is_inactive === 1)/>
                                                        <strong class="ms-4 h5">{{ __('product/product.disable') }}</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-start">
                        <!--begin::Button-->
                        <a href="{{ url('/product') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-light me-5">Cancel</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            {{-- <button type="submit" class="btn btn-warning btn-sm">Save & Add Selling-Price-Group Prices</button>
                            <button type="submit" class="btn btn-success btn-sm">Save & Add Opening Stock</button> --}}
                            {{-- <button type="submit" class="btn btn-info btn-sm" name="save" value="save_and_another">Save & Add Another</button> --}}
                            <button type="submit" class="btn btn-primary btn-sm" name="save" value="save">Save</button>
                          </div>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </form>
            <!--end::Form-->

            {{-- =============================>BEGIN :: MODALS <==================================== --}}

            {{-- =========> Begin :: Brand Create Modal <============ --}}
            <div class="modal fade" tabindex="-1" id="kt_modal_brand">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('brand.create') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h3 class="modal-title">Add Brand</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </div>
                                <!--end::Close-->
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="" class="required form-label">Brand Name</label>
                                    <input type="text" name="brand_name" class="form-control" placeholder="Name">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Short description</label>
                                    <input type="text" name="brand_desc" class="form-control" placeholder="Short name">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="brand_create" value="brand_create">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- =========> End :: Brand Create Modal <============ --}}

            {{-- =========> Begin :: Manufacturer Create Modal <============ --}}
            <div class="modal fade" tabindex="-1" id="kt_modal_manufacturer">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('manufacturer.create') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h3 class="modal-title">Add Manufacturer</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </div>
                                <!--end::Close-->
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="" class="required form-label">Manufacturer Name</label>
                                    <input type="text" name="manufacturer_name" class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="manufacturer_create" value="manufacturer">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- =========> End :: Manufacturer Create Modal <============ --}}

            {{-- =========> Begin :: Generic Create Modal <============ --}}
            <div class="modal fade" tabindex="-1" id="kt_modal_generic">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('generic.create') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h3 class="modal-title">Add Generic</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </div>
                                <!--end::Close-->
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="" class="required form-label">Generic Name</label>
                                    <input type="text" name="generic_name" class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="generic_create" value="generic_create">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- =========> End :: Generic Create Modal <============ --}}
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script src="/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toastr-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // ============= > Begin:: For Product Description < =====================
        var quill = new Quill('#kt_docs_quill_basic', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: '',
            theme: 'snow' // or 'bubble'
        });
        const productDescription = @json($product->product_description);
        const delta = quill.clipboard.convert(productDescription);
        quill.setContents(delta);

        quill.on('text-change', function() {
            var quillData = quill.root.innerHTML;
            document.querySelector('input[name="quill_data"]').value = quillData;
        });
    // ============= > End:: For Product Description < =======================

    // ============= > Begin:: For Product Type      < =======================

        const selectBox = document.getElementById("product_type");
        const singleBox = document.getElementById("single_box");
        const variableBox = document.getElementById("variable_box");

        if(selectBox.value === "variable"){
            variableBox.style.display = "block";
        }else{
            singleBox.style.display = "block";
        }

        selectBox.addEventListener("change", () => {
            const selectedValue = selectBox.value;

            // Hide all contact boxes
            singleBox.style.display = "none";
            variableBox.style.display = "none";

            // Show the contact box associated with the selected option
            if (selectedValue === "single") {
                singleBox.style.display = "block";
            } else if (selectedValue === "variable") {
                variableBox.style.display = "block";
            }
        });
    // ============= > End:: For Product Type      < =========================

    // ============= > Begin:: Formula ProfitPercentage and Selling Price ====
        let profitPercentage = (sell, purchase) => Math.ceil( ( (parseInt(sell) - parseInt(purchase)) * 100)/parseInt(purchase) );

        let sellingPrice = (profit, purchase) => Math.ceil( ( (100 + parseInt(profit)) * parseInt(purchase))/100 );
    // ============= > Begin:: Formula ProfitPercentage and Selling Price ====

    // ============= > Begin:: For Single Product Type Calculate < ===========

        let singleExc = $('[name="single_exc"]');
        let singleInc = $('[name="single_inc"]');
        let singleProfit = $('[name="single_profit"]');
        let singleSelling = $('[name="single_selling"]');

        let anotherPurchase = $('input[name="purchase_price_for_single"]');
        let anotherProfit = $('input[name="profit_margin_for_single"]');
        let anotherSell = $('input[name="sell_price_for_single"]');

        let oldSingleValue = @json($productVariation);
        
        let oldSingleSellingPrice;
        if(oldSingleValue.length !== 0){
            oldSingleSellingPrice = profitPercentage( oldSingleValue[0].default_selling_price, oldSingleValue[0].default_purchase_price );
        }
        
        let singleProductType = (@json($product)).product_type;

        if(singleProductType === "variable"){
            anotherPurchase.prop('disabled', true);
            anotherProfit.prop('disabled', true);
            anotherSell.prop('disabled', true);
        }
        
        if((oldSingleValue.length !== 0) && (singleProductType !== "variable")){
            singleExc.val(oldSingleValue[0].default_purchase_price);
            singleInc.val(oldSingleValue[0].default_purchase_price);
            singleProfit.val(oldSingleSellingPrice);
            singleSelling.val(oldSingleValue[0].default_selling_price);

            anotherPurchase.val(oldSingleValue[0].default_purchase_price)
            anotherProfit.val(oldSingleSellingPrice)
            anotherSell.val(oldSingleValue[0].default_selling_price)
            
            $(document).find('input[name="sell_price_for_single"]').val(oldSingleValue[0].default_selling_price) // for basic form selling price
        }

        singleExc.on('keyup', (e) => {
            let excVal = e.target.value;
            singleInc.val(excVal);
            anotherPurchase.val(excVal);

            if(singleSelling.val()){
                let profitValue = profitPercentage(singleSelling.val(), excVal);
                singleProfit.val(profitValue);
                anotherProfit.val(profitValue)
                if(isNaN(profitValue)){
                    singleProfit.val('')
                    anotherProfit.val(profitValue)
                }
            }
        })
        singleInc.on('keyup', (e) => {
            let incVal = e.target.value;
            singleExc.val(incVal);
            anotherPurchase.val(incVal);

            if(singleSelling.val()){
                let profitValue = profitPercentage(singleSelling.val(), incVal);
                singleProfit.val(profitValue);
                anotherProfit.val(profitValue)
                if(isNaN(profitValue)){
                    singleProfit.val('')
                    anotherProfit.val(profitValue)
                }
            }
        })
        singleProfit.on('keyup', (e) => {
            let profitValue = e.target.value;
            anotherProfit.val(profitValue);

            if(singleExc.val() || singleInc.val()){
                let resultSelling = sellingPrice(profitValue, singleExc.val());
                singleSelling.val(resultSelling);
                anotherSell.val(resultSelling);
                if(isNaN(resultSelling)){
                    singleSelling.val('');
                    anotherSell.val(resultSelling);
                }
            }
        })
        singleSelling.on('keyup', (e) => {
            let sellingValue = e.target.value;
            anotherSell.val(sellingValue);

            if(singleExc.val() || singleInc.val()){
                let resultProfit = profitPercentage(sellingValue, singleExc.val());
                singleProfit.val(resultProfit);
                anotherProfit.val(resultProfit)
                if(isNaN(resultProfit)){
                    singleProfit.val('');
                    anotherProfit.val(resultProfit)
                }
            }
        })

    // ============= > End:: For Single Product Type Calculate   < ===========


    // ============= > Begin:: For Variation table repeater  < ===============
        $(document).ready(function () {
            let newVariation = `
            <tr data-repeater-item class="variation-add-delete">
                <input type="hidden" name="variation_id[]">
                <input type="hidden" name="product_variation_id[]">
                <td>
                    <input type="text" class="form-control form-control-sm" name="variation_sku[]">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm variation_name" name="variation_value[]" value="">
                </td>
                <td>
                    <div class="input-group input-group-sm mb-5">
                        <input type="text" class="form-control" placeholder="Exc. tax" name="exc_purchase[]" />
                        <input type="text" class="form-control" placeholder="Inc. tax" name="inc_purchase[]" />
                        <span class="input-group-text cursor-pointer" name="double-mark1" data-bs-toggle="tooltip" data-bs-placement="top" title="Apply all">
                            <span class="svg-icon svg-icon-muted svg-icon-2 "><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M12.8956 13.4982L10.7949 11.2651C10.2697 10.7068 9.38251 10.7068 8.85731 11.2651C8.37559 11.7772 8.37559 12.5757 8.85731 13.0878L12.7499 17.2257C13.1448 17.6455 13.8118 17.6455 14.2066 17.2257L21.1427 9.85252C21.6244 9.34044 21.6244 8.54191 21.1427 8.02984C20.6175 7.47154 19.7303 7.47154 19.2051 8.02984L14.061 13.4982C13.7451 13.834 13.2115 13.834 12.8956 13.4982Z" fill="currentColor"/>
                                <path d="M7.89557 13.4982L5.79487 11.2651C5.26967 10.7068 4.38251 10.7068 3.85731 11.2651C3.37559 11.7772 3.37559 12.5757 3.85731 13.0878L7.74989 17.2257C8.14476 17.6455 8.81176 17.6455 9.20663 17.2257L16.1427 9.85252C16.6244 9.34044 16.6244 8.54191 16.1427 8.02984C15.6175 7.47154 14.7303 7.47154 14.2051 8.02984L9.06096 13.4982C8.74506 13.834 8.21146 13.834 7.89557 13.4982Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm mb-5">
                        <input type="text" class="form-control" value="" name="profit_percentage[]"/>
                        <span class="input-group-text cursor-pointer" name="double-mark2" data-bs-toggle="tooltip" data-bs-placement="top" title="Apply all">
                            <span class="svg-icon svg-icon-muted svg-icon-2 "><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M12.8956 13.4982L10.7949 11.2651C10.2697 10.7068 9.38251 10.7068 8.85731 11.2651C8.37559 11.7772 8.37559 12.5757 8.85731 13.0878L12.7499 17.2257C13.1448 17.6455 13.8118 17.6455 14.2066 17.2257L21.1427 9.85252C21.6244 9.34044 21.6244 8.54191 21.1427 8.02984C20.6175 7.47154 19.7303 7.47154 19.2051 8.02984L14.061 13.4982C13.7451 13.834 13.2115 13.834 12.8956 13.4982Z" fill="currentColor"/>
                                <path d="M7.89557 13.4982L5.79487 11.2651C5.26967 10.7068 4.38251 10.7068 3.85731 11.2651C3.37559 11.7772 3.37559 12.5757 3.85731 13.0878L7.74989 17.2257C8.14476 17.6455 8.81176 17.6455 9.20663 17.2257L16.1427 9.85252C16.6244 9.34044 16.6244 8.54191 16.1427 8.02984C15.6175 7.47154 14.7303 7.47154 14.2051 8.02984L9.06096 13.4982C8.74506 13.834 8.21146 13.834 7.89557 13.4982Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" placeholder="Exc. tax" name="selling_price[]">
                </td>
                <td>
                    <input type="file" class="form-control form-control-sm" name="variation_image[]">
                </td>
                <td class="min-w-50px " >
                    <span id="delete-variation" data-repeater-delete name="delete" class="deleteButton svg-icon svg-icon-danger svg-icon-4 cursor-pointer d-flex align-items-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                        </svg>
                    </span>
                </td>
            </tr>
            `;

            let calculateVariation = () => {
                let exc = $('[name="exc_purchase[]"]');
                let inc = $('[name="inc_purchase[]"]');
                let profit = $('[name="profit_percentage[]"]');
                let selling = $('[name="selling_price[]"]');
                // begin:: exc and inc input to the same
                exc.on('keyup', (e) => {
                    let excVal = e.target.value;
                    let input = $(e.currentTarget).closest('tr').find(inc);
                    input.val(e.target.value)

                    let currentSelling = $(e.currentTarget).closest('tr').find(selling);
                    let currentProfit = $(e.currentTarget).closest('tr').find(profit);

                    if(currentSelling.val()){
                        let profitValue = profitPercentage(currentSelling.val(), excVal);
                        currentProfit.val(profitValue);
                        if(isNaN(profitValue)){
                            currentProfit.val('')
                        }
                    }
                })
                inc.on('keyup', (e) => {
                    let incVal = e.target.value;
                    let input = $(e.currentTarget).closest('tr').find(exc);
                    input.val(e.target.value)
                    let currentSelling = $(e.currentTarget).closest('tr').find(selling);
                    let currentProfit = $(e.currentTarget).closest('tr').find(profit);

                    if(currentSelling.val()){
                        let profitValue = profitPercentage(currentSelling.val(), incVal);
                        currentProfit.val(profitValue);
                        if(isNaN(profitValue)){
                            currentProfit.val('');
                        }
                    }
                })
                // end:: exc and inc input to the same
                // if typing profit percentage input
                profit.on('keyup', (e) => {
                    let currentProfitValue = e.target.value;

                    let currentExc = $(e.currentTarget).closest('tr').find(exc);
                    let currentInc = $(e.currentTarget).closest('tr').find(inc);
                    let sellingInput = $(e.currentTarget).closest('tr').find(selling);

                    if(currentExc.val() || currentInc.val()){
                        let resultSelling = sellingPrice(currentProfitValue, currentExc.val());
                        sellingInput.val(resultSelling);
                        if(isNaN(resultSelling)){
                            sellingInput.val('');
                        }
                    }
                })
                // if typing selling price input
                selling.on('keyup', (e) => {
                    let currentSellingValue = e.target.value;
                    let currentExc = $(e.currentTarget).closest('tr').find(exc);
                    let currentInc = $(e.currentTarget).closest('tr').find(inc);
                    let currentProfit = $(e.currentTarget).closest('tr').find(profit);
                    if(currentExc.val() || currentInc.val()){
                        let resultProfit = profitPercentage(currentSellingValue, currentExc.val());
                        currentProfit.val(resultProfit);
                    }
                })
                // for aplly all data
                $('[name="double-mark1"]').on('click', () => {
                    console.log('click 1')
                })
                $('[name="double-mark2"]').on('click', () => {
                    console.log('click 2')
                })
            }
            // for variation select
            const variationSelect = $('#variationSelect');

            // show old value variation
            let toShowVariation = false;
            if(!toShowVariation){
                let productType = (@json($product)).product_type;
                if(productType !== "single"){
                    let oldValues = @json($productVariation);

                    $.each(oldValues, function(index, item){
                        let profit_percent = profitPercentage(item.default_selling_price, item.default_purchase_price);

                        let cloneRow = $(newVariation).clone();
                        cloneRow.find('input[name="variation_value[]"]').val(item.variation_template_value.name)
                        cloneRow.find('input[name="variation_id[]"]').val(item.variation_template_value.id)
                        cloneRow.find('input[name="product_variation_id[]"]').val(item.id)
                        cloneRow.find('input[name="exc_purchase[]"]').val(item.default_purchase_price)
                        cloneRow.find('input[name="inc_purchase[]"]').val(item.default_purchase_price)
                        cloneRow.find('input[name="profit_percentage[]"]').val(profit_percent)
                        cloneRow.find('input[name="selling_price[]"]').val(item.default_selling_price)
                        cloneRow.find('input[name="variation_value[]"]').attr('readonly', true);
                        $('#variation-row').append(cloneRow);
                    })
                    calculateVariation();
                }
                // variationCalculate();
                toShowVariation = true;
            }

            // variationSelect.on('change', function() {
            //     let id = variationSelect.val()
            //     $.ajax({
            //         url: '/variation-values/'+id,
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function(data) {
            //             $('tbody tr[data-repeater-item]').remove();

            //             $.each(data, function(index, item) {
            //                 $('.variation-add-delete').remove();
            //                 let cloneRow = $(newVariation).clone();
            //                 cloneRow.find('input[name="variation_value[]"]').val(item.name)
            //                 cloneRow.find('input[name="product_variation_id[]"]').val(oldValues[index].id)
            //                 cloneRow.find('input[name="variation_id[]"]').val(item.id)
            //                 cloneRow.find('input[name="variation_value[]"]').attr('readonly', true);
            //                 $('#variation-row').append(cloneRow);
            //             });
            //             calculateVariation();
            //         },
            //         error: function(xhr, status, error) {
            //             console.log(error);
            //         }
            //     });
            // })
            $(document).on('click', '#child-repeater', function() {
                $('#variation-row').append(newVariation);
                calculateVariation();
            })
            $(document).on('click', '#delete-variation', function() {
                $(this).closest('.variation-add-delete').remove();
            })

        });

    // ============= > Begin:: For Variation table repeater < ================

    // ============= > Begin:: For Sub Category Select Box  < ================

        $(document).ready(function() {
            const cateSelect = $('#categorySelect');

            let toShowSubCategory = false;
            if(!toShowSubCategory){
                let id = cateSelect.val();
                $.ajax({
                    url: '/category/sub-category/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const subCategorySelect = $('#subCategorySelect')[0];
                        subCategorySelect.innerHTML = '';

                        const defaultOption = document.createElement('option'); // Create default option
                        defaultOption.value = '';
                        defaultOption.text = 'Select an option';
                        $(subCategorySelect).append(defaultOption);

                        let oldSubCategoryId = (@json($product)).sub_category_id;
                        $.each(data, function(index, item) {
                            let option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.name;
                            if(data[index].id === oldSubCategoryId){
                                option.selected = true;
                            }
                            subCategorySelect.append(option);
                        });

                        $('#subCategorySelect').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin

                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // handle the error
                    }
                });
                toShowSubCategory = true;
            }

            cateSelect.on('change', function() {
                let id = cateSelect.val();
                
                $.ajax({
                    url: '/category/sub-category/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const subCategorySelect = $('#subCategorySelect')[0];
                        subCategorySelect.innerHTML = '';

                        const defaultOption = document.createElement('option'); // Create default option
                        defaultOption.value = '';
                        defaultOption.text = 'Select an option';
                        $(subCategorySelect).append(defaultOption);

                        for (let item of data) {
                            let option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.name;
                            subCategorySelect.append(option);
                        }

                        $('#subCategorySelect').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin

                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // handle the error
                    }
                });
            })
        });

    // ============= > End:: For Sub Category Select Box  < ==================

    // ============= > Begin:: For Purchase, Profit, Selling price  < ==================
        $(document).on('input', 'input[name="purchase_price_for_single"]', function() {
            let value = $(this).val();
            let profit = $(document).find('input[name="profit_margin_for_single"]').val();
            
            let sellPrice;
            if(profit !== ''){
                sellPrice = (profit, value);

                $(document).find('input[name="sell_price_for_single"]').val(sellPrice);
                $(document).find('input[name="single_selling"]').val(sellPrice);
            }
            $(document).find('input[name="single_exc"]').val(value)
            $(document).find('input[name="single_inc"]').val(value)
        })

        $(document).on('input', 'input[name="profit_margin_for_single"]', function() {
            let value = $(this).val();
            let purchase = $(document).find('input[name="purchase_price_for_single"]').val();
            let sellPrice = sellingPrice(value, purchase);

            $(document).find('input[name="sell_price_for_single"]').val(sellPrice)
            $(document).find('input[name="single_profit"]').val(value)
            $(document).find('input[name="single_selling"]').val(sellPrice)
        })

        $(document).on('input', 'input[name="sell_price_for_single"]', function() {
            let value = $(this).val();
            let purchase = $(document).find('input[name="purchase_price_for_single"]').val();
            let profit = profitPercentage(value, purchase);

            $(document).find('input[name="profit_margin_for_single"]').val(profit)
            $(document).find('input[name="single_profit"]').val(profit)
            $(document).find('input[name="single_selling"]').val(value)
        })
    // ============= > End:: For Purchase, Profit, Selling price  < ==================

    // ============= > Begin:: For Show advance  < ==================
        $(document).on('click', '#advance_toggle', function() {
            $('.show_advance, .hide_advance').toggleClass('d-none');
            $('.advance-toggle-class').toggleClass('d-none');
        })
    // ============= > End:: For Show advance  < ==================

    // ============= > Begin:: For UOM  < ==================
        let isSelectUoM = false;
        if(!isSelectUoM){
            let id = $(document).find('select[name="uom_id"]').val();
            $.ajax({
                url: `/uom/get/${id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(results){
                    const purchaseUoM = $('#unitOfUom')[0];
                    purchaseUoM.innerHTML = '';

                    const defaultOption = document.createElement('option'); // Create default option
                    defaultOption.value = '';
                    defaultOption.text = 'Select an option';
                    $(purchaseUoM).append(defaultOption);

                    let oldUoMId = @json($product->purchase_uom_id ?? null);
                    for (let item of results) {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.text = item.name;
                        if(item.id == oldUoMId){
                            option.selected = true;
                        }
                        purchaseUoM.append(option);
                    }

                    $('#unitOfUom').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin
                },
                error: function(e){
                    console.log(e.responseJSON.error);
                }
            });
            isSelectUoM = true;
        }

        $(document).on('change', 'select[name="uom_id"]', function() {
            let uom_id = $(this).val();
            
            $.ajax({
                url: `/uom/get/${uom_id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(results){
                    const purchaseUoM = $('#unitOfUom')[0];
                    purchaseUoM.innerHTML = '';

                    const defaultOption = document.createElement('option'); // Create default option
                    defaultOption.value = '';
                    defaultOption.text = 'Select an option';
                    $(purchaseUoM).append(defaultOption);

                    for (let item of results) {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.text = item.name;
                        purchaseUoM.append(option);
                    }

                    $('#unitOfUom').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin
                },
                error: function(e){
                    console.log(e.responseJSON.error);
                }
            });
        })
    // ============= > End:: For UOM  < ==================
</script>

    @if (session('message'))
        <script>
            toastr.success("{{session('message')}}");
        </script>
    @endif
@endpush
