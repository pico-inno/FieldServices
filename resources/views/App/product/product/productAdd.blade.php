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
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/product.add_product') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('products') }}" class="text-muted text-hover-primary">{{ __('product/product.product') }}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{ __('product/product.add') }}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('add_product_menu_link', 'active')

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="product_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Form-->
            <form action="{{ route('product.create') }}" method="POST" enctype="multipart/form-data"
                id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row"
                data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/products.html">
                @csrf


                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <div class="card-body pt-0">
                                        {{-- Product Image --}}
                                        <div class="mb-10 fv-row">
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label
                                                        class="form-label d-block">{{ __('product/product.product_image') }}</label>

                                                    <style>
                                                        .image-input-placeholder {
                                                            background-image: url('assets/media/svg/files/blank-image.svg');
                                                        }

                                                        [data-bs-theme="dark"] .image-input-placeholder {
                                                            background-image: url('assets/media/svg/files/blank-image-dark.svg');
                                                        }
                                                    </style>

                                                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                                        data-kt-image-input="true">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-150px h-150px"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            title="Change avatar">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="avatar"
                                                                accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                            title="Cancel avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            title="Remove avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Remove-->
                                                    </div>
                                                    <div class="text-muted fs-7">Max File Size: 5MB</div>
                                                    <div class="text-muted fs-7">Aspect ratio should be 1:1</div>
                                                    @error('avatar')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_sale">
                                                            <input class="form-check-input" name="can_sale" type="checkbox"
                                                                value="1" id="can_sale" checked
                                                                {{ old('can_sale') ? 'checked' : '' }} />
                                                            <strong
                                                                class="ms-4 h5">{{ __('product/product.can_sale') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_purchase">
                                                            <input class="form-check-input" name="can_purchase"
                                                                type="checkbox" value="1" id="can_purchase"
                                                                {{ old('can_purchase') === '1' || old('can_purchase') === null ? 'checked' : '' }} />
                                                            <strong
                                                                class="ms-4 h5">{{ __('product/product.can_purchase') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_expense">
                                                            <input class="form-check-input" name="can_expense"
                                                                type="checkbox" value="1" id="can_expense"
                                                                {{ old('can_expense') ? 'checked' : '' }} />
                                                            <strong
                                                                class="ms-4 h5">{{ __('product/product.can_expense') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="is_recurring">
                                                            <input class="form-check-input" name="is_recurring"
                                                                type="checkbox" value="1" id="is_recurring"
                                                                {{ old('is_recurring') ? 'checked' : '' }} />
                                                            <strong
                                                                class="ms-4 h5">{{ __('product/product.is_recurring') }}</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-10 fv-row">
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <div class="fv-row">
                                                        <label class="form-label required">

                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <div class="btn btn-sm btn-light-info w-200px"
                                                                    id="advance_toggle">
                                                                    <span class="show_advance"><i
                                                                            class="fa-solid fa-eye-slash me-5"></i>Show
                                                                        Advance</span>
                                                                    <span class="hide_advance d-none"><i
                                                                            class="fa-solid fa-eye me-5"></i>Hide
                                                                        Advance</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="fv-row">
                                                        <label class="form-label required">
                                                            {{ __('product/product.product_type') }}
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="product_type"
                                                                    class="form-select form-select-sm"
                                                                    data-control="select2"
                                                                    data-placeholder="Select Product Type">
                                                                    <option></option>
                                                                    <option value="consumeable"
                                                                        {{ old('product_type') == 'consumeable' ? 'selected' : '' }}>
                                                                        Consumeable</option>
                                                                    <option value="storable"
                                                                        {{ old('product_type') == 'storable' || old('product_type') === null ? 'selected' : '' }}>
                                                                        Storable</option>
                                                                    <option value="service"
                                                                        {{ old('product_type') == 'service' ? 'selected' : '' }}>
                                                                        Service</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('product_type')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5 fv-row">
                                                    <label
                                                        class="required form-label">{{ __('product/product.product_name') }}</label>
                                                    <input type="text" name="product_name"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Product name" value="{{ old('product_name') }}" />
                                                    @error('product_name')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <label class="form-label">
                                                            {{ __('product/product.product_code') }}
                                                        </label>
                                                        <input type="text" name="product_code"
                                                            class="form-control form-control-sm mb-2"
                                                            placeholder="Product code"
                                                            value="{{ old('product_code') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <label class="form-label">
                                                        {{ __('product/product.sku') }} <i
                                                            class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help"
                                                            data-bs-toggle="tooltip" data-bs-html="true"
                                                            style="cursor:help"
                                                            title="Unique product id or Stock Keeping Unit <br/><br/> Keep it blank to automatically generate sku.<br/><span class='text-muted'>You can modify sku prefix in Business settings.</span>"></i>
                                                    </label>
                                                    <input type="text" name="sku"
                                                        class="form-control mb-2 form-control-sm" placeholder="SKU Number"
                                                        value="" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label for=""
                                                        class="form-label">{{ __('product/product.brand') }}</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="brand"
                                                                class="form-select form-select-sm rounded-end-0"
                                                                data-control="select2" data-placeholder="Select brand">
                                                                <option></option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}"
                                                                        @selected(old('brand') == $brand->id)>{{ $brand->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer"
                                                            data-bs-toggle="modal" id="basic-addon1"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_brand">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">
                                                        {{ __('product/product.category') }}
                                                    </label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select id="categorySelect" name="category"
                                                                class="form-select form-select-sm rounded-end-0"
                                                                data-control="select2" data-placeholder="Select category">
                                                                <option></option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        @selected(old('category') == $category->id)>{{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer"
                                                            data-bs-toggle="modal" id="basic-addon1"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_category">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <label for="" class="form-label">
                                                        {{ __('product/product.sub_category') }}
                                                    </label>

                                                    <select class="form-select form-select-sm" name="sub_category"
                                                        id="subCategorySelect" data-control="select2"
                                                        data-hide-search="true" data-placeholder="Select sub category">

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="row advance-toggle-class d-none">
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Manufacturer</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="manufacturer"
                                                                class="form-select form-select-sm rounded-end-0"
                                                                data-control="select2"
                                                                data-placeholder="Select manufacturer">
                                                                <option></option>
                                                                @foreach ($manufacturers as $manufacturer)
                                                                    <option value="{{ $manufacturer->id }}"
                                                                        @selected(old('manufacturer') == $manufacturer->id)>
                                                                        {{ $manufacturer->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_manufacturer">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Generic</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="generic"
                                                                class="form-select form-select-sm rounded-end-0"
                                                                data-control="select2" data-placeholder="Select generic">
                                                                <option></option>
                                                                @foreach ($generics as $generic)
                                                                    <option value="{{ $generic->id }}"
                                                                        @selected(old('generic') == $generic->id)>{{ $generic->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_generic">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    {{-- <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label">
                                                            Lot
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="text" name="lot_count" class="form-control form-control-sm mb-2" placeholder="Product code" value="" />
                                                        <!--end::Input-->
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <label class="form-label required">
                                                            Unit Category
                                                        </label>
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="unit_categories"
                                                                    class="form-select form-select-sm"
                                                                    data-control="select2"
                                                                    data-placeholder="Select Unit Categories">
                                                                    <option></option>
                                                                    @foreach ($unitCategories as $unitCategorie)
                                                                        <option value="{{ $unitCategorie->id }}"
                                                                            @selected(old('uom_id') == $unitCategorie->id)>
                                                                            {{ $unitCategorie->name }}</option>
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
                                                        <label class="form-label required">
                                                            {{ __('product/product.uom') }}
                                                        </label>
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="uom_id"
                                                                    class="form-select form-select-sm uomDatas"
                                                                    data-control="select2" data-placeholder="Select UoM">
                                                                    <option selected></option>
                                                                    {{--                                                                    @foreach ($uoms as $uom) --}}
                                                                    {{--                                                                        <option value="{{ $uom->id }}" data-uom-name="{{$uom->name}}" @selected(old('uom_id') == $uom->id)>{{ $uom->name }}</option> --}}
                                                                    {{--                                                                    @endforeach --}}
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
                                                        <label class="form-label required">
                                                            {{ __('product/product.purchase_uom') }}

                                                        </label>
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select class="form-select form-select-sm unitOfUom"
                                                                    name="purchase_uom_id" id="unitOfUom"
                                                                    data-control="select2" data-hide-search="true"
                                                                    data-placeholder="Select purchase UoM">

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('purchase_uom_id')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">

                                                    <div id="single_alert_qty_ui" class="fv-row">
                                                        <label class="form-label">
                                                            {{ __('product/product.alert_quantity') }}
                                                        </label>

                                                        <input type="text" name="single_alert_quantity"
                                                            class="form-control form-control-sm mb-2"
                                                            placeholder="Alert Quantity"
                                                            value="{{ old('single_alert_quantity') }}" />

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row advance-toggle-class">
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.purchase_price') }}
                                                        <span class="uom-label"></span>
                                                    </label>
                                                    <input type="text" name="purchase_price_for_single"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Purchase price"
                                                        value="{{ old('purchase_price_for_single') }}" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label
                                                        class="form-label">{{ __('product/product.profit_margin') }}</label>
                                                    <input type="text" name="profit_margin_for_single"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Profit mergin (%)"
                                                        value="{{ old('profit_margin_for_single') }}" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.sell_price') }}
                                                        <span class="uom-label"></span>
                                                    </label>
                                                    <input type="text" name="sell_price_for_single"
                                                        class="form-control form-control-sm mb-2" placeholder="Sell price"
                                                        value="{{ old('sell_price_for_single') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Input group-->

                                        {{-- Product Type  --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3 col-md-offset-4">
                                                <label for="" class="form-label required">
                                                    Has Variation
                                                </label>
                                                <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help"
                                                    data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                    title="<div class='text-start'><strong>Single product: </strong> Product with no variations. <br/>
                                                            <strong>Variable product: </strong> Product with variations such as size, color etc. <br/>
                                                            <strong>Combo product: </strong> A combination of multiple products, also called bundle product.</div>"></i>
                                                <div class="mb-3">
                                                    <select class="form-select form-select-sm" name="has_variation"
                                                        data-control="select2" id="has_variation"
                                                        data-hide-search="true">
                                                        <option value="single"
                                                            {{ old('has_variation') == 'single' || old('has_variation') === null ? 'selected' : '' }}>
                                                            Single</option>
                                                        <option value="variable"
                                                            {{ old('has_variation') == 'variable' ? 'selected' : '' }}>
                                                            Variable</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="variation_selection"
                                                class="col-md-4 mb-3 col-md-offset-4 box advance-toggle-class d-none">
                                                <label for="" class="form-label required">
                                                    Variation
                                                </label>
                                                <div class="mb-3">
                                                    <select name="variation_name[]" id="variationSelect"
                                                        class="form-select form-select-sm" data-control="select2"
                                                        data-hide-search="true" data-placeholder="Please select" multiple>
                                                        <option></option>
                                                        @foreach ($variations as $variation)
                                                            <option value="{{ $variation->id }}">
                                                                {{ $variation->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="single_box" class="box advance-toggle-class d-none">
                                            <div class="table-responsive mb-4">
                                                <table class="table table-row-dashed fs-6 gy-4" id="">
                                                    <thead>
                                                        <tr class="text-start text-gray-800 bg-light">
                                                            <th class="min-w-200px">Default Purchase Price</th>
                                                            <th class="min-w-100px">
                                                                x Margin(%) <i
                                                                    class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help"
                                                                    data-bs-toggle="tooltip" data-bs-html="true"
                                                                    style="cursor:help"
                                                                    title="Default profit margin for the product.<br/>
                                                                       <i class='text-muted'>You can manage default profit margin in Business Settings.</i>"></i>
                                                            </th>
                                                            <th class="min-w-100px">Default Selling Price</th>
                                                            <th class="min-w-150px">Product Image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-700 x" id="price_list_body">
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="me-4">
                                                                        <label for="" class="form-label">Exc.
                                                                            tax</label>
                                                                        <input type="text" name="single_exc"
                                                                            class="form-control rounded-0 form-control-sm"
                                                                            placeholder="Exc. tax">
                                                                    </div>
                                                                    <div class="">
                                                                        <label for=""
                                                                            class="required form-label">Inc. tax</label>
                                                                        <input type="text" name="single_inc"
                                                                            class="form-control rounded-0 form-control-sm"
                                                                            placeholder="Inc. tax">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for="" class=" form-label">Margin</label>
                                                                <input type="text" name="single_profit"
                                                                    class="form-control rounded-0 form-control-sm"
                                                                    value="">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Exc. Tax</label>
                                                                <input type="text" name="single_selling"
                                                                    class="form-control rounded-0 form-control-sm"
                                                                    placeholder="Exc. tax">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Product
                                                                    image</label>
                                                                <input type="file" name="" id=""
                                                                    class="form-control rounded-0 form-control-sm">
                                                                <div class="text-muted">
                                                                    Max File size: 5MB <br />
                                                                    Aspect ration should be 1:1
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="variable_box" class="box advance-toggle-class d-none">
                                            <div class="my-3 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="fw-bold fs-3 text-gray-800 text-start bg-gray-300">
                                                            {{--                                                            <th class="text-center">Variation</th> --}}
                                                            <th>Variation Values</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="repeater">
                                                        <tr>
                                                            {{--                                                            <td class="min-w-200px"> --}}
                                                            {{--                                                                <select name="variation_name[]" id="variationSelect" --}}
                                                            {{--                                                                    class="form-select rounded-0" data-control="select2" --}}
                                                            {{--                                                                    data-hide-search="true" --}}
                                                            {{--                                                                    data-placeholder="Please select" multiple> --}}
                                                            {{--                                                                    <option></option> --}}
                                                            {{--                                                                    @foreach ($variations as $variation) --}}
                                                            {{--                                                                        <option value="{{ $variation->id }}"> --}}
                                                            {{--                                                                            {{ $variation->name }}</option> --}}
                                                            {{--                                                                    @endforeach --}}
                                                            {{--                                                                </select> --}}

                                                            {{--                                                            </td> --}}
                                                            <td>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-bordered"
                                                                        id="variation-table">
                                                                        <thead>
                                                                            <tr
                                                                                class="fw-bold fs-6 text-gray-800 text-start bg-gray-500">
                                                                                <th class="text-center min-w-100px">
                                                                                    SKU <i
                                                                                        class="fas fa-exclamation-circle ms-1 fs-7 text-primary cursor-help"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-html="true"
                                                                                        style="cursor:help"
                                                                                        title="SKU is optional. <br/> <br/>
                                                                                           Keep it blank to automatically generate sku."></i>
                                                                                </th>
                                                                                <th class="min-w-100px">Value</th>
                                                                                <th class="min-w-200px">
                                                                                    Default Purchase Price <br />
                                                                                    <i>Exc. tax Inc. tax</i>
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    x Margin(%)
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    Default Selling Price <br />
                                                                                    <i>Exc. Tax</i>
                                                                                </th>
                                                                                <th class="min-w-100px">Alert Quantity</th>
                                                                                <th class="min-w-200px">Variation Images
                                                                                </th>

                                                                                <th class=" min-w-50px">
                                                                                    <span id="child-repeater"
                                                                                        name="add" data-repeater-create
                                                                                        class="svg-icon svg-icon-primary svg-icon-4 cursor-pointer add-btn"><svg
                                                                                            width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path opacity="0.3"
                                                                                                d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z"
                                                                                                fill="currentColor" />
                                                                                            <path
                                                                                                d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z"
                                                                                                fill="currentColor" />
                                                                                        </svg>
                                                                                    </span>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody data-repeater-list="variation_lists"
                                                                            id="variation-row">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="separator border-dark my-10 advance-toggle-class d-none"></div>
                                        {{-- Custom Fields  --}}
                                        <div class="row mt-5 advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3">
                                                <label
                                                    class="form-label">{{ __('product/product.custom_field_1') }}</label>

                                                <input type="text" name="custom_field1"
                                                    class="form-control form-control-sm mb-2" placeholder="Custom field1"
                                                    value="" />

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label
                                                    class="form-label">{{ __('product/product.custom_field_2') }}</label>
                                                <input type="text" name="custom_field2"
                                                    class="form-control form-control-sm mb-2" placeholder="Custom field2"
                                                    value="" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label
                                                    class="form-label">{{ __('product/product.custom_field_3') }}</label>
                                                <input type="text" name="custom_field3"
                                                    class="form-control form-control-sm mb-2" placeholder="Custom field3"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="row mb-5 advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3">
                                                <label
                                                    class="form-label">{{ __('product/product.custom_field_4') }}</label>
                                                <input type="text" name="custom_field4"
                                                    class="form-control form-control-sm mb-2" placeholder="Custom field4"
                                                    value="" />
                                            </div>
                                            <div class="col-md-4 mb-3">

                                            </div>
                                            <div class="col-md-4 mb-3">

                                            </div>
                                        </div>

                                        {{-- Product Description  --}}
                                        <div class="mb-5">
                                            <!--begin::Label-->
                                            <h3>{{ __('product/product.product_description') }}</h3>
                                            <!--end::Label-->
                                            <!--begin::Editor-->
                                            <div id="kt_docs_quill_basic" name="product_desc" class="min-h-100px mb-2">

                                            </div>
                                            <input type="hidden" name="quill_data"
                                                value="{{ old('quill_data', $quillData ?? '') }}">
                                            <!--end::Editor-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Set a description to the product for better
                                                visibility.</div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Input group-->

                                        <div class="ad_pd">
                                            <div class="d-flex justify-content-center align-items-center mt-3 mb-6">
                                                <div class="col-6 col-md-2 fs-4  fw-semibold  text-primary">
                                                    Additional Products
                                                </div>
                                                <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                                            </div>
                                            <!--end::Additional product-->
                                            <div class="row align-items-center mb-8">
                                                <div class="col-12">
                                                    <div class="input-group quick-search-form p-0">
                                                        <div class="input-group-text">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control form-control-sm rounded-end-3"
                                                            id="searchInput" placeholder="Search...">
                                                        <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card z-index-1 autocomplete shadow"
                                                            id="autocomplete" data-allow-clear="true"
                                                            style="max-height: 300px;z-index: 100;"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-rounded table-striped border gy-4 gs-4"
                                                    id="additional_product_table">
                                                    <!--begin::Table head-->
                                                    <thead class="bg-light rounded-3">
                                                        <!--begin::Table row-->
                                                        <tr class="text-start text-primary fw-bold fs-8 text-uppercase">
                                                            <th class="min-w-125px" style="max-width: 125px">Product</th>
                                                            <th class="w-200px">Quantity</th>
                                                            <th class="w-300px">UOM</th>
                                                            <th class="text-center"><i
                                                                    class="fa-solid fa-trash text-primary"
                                                                    type="button"></i></th>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    </thead>
                                                    <!--end::Table head-->
                                                    <!--begin::Table body-->
                                                    <tbody class="fw-semibold text-gray-600 data-table-body">
                                                        <tr class="dataTables_empty text-center">
                                                            <td colspan="8 ">There is no data to show</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--end::Additional product-->

                                            {{-- Product Disable --}}
                                            <div class="row advance-toggle-class d-none">
                                                <div class="col-md-3 mb-8">
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="tab2_check1">
                                                            <input class="form-check-input" name="product_inactive"
                                                                type="checkbox" value="1" id="tab2_check1" />
                                                            <strong
                                                                class="ms-4 h5">{{ __('product/product.disable') }}</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="packaging_pd advance-toggle-class d-none">
                                            <div class="d-flex justify-content-center align-items-center mt-3 mb-6">
                                                <div class="col-6 col-md-2 fs-4  fw-semibold  text-primary">
                                                    Products Packaging
                                                </div>
                                                <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                                            </div>
                                            <div class="form">
                                                <!--begin::Repeater-->
                                                <div id="packaging_repeater">
                                                    <!--begin::Form group-->
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table table-rounded table-striped border gy-4 gs-4"
                                                                id="packaging_table">
                                                                <!--begin::Table head-->
                                                                <thead class="bg-light rounded-3">
                                                                    <!--begin::Table row-->
                                                                    <tr
                                                                        class="text-start text-primary fw-bold fs-8 text-uppercase">
                                                                        <th class="min-w-100px">Packaging Name</th>
                                                                        <th class="min-w-100px">Quantity</th>
                                                                        <th class="min-w-100px">UOM</th>
                                                                        <th class="min-w-100px">For Purchase</th>
                                                                        <th class="min-w-100px">For Sale</th>
                                                                        <th class="text-center"><i
                                                                                class="fa-solid fa-trash text-primary"
                                                                                type="button"></i></th>
                                                                    </tr>
                                                                    <!--end::Table row-->
                                                                </thead>
                                                                <!--end::Table head-->
                                                                <!--begin::Table body-->
                                                                <tbody class="fw-semibold text-gray-600 data-table-body"
                                                                    data-repeater-list="packaging_repeater">
                                                                    <tr class=" text-center p-row" data-repeater-item>
                                                                        <td class="fv-row">
                                                                            <div class="fv-row text-start">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm "
                                                                                    name="packaging_name"
                                                                                    placeholder="Packaging Name"
                                                                                    data-placeholder="Packaging Name">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="fv-row text-start">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm "
                                                                                    name="packaging_quantity"
                                                                                    placeholder="Qty"
                                                                                    data-placeholder="Packaging Name">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="fv-row text-start">
                                                                                <select
                                                                                    class="form-select form-select-sm unitOfUom"
                                                                                    name="packaging_uom_id"
                                                                                    data-control="select2"
                                                                                    data-kt-select2="true"
                                                                                    data-hide-search="true"
                                                                                    data-placeholder="Select UoM">
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-check float-start  user-select-none">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="for_purchase" />
                                                                                <label class="form-check-label text-start"
                                                                                    for="forPurchase">
                                                                                    For Purchase
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-check justify-content-start user-select-none float-start">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="for_sale" />
                                                                                <label class="form-check-label text-start"
                                                                                    for="forSale">
                                                                                    For Sale
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-danger cursor-pointer user-select-none"
                                                                            p-remove>
                                                                            <i
                                                                                class="fa-solid fa-trash text-danger deleteRow"></i>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!--end::Form group-->

                                                    <!--begin::Form group-->
                                                    <div class="form-group mt-5">
                                                        <a href="javascript:;" data-repeater-create
                                                            class="btn btn-light-primary btn-sm">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            Add
                                                        </a>
                                                    </div>
                                                    <!--end::Form group-->
                                                </div>
                                                <!--end::Repeater-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">

                        <a href="{{ url('/product') }}" id="kt_ecommerce_add_product_cancel"
                            class="btn btn-sm btn-light me-5">Cancel</a>

                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            {{-- <button type="submit" class="btn btn-warning btn-sm">Save & Add Selling-Price-Group Prices</button> --}}
                            <button type="submit" class="btn btn-info btn-sm submit" name="save"
                                value="app_opening_stock">Save & Add Opening Stock</button>
                            <button type="submit" class="btn btn-warning btn-sm submit" name="save"
                                value="save_and_another">Save & Add Another</button>
                            <button type="submit" class="btn btn-primary btn-sm submit" name="save"
                                value="save">Save</button>
                        </div>
                    </div>
                </div>
                <!--end::Main column-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
    @include('App.product.brand.quickAddBrand')
    @include('App.product.category.quickAddCategory')
    @include('App.product.generic.quickAddGeneric')
    @include('App.product.manufacturer.quickAddManufacturer')
@endsection

@push('scripts')
    <script src="{{ asset('customJs/product/addValidation.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
    <script src="{{ asset('customJs/product/packagingValidation.js') }}"></script>
    @include('App.product.JS.productQuickSearch')
    <script>
        var currentUoMData = [];
        $('#packaging_repeater').repeater({
            initEmpty: true,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function() {
                $(this).slideDown();

                $(this).find('[data-control="select2"]').select2({
                    data: currentUoMData,
                    minimumResultsForSearch: Infinity
                });
                const index = $(this).closest("[data-repeater-item]").index();

                addFields(index);
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
        $(document).on('click', '[p-remove]', function() {
            $(this).closest('.p-row').remove();
        })
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
            placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });
        quill.on('text-change', function() {
            var quillData = quill.root.innerHTML;
            document.querySelector('input[name="quill_data"]').value = quillData;
        });
        // ============= > End:: For Product Description < =======================

        // ============= > Begin:: For Product Type      < =======================
        const selectBox = $("#has_variation");
        const singleBox = $("#single_box");
        const variableBox = $("#variable_box");
        const variableSelection = $('#variation_selection');

        singleBox.show();

        selectBox.on("change", function() {
            const selectedValue = selectBox.val();
            $('#single_alert_qty_ui').toggleClass('d-none');
            // Hide all contact boxes
            singleBox.hide();
            variableBox.hide();
            variableSelection.hide();

            // Show the contact box associated with the selected option
            if (selectedValue === "single") {
                singleBox.show();
            } else if (selectedValue === "variable") {
                variableBox.show();
                variableSelection.show();
            }
        });
        // ============= > End:: For Product Type      < =========================

        // ============= > Begin:: Formula ProfitPercentage and Selling Price ====
        let profitPercentage = (sell, purchase) => Math.ceil(((parseInt(sell) - parseInt(purchase)) * 100) / parseInt(
            purchase));

        let sellingPrice = (profit, purchase) => Math.ceil(((100 + parseInt(profit)) * parseInt(purchase)) / 100);
        // ============= > Begin:: Formula ProfitPercentage and Selling Price ====

        // ============= > Begin:: For Single Product Type Calculate < ===========

        let singleExc = $('[name="single_exc"]');
        let singleInc = $('[name="single_inc"]');
        let singleProfit = $('[name="single_profit"]');
        let singleSelling = $('[name="single_selling"]');

        let anotherPurchase = $('input[name="purchase_price_for_single"]');
        let anotherProfit = $('input[name="profit_margin_for_single"]');
        let anotherSell = $('input[name="sell_price_for_single"]');

        singleExc.on('keyup', (e) => {
            let excVal = e.target.value;
            singleInc.val(excVal);
            anotherPurchase.val(excVal);

            if (singleSelling.val()) {
                let profitValue = profitPercentage(singleSelling.val(), excVal);
                singleProfit.val(profitValue);
                anotherProfit.val(profitValue)
                if (isNaN(profitValue)) {
                    singleProfit.val('')
                    anotherProfit.val('')
                }
            }
        })
        singleInc.on('keyup', (e) => {
            let incVal = e.target.value;
            singleExc.val(incVal);
            anotherPurchase.val(incVal);

            if (singleSelling.val()) {
                let profitValue = profitPercentage(singleSelling.val(), incVal);
                singleProfit.val(profitValue);
                anotherProfit.val(profitValue)
                if (isNaN(profitValue)) {
                    singleProfit.val('')
                    anotherProfit.val('')
                }
            }
        })
        singleProfit.on('keyup', (e) => {
            let profitValue = e.target.value;
            anotherProfit.val(profitValue)

            if (singleExc.val() || singleInc.val()) {
                let resultSelling = sellingPrice(profitValue, singleExc.val());
                singleSelling.val(resultSelling);
                anotherSell.val(resultSelling)
                if (isNaN(resultSelling)) {
                    singleSelling.val('');
                    anotherSell.val('')
                }
            }
        })
        singleSelling.on('keyup', (e) => {
            let sellingValue = e.target.value;
            anotherSell.val(sellingValue)

            if (singleExc.val() || singleInc.val()) {
                let resultProfit = profitPercentage(sellingValue, singleExc.val());
                singleProfit.val(resultProfit);
                anotherProfit.val(resultProfit)
                if (isNaN(resultProfit)) {
                    singleProfit.val('');
                    anotherProfit.val('')
                }
            }
        })

        // ============= > End:: For Single Product Type Calculate   < ===========


        // ============= > Begin:: For Variation table repeater  < ===============
        $(document).ready(function() {
            let newVariation = `
            <tr data-repeater-item class="variation-add-delete">
                <input type="hidden" name="variation_id[]">
                <td>
                    <input type="text" class="form-control rounded-0 form-control-sm" name="variation_sku[]">
                </td>
                <td>
                    <input type="text" class="form-control rounded-0 form-control-sm variation_name" name="variation_value[]" value="">
                </td>
                <td>
                    <div class="input-group input-group-sm mb-5">
                        <input type="text" class="form-control rounded-0" placeholder="Exc. tax" name="exc_purchase[]" />
                        <input type="text" class="form-control rounded-0" placeholder="Inc. tax" name="inc_purchase[]" />
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
                        <input type="text" class="form-control rounded-0" value="" name="profit_percentage[]"/>
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
                    <input type="text" class="form-control form-control-sm rounded-0" placeholder="Exc. tax" name="selling_price[]">
                </td>
  <td>
     <input type="text" name="alert_quantity[]" class="form-control form-control-sm mb-2" placeholder="Alert Quantity" value="" />
                </td>
                <td>
                    <input type="file" class="form-control form-control-sm rounded-0" name="variation_image[]">
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

                    if (currentSelling.val()) {
                        let profitValue = profitPercentage(currentSelling.val(), excVal);
                        currentProfit.val(profitValue);
                        if (isNaN(profitValue)) {
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

                    if (currentSelling.val()) {
                        let profitValue = profitPercentage(currentSelling.val(), incVal);
                        currentProfit.val(profitValue);
                        if (isNaN(profitValue)) {
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

                    if (currentExc.val() || currentInc.val()) {
                        let resultSelling = sellingPrice(currentProfitValue, currentExc.val());
                        sellingInput.val(resultSelling);
                        if (isNaN(resultSelling)) {
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
                    if (currentExc.val() || currentInc.val()) {
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

            $(document).on('click', '#child-repeater', function() {
                $('#variation-row').append(newVariation);
                calculateVariation();
            })
            $(document).on('click', '#delete-variation', function() {
                $(this).closest('.variation-add-delete').remove();
            })
            // $(document).on('change', '#variationSelect', function() {
            //     let id = $('#variationSelect').val();
            //     $.ajax({
            //         url: '/variation-values/' + id,
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function(data) {
            //             $('.variation-add-delete').remove();
            //             $.each(data, function(index, item) {
            //                 let cloneRow = $(newVariation).clone();
            //                 cloneRow.find('input[name="variation_value[]"]').val(item
            //                     .name)
            //                 cloneRow.find('input[name="variation_id[]"]').val(item.id)
            //                 cloneRow.find('input[name="variation_value[]"]').attr(
            //                     'readonly', true);
            //                 $('#variation-row').append(cloneRow);
            //             });
            //             calculateVariation();
            //         },
            //         error: function(xhr, status, error) {

            //         }
            //     })
            // })
            $(document).on('change', '#variationSelect', function() {
                let selectedIds = $(this).val();
                if (selectedIds.length === 0) {
                    $('.variation-add-delete').remove()
                }
                if (selectedIds.length === 1) {
                    // Only one option selected
                    let id = selectedIds[0];
                    fetchAndCloneRows(id);
                } else if (selectedIds.length > 1) {
                    // Multiple options selected, generate rows for combinations of values
                    generateCombinationRows(selectedIds);
                }
            });

            function fetchAndCloneRows(id) {
                $.ajax({
                    url: '/variation-values/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('.variation-add-delete').remove();
                        data.forEach(item => {
                            let cloneRow = $(newVariation).clone();
                            cloneRow.find('input[name="variation_value[]"]').val(item.name);
                            cloneRow.find('input[name="variation_id[]"]').val(item.id);
                            cloneRow.find('input[name="variation_value[]"]').attr('readonly',
                                true);
                            $('#variation-row').append(cloneRow);
                        });
                        calculateVariation();
                    },
                    error: function(xhr, status, error) {}
                });
            }

            function generateCombinationRows(selectedIds) {
                let variationValues = [];

                // Fetch variation values for each selected ID
                let fetchPromises = selectedIds.map(id => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '/variation-values/' + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                variationValues.push(
                                    data); // Store variation values for each ID
                                resolve();
                            },
                            error: function(xhr, status, error) {
                                reject(error);
                            }
                        });
                    });
                });


                // Wait for all AJAX requests to complete
                Promise.all(fetchPromises)
                    .then(() => {
                        // Generate all combinations of values
                        let combinations = generateCombinations(variationValues);

                        // Render rows for each combination
                        renderCombinationRows(combinations);
                    })
                    .catch(error => {
                        console.error('Error fetching variation values:', error);
                    });
            }

            function generateCombinations(variationValues) {

                if (variationValues.length === 0) return [];
                let generate = (variations, index, combination) => {
                    if (index === variations.length) {
                        return [combination];
                    }
                    let results = [];
                    variations[index].forEach(value => {
                        let newCombination = combination.concat(value);

                        let subResults = generate(variations, index + 1, newCombination);
                        results.push(...subResults);
                    });
                    return results;
                };

                return generate(variationValues, 0, []);
            }

            function renderCombinationRows(combinations) {
                $('.variation-add-delete').remove();

                // Generate rows for each combination
                combinations.forEach(combination => {
                    let cloneRow = $(newVariation).clone();
                    let values = combination.map(variation => variation.name).join(
                        '-'); // Concatenate variation names
                    let ids = combination.map(variation => variation.id).join(
                        '-');
                    cloneRow.find('input[name="variation_value[]"]').val(values);
                    // cloneRow.find('input[name="variation_id[]"]').each(function(index) {
                    //     $(this).val(combination[index].id);
                    // });
                    cloneRow.find('input[name="variation_id[]"]').val(ids);
                    cloneRow.find('input[name="variation_value[]"]').attr('readonly', true);
                    $('#variation-row').append(cloneRow);
                });

                calculateVariation();
            }




        });

        // ============= > Begin:: For Variation table repeater < ================

        // ============= > Begin:: For Sub Category Select Box  < ================

        $(document).ready(function() {
            const cateSelect = $('#categorySelect');

            cateSelect.on('change', function() {
                let id = cateSelect.val()
                $.ajax({
                    url: '/category/sub-category/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const subCategorySelect = $('#subCategorySelect')[0];
                        subCategorySelect.innerHTML = '';

                        const defaultOption = document.createElement(
                            'option'); // Create default option
                        defaultOption.value = '';
                        defaultOption.text = 'Select an option';
                        $(subCategorySelect).append(defaultOption);

                        for (let item of data) {
                            let option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.name;
                            subCategorySelect.append(option);
                        }

                        $('#subCategorySelect').select2({
                            minimumResultsForSearch: Infinity
                        }); // Initialize select2 plugin

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
            if (profit !== '') {
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

        $(document).on('change', 'select[name="unit_categories"]', function() {
            let unit_category_id = $(this).val();
            $('.unitOfUom').empty();

            $.ajax({
                url: `/uom/category/get/${unit_category_id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(results) {
                    console.log(results);
                    $('.uomDatas').empty();
                    let data = [];
                    for (let item of results) {

                        data = [...data,
                            {
                                'id': item.id,
                                'text': item.name,
                                'data-uom-name': item.name
                            }
                        ]
                    }
                    currentUoMData = data;

                    let selectElement = $('.uomDatas');
                    selectElement.empty(); // Clear existing options
                    for (let item of data) {
                        let option = $('<option></option>').val(item.id).text(item.text).attr(
                            'data-uom-name', item.text);
                        selectElement.append(option);
                    }


                    $('.uomDatas').select2({
                        minimumResultsForSearch: Infinity,
                    });


                    setTimeout(function() {
                        let selectedOption = $('select[name="uom_id"]').find('option:selected');
                        let uomName = selectedOption.attr('data-uom-name');


                        $('.uom-label').text('(' + uomName + ')');

                    }, 150);
                    $('[name="uom_id"]').val(data[0].id).trigger('change');
                },
                error: function(e) {
                    console.log(e.responseJSON.error);
                }
            });
        });

        // ============= > Begin:: For UOM  < ==================
        $(document).on('change', 'select[name="uom_id"]', function() {
            let uom_id = $(this).val();

            let selectedOption = $(this).find('option:selected');
            let uomName = selectedOption.attr('data-uom-name');

            $('.uom-label').text('(' + uomName + ')');

            $.ajax({
                url: `/uom/get/${uom_id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(results) {
                    $('.unitOfUom').empty();
                    // const purchaseUoM = $('.unitOfUom');
                    // purchaseUoM.innerHTML = '';

                    // const defaultOption = document.createElement('option'); // Create default option
                    // defaultOption.value = '';
                    // defaultOption.text = 'Select an option';
                    // $(purchaseUoM).append(defaultOption);
                    let data = [];
                    for (let item of results) {
                        // let option = document.createElement('option');
                        data = [...data,
                            {
                                'id': item.id,
                                'text': item.name
                            }
                        ]
                    }
                    currentUoMData = data;
                    $('.unitOfUom').select2({
                        data,
                        minimumResultsForSearch: Infinity
                    }); // Initialize select2 plugin
                },
                error: function(e) {
                    console.log(e.responseJSON.error);
                }
            });
        })
        // ============= > End:: For UOM  < ==================

        // ============= > Begin:: For Brand  < ==================
        $('.quick-add-brand').on('click', function(e) {
            // e.preventDefault();
            let brand_name = $(document).find('input[name="brand_name"]').val();
            let brand_desc = $(document).find('input[name="brand_desc"]').val();
            let form_type = "from_product";

            var formData = {
                brand_name,
                brand_desc,
                form_type
            };

            $.ajax({
                url: '/brands/create',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    $('select[name="brand"]').empty();

                    $('select[name="brand"]').append($('<option>'));

                    $.each(response.brands, function(index, brand) {
                        $('select[name="brand"]').append($('<option>', {
                            value: brand.id,
                            text: brand.name
                        }));
                        $('select[name="brand"]').val(brand.id).trigger('change');
                    });

                    $('#kt_modal_brand').modal('hide');
                },
                error: function(error) {

                }
            });
        })
        // ============= > End:: For Brand  < ==================

        // ============= > Begin:: For Category  < ==================
        $('.quick-add-category').on('click', function(e) {
            // e.preventDefault();
            let category_name = $(document).find('input[name="category_name"]').val();
            let category_code = $(document).find('input[name="category_code"]').val();
            let parent_id = $(document).find('select[name="parent_id"]').val();
            let category_desc = $(document).find('input[name="category_desc"]').val();
            let form_type = "from_product";

            var formData = {
                category_name,
                category_code,
                parent_id,
                category_desc,
                form_type
            };

            $.ajax({
                url: '/category/create',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    $('select[name="category"]').empty();

                    $('select[name="category"]').append($('<option>'));

                    $.each(response.categories, function(index, category) {
                        $('select[name="category"]').append($('<option>', {
                            value: category.id,
                            text: category.name
                        }));
                        $('select[name="category"]').val(category.id).trigger('change');
                    });

                    $('#kt_modal_category').modal('hide');
                },
                error: function(error) {

                }
            });
        })
        // ============= > End:: For Category  < ==================

        // ============= > Begin:: For Generic  < ==================
        $('.quick-add-generic').on('click', function(e) {
            let generic_name = $(document).find('input[name="generic_name"]').val();
            let form_type = "from_product";

            var formData = {
                generic_name,
                form_type
            };

            $.ajax({
                url: '/generic/create',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    $('select[name="generic"]').empty();

                    $('select[name="generic"]').append($('<option>'));

                    $.each(response.generics, function(index, generic) {
                        $('select[name="generic"]').append($('<option>', {
                            value: generic.id,
                            text: generic.name
                        }));
                        $('select[name="generic"]').val(generic.id).trigger('change');
                    });
                    $('#kt_modal_generic').modal('hide');
                },
                error: function(error) {

                }
            });
        })
        // ============= > End:: For Generic  < ==================

        // ============= > Begin:: For Manufacturer  < ==================
        $('.quick-add-manufacturer').on('click', function(e) {
            let manufacturer_name = $(document).find('input[name="manufacturer_name"]').val();
            let form_type = "from_product";

            var formData = {
                manufacturer_name,
                form_type
            };

            $.ajax({
                url: '/manufacturer/create',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    $('select[name="manufacturer"]').empty();

                    $('select[name="manufacturer"]').append($('<option>'));

                    $.each(response.manufacturers, function(index, manufacturer) {
                        $('select[name="manufacturer"]').append($('<option>', {
                            value: manufacturer.id,
                            text: manufacturer.name
                        }));
                        $('select[name="manufacturer"]').val(manufacturer.id).trigger('change');
                    });

                    $('#kt_modal_manufacturer').modal('hide');
                },
                error: function(error) {

                }
            });
        })
        // ============= > End:: For Manufacturer  < ==================
    </script>

    @if (session('message'))
        <script>
            toastr.success("{{ session('message') }}");
        </script>
    @endif
@endpush
