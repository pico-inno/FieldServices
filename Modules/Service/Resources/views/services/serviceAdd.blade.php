@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('service_icon', 'active')
@section('service_show', 'active show')
@section('service_active_show', 'active')



@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('service.add_service') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('service.service') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('service') }}" class="text-muted text-hover-primary">{{ __('service.service_list') }}</a>
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
			<!--begin::Card-->
			<form id="service_add_form" class="form" action="{{ route('service.create') }}" method="POST">
				@csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin:::Tab content-->
                            <div class="tab-content" id="myTabContent">
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>{{ __('service.add_service') }}</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">{{ __('service.service_name') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="name" value="" placeholder="Service name"/>
                                            <!--end::Input-->
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">{{ __('service.service_code') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="service_code" value="" placeholder="Service code"/>
                                            <!--end::Input-->
                                            @error('service_code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">{{ __('service.service_type') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select mb-2 form-select-sm" name="service_type_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($service_types as $service_type)
                                                    <option  value="{{ $service_type->id }}">{{ $service_type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('service_type_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">{{ __('product/product.uom') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select mb-2 form-select-sm" name="uom_id" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($uoms as $uom)
                                                    <option  value="{{ $uom->id }}">{{ $uom->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('uom_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">{{ __('service.price') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="price" value="" placeholder="Price"/>
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">{{ __('service.is_active') }}</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" name="active" type="checkbox" value="1" id="flexCheckChecked" checked />
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    {{ __('service.is_active') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end:::Tab pane-->
                            </div>
                            <!--end:::Tab content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('product/product.add_product') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="row align-items-center mb-8 d-flex justify-content-between">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control form-control-sm rounded-start-0" id="search-product" placeholder="Search products...">
                                        <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card autocomplete shadow" id="search_container" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed fs-6 gy-5 mt-10" id="sale_table">
                                        <!--begin::Table head-->
                                        <thead class="">
                                            <!--begin::Table row-->
                                            <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                                <th class="min-w-125px">
                                                    Product
                                                </th>
                                                <th class="min-w-125px">UoM</th>
                                                <th class="min-w-125px">Variation</th>
                                                <th class="min-w-150px">Quantity</th>
                                                <th class="" ><i class="fa-solid fa-trash text-primary" type="button" readonly></i></th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600" id="table-body">
                                            <tr class="dataTables_empty text-center d-none">
                                                <td colspan="5" >There is no data to show</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="col-6 col-md-3 btn-light-primary btn my-5 my-lg-0"   data-bs-toggle="modal" type="button" data-bs-target="#add_service_modal" data-href="">
                                    <i class="fa-solid fa-plus me-2 text-white"></i> Add new service
                                </div> --}}
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <div class="d-flex justify-content-start mt-5">
                        <!--begin::Button-->
                        <a href="{{ route('service') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('product/product.save') }}
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
			</form>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Content-->
@endsection

@push('scripts')
    @include('service::JS.productForService')
    {{-- <script>
        let productsList = @json($products);

        $.each(productsList, function(index, value){
            $('#select-product').append(`<option value="${value.id}">${value.name}</option>`)
        })

        var inputElm = document.querySelector('#product-tagify');

        $('#select-product').change(function(){
            let currentVal = parseInt($(this).val());
            let currentName = $(this).find('option:selected').text();

            var tagify = new Tagify(inputElm)

            let tagValue = currentVal + ' | ' + currentName
            tagify.addTags(tagValue)
        })
    </script> --}}
@endpush
