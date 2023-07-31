@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('variations_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/variation.add_variation') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('variations') }}" class="text-muted text-hover-primary">{{ __('product/variation.variation') }}</a>
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
			<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('variation.create') }}" method="POST">
				@csrf
			
				<div class="card card-flush">		
					<div class="card-body">
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
								
									<div class="row mb-7">
										<div class="col-md-9 offset-md-3">
											<h2>{{ __('product/variation.add_variation') }}</h2>
										</div>
									</div>
					
									<div class="row fv-row mb-7">
										<div class="col-md-3 text-md-end">
											<label class="fs-6 fw-semibold form-label mt-3">
												<span class="required">{{ __('product/variation.variation_name') }}</span>
											</label>
										</div>
										<div class="col-md-6">						
											<input type="text" class="form-control form-control-sm form-control-solid" name="variation_name" value="" placeholder="Variation name"/>
											@error('variation_name')
												<div class="text-danger my-2">{{ $message }}</div>
											@enderror
										</div>
										<div class="col-md-3"></div>
									</div>
					
									<div class="row fv-row mb-7">
										<div class="col-md-3 text-md-end">
											<label class="fs-6 fw-semibold form-label mt-3">
												<span class="">{{ __('product/variation.add_variation_value') }}</span>
											</label>
										</div>							
										<div class="col-md-9" id="kt_docs_repeater_basic">							
											<div class="form-group">
												<div data-repeater-list="variation_value">
													<div data-repeater-item>
														<div class="form-group row mb-3">
															<div class="col-md-8">
																<input type="text" class="form-control mb-2 mb-md-0" name="value"/>
															</div>
															<div class="col-md-4">
																<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger ">
																	<i class="la la-trash-o"></i>{{ __('product/product.delete') }}
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>								
											<div class="form-group mt-5">
												<a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
													<i class="la la-plus"></i>{{ __('product/product.add') }}
												</a>
											</div>
									
										</div>							
									</div>
								</div>							
							</div>					
						</div>				
					</div>
			
					<div class="d-flex justify-content-start mt-5">
             
                        <a href="{{ url('/variation') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
       
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('product/product.save') }}
                        </button>
                
                    </div>
			</form>
		</div>
		<!--end::Container-->
	</div>
	<!--end::Content-->
@endsection

@push('scripts')
    <script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
    <script>
        $('#kt_docs_repeater_basic').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@endpush
