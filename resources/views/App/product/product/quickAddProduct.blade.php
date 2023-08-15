<style>
    .box {
        display: none;
    }
</style>
<div class="modal-dialog  modal-dialog-scrollable">
        <form action="{{ route('product.create') }}" class="modal-content" method="POST" enctype="multipart/form-data" id="quick_add_product_form" >
            <div class="modal-header">
                <h3 class="modal-title">Add Product</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body ">
                @csrf
                <input type="hidden" name="form_type" value="from_pos">
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class=" py-4">
                                        {{-- Product Image --}}
                                        <div class="mb-10 fv-row">
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label d-block">{{ __('product/product.product_image') }}</label>

                                                    <style>.image-input-placeholder { background-image: url('assets/media/svg/files/blank-image.svg'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                                                    <!--end::Image input placeholder-->
                                                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-150px h-150px"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <!--end::Remove-->
                                                    </div>
                                                    <!--end::Image input-->
                                                    <div class="text-muted fs-7">Max File Size: 5MB</div>
                                                    <div class="text-muted fs-7">Aspect ratio should be 1:1</div>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_sale">
                                                            <input class="form-check-input" name="can_sale" type="checkbox" value="1" id="can_sale" checked/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_sale') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_purchase">
                                                            <input class="form-check-input" name="can_purchase" type="checkbox" value="1" id="can_purchase" checked/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_purchase') }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mt-8">
                                                        <label class="" for="can_expense">
                                                            <input class="form-check-input" name="can_expense" type="checkbox" value="1" id="can_expense"/>
                                                            <strong class="ms-4 h5">{{ __('product/product.can_expense') }}</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-10 fv-row">
                                            <div class="row mb-5">
                                                <div class="btn btn-sm btn-light-info w-200px" id="advance_toggle">
                                                    <span class="show_advance"><i class="fa-solid fa-eye-slash me-5"></i>Show Advance</span>
                                                    <span class="hide_advance d-none"><i class="fa-solid fa-eye me-5"></i>Hide Advance</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label class="required form-label">{{ __('product/product.product_name') }}</label>
                                                    <input type="text" name="product_name" class="form-control form-control-sm mb-2" placeholder="Product name" value="" />
                                                    @error('product_name')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <label class="form-label">
                                                            {{ __('product/product.product_code') }}
                                                        </label>
                                                        <input type="text" name="product_code" class="form-control form-control-sm mb-2" placeholder="Product code" value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <label class="form-label">
                                                        {{ __('product/product.sku') }} <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                        title="Unique product id or Stock Keeping Unit <br/><br/> Keep it blank to automatically generate sku.<br/><span class='text-muted'>You can modify sku prefix in Business settings.</span>"></i>
                                                    </label>
                                                    <input type="text" name="sku" class="form-control mb-2 form-control-sm" placeholder="SKU Number" value="" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">{{ __('product/product.brand') }}</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="brand" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select brand">
                                                                <option></option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" data-bs-toggle="modal" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_brand">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">
                                                        {{ __('product/product.category') }}
                                                    </label>
                                                    <select id="categorySelect" name="category" class="form-select form-select-sm" data-control="select2" data-placeholder="Select category" data-parent='#quick_add_product_form'>
                                                        <option></option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-5 advance-toggle-class d-none">
                                                    <label for="" class="form-label">
                                                        {{ __('product/product.sub_category') }}
                                                    </label>
                                                    <select class="form-select form-select-sm" name="sub_category" id="subCategorySelect" data-control="select2" data-hide-search="true" data-placeholder="Select sub category">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row advance-toggle-class d-none">
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Manufacturer</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="manufacturer" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select manufacturer">
                                                                <option></option>
                                                                @foreach ($manufacturers as $manufacturer)
                                                                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_manufacturer">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label for="" class="form-label">Generic</label>
                                                    <div class="input-group mb-5 flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="generic" class="form-select form-select-sm rounded-end-0" data-control="select2" data-placeholder="Select generic">
                                                                <option></option>
                                                                @foreach ($generics as $generic)
                                                                    <option value="{{ $generic->id }}">{{ $generic->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="input-group-text cursor-pointer" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#kt_modal_generic">
                                                            <i class="fas fa-circle-plus text-primary"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    {{-- <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label">
                                                            Lot
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="text" name="lot_count" class="form-control form-control-sm mb-2" placeholder="Product code" value="" />
                                                        <!--end::Input-->
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label required">
                                                            {{ __('product/product.uom') }}
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="uom_id" class="form-select form-select-sm" data-control="select2" data-placeholder="Select UoM">
                                                                    <option></option>
                                                                    @foreach ($uoms as $uom)
                                                                        <option value="{{ $uom->id }}">{{ $uom->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('uom_id')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <div class="fv-row">
                                                        <!--begin::Label-->
                                                        <label class="form-label required">
                                                            {{ __('product/product.purchase_uom') }}
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group mb-5 flex-nowrap">
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select class="form-select form-select-sm" name="purchase_uom_id" id="unitOfUom" data-control="select2" data-hide-search="true" data-placeholder="Select purchase UoM">

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('purchase_uom_id')
                                                        <div class="text-danger my-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col md-4 mb-5">
                                                </div>
                                            </div>
                                            <div class="row advance-toggle-class">
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.purchase_price') }}</label>
                                                    <input type="text" name="purchase_price_for_single" class="form-control form-control-sm mb-2" placeholder="Purchase price" value="" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.profit_margin') }}</label>
                                                    <input type="text" name="profit_margin_for_single" class="form-control form-control-sm mb-2" placeholder="Profit mergin (%)" value="" />
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">{{ __('product/product.sell_price') }}</label>
                                                    <input type="text" name="sell_price_for_single" class="form-control form-control-sm mb-2" placeholder="Sell price" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Input group-->

                                        {{-- Product Type  --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3 col-md-offset-4">
                                                <label for="" class="form-label required">
                                                    Product Type
                                                </label>
                                                <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                    title="<div class='text-start'><strong>Single product: </strong> Product with no variations. <br/>
                                                            <strong>Variable product: </strong> Product with variations such as size, color etc. <br/>
                                                            <strong>Combo product: </strong> A combination of multiple products, also called bundle product.</div>"></i>
                                                <div class="mb-3">
                                                    <select class="form-select form-select-sm" name="product_type" data-control="select2" id="product_type" data-hide-search="true">
                                                        <option value="single" selected>Single</option>
                                                        <option value="variable">Variable</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="single_box" class="box advance-toggle-class d-none">
                                            <div class="table-responsive mb-4">
                                                <table class="table table-row-dashed fs-6 gy-4" id="">
                                                    <thead>
                                                        <tr class="text-start text-gray-800 bg-light">
                                                            <th class="min-w-200px">Default Purchase Price</th>
                                                            <th class="min-w-100px">
                                                                x Margin(%) <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                title="Default profit margin for the product.<br/>
                                                                        <i class='text-muted'>You can manage default profit margin in Business Settings.</i>"></i>
                                                            </th>
                                                            <th class="min-w-100px">Default Selling Price</th>
                                                            <th class="min-w-150px">Product Image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-700 x" id="price_list_body">
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="me-4">
                                                                        <label for="" class="form-label">Exc. tax</label>
                                                                        <input type="text" name="single_exc" class="form-control rounded-0 form-control-sm" placeholder="Exc. tax">
                                                                    </div>
                                                                    <div class="">
                                                                        <label for="" class="required form-label">Inc. tax</label>
                                                                        <input type="text" name="single_inc" class="form-control rounded-0 form-control-sm" placeholder="Inc. tax">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for="" class=" form-label">Margin</label>
                                                                <input type="text" name="single_profit" class="form-control rounded-0 form-control-sm" value="">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Exc. Tax</label>
                                                                <input type="text" name="single_selling" class="form-control rounded-0 form-control-sm" placeholder="Exc. tax">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Product image</label>
                                                                <input type="file" name="" id="" class="form-control rounded-0 form-control-sm">
                                                                <div class="text-muted">
                                                                    Max File size: 5MB <br/>
                                                                    Aspect ration should be 1:1
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="variable_box" class="box advance-toggle-class d-none">
                                            <div class="my-3 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="fw-bold fs-3 text-gray-800 text-start bg-gray-300">
                                                            <th class="text-center">Variation</th>
                                                            <th>Variation Values</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="repeater" >
                                                        <tr >
                                                            <td class="min-w-200px">
                                                                <select name="variation_name" id="variationSelect" class="form-select rounded-0" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                                                                    <option></option>
                                                                    @foreach ($variations as $variation)
                                                                        <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-bordered" id="variation-table">
                                                                        <thead>
                                                                            <tr class="fw-bold fs-6 text-gray-800 text-start bg-gray-500">
                                                                                <th class="text-center min-w-100px">
                                                                                    SKU <i class="fas fa-exclamation-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                                    title="SKU is optional. <br/> <br/>
                                                                                            Keep it blank to automatically generate sku."></i>
                                                                                </th>
                                                                                <th class="min-w-100px">Value</th>
                                                                                <th class="min-w-200px">
                                                                                    Default Purchase Price <br/>
                                                                                    <i>Exc. tax Inc. tax</i>
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    x Margin(%)
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    Default Selling Price <br/>
                                                                                    <i>Exc. Tax</i>
                                                                                </th>
                                                                                <th class="min-w-200px">Variation Images</th>
                                                                                <th class=" min-w-50px">
                                                                                    <span id="child-repeater" name="add" data-repeater-create class="svg-icon svg-icon-primary svg-icon-4 cursor-pointer add-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody data-repeater-list="variation_lists" id="variation-row">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="separator border-dark my-10 advance-toggle-class d-none"></div>
                                        {{-- Custom Fields  --}}
                                        <div class="row mt-5 advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">{{ __('product/product.custom_field_1') }}</label>

                                                <input type="text" name="custom_field1" class="form-control form-control-sm mb-2" placeholder="Custom field1" value="" />

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">{{ __('product/product.custom_field_2') }}</label>
                                                <input type="text" name="custom_field2" class="form-control form-control-sm mb-2" placeholder="Custom field2" value="" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">{{ __('product/product.custom_field_3') }}</label>
                                                <input type="text" name="custom_field3" class="form-control form-control-sm mb-2" placeholder="Custom field3" value="" />
                                            </div>
                                        </div>
                                        <div class="row mb-5 advance-toggle-class d-none">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">{{ __('product/product.custom_field_4') }}</label>
                                                <input type="text" name="custom_field4" class="form-control form-control-sm mb-2" placeholder="Custom field4" value="" />
                                            </div>
                                            <div class="col-md-4 mb-3">

                                            </div>
                                            <div class="col-md-4 mb-3">

                                            </div>
                                        </div>

                                        {{-- Product Description  --}}
                                        <div class="mb-5">
                                            <!--begin::Label-->
                                            <h3>{{ __('product/product.product_description') }}</h3>
                                            <!--end::Label-->
                                            <!--begin::Editor-->
                                            <div id="kt_docs_quill_basic" name="product_desc" class="min-h-100px mb-2">

                                            </div>
                                            <input type="hidden" name="quill_data" value="{{ old('quill_data', $quillData ?? '') }}">
                                            <!--end::Editor-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Input group-->

                                        {{-- Product Disable --}}
                                        <div class="row advance-toggle-class d-none">
                                            <div class="col-md-3 mb-8">
                                                <div class="form-check form-check-custom form-check-solid mt-8">
                                                    <label class="" for="tab2_check1">
                                                        <input class="form-check-input" name="product_inactive" type="checkbox" value="1" id="tab2_check1"/>
                                                        <strong class="ms-4 h5">{{ __('product/product.disable') }}</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <!--end::Card header-->
                                </div>
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        {{-- <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <!--begin::Label-->
                                                <label class="form-label">Custom Field1</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="custom_field1" class="form-control form-control-sm mb-2" placeholder="Custom field1" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <!--begin::Label-->
                                                <label class="form-label">Custom Field2</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="custom_field2" class="form-control form-control-sm mb-2" placeholder="Custom field2" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <!--begin::Label-->
                                                <label class="form-label">Custom Field3</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="custom_field3" class="form-control form-control-sm mb-2" placeholder="Custom field3" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <!--begin::Label-->
                                                <label class="form-label">Custom Field4</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="custom_field4" class="form-control form-control-sm mb-2" placeholder="Custom field4" value="" />
                                                <!--end::Input-->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3 col-md-offset-4">
                                                <label for="" class="form-label required">
                                                    Product Type
                                                </label>
                                                <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                    title="<div class='text-start'><strong>Single product: </strong> Product with no variations. <br/>
                                                            <strong>Variable product: </strong> Product with variations such as size, color etc. <br/>
                                                            <strong>Combo product: </strong> A combination of multiple products, also called bundle product.</div>"></i>
                                                <div class="mb-3">
                                                    <select class="form-select form-select-sm" name="product_type" id="product_type" data-hide-search="true">
                                                        <option value="single" selected>Single</option>
                                                        <option value="variable">Variable</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-8 col-md-offset-4">
                                                <div class="form-check form-check-custom form-check-solid mt-8">
                                                    <label class="" for="tab2_check1">
                                                        <input class="form-check-input" name="product_inactive" type="checkbox" value="1" id="tab2_check1"/>
                                                        <strong class="ms-4 h5">Product Inactive</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="single_box" class="box">

                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-secondary fw-bold fs-6 text-gray-800 text-center">
                                                            <th>Default Purchase Price</th>
                                                            <th>
                                                                x Margin(%) <i class="fas fa-info-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                title="Default profit margin for the product.<br/>
                                                                    <i class='text-muted'>You can manage default profit margin in Business Settings.</i>"></i>
                                                            </th>
                                                            <th>Default Selling Price</th>
                                                            <th>Product image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="" class="required form-label">Exc. tax</label>
                                                                        <input type="text" name="single_exc" class="form-control form-control-sm" placeholder="Exc. tax">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="required form-label">Inc. tax</label>
                                                                        <input type="text" name="single_inc" class="form-control form-control-sm" placeholder="Inc. tax">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for=""></label>
                                                                <input type="text" name="single_profit" class="form-control form-control-sm" value="">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Exc. Tax</label>
                                                                <input type="text" name="single_selling" class="form-control form-control-sm" placeholder="Exc. tax">
                                                            </td>
                                                            <td>
                                                                <label for="" class="form-label">Product image</label>
                                                                <input type="file" name="" id="" class="form-control form-control-sm">
                                                                <div class="text-muted">
                                                                    Max File size: 5MB <br/>
                                                                    Aspect ration should be 1:1
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="variable_box" class="box">
                                            <span class="required fs-2">
                                                Add Variation
                                            </span>

                                            <div class="my-3 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="fw-bold fs-3 text-gray-800 text-start bg-gray-300">
                                                            <th class="text-center">Variation</th>
                                                            <th>Variation Values</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="repeater" >
                                                        <tr >
                                                            <td class="min-w-200px">
                                                                <select name="variation_name" id="variationSelect" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                                                                    <option></option>
                                                                    @php
                                                                        $variations = \App\Models\Product\VariationTemplates::all();
                                                                    @endphp
                                                                    @foreach ($variations as $variation)
                                                                        <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-bordered" id="variation-table">
                                                                        <thead>
                                                                            <tr class="fw-bold fs-6 text-gray-800 text-start bg-gray-500">
                                                                                <th class="text-center min-w-100px">
                                                                                    SKU <i class="fas fa-exclamation-circle ms-1 fs-7 text-primary cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                                    title="SKU is optional. <br/> <br/>
                                                                                        Keep it blank to automatically generate sku."></i>
                                                                                </th>
                                                                                <th class="min-w-100px">Value</th>
                                                                                <th class="min-w-200px">
                                                                                    Default Purchase Price <br/>
                                                                                    <i>Exc. tax Inc. tax</i>
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    x Margin(%)
                                                                                </th>
                                                                                <th class="min-w-150px">
                                                                                    Default Selling Price <br/>
                                                                                    <i>Exc. Tax</i>
                                                                                </th>
                                                                                <th class="min-w-200px">Variation Images</th>
                                                                                <th class=" min-w-50px">
                                                                                    <span id="child-repeater" name="add" data-repeater-create class="svg-icon svg-icon-primary svg-icon-4 cursor-pointer add-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody data-repeater-list="variation_lists" id="variation-row">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
</div>

{{-- <script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script> --}}
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').each(function() {
            $(this).select2({ dropdownParent: $(this).parent()});
        })
        $(document).on('click', '#advance_toggle', function() {
            $('.show_advance, .hide_advance').toggleClass('d-none');
            $('.advance-toggle-class').toggleClass('d-none');
        })
    })
    // ============= > Begin:: For Product Description < =====================
        var quill = new Quill('#kt_docs_quill_basic', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });
        quill.on('text-change', function() {
            var quillData = quill.root.innerHTML;
            document.querySelector('input[name="quill_data"]').value = quillData;
        });
    // ============= > End:: For Product Description < =======================
        $('#quick_add_product_modal').on('shown.bs.modal', function() {

            // ============= > Begin:: For Product Type      < =======================

                const selectBox = document.getElementById("product_type");
                const singleBox = document.getElementById("single_box");
                const variableBox = document.getElementById("variable_box");

                singleBox.style.display = "block";

                selectBox.addEventListener("change", () => {
                    const selectedValue = selectBox.value;

                    // Hide all contact boxes
                    singleBox.style.display = "none";
                    variableBox.style.display = "none";

                    // Show the contact box associated with the selected option
                    if (selectedValue === "single") {
                        singleBox.style.display = "block";
                    } else if (selectedValue === "variable") {
                        variableBox.style.display = "block";
                    }
                });
            // ============= > End:: For Product Type      < =========================

            // ============= > Begin:: Formula ProfitPercentage and Selling Price ====
                let profitPercentage = (sell, purchase) => Math.ceil( ( (parseInt(sell) - parseInt(purchase)) * 100)/parseInt(purchase) );

                let sellingPrice = (profit, purchase) => Math.ceil( ( (100 + parseInt(profit)) * parseInt(purchase))/100 );
            // ============= > Begin:: Formula ProfitPercentage and Selling Price ====

            // ============= > Begin:: For Single Product Type Calculate < ===========

                let singleExc = $('[name="single_exc"]');
                let singleInc = $('[name="single_inc"]');
                let singleProfit = $('[name="single_profit"]');
                let singleSelling = $('[name="single_selling"]');

                singleExc.on('keyup', (e) => {
                    let excVal = e.target.value;
                    singleInc.val(excVal);

                    if(singleSelling.val()){
                        let profitValue = profitPercentage(singleSelling.val(), excVal);
                        singleProfit.val(profitValue);
                        if(isNaN(profitValue)){
                            singleProfit.val('')
                        }
                    }
                })
                singleInc.on('keyup', (e) => {
                    let incVal = e.target.value;
                    singleExc.val(incVal);

                    if(singleSelling.val()){
                        let profitValue = profitPercentage(singleSelling.val(), incVal);
                        singleProfit.val(profitValue);
                        if(isNaN(profitValue)){
                            singleProfit.val('')
                        }
                    }
                })
                singleProfit.on('keyup', (e) => {
                    let profitValue = e.target.value;

                    if(singleExc.val() || singleInc.val()){
                        let resultSelling = sellingPrice(profitValue, singleExc.val());
                        singleSelling.val(resultSelling);
                        if(isNaN(resultSelling)){
                            singleSelling.val('');
                        }
                    }
                })
                singleSelling.on('keyup', (e) => {
                    let sellingValue = e.target.value;

                    if(singleExc.val() || singleInc.val()){
                        let resultProfit = profitPercentage(sellingValue, singleExc.val());
                        singleProfit.val(resultProfit);
                        if(isNaN(resultProfit)){
                            singleProfit.val('');
                        }
                    }
                })

            // ============= > End:: For Single Product Type Calculate   < ===========

            // ============= > Begin:: For Variation table repeater  < ===============
                let newVariation = `
                    <tr data-repeater-item class="variation-add-delete">
                        <input type="hidden" name="variation_id[]">
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variation_sku[]">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm variation_name" name="variation_value[]" value="">
                        </td>
                        <td>
                            <div class="input-group input-group-sm mb-5">
                                <input type="text" class="form-control" placeholder="Exc. tax" name="exc_purchase[]" />
                                <input type="text" class="form-control" placeholder="Inc. tax" name="inc_purchase[]" />
                                <span class="input-group-text cursor-pointer" name="double-mark1" data-bs-toggle="tooltip" data-bs-placement="top" title="Apply all">
                                    <span class="svg-icon svg-icon-muted svg-icon-2 "><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12.8956 13.4982L10.7949 11.2651C10.2697 10.7068 9.38251 10.7068 8.85731 11.2651C8.37559 11.7772 8.37559 12.5757 8.85731 13.0878L12.7499 17.2257C13.1448 17.6455 13.8118 17.6455 14.2066 17.2257L21.1427 9.85252C21.6244 9.34044 21.6244 8.54191 21.1427 8.02984C20.6175 7.47154 19.7303 7.47154 19.2051 8.02984L14.061 13.4982C13.7451 13.834 13.2115 13.834 12.8956 13.4982Z" fill="currentColor"/>
                                        <path d="M7.89557 13.4982L5.79487 11.2651C5.26967 10.7068 4.38251 10.7068 3.85731 11.2651C3.37559 11.7772 3.37559 12.5757 3.85731 13.0878L7.74989 17.2257C8.14476 17.6455 8.81176 17.6455 9.20663 17.2257L16.1427 9.85252C16.6244 9.34044 16.6244 8.54191 16.1427 8.02984C15.6175 7.47154 14.7303 7.47154 14.2051 8.02984L9.06096 13.4982C8.74506 13.834 8.21146 13.834 7.89557 13.4982Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm mb-5">
                                <input type="text" class="form-control" value="" name="profit_percentage[]"/>
                                <span class="input-group-text cursor-pointer" name="double-mark2" data-bs-toggle="tooltip" data-bs-placement="top" title="Apply all">
                                    <span class="svg-icon svg-icon-muted svg-icon-2 "><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12.8956 13.4982L10.7949 11.2651C10.2697 10.7068 9.38251 10.7068 8.85731 11.2651C8.37559 11.7772 8.37559 12.5757 8.85731 13.0878L12.7499 17.2257C13.1448 17.6455 13.8118 17.6455 14.2066 17.2257L21.1427 9.85252C21.6244 9.34044 21.6244 8.54191 21.1427 8.02984C20.6175 7.47154 19.7303 7.47154 19.2051 8.02984L14.061 13.4982C13.7451 13.834 13.2115 13.834 12.8956 13.4982Z" fill="currentColor"/>
                                        <path d="M7.89557 13.4982L5.79487 11.2651C5.26967 10.7068 4.38251 10.7068 3.85731 11.2651C3.37559 11.7772 3.37559 12.5757 3.85731 13.0878L7.74989 17.2257C8.14476 17.6455 8.81176 17.6455 9.20663 17.2257L16.1427 9.85252C16.6244 9.34044 16.6244 8.54191 16.1427 8.02984C15.6175 7.47154 14.7303 7.47154 14.2051 8.02984L9.06096 13.4982C8.74506 13.834 8.21146 13.834 7.89557 13.4982Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" placeholder="Exc. tax" name="selling_price[]">
                        </td>
                        <td>
                            <input type="file" class="form-control form-control-sm" name="variation_image[]">
                        </td>
                        <td class="min-w-50px " >
                            <span id="delete-variation" data-repeater-delete name="delete" class="deleteButton svg-icon svg-icon-danger svg-icon-4 cursor-pointer d-flex align-items-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                `;

                let calculateVariation = () => {
                    let exc = $('[name="exc_purchase[]"]');
                    let inc = $('[name="inc_purchase[]"]');
                    let profit = $('[name="profit_percentage[]"]');
                    let selling = $('[name="selling_price[]"]');
                    // begin:: exc and inc input to the same
                    exc.on('keyup', (e) => {
                        let excVal = e.target.value;
                        let input = $(e.currentTarget).closest('tr').find(inc);
                        input.val(e.target.value)

                        let currentSelling = $(e.currentTarget).closest('tr').find(selling);
                        let currentProfit = $(e.currentTarget).closest('tr').find(profit);

                        if(currentSelling.val()){
                            let profitValue = profitPercentage(currentSelling.val(), excVal);
                            currentProfit.val(profitValue);
                            if(isNaN(profitValue)){
                                currentProfit.val('')
                            }
                        }
                    })
                    inc.on('keyup', (e) => {
                        let incVal = e.target.value;
                        let input = $(e.currentTarget).closest('tr').find(exc);
                        input.val(e.target.value)
                        let currentSelling = $(e.currentTarget).closest('tr').find(selling);
                        let currentProfit = $(e.currentTarget).closest('tr').find(profit);

                        if(currentSelling.val()){
                            let profitValue = profitPercentage(currentSelling.val(), incVal);
                            currentProfit.val(profitValue);
                            if(isNaN(profitValue)){
                                currentProfit.val('');
                            }
                        }
                    })
                    // end:: exc and inc input to the same
                    // if typing profit percentage input
                    profit.on('keyup', (e) => {
                        let currentProfitValue = e.target.value;

                        let currentExc = $(e.currentTarget).closest('tr').find(exc);
                        let currentInc = $(e.currentTarget).closest('tr').find(inc);
                        let sellingInput = $(e.currentTarget).closest('tr').find(selling);

                        if(currentExc.val() || currentInc.val()){
                            let resultSelling = sellingPrice(currentProfitValue, currentExc.val());
                            sellingInput.val(resultSelling);
                            if(isNaN(resultSelling)){
                                sellingInput.val('');
                            }
                        }
                    })
                    // if typing selling price input
                    selling.on('keyup', (e) => {
                        let currentSellingValue = e.target.value;
                        let currentExc = $(e.currentTarget).closest('tr').find(exc);
                        let currentInc = $(e.currentTarget).closest('tr').find(inc);
                        let currentProfit = $(e.currentTarget).closest('tr').find(profit);
                        if(currentExc.val() || currentInc.val()){
                            let resultProfit = profitPercentage(currentSellingValue, currentExc.val());
                            currentProfit.val(resultProfit);
                        }
                    })
                    // for aplly all data
                    $('[name="double-mark1"]').on('click', () => {
                        console.log('click 1')
                    })
                    $('[name="double-mark2"]').on('click', () => {
                        console.log('click 2')
                    })
                }

                $(document).on('click', '#child-repeater', function() {
                    $('#variation-row').append(newVariation);
                    calculateVariation();
                })
                $(document).on('click', '#delete-variation', function() {
                    $(this).closest('.variation-add-delete').remove();
                })
                $(document).on('change', '#variationSelect', function() {
                    let id = $('#variationSelect').val();
                    $.ajax({
                        url: '/variation-values/'+id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('.variation-add-delete').remove();
                            $.each(data, function(index, item) {
                                let cloneRow = $(newVariation).clone();
                                cloneRow.find('input[name="variation_value[]"]').val(item.name)
                                cloneRow.find('input[name="variation_id[]"]').val(item.id)
                                cloneRow.find('input[name="variation_value[]"]').attr('readonly', true);
                                $('#variation-row').append(cloneRow);
                            });
                            calculateVariation();
                        },
                        error: function(xhr, status, error) {

                        }
                    })
                })
            // ============= > Begin:: For Variation table repeater < ================

            // ============= > Begin:: For Sub Category Select Box  < ================
                const cateSelect = $('#categorySelect');

                cateSelect.on('change', function() {
                    let id = cateSelect.val()
                    $.ajax({
                        url: '/category/sub-category/'+id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const subCategorySelect = $('#subCategorySelect')[0];
                            subCategorySelect.innerHTML = '';

                            const defaultOption = document.createElement('option'); // Create default option
                            defaultOption.value = '';
                            defaultOption.text = 'Select an option';
                            $(subCategorySelect).append(defaultOption);

                            for (let item of data) {
                                let option = document.createElement('option');
                                option.value = item.id;
                                option.text = item.name;
                                subCategorySelect.append(option);
                            }

                            $('#subCategorySelect').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin

                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            // handle the error
                        }
                    });
                })
            // ============= > End:: For Sub Category Select Box  < ==================

            $(document).on('change', 'select[name="uom_id"]', function() {
                let uom_id = $(this).val();
                $.ajax({
                    url: `/uom/get/${uom_id}`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(results){
                        const purchaseUoM = $('#unitOfUom')[0];
                        purchaseUoM.innerHTML = '';

                        const defaultOption = document.createElement('option'); // Create default option
                        defaultOption.value = '';
                        defaultOption.text = 'Select an option';
                        $(purchaseUoM).append(defaultOption);

                        for (let item of results) {
                            let option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.name;
                            purchaseUoM.append(option);
                        }

                        $('#unitOfUom').select2({minimumResultsForSearch: Infinity}); // Initialize select2 plugin
                    },
                    error: function(e){
                        console.log(e.responseJSON.error);
                    }
                });
            })
            // ============= > Begin:: For Show advance  < ==================

            // ============= > End:: For Show advance  < ==================
        })

            // Begin:: quick add product
        $('form#quick_add_product_form').off("submit").submit(function(e) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    if (response.success == true) {
                        $('#quick_add_product_modal').modal('hide');
                        success(response.message);

                        // Clear the input fields in the modal form
                        $('#quick_add_product_form')[0].reset();
                    }
                },
                error: function(result) {
                    //
                }
            })
        })
        // End
</script>
