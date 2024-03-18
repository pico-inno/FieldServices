@extends('App.main.navBar')
@section('styles')
<style>


</style>
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('price_list_detail_menu_link', 'active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">{{ __('product/pricelist.edit_pricelist') }}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('price-list-detail') }}" class="text-muted text-hover-primary">{{ __('product/pricelist.pricelist') }}</a>
    </li>
    <li class="breadcrumb-item text-dark">{{ __('product/product.edit') }}</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="{{route('price-list-detail.update', $priceList->id)}}" method="POST" id="priceList_form">
            @method('PUT')
            @csrf
            <!--begin::Card-->
            <div class="card card-p-4 card-flush mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label for="" class="fs-5 form-label required">{{ __('product/product.name') }}</label>
                            <input type="text" class="form-control form-control-sm " name="name" placeholder="Name" value="{{old('name',$priceList->name)}}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label class="form-label required">{{ __('product/pricelist.base_price') }}</label>
                            <select name="base_price" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Select Base Price">
                                <option></option>
                                @if (count($priceList->priceListDetails)>0)
                                    <option value="0" @selected($priceList->priceListDetails[0]->base_price ==0)>{{ __('product/pricelist.cost') }}</option>
                                    @foreach($price_lists as $price_list)
                                        <option value="{{ $price_list->id }}" @selected($price_list->id === $priceList->priceListDetails[0]->base_price)>{{
                                            $price_list->name }}</option>
                                    @endforeach
                                @else
                                <option value="0">{{ __('product/pricelist.cost') }}</option>
                                @foreach($price_lists as $price_list)
                                    <option value="{{ $price_list->id }}" >{{
                                        $price_list->name }}</option>
                                    @endforeach
                                @endif

                            </select>
                            @error('base_price')
                                <div class="text-danger my-2">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="col-md-4 col-sm-12 mb-8 ">
                        </div> --}}
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label for="" class="form-label required">{{ __('product/pricelist.currency') }}</label>
                            <select name="currency_id" id="currency_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" @selected($currency->id === $priceList->currency_id)>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="text-danger my-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-12">
                            <label for="" class="fs-5 form-label">{{ __('product/category.description') }}</label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="3">{{ old('description',$priceList->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-p-4 card-flush">

                <div class="card-body">
                    <div class="text-end mb-5">
                        <a href="{{route('export-priceList',$priceList->id)}}" class="btn btn-light-primary btn-sm">
                          <span class="fa-solid fa-upload me-3"></span>  Export Price List Data
                        </a>
                        <button type="button"  class="btn btn-light-success btn-sm" data-bs-toggle="modal" data-bs-target="#priceListModal">
                            <span class="fa-solid fa-download me-3"></span> Update Price List With Excel
                        </button>

                    </div>
                    <div class="separator mb-5"></div>
                        <livewire:PriceList.price-list-edit-table :id="$priceList['id']" />
                    <br>
                    <div class="row mb-8">
                        <div class="d-flex">
                            <button type="button" class="btn btn-light-primary btn-sm me-3" id="add_price_list_row"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('price-list-detail') }}"  class="btn btn-light me-5 btn-sm">{{ __('product/product.cancle') }}</a>
                    <button type="submit" id="submit" class="btn btn-primary">{{ __('product/product.save') }}</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
<div class="modal" id="priceListModal" tabindex="-1" aria-labelledby="priceListLabel" aria-hidden="true">
    <div class="modal-dialog w-500px  modal-dialog-centered">
        <form action="{{route('priceListImport',['action'=>'update','id'=>$priceList->id])}}" id="priceListForm" method="POSt" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="priceListLabel">Upload File</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="fv-row">
                        <label for="" class="required form-label">Price List file</label>
                        <input type="file" name="importPriceList" class="form-control" id="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="upload" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Define form element
const form = document.getElementById('priceListModal');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'importPriceList': {
                validators: {
                    notEmpty: {
                        message: 'Price List File is required'
                    }
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('upload');
submitButton.addEventListener('click', function (e) {
    // Prevent default button action

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            if (status == 'Valid') {
                e.currentTarget=true;
                return true;
            } else {
                e.preventDefault();
                // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    }
});
</script>
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>


    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                error( @json($error) )
            </script>
        @endforeach
    @endif$(e)

    {{-- @include('App.product.PriceListDetail.js.pricelist_js_for_edit'); --}}
    @include('App.product.PriceListDetail.js.price_list_detail_js');

    <script>
        $(document).ready(function() {
            $('select[name="apply_type[]"]').each(function(){
                let current_row = $(this).closest('tr');
                let applied_type = current_row.find('select[name="apply_type[]"]').val();
                getApplyValue(current_row,applied_type);
            })
        });

    </script>
@endpush
