@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('units_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/unit-and-uom.edit_uom') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('unit-category') }}" class="text-muted text-hover-primary">{{ __('product/unit-and-uom.unit_list') }}</a>
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
			<form id="kt_ecommerce_settings_general_form" class="form" action="{{ route('uom.update', $uom->id) }}" method="POST">
				@csrf
                @method('PUT')
				<div class="card card-flush">
					<div class="card-body">
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
                                <div class="row mb-7">
                                    <div class="col-md-9 offset-md-3">
                                        <h2>{{ __('product/unit-and-uom.edit_uom') }}</h2>
                                    </div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">{{ __('product/unit-and-uom.add_unit_category') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 don_click">
                                        <select class="form-select mb-2 form-select-sm unit_category" name="unit_category" data-control="select2" data-hide-search="true" data-placeholder="Select unit category">
                                            <option></option>
                                            @foreach ($unitCategories as $category)
                                                <option  value="{{ $category->id }}" @selected($uom->unit_category_id === $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_category')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">{{ __('product/product.name') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="name" value="{{old('name',$uom->name)}}" placeholder="UoM name"/>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>Short Name</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="short_name" value="{{old('short_name',$uom->short_name)}}" placeholder="UoM short name">
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7 d-none reference-unit">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="">{{ __('product/unit-and-uom.reference_unit') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="refUnitTxt" value="" readonly>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">{{ __('product/unit-and-uom.unit_type') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select mb-2 form-select-sm unit_type" name="unit_type" data-control="select2" data-hide-search="true" data-placeholder="Select unit type">
                                            <option></option>
                                            <option value="reference" @selected($uom->unit_type === "reference")>{{ __('product/unit-and-uom.reference') }}</option>
                                            <option value="bigger" @selected($uom->unit_type === "bigger")>{{ __('product/unit-and-uom.bigger') }}</option>
                                            <option value="smaller" @selected($uom->unit_type === "smaller")>{{ __('product/unit-and-uom.smaller') }}</option>
                                        </select>
                                        @error('unit_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">{{ __('product/variation.value') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="value" value="{{ old('value', $uom->value * 1) }}" placeholder="Value">
                                        @error('value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="">{{ __('product/unit-and-uom.rounded_amount') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="rounded_amount" value="{{ old('rounded_amount', $uom->rounded_amount * 1) }}" placeholder="Rounded Amount">
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-start mt-5">
					<a href="{{ route('unit-category') }}"  type="submit" class="btn btn-light me-5 btn-sm" >{{ __('product/product.cancle') }}</a>
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
<script>
    $(document).ready(function() {

        let callCheckUoM = (id) => {
            $.ajax({
                url: `/uom/check-uom/${id}`,
                type: 'GET',
                success: function(response) {
                    let refUnitTxt = (response[0].value * 1) + ' ' + `${response[0].short_name ? response[0].short_name : ''}` + ' ' + `(${response[0].name})`;
                    $('input[name="refUnitTxt"]').val(refUnitTxt);
                    // if(response.length === 0){
                    //     $('.unit_type option[value="reference"]').removeProp('disabled');
                    //     $('.unit_type option[value="bigger"]').prop('disabled', true);
                    //     $('.unit_type option[value="smaller"]').prop('disabled', true);
                    // }

                    // if(response.length > 0){
                    //     let refUnitTxt = (response[0].value * 1) + ' ' + `${response[0].short_name ? response[0].short_name : ''}` + ' ' + `(${response[0].name})`;
                    //     $('input[name="refUnitTxt"]').val(refUnitTxt);
                    //     $('.reference-unit').removeClass('d-none');
                    //     $('.unit_type option[value="reference"]').prop('disabled', true);
                    //     $('.unit_type option[value="bigger"]').removeProp('disabled');
                    //     $('.unit_type option[value="smaller"]').removeProp('disabled');
                    // }
                }

            })
        }

        let toShowRefUnit = false;
        if(!toShowRefUnit){
            let unit_category_id = $('.unit_category').val();
            
            let uom = @json($uom);
            
            if(uom.unit_type === "reference"){
                $('.unit_category option:not(:selected)').prop('disabled', true);
                $('.unit_type option:not(:selected)').prop('disabled', true);
                $('input[name="value"]').prop('readonly', true);
            }

            if(uom.unit_type !== "reference"){
                $('.reference-unit').removeClass('d-none');
                $('.unit_category option:not(:selected)').prop('disabled', true);
                $('.unit_type option[value="reference"]').prop('disabled', true);

                callCheckUoM(unit_category_id)
            }

            toShowRefUnit = true;
        }
    })
</script>
@endpush
