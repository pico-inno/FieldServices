@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('service_icon', 'active')
@section('service_show', 'active show')
@section('service_sales_active_show', 'active')



@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('service.add_service_sale') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('service.service') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('service-sale') }}" class="text-muted text-hover-primary">{{ __('service.service_sale_list') }}</a>
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
			<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('service-sale.create') }}" method="POST">
				@csrf
				<div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                    <div class="row">
                        <div class="col-12 input-group flex-nowrap">
                            <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                            <select name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true" data-placeholder="Select locations">
                                <option></option>
                                @foreach ($locations as $location)
                                    <option value="{{$location->id}}">{{$location->name}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Business location from where you went to sell </span>">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                            </button>
                        </div>
                        @error('business_location_id')
                            <div class="text-danger my-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-10 col-12 col-md-6">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        {{ __('service.voucher_no') }}
                                    </label>
                                    <input type="text" name="service_voucher_no" class="form-control mb-2" placeholder="Service voucher no" value="" />
                                </div>
                                <div class="mb-10 col-12 col-md-6">
                                    <label class="form-label fs-6 fw-semibold required">{{ __('service.customer') }}</label>
                                    <div class="input-group input-group-sm flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select class="form-select fw-bold rounded-0"  name="contact_id" data-kt-select2="true" data-placeholder="Select customer name" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="false">
                                            <option></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}">{{ $customer->getFullNameAttribute() ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                            <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                        </button>
                                    </div>
                                    @error('contact_id')
                                        <div class="text-danger my-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-10 col-12 col-md-6">
                                    <label class="form-label fs-6 fw-semibold required" for="">
                                        {{ __('service.status') }}
                                    </label>
                                    <select name="status" id="" class="form-select" data-kt-select2="true" data-placeholder="Select status" data-hide-search="true">
                                        <option></option>
                                        <option value="request">Request</option>
                                        {{-- <option value="order">Order</option> --}}
                                        {{-- <option value="quotation">Quotation</option> --}}
                                        <option value="draft">Draft</option>
                                        <option value="suspend">Suspend</option>
                                        <option value="final">Final</option>
                                        <option value="confirm">Confirm</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger my-2">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-8 d-flex justify-content-between">
                                <div class="col-6 col-md-7">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control rounded-start-0" id="searchInput" placeholder="Search...">
                                        <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card  autocomplete shadow" id="search_container" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 btn-light-primary btn my-5 my-lg-0"   data-bs-toggle="modal" type="button" data-bs-target="#add_service_modal" data-href="">
                                    <i class="fa-solid fa-plus me-2 text-white"></i> {{ __('service.add_service') }}
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-row-dashed fs-6 gy-5 mt-10" id="sale_table">
                                    <!--begin::Table head-->
                                    <thead class="">
                                        <!--begin::Table row-->
                                        <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                            <th class="min-w-125px">
                                                Service
                                            </th>
                                            <th class="min-w-125px">Unit</th>
                                            <th class="min-w-150px">Quantity</th>
                                            <th class="min-w-125px">Sale Price <br> Without Discount</th>
                                            <th class="min-w-125px">Discount</th>
                                            <th class="min-w-125px">Sale Price</th>
                                            <th class="" ><i class="fa-solid fa-trash text-primary" type="button" readonly></i></th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600" id="table-body">
                                        <tr class="dataTables_empty text-center d-none">
                                            <td colspan="7" >There is no data to show</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="separator my-5"></div>
                            <div class="col-4 float-end mt-3">
                                <table class="col-12 ">
                                <tbody>
                                    <tr>
                                        <th >{{ __('service.items') }}: <span class="fw-medium fs-5 total_item" id="total-item">0</span></th>
                                        <th class="">{{ __('service.sale_amount') }}: <span class="fw-medium fs-5 sale_amount_txt" id="total-amount">0</span></th>
                                        <input type="hidden" name="sale_amount" class="sale_amount_input" value="0">
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    {{-- Begin:: For Service Used Products --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed fs-6 gy-5 mt-10" id="sale_table">
                                    <!--begin::Table head-->
                                    <thead class="">
                                        <!--begin::Table row-->
                                        <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                            <th class="min-w-125px">Service</th>
                                            <th class="min-w-125px">Product</th>
                                            <th class="min-w-125px">Unit</th>
                                            <th class="min-w-125px">Variation</th>
                                            <th class="min-w-150px">Quantity</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600" id="product-table-body">
                                        <tr class="product_dataTables_empty text-center d-none">
                                            <td colspan="5" >There is no data to show</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- End:: For Service Used Products --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="row total-dis-row">
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold " for="">
                                        {{ __('service.discount_type') }}
                                    </label>
                                    <select name="parent_discount_type" id="parent-discount-type" data-placeholder="Select status" data-hide-search="true" class="form-select total_discount_type" data-control="select2">
                                        {{-- <option></option> --}}
                                        <option value="fixed" selected>Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold " for="">
                                        {{ __('service.discount_amount') }}
                                    </label>
                                    <input type="text" name="total_discount_amount" class="form-control total_discount_amount_input">
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-4 d-flex justify-content-center align-items-center">
                                    <span class=" fs-6 fw-semibold " for="">
                                        {{ __('service.discount') }}:(-) Ks <span class="total_discount_amount_txt">0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-6 col-md-offset-6">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        {{ __('service.remark') }}
                                    </label>
                                    <textarea name="remark" id="" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="mb-7  col-12 col-md-4 d-flex justify-content-center align-items-center offset-lg-8 float-end">
                                    <span class=" fs-6 fw-bold " for="">
                                        <span class="text-gray-800">{{ __('service.total_sale_amount') }}</span>: (+) <span  class="total_sale_amount_txt">0</span>
                                    </span>
                                    <input type="hidden" name="total_sale_amount" class="total_sale_amount" value="0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold " for="">
                                        {{ __('service.paid_amount') }}:
                                    </label>
                                <input type="text" class="form-control paid_amount_input"  name="paid_amount" value="0">
                                </div>
                                <div class="mb-7  col-12 col-md-4 d-flex justify-content-center align-items-center offset-lg-4 float-end">
                                    <span class=" fs-6 fw-semibold " for="" >
                                        {{ __('service.paid_amount') }}:(+) Ks <span class="paid_amount_txt">0</span>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-7 mt-3 col-12 col-md-4 d-none">
                                    <label class="form-label fs-6 fw-semibold " for="">
                                        {{ __('service.balance_amount') }}:
                                    </label>
                                <input type="hidden" class="form-control balance_amount_input" name="balance_amount">
                                </div>
                                <div class="mb-7  col-12 col-md-4 d-flex justify-content-center align-items-center offset-lg-8 float-end">
                                    <span class=" fs-6 fw-semibold " for="">
                                        {{ __('service.balance_amount') }}:(?) Ks <span class="balance_amount_txt">0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary save_btn">{{ __('product/product.save') }}</button>
                     <button type="submit" class="btn btn-success save_btn">{{ __('service.save_and_print') }}</button>
                </div>
			</form>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Content-->

    @include('App.purchase.contactAdd')
    @include('service::services.serviceModalAdd')
@endsection

@push('scripts')

    @include('service::JS.calServiceSale')
@endpush
