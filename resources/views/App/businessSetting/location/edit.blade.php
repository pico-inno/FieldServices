@extends('App.main.navBar')

@section('setting_active','active')
@section('setting_active_show','active show')
@section('location_here_show','here show')
@section('location_add_nav','active')

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
                    <div class="me-n7 pe-7 mt-5" id="kt_modal_add_customer_scroll" data-kt-scroll="false"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <div class="row justify-content-between">
                            <!--begin::Input group-->
                            <div
                                class="fv-row col-12 col-lg-6 pe-lg-19  d-flex mb-5 justify-content-between align-items-end">
                                <div class="">
                                    <label class="required fs-6 fw-semibold mb-2">Location Name</label>
                                </div>
                                <div class="col-6 ms-5">
                                    <x-forms.input placeholder="Eg : Warehouse" name="name" :value='$bl->name'></x-forms.input>
                                </div>
                            </div>
                            <div
                                class="fv-row col-12 col-md-6 pe-lg-19 d-flex mb-5 mt-3 justify-content-between align-items-end">
                                <!--begin::Label-->
                                <div class="">
                                    <label class="required fs-6 fw-semibold mb-2">Location Type</label>
                                </div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="col-6 ms-5">
                                    <x-forms.nob-select placeholder="Select Location Type" name="location_type">
                                        <option value=""></option>
                                        @foreach ($locationType as $lt)
                                        <option value="{{$lt->id}}" @selected($lt->id==$bl->location_type)>{{$lt->name}}</option>
                                        @endforeach
                                    </x-forms.nob-select>
                                </div>
                                <!--end::Input-->
                            </div>
                            <div
                                class="fv-row col-12 col-md-6 d-flex pe-lg-19 mb-10 mt-3 justify-content-between align-items-end">
                                <!--begin::Label-->
                                <div class="">
                                    <label class="required fs-6 fw-semibold mb-2">Parent Location Name</label>
                                </div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="col-6 ms-5">
                                    {{-- @foreach ($locations as $lt) --}}
                                    {{-- {{dd($lt)}} --}}
                                    {{-- @endforeach --}}
                                    <x-forms.nob-select placeholder="Parent Location" name="parent_location_id"
                                        attr='data-allow-clear="true"'>
                                        <option value=""></option>
                                        @foreach ($locations as $l)
                                        @php
                                        $parentName=getParentName($l->parentLocation);
                                        @endphp
                                        <option value="{{$l->id}}" @selected($l->id==$bl->parent_location_id)>{{$parentName ? substr($parentName,2).' / ' : ''}}
                                            {{$l->name}}</option>
                                        @endforeach
                                    </x-forms.nob-select>
                                </div>
                                <!--end::Input-->
                            </div>
                            <div
                                class="fv-row col-12 col-md-6 d-flex pe-lg-19 mb-10 mt-3 justify-content-between align-items-end">
                                <!--begin::Label-->
                                <div class="">
                                    <label class="required fs-6 fw-semibold mb-2">Inventory Flow</label>
                                </div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="col-6 ms-5">
                                    <x-forms.nob-select placeholder="Inventory Flow" name="inventory_flow"
                                        attr="data-hide-search='true'">
                                        <option value=""></option>
                                        <option value="fifo" @selected($bl->inventory_flow=='fifo')>FIFO</option>
                                        <option value="lifo" @selected($bl->inventory_flow=='lifo')>LIFO</option>
                                    </x-forms.nob-select>
                                </div>
                                <!--end::Input-->
                            </div>

                        </div>
                        <div class="row">
                            <!--end::Input group-->
                            <div class="col-md-4 col-12 d-flex align-items-center  mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700" for='is_active'>Is Active?</label>
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                                        @checked($bl->is_active)>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-center  mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_purchase_order'>Allow Purchase Order</label>
                                    <input type="checkbox" name="allow_purchase_order" id="allow_purchase_order" value="1" class="form-check-input"
                                        @checked($bl->allow_purchase_order == 1)>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 d-flex align-items-end mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_sale_order'>Allow Sale Order</label>
                                    <input type="checkbox" name="allow_sale_order" id="allow_sale_order" value="1" class="form-check-input"
                                        @checked($bl->allow_sale_order == 1) >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <x-location-input label="Location Code">
                                    <x-forms.input placeholder="Location Code" name="location_code" :value="$bl->location_code"></x-forms.input>
                                </x-location-input>

                                <x-location-input label="Address">
                                    <x-forms.input placeholder="Address" name="address" :value="arr($address,'address')"></x-forms.input>
                                </x-location-input>
                                <x-location-input label="City">
                                    <x-forms.input placeholder="City" name="city" :value="arr($address,'city')"></x-forms.input>
                                </x-location-input>
                                <x-location-input label="State">
                                    <x-forms.input placeholder="State" name="state" :value="arr($address,'state')"></x-forms.input>
                                </x-location-input>
                                <x-location-input label="Country">
                                    <x-forms.input placeholder="Country" name="country" :value="arr($address,'country')"></x-forms.input>
                                </x-location-input>
                                <x-location-input label="Zip Code:">
                                    <x-forms.input placeholder="Zip Code:" name="zip_postal_code" :value="arr($address,'zip_postal_code')"></x-forms.input>
                                </x-location-input>
                            </div>
                            <div class="col-6">
                                <x-location-input label="Mobile">
                                    <x-forms.input placeholder="Mobile" name="mobile" :value="arr($address,'mobile')"></x-forms.input>
                                </x-location-input>

                                <x-location-input label="Alternate contact number">
                                    <x-forms.input placeholder="Alternate contact number" name="alternate_number" :value="arr($address,'alternate_number')">
                                    </x-forms.input>
                                </x-location-input>
                                <x-location-input label="Email">
                                    <x-forms.input placeholder="Email" name="email" :value="arr($address,'email')"></x-forms.input>
                                </x-location-input>
                                <x-location-input label="Price list:">
                                    <x-forms.nob-select placeholder="Price list" name="price_lists_id">
                                        <option value=""></option>
                                        @foreach ($priceLists as $p)
                                        <option value="{{$p->id}}" @selected($bl->price_lists_id==$p->id)>{{$p->name}}</option>
                                        @endforeach
                                    </x-forms.nob-select>
                                </x-location-input>
                                <x-location-input label="Invoice scheme:">
                                    <x-forms.nob-select placeholder="Invoice scheme">
                                        <option value=""></option>
                                        <option value="">Please Select</option>
                                        <option value="">Default</option>
                                    </x-forms.nob-select>
                                </x-location-input>
                            </div>
                        </div>
                        <!--begin::Input group-->
                        <div class="d-flex">

                            <!--begin::Input group-->
                            {{-- <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Invoice Layouts For POS:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="" id="" class="form-select form-select-sm" data-control="select2"
                                    data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Invoice layout for sale:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="" id="" class="form-select form-select-sm" data-control="select2"
                                    data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                                </select>
                                <!--end::Input-->
                            </div> --}}
                            <!--end::Input group-->
                        </div>

                        <div class="separator border-gray-300 my-8"></div>




                    </div>
                    <!--end::Scroll-->
                    <div class="d-flex flex-center mt-10">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel"
                            class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary" data-kt-location-action="submit">
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
<script src={{asset('customJs/businessJs/locationValidation.js')}}></script>
@endpush
