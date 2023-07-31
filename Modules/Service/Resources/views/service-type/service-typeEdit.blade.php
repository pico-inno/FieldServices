@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('service_icon', 'active')
@section('service_show', 'active show')
@section('service_type_active_show', 'active')



@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('service.edit_service_type') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('service.service') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('service-type') }}" class="text-muted text-hover-primary">{{ __('service.service_type_list') }}</a>
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
			<!--begin::Card-->
			<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('service-type.update', $serviceType->id) }}" method="POST">
                @method('PUT')
				@csrf
				<div class="card card-flush">
					<!--begin::Card body-->
					<div class="card-body">
						<!--begin:::Tab content-->
						<div class="tab-content" id="myTabContent">
							<!--begin:::Tab pane-->
							<div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
								<!--begin::Heading-->
                                <div class="row mb-7">
                                    <div class="col-md-9 offset-md-3">
                                        <h2>{{ __('service.edit_service_type') }}</h2>
                                    </div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">{{ __('product/product.name') }}</span>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-6">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="name" value="{{old('name',$serviceType->name)}}" placeholder="Service type name"/>
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
                                            <span class="">{{ __('product/category.description') }}</span>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-6">
                                        <!--begin::Input-->
                                        <textarea name="description" id="" cols="30" rows="3" class="form-control">
                                            {{ old('description', $serviceType->description) }}
                                        </textarea>
                                        <!--end::Input-->
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <!--end::Input group-->
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
                                            <input class="form-check-input" name="is_active" type="checkbox" value="1" @checked($serviceType->is_active) id="flexCheckChecked" />
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
				<div class="d-flex justify-content-start mt-5">
					<!--begin::Button-->
					<a href="{{ route('service-type') }}"  class="btn btn-light btn-sm me-5">{{ __('product/product.cancle') }}</a>
					<!--end::Button-->
					<!--begin::Button-->
					<button type="submit" class="btn btn-primary btn-sm">
						{{ __('product/product.save') }}
					</button>
					<!--end::Button-->
				</div>
			</form>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Content-->
@endsection

@push('scripts')
   
@endpush
