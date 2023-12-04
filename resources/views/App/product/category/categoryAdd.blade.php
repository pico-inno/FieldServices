@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('categories_menu_link', 'active')


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/category.add_category') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('categories') }}" class="text-muted text-hover-primary">{{ __('product/category.category_list') }}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{ __('product/product.add') }}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form action="{{ route('category.create') }}" method="POST" enctype="multipart/form-data" id="kt_ecommerce_add_category_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/categories.html">
                @csrf
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('product/category.add_category') }}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">{{ __('product/category.category_name') }}</label>
                                <input type="text" name="category_name" class="form-control form-control-sm mb-2" placeholder="Category name" value="" />
                                @error('category_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="text-muted fs-7 mt-2">A category name is required and recommended to be unique.</div>
                            </div>
                            <div class="mb-10 fv-row">
                                <label class="form-label">{{ __('product/category.category_code') }}</label>
                                <input type="text" name="category_code" class="form-control form-control-sm mb-2" placeholder="Category code" value="" />
                                <div class="text-muted fs-7">Category code is same as <b>HSN code</b></div>
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="form-label">{{ __('product/category.description') }}</label>
                                <textarea name="category_desc" id="" cols="30" rows="5" class="form-control"></textarea>

                                <div class="text-muted fs-7">Set a description to the category for better visibility.</div>

                            </div>
                            @if(hasModule('Service') && isEnableModule('Service'))
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input type="checkbox" name="service_category" value="1"    class="form-check-input me-3">
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="service_category">
                                                <div class="fw-bold">This category make for service category</div>

                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                            @endif
                        </div>
                    </div>

                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('product/category.add_sub_category') }}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <label class="form-label mb-5">{{ __('product/category.select_parent_category') }}</label>

                                <select class="form-select mb-2 form-select-sm" name="parent_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                    <option  selected="selected">Select</option>
                                    @foreach ($categories as $category)
                                        <option  value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start">

                        <a href="{{ route('categories') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>

                        <button type="submit" class="btn btn-primary btn-sm" name="save" value="save">
                            {{ __('product/product.save') }}
                        </button>
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
