@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('categories_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/category.edit_category') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('categories') }}" class="text-muted text-hover-primary">{{ __('product/category.category_list') }}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{ __('product/product.edit') }}</li>
    </ul>
<!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form id="kt_ecommerce_add_category_form" action="{{ route('category.update', $category->id) }}" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/categories.html">
                @csrf
                @method('PUT')

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                   
                    <div class="card card-flush py-4">
                   
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('product/category.edit_category') }}</h2>
                            </div>
                        </div>
                     
                        <div class="card-body pt-0">
                  
                            <div class="mb-10 fv-row">
                          
                                <label class="required form-label">{{ __('product/category.category_name') }}</label>
                               
                                <input type="text" name="category_name" value="{{old('category_name',$category->name)}}" class="form-control mb-2 form-control-sm" placeholder="Category name" />
                           
                                @error('category_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="text-muted fs-7 mt-2">A category name is required and recommended to be unique.</div>
                            </div>
                            
                            <div class="mb-10 fv-row">
                         
                                <label class="form-label">{{ __('product/category.category_code') }}</label>
                               
                                <input type="text" name="category_code" value="{{old('category_code',$category->short_code)}}" class="form-control form-control-sm mb-2" placeholder="Category code" />
                              
                                <div class="text-muted fs-7">Category code is same as <b>HSN code</b></div>
                             
                            </div>
                            
                            <div>
                              
                                <label class="form-label">{{ __('product/category.description') }}</label>
                                <textarea name="category_desc" id="" cols="30" rows="3" class="form-control">{{ old('category_desc', $category->description) }}</textarea>
                           
                                <div class="text-muted fs-7">Set a description to the category for better visibility.</div>
                            
                            </div>
                           
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
                                    @if ($category->parent_id)                                        
                                        @foreach ($categories as $parent_cate)
                                            <option  value="{{ $parent_cate->id }}" @selected($category->parent_id === $parent_cate->id)>{{ $parent_cate->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                          
                        </div>
                   
                    </div>
                   
                    <div class="d-flex justify-content-start">
                       
                        <a href="{{ route('categories') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
                       
                        <button type="submit" class="btn btn-primary btn-sm">
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

@push('scripts')
    <script src="/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
    <script src="/assets/js/custom/apps/ecommerce/catalog/save-category.js"></script>
@endpush
