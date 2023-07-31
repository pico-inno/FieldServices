<div class="modal fade" tabindex="-1" id="add_new_product_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h3 class="modal-title">New Stock In</h3>

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
                            <label class="form-label">
                               Lot No
                            </label>
                            <div class="card-body pt-0">
                                <input name="lot_no" class="form-control d-flex align-items-center" value="001" id="lot_no" />
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <div class="fv-row">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    Expired Date
                                </label>
                                <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                    <input class="form-control" name="expired_date" placeholder="Pick a expired date"
                                           data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                           value="{{date('d-m-Y')}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">
                                UOM Set <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                               title="Unit of measurement"></i>
                            </label>
                            <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                <option value="1">Please Select</option>
                                <option value="2">1box-10card-10Cap</option>
                                <option value="2">1box-6bottle</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-8">
                            <label for="" class="form-label">
                               Unit
                            </label>
                            <select class="form-select" data-control="select2" data-placeholder="Select an option">
                                <option value="1">Please Select</option>
                                <option value="2">Box</option>
                                <option value="2">Card</option>
                                <option value="2">Bottle</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-8">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Quantity
                                </label>
                                <div class="card-body pt-0">
                                    <input name="quantity" class="form-control d-flex align-items-center" value="" id="quantity" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
{{--                            <p></p>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Remark
                            </label>
                            <textarea name="" id="" cols="10" rows="5" class="form-control"
                                      placeholder="Write Something"></textarea>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
{{--                <!--begin::Input group-->--}}
{{--                <div class="mb-8">--}}
{{--                    <!--begin::Label-->--}}
{{--                    <h3>Product Description</h3>--}}
{{--                    <!--end::Label-->--}}
{{--                    <!--begin::Editor-->--}}
{{--                    <div id="kt_docs_quill_basic" name="kt_docs_quill_basic" class="min-h-200px mb-2 ">--}}

{{--                    </div>--}}
{{--                    <!--end::Editor-->--}}
{{--                    <!--begin::Description-->--}}
{{--                    <div class="text-muted fs-7">Set a description to the product for better visibility.</div>--}}
{{--                    <!--end::Description-->--}}
{{--                </div>--}}
{{--                <!--end::Input group-->--}}

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



