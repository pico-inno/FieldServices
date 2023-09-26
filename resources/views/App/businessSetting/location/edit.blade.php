@extends('App.main.navBar')


@section('setting_active','active')
@section('setting_active_show','active show')
@section('location_here_show','here show')


@section('styles')
		<link href="{{asset('customCss/businessSetting.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Edit Bussiness Location</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Setting</li>
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted text-hover-primary">Bussiness Location</a>
        </li>
        <li class="breadcrumb-item text-dark">Edit Bussiness Location </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')


<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Container-->
    <div class="container-xxl" id="location">

        <!--begin::Modals-->
        <div class="card">
            <div class="card-body user-select-none">
            <!--begin::Form-->
            <form class="form" action="{{route('location_update',$bl->id)}}" method="POST" id="location_form">
                @csrf
                 <!--begin::Scroll-->
                    <div class="me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="false" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                       <div class="row">
                            <!--begin::Input group-->
                            <div class="fv-row mb-12  col-12 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="" name="name" value="{{old('name',$bl->name)}}" />
                                <!--end::Input-->
                            </div>
                       </div>
                       <div class="row">
                                                    <!--end::Input group-->
                            <div class="col-md-4 col-12 d-flex align-items-center  mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700" for='is_active'>Is Active?</label>
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" @checked($bl->is_active)>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-center  mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_purchase_order'>Allow Purchase Order</label>
                                    <input type="checkbox" name="allow_purchase_order" id="allow_purchase_order" value="1" class="form-check-input"  @checked($bl->allow_purchase_order == 1)>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 d-flex align-items-end mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_sale_order'>Allow Sale Order</label>
                                    <input type="checkbox" name="allow_sale_order" id="allow_sale_order" value="1" class="form-check-input"  @checked($bl->allow_sale_order == 1) >
                                </div>
                            </div>
                       </div>
                        <!--begin::Input group-->
                        <div class="row row-cols">
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Location ID</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Location Id" name="location_id" value="{{old('location_id',$bl->location_id)}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">address</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="address" name="address" value="{{old('address',$bl->address)}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">City</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="city" name="city" value="{{old('city',$bl->city)}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Zip Code:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Zip Code" name="zip_code" value="{{old('zip_code',$bl->zip_code)}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">State</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="State" name="state" value="{{old('state',$bl->state)}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Country</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="country" name="country" value="{{old('country',$bl->country)}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Mobile</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Mobile" name="mobile" value="{{old('mobile',$bl->mobile)}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Alternate contact number:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Alternate contact number" name="alternate_number" value="{{old('alternate_number',$bl->alternate_number)}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Email</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="mail" class="form-control form-control-sm " placeholder="email" name="email" value="{{old('email',$bl->email)}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Website</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm" placeholder="website" name="website" value="{{old('website',$bl->website)}}" />
                                <!--end::Input-->
                            </div>

                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Price list:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="price_lists_id" id="" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-placeholder="Price List" placeholder="price list">

                                    <option value=""></option>
                                    @foreach ($priceLists as $p)
                                        <option value="{{$p->id}}" @selected($p->id==$bl->price_lists_id)>{{$p->name}}</option>
                                    @endforeach
                               </select>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_1">
                                    <span class="required">custom field 1</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="" class="form-control form-control-sm " placeholder="custom field 1" name="custom_field1" value="{{old('custom_field1')}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_2">custom field 2</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Custom Field 2" name="custom_field2" value="{{old('custom_field2')}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_3">
                                    <span class="required">custom field 3</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Custom Field 3" name="custom_field3"  value="{{old('custom_field3')}}"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_4">custom field 4</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Custom Field 4" name="custom_field4"  value="{{old('custom_field4')}}" />
                                <!--end::Input-->
                            </div>
                        </div>

                        <div class="separator border-gray-600 my-8"></div>


                    </div>
                    <!--end::Scroll-->
                    <div class="d-flex flex-center mt-10" >
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary data-kt-location-action="submit"">
                            <span class="indicator-label">Submit</span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
            </form>
            <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')
		<script src="{{asset('customJs/businessJs/locationValidation.js')}}"></script>
@endpush
