<div class="modal fade" tabindex="-1" id="add_supplier_modal"> 
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> 
            <form>
                <div class="modal-header">
                    <h3 class="modal-title">Add a new contact</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="d-flex mb-6 text-center justify-content-center">
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="individual_ck" id="individual_ck">
                            <label class="form-check-label" for="individual_ck">
                                Individual
                            </label>
                        </div>
                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="bussiness_ck">
                            <label class="form-check-label" for="bussiness_ck">
                                Business
                            </label>
                        </div>
                    </div>
                    <div class="separatorr"></div>
                    <div class="row mb-5">
                        <div class="col-lg-4 col-12 mb-4">
                            <label for="contact_id" class="form-label">Contact Id</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-regular fa-address-book"></i>
                                </div>
                                <input type="text" id="contact_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 mb-4 d-none" id="bussiness_id_div">
                            <label for="bussiness_id" class="form-label">Bussiness Id</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-regular fa-address-book"></i>
                                </div>
                                <input type="text" id="bussiness_id" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 d-none" id="individual_input_gp">
                        <div class="col-lg-3 col-12 mb-4">
                            <label for="prefix" class="form-label">Prefix:</label>
                            <input type="text" id="prefix" class="form-control" placeholder="Mr / Mrs / Miss">
                        </div>
                        <div class="col-lg-3 col-12 mb-4">
                            <label for="first_name" class="form-label required">First Name</label>
                            <input type="text" id="first_name" class="form-control">
                        </div>
                        <div class="col-lg-3 col-12 mb-4">
                            <label for="middle_name" class="form-label required">Middle Name</label>
                            <input type="text" id="middle_name" class="form-control">
                        </div>
                        <div class="col-lg-3 col-12 mb-4">
                            <label for="last_name" class="form-label required">Last Name</label>
                            <input type="text" id="last_name" class="form-control">
                        </div>
                    </div>
                     <div class="row mb-5">
                        <div class="col-lg-3 col-12 mb-4">
                            <label for="mobile" class="form-label required">Mobile :</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-mobile-screen-button"></i>
                                </div>
                                <input type="text" id="contact_id" class="form-control" placeholder="mobile">
                            </div>
                        </div>
                         <div class="col-lg-3 col-12 mb-4">
                            <label for="alternate_contact_number" class="form-label">Alternate contact number:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <input type="text" id="alternate_contact_number" class="form-control" placeholder="Alternate contact number">
                            </div>
                        </div>
                         <div class="col-lg-3 col-12 mb-4">
                            <label for="landLine" class="form-label">Landline:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <input type="text" id="landLine" class="form-control" placeholder="Landline">
                            </div>
                        </div>
                         <div class="col-lg-3 col-12 mb-4">
                            <label for="email" class="form-label">Email:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-regular fa-address-book"></i>
                                </div>
                                <input type="text" id="email" class="form-control" placeholder="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-7 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="dateOfBirth">
                                Date of birth:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" data-td-target="#kt_datepicker_3" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input class="form-control" name="start_date" placeholder="Pick a date"  id="kt_datepicker_3" value="{{date('d-m-Y')}}" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 mb-4">
                            <label for="email" class="form-label">Assigned to:</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <input class="form-control d-flex align-items-center border-gray-400" value="" id="kt_tagify_users" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5" >
                        <div class="col-12 justify-content-center d-flex mt-3 mb-5">
                            <div class="col-6 text-center float-center ">
                                <button class="btn btn-primary  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                    More Information <i class="fa-solid fa-chevron-down ms-3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                       <div class="row">
                            <div class="col-lg-4 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">Tax Number</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        T
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" placeholder="tax_number">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="opening_balance">
                                    <span class="required">Opening Balance:</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        O
                                    </div>
                                    <input type="text" class="form-control" id="opening_balance" placeholder="tax_number">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="update_logo">
                                    <span class="required">Pay term: </span>
                                </label>
                                <div class="input-group flex-nowrap">
                                    <div class="input-text">
                                        <input type="text" class="form-control rounded-end-0" placeholder="Pay term:">
                                    </div>
                                    <select class="form-select  fw-bold rounded-start-0 border-gray-500" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                        <option>Please Select</option>
                                        <option value="Administrator">Months</option>
                                        <option value="Analyst">Days</option>
                                    </select>
                                </div>
                            </div>
                       </div>
                        <div class="separator my-5"></div>
                        <div class="row mb-5">
                            <div class="col-6 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">Address line 1:</span>
                                </label>
                                <input type="text" class="form-control" id="tax_number" placeholder="Address line 1">
                            </div>
                            <div class="col-6 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="opening_balance">
                                    <span class="required">Address line 2:</span>
                                </label>
                                <input type="text" class="form-control" id="opening_balance" placeholder="Address line 2">
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-lg-3 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">City</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" placeholder="city">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">State</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" placeholder="State">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">Country</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" placeholder="Country">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="tax_number">
                                    <span class="required">Zip Code</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" placeholder="Zip Code">
                                </div>
                            </div>


                        </div>

                        <div class="separator my-5"></div>

                        <div class="row fv-row row-cols flex-wrap">
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_1">
                                    <span class="">Custom Field 1</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_1" name="contact_custom_field_1" value="" />
                            </div>
                            <div class="col-md-12  mb-7 col-lg-4">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_2">
                                        <span class="">Custom Field 2</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="text" class="form-control" name="contact_custom_field_2" id="contact_custom_field_2" value="" placeholder="" />
                            </div>
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_3">
                                    <span class="">Custom Field 3</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" name="contact_custom_field_3" id="contact_custom_field_3" value=""  />
                            </div>
                        </div>


                        <div class="row fv-row row-cols flex-wrap">
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_4">
                                    <span class="">Custom Field 4</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_4" name="contact_custom_field_4" value="" />
                            </div>
                            <div class="col-md-12  mb-7 col-lg-4">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_5">
                                        <span class="">Custom Field 5</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="text" class="form-control" name="contact_custom_field_5" id="contact_custom_field_5" value="" placeholder="" />
                            </div>
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_6">
                                    <span class="">Custom Field 6</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" name="contact_custom_field_6" id="contact_custom_field_6" value=""  />
                            </div>
                        </div>


                        <div class="row fv-row row-cols flex-wrap">
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_7">
                                    <span class="">Custom Field 7</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_7" name="contact_custom_field_7" value="" />
                            </div>
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_8">
                                    <span class="">Custom Field 8</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_8" name="contact_custom_field_8" value="" />
                            </div>
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_9">
                                    <span class="">Custom Field 9</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_9" name="contact_custom_field_9" value="" />
                            </div>
                        </div>

                        <div class="row row-cols flex-wrap">
                            <div class="col-md-12 mb-7 col-lg-4">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_10">
                                    <span class="">Custom Field 10</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" id="contact_custom_field_10" name="contact_custom_field_10" value="" />
                            </div>
                        </div>

                        <div class="separator my-5"></div>

                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <label for="" class="form-label">Shipping Address</label>
                                <input type="text" class="form-control" placeholder="Shipping Address">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


