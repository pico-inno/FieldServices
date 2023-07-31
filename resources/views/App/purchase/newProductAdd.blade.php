<div class="modal fade" tabindex="-1" id="add_new_product_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h3 class="modal-title">Add a new Product</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                <div class="mb-10 fv-row">
                    <div class="row">
                        <div class="col-md-4 mb-8">
                            <!--begin::Label-->
                            <label class="required form-label">Product Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="product_name" class="form-control mb-2" placeholder="Product name" value="" />
                            <!--end::Input-->
                        </div>
                        <div class="col-md-4 mb-8">
                            <!--begin::Input group-->
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="form-label">
                                    SKU <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                    title="Unique product id or Stock Keeping Unit <br/><br/> Keep it blank to automatically generate sku.<br/><span class='text-muted'>You can modify sku prefix in Business settings.</span>"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="col-md-4 mb-8">
                            <label class="required form-label">Barcode</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                <option value="1">Code 128 (C128)</option>
                                <option value="2">EAN-13</option>
                                <option value="2">EAN-8</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-8">
                            <label class="required form-label">Unit</label>
                            <div class="input-group input-group-solid flex-nowrap">
                                <div class="overflow-hidden flex-grow-1">
                                    <select class="form-select  rounded-0 border-start border-end" data-control="select2" data-placeholder="Select an option">
                                        <option>Please Select</option>
                                        <option value="1">Pieces</option>
                                        <option value="2">ထည်</option>
                                    </select>
                                </div>
                                <span data-bs-toggle="modal" data-bs-target="#kt_modal_1" class="cursor-pointer input-group-text">
                                    <span class="svg-icon svg-icon-primary svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor"/>
                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor"/>
                                    </svg>
                                    </span>
                                </span>
                            </div>

                            {{-- Add Unit Modal --}}
                            <div class="modal fade" tabindex="-1" id="kt_modal_1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Add Unit</h3>

                                                <!--begin::Close-->
                                                <div class="cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                                                    </svg>
                                                    </span>
                                                </div>
                                                <!--end::Close-->
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="" class="required form-label">Name</label>
                                                    <input type="text" class="form-control" placeholder="Name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="required form-label">Short name</label>
                                                    <input type="text" class="form-control" placeholder="Short name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="required form-label">Allow decimal</label>
                                                    <select class="form-select  rounded-0 border-start border-end">
                                                        <option>Please Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Related Sub Units <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                    title="Based on selected Unit it will show sub units for it. Select the sub-unit applicable. Leave blank if all sub-units are applicable for the product."></i>
                                </label>
                                <div class="card-body pt-0">
                                    <input class="form-control d-flex align-items-center" value="" id="sub_units" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">
                                Second Unit <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                title="Allows user to enter product quantity in secondary unit during purchase/sell"></i>
                            </label>
                            <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                <option value="1">Please Select</option>
                                <option value="2">Pieces</option>
                                <option value="2">ဘူး</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">Brand</label>
                            <div class="input-group input-group-solid flex-nowrap">
                                <div class="overflow-hidden flex-grow-1">
                                    <select class="form-select  rounded-0 border-start border-end" data-control="select2" data-placeholder="Select an option">
                                        <option>Please Select</option>
                                        <option value="1">Apple</option>
                                        <option value="2">Oppo</option>
                                    </select>
                                </div>
                                <span data-bs-toggle="modal" data-bs-target="#kt_modal_brand" class="cursor-pointer input-group-text">
                                    <span class="svg-icon svg-icon-primary svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor"/>
                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor"/>
                                    </svg>
                                    </span>
                                </span>

                                {{-- Add Brand Modal --}}
                                <div class="modal fade" tabindex="-1" id="kt_modal_brand">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Add Brand</h3>

                                                    <!--begin::Close-->
                                                    <div class="cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                                                        <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                                        <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                                        <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                                                        </svg>
                                                        </span>
                                                    </div>
                                                    <!--end::Close-->
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="" class="required form-label">Brand Name</label>
                                                        <input type="text" class="form-control" placeholder="Name">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Short description</label>
                                                        <input type="text" class="form-control" placeholder="Short name">
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault"/>
                                                            <label class="form-check-label">
                                                                <span class="fw-bold">Use for repair ? </span>
                                                                <i class="fas fa-exclamation-circle ms-1 fs-7 text-success" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                title="If checked, brand will be displayed in dropdown used on <span class='badge badge-light text-danger'>repair module!</span>"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">
                                Category
                            </label>
                            <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                <option value="1">Please Select</option>
                                <option value="2">Color</option>
                                <option value="2">Noddle-N</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">
                                Sub Category
                            </label>
                            <select class="form-select" data-control="select2">
                                <option value="1">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-8">
                            <label class="form-label">
                                Business Locations <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                title="Locations where product will be available."></i>
                            </label>
                            <input class="form-control d-flex align-items-center" value="" id="business_locations" />
                        </div>
                        <div class="col-md-4 mb-8"></div>
                        <div class="col-md-4 mb-8"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-8" id="alertStock">
                            <div class="form-check form-check-custom form-check-solid mt-8">
                                <input class="form-check-input" type="checkbox" checked value="1" id="manageStock"/>
                                <label class="" for="manageStock">
                                    <strong class="ms-2">Manage Stock?</strong> <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                    title="<strong>Enable or disable stock management for a product</strong><br/><br/>
                                            Stock Management should be disable mostly for services. Example: Hair-Cutting, Repairing, etc."></i>
                                </label>
                            </div>
                            <div class="text-muted mt-2"><i>Enable stock management at product level</i></div>
                        </div>
                        <div class="col-md-4 mb-8" id="alert-quantity">
                            <label for="" class="form-label">
                                Alert quantity <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                title="<strong>Get alert when product stock reaches or goes below the specified quantity.</strong><br/><br/> <span class='text-muted'>Products with low stock will be displayed in dashboard - Product Stock Alert sections.</span>"></i>
                            </label>
                            <input type="text" class="form-control" placeholder="Alert quantity">
                        </div>
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">Device Model</label>
                            <select class="form-select form-select-lg" name="" id="">
                                <option >Please Select</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-8">
                    <!--begin::Label-->
                    <h3>Product Description</h3>
                    <!--end::Label-->
                    <!--begin::Editor-->
                    <div id="kt_docs_quill_basic" name="kt_docs_quill_basic" class="min-h-200px mb-2 ">

                    </div>
                    <!--end::Editor-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



