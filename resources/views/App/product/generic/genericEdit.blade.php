@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('generic_menu_link', 'active')



@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/generic.edit_generic') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('generic') }}" class="text-muted text-hover-primary">{{ __('product/generic.generic_list') }}</a>
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
			<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('generic.update', $generic->id) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="card card-flush">
				
					<div class="card-body">
			
						<div class="tab-content" id="myTabContent">
				
							<div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
			
								<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('generic.update', $generic->id) }}" method="POST">
									@csrf
									@method('PUT')
					
									<div class="row mb-7">
										<div class="col-md-9 offset-md-3">
											<h2>{{ __('product/generic.edit_generic') }}</h2>
										</div>
									</div>
				
									<div class="row fv-row mb-7">
										<div class="col-md-3 text-md-end">
						
											<label class="fs-6 fw-semibold form-label mt-3">
												<span class="required">{{ __('product/product.name') }}</span>
											</label>
							
										</div>
										<div class="col-md-6">
						
											<input type="text" class="form-control form-control-sm form-control-solid" name="generic_name" value="{{old('generic_name',$generic->name)}}" placeholder="Generic name"/>
								
											@error('generic_name')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>
										<div class="col-md-3"></div>
									</div>
					
							</div>
	
						</div>

					</div>

				</div>
				<div class="d-flex justify-content-start mt-5">
				
					<a href="{{ url('/generic') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
			
					<button type="submit" class="btn btn-primary btn-sm">
						{{ __('product/product.save') }}
					</button>
				
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
