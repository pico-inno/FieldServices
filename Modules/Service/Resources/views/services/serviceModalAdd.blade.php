<div class="modal fade" tabindex="-1" id="add_service_modal"> 
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> 
            <form id="service_add_form" class="form" action="{{ route('service.create') }}" method="POST">
				@csrf

                <div class="modal-header">
                    <h3 class="modal-title">Add new servic</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="card card-flush">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin:::Tab content-->
                            <div class="tab-content" id="myTabContent">
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Service Name</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="name" value="" placeholder="Service name"/>
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
                                                <span class="">Service Code</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="service_code" value="" placeholder="Service code"/>
                                            <!--end::Input-->
                                            @error('service_code')
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
                                                <span class="">Service Type</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select form-select-sm mb-2" name="service_type_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($service_types as $service_type)
                                                    <option  value="{{ $service_type->id }}">{{ $service_type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('service_type_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">Unit</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select form-select-sm mb-2" name="uom_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($uoms as $uom)
                                                    <option  value="{{ $uom->id }}">{{ $uom->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('unit_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
    
                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">Price</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control form-control-sm form-control-solid mb-2" name="price" value="" placeholder="Price"/>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <!--end::Input group-->
    
                                    <!--end::Card header-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="">Is Active</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" name="active" type="checkbox" value="1" id="flexCheckChecked" checked />
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    Is active
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
                </div>
                <input type="hidden" name="close_modal" value="1">
				<div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
			</form>
        </div>
    </div>
</div>



