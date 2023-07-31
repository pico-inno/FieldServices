@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('warranties_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Edit Warranty</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Products</li>
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted text-hover-primary">Warranty List</a>
        </li>
        <li class="breadcrumb-item text-dark">edit</li>
    </ul>
<!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Container-->
		<div class="container-xxl" id="kt_content_container">
			<!--begin::Card-->
			<div class="card card-flush">
				<!--begin::Card body-->
				<div class="card-body">
					<!--begin:::Tabs-->
					<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15">
						<!--begin:::Tab item-->
						{{-- <li class="nav-item">
							<a class="nav-link text-active-primary pb-5 active" data-bs-toggle="tab" href="#kt_ecommerce_settings_general">
                                Edit Warranty</a>
						</li> --}}
						<!--end:::Tab item-->
					</ul>

					<!--end:::Tabs-->
					<!--begin:::Tab content-->
					<div class="tab-content" id="myTabContent">
						<!--begin:::Tab pane-->
						<div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
							<!--begin::Form-->
							<form id="kt_ecommerce_settings_general_form" class="form" action="#">
								<!--begin::Heading-->
								<div class="row mb-7">
									<div class="col-md-9 offset-md-3">
										<h2>Edit Warranty</h2>
									</div>
								</div>
								<!--end::Heading-->
								<!--begin::Input group-->
								<div class="row fv-row mb-7">
									<div class="col-md-3 text-md-end">
										<!--begin::Label-->
										<label class="fs-6 fw-semibold form-label mt-3">
											<span class="required">Name</span>
										</label>
										<!--end::Label-->
									</div>
									<div class="col-md-6">
										<!--begin::Input-->
										<input type="text" class="form-control form-control-sm form-control-solid" name="warranty_name" value="" placeholder="Name"/>
										<!--end::Input-->
									</div>
									<div class="col-md-3"></div>
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="row fv-row mb-7">
									<div class="col-md-3 text-md-end">
										<!--begin::Label-->
										<label class="fs-6 fw-semibold form-label mt-3">
											<span>Description</span>
										</label>
										<!--end::Label-->
									</div>
									<div class="col-md-6">
										<!--begin::Input-->
										<textarea class="form-control form-control-solid" name="warranty_desc" placeholder="Description"></textarea>

										<!--end::Input-->
									</div>
									<div class="col-md-3"></div>
								</div>
								<!--end::Input group-->
                                <!--begin::Input group-->
								<div class="row fv-row mb-7">
									<div class="col-md-3 text-md-end">
										<!--begin::Label-->
										<label class="fs-6 fw-semibold form-label mt-3">
											<span>Duration</span>
										</label>
										<!--end::Label-->
									</div>
									<div class="col-md-6">
										<!--begin::Input-->
										<div class="input-group input-group-sm mb-5">
                                            <input type="number" class="form-control" placeholder="Duration">
                                            <span>
                                                <select class="form-select form-select-sm rounded-0" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                    <option>Please Select</option>
                                                    <option value="1">Days</option>
                                                    <option value="0">Months</option>
                                                    <option value="0">Years</option>
                                                </select>
                                            </span>
                                        </div>
										<!--end::Input-->
									</div>
									<div class="col-md-3"></div>
								</div>
								<!--end::Input group-->
								<!--begin::Action buttons-->
								<div class="row py-5">
									<div class="col-md-9 offset-md-3">
										<div class="d-flex">
											<!--begin::Button-->
											<a href="{{ url('/warranites') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-light me-5">Cancel</a>
											<!--end::Button-->
											<!--begin::Button-->
											<button type="submit" class="btn btn-primary btn-sm">Save</button>
											<!--end::Button-->
										</div>
									</div>
								</div>
								<!--end::Action buttons-->
							</form>
							<!--end::Form-->
						</div>
						<!--end:::Tab pane-->
					</div>
					<!--end:::Tab content-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Content-->
@endsection

@push('scripts')

@endpush
