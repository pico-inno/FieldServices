{{-- <div class="modal fade" tabindex="-1" id="add_supplier_modal">
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
                                    <span class="required">LocalAddress line 1:</span>
                                </label>
                                <input type="text" class="form-control" id="tax_number" placeholder="LocalAddress line 1">
                            </div>
                            <div class="col-6 mb-4">
                                <label class="fs-6 fw-semibold form-label " for="opening_balance">
                                    <span class="required">LocalAddress line 2:</span>
                                </label>
                                <input type="text" class="form-control" id="opening_balance" placeholder="LocalAddress line 2">
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
                                <label for="" class="form-label">Shipping LocalAddress</label>
                                <input type="text" class="form-control" placeholder="Shipping LocalAddress">
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
</div> --}}

<style>
    .individual-div.hide,
    .business-div.hide,
    .customer-group.hide,
    .credit-limit.hide {
        display: none;
    }
</style>
<div class="modal fade" tabindex="-1" id="contact_add_modal">
    <div class="modal-dialog">
        <div class="modal-content ">
            <form action="/customers" method="POST" id="add_contact_form">
                @csrf
                <input type="hidden" name="form_type" value="from_pos">

                <div class="modal-header">
                    <h3 class="modal-title">Add Contact</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="contact-type" class="required form-label">Contact Type</label>
                            <select name="type" class="form-select" id="contact-type" data-control="select2" required   data-parent='#contact_add_modal'
                                aria-label="Select example">
                                <option value="Supplier" selected>Suppliers</option>
                                <option value="Customer" >Customers</option>
                                <option value="Both">Both (Suppliers and Customers)</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-6 mb-6">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="radio" id="individual" value="" checked />
                                        <span class="form-check-label">
                                            Individual
                                        </span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" id="business" value="" />
                                        <span class="form-check-label">
                                            Business
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-6">
                            <label for="contact_id" class="form-label">Contact ID</label>
                            <input type="text" name="contact_id" id="contact_id" class="form-control"
                                placeholder="Contact ID" />
                            <span class="text-gray-400">Leave empty to autogenerate</span>
                        </div>
                    </div>
                    <div class="row mb-6 customer-group">
                        <div class="col-md-4 col-sm-12">
                            <label for="customer_group_id" class="form-label">Customer Group</label>
                            <select name="customer_group_id" class="form-select " data-control="select2" data-parent='#contact_add_modal'
                                data-placeholder="None" data-hide-search="true">
                                <option></option>
                                @php
                                $customer_groups = \App\Models\Contact\CustomerGroup::all();
                                @endphp
                                @foreach($customer_groups as $customer_group)
                                <option value="{{ $customer_group->id}}">{{ $customer_group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row individual-div">
                        <div class="col-md-3 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="prefix" class="form-label">Prefix</label>
                            <input type="text" name="prefix" id="prefix" class="form-control"
                                placeholder="Mr / Mrs / Miss" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                placeholder="First Name" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="middle_name" class="form-label detail-toggle-class d-none">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control"
                                placeholder="Middle Name" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                placeholder="Last Name" />
                        </div>
                    </div>
                    <div class="row business-div hide">
                        <div class="col-md-4 col-sm-12 mb-6">
                            <label for="company_name" class="form-label">Business Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control"
                                placeholder="Business Name" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-6">
                            <label for="mobile" class="required form-label">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile"
                                required />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="alternate_number" class="form-label">Alternate Contact Number</label>
                            <input type="text" name="alternate_number" id="alternate_number" class="form-control"
                                placeholder="Alternate Contact Numbe" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="landline" class="form-label">Landline</label>
                            <input type="text" name="landline" id="landline" class="form-control"
                                placeholder="Landline" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-md-4 col-sm-12 mb-6 individual-div detail-toggle-class d-none">
                            <label for="dob" class="form-label">Date of birth</label>
                            <input type="text" name="dob" id="dob" class="form-control" placeholder="Date of birth"
                                autocomplete="off" />
                        </div>
                        <div class="col-md-4 col-sm-12 mb-6 detail-toggle-class d-none">
                            <label for="" class="form-label">Assigned to</label>
                            <input type="text" class="form-control" placeholder="" />
                        </div>
                    </div>
                    <div class="form-group text-center mt-5 toggle-details-btn" id="show_more_info">
                        <button type="button" class="btn btn-primary btn-lg moreBtn rounded text-white show_detail"
                            id="show_more_info_btn">
                            <i class="fa-solid fa-eye-slash me-5"></i> Show Details
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-lg moreBtn rounded text-white hide_detail d-none"
                            id="show_more_info_btn">
                            <i class="fa-solid fa-eye me-5"></i> Hide Details
                        </button>
                    </div>
                    <div id="more-info-fields" class="mt-8">
                        <div class="row detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="tax_number" class="form-label">Tax Number</label>
                                <input type="text" name="tax_number" id="tax_number" class="form-control"
                                    placeholder="Tax Number">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="balance" class="form-label">Opening Balance</label>
                                <input type="number" name="balance" id="balance" class="form-control"
                                    placeholder="Opening Balance">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="pay_term_number" class="form-label">Pay Term</label>
                                <div class="input-group">
                                    <input type="number" name="pay_term_number" id="pay_term_number"
                                        class="form-control">
                                    <select name="pay_term_type" class="form-select" aria-label="Select example" data-parent='#contact_add_modal'>
                                        <option value="" selected disabled>Please select</option>
                                        <option value="Months">Months</option>
                                        <option value="Days">Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row credit-limit detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="credit_limit" class="form-label">Credit Limit</label>
                                <input type="number" name="credit_limit" id="credit_limit" class="form-control"
                                    placeholder="Credit Limit">
                            </div>
                        </div>
                        <hr style="opacity: 0.3;">
                        <div class="row mt-6">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="address_line_1" class="form-label">Address Line 1</label>
                                <input type="text" name="address_line_1" id="address_line_1" class="form-control"
                                    placeholder="Address Line 1">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="address_line_2" class="form-label">Address Line 2</label>
                                <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                                    placeholder="Address Line 2">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="City">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state" id="state" class="form-control" placeholder="State">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6 detail-toggle-class d-none">
                                <label for="country" class="form-label">Country</label>
                                <select name="country" id="country" class="form-select" aria-label="Select example" data-parent='#contact_add_modal'>
                                    <option value="" selected disabled>Select Country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of
                                    </option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba
                                    </option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory
                                    </option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaçao">Curaçao</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's
                                        Republic of</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic
                                    </option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of
                                    </option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
                                    </option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthélemy">Saint Barthélemy</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin (French part)">Saint Martin (French part)</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                    </option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Korea">South Korea</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of
                                    </option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands">Virgin Islands</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6 detail-toggle-class d-none">
                                <label for="zip_code" class="form-label">Zip Code</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control"
                                    placeholder="Zip Code">
                            </div>
                        </div>
                        <hr style="opacity: 0.3;" class="detail-toggle-class d-none">
                        <div class="row mt-6 detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_1" class="form-label">Custom Field 1</label>
                                <input type="text" name="custom_field_1" id="custom_field_1" class="form-control"
                                    placeholder="Custom Field 1">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_2" class="form-label">Custom Field 2</label>
                                <input type="text" name="custom_field_2" id="custom_field_2" class="form-control"
                                    placeholder="Custom Field 2">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_3" class="form-label">Custom Field 3</label>
                                <input type="text" name="custom_field_3" id="custom_field_3" class="form-control"
                                    placeholder="Custom Field 3">
                            </div>
                        </div>
                        <div class="row detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_4" class="form-label">Custom Field 4</label>
                                <input type="text" name="custom_field_4" id="custom_field_4" class="form-control"
                                    placeholder="Custom Field 4">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_5" class="form-label">Custom Field 5</label>
                                <input type="text" name="custom_field_5" id="custom_field_5" class="form-control"
                                    placeholder="Custom Field 5">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_6" class="form-label">Custom Field 6</label>
                                <input type="text" name="custom_field_6" id="custom_field_6" class="form-control"
                                    placeholder="Custom Field 6">
                            </div>
                        </div>
                        <div class="row detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_7" class="form-label">Custom Field 7</label>
                                <input type="text" name="custom_field_7" id="custom_field_7" class="form-control"
                                    placeholder="Custom Field 7">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_8" class="form-label">Custom Field 8</label>
                                <input type="text" name="custom_field_8" id="custom_field_8" class="form-control"
                                    placeholder="Custom Field 8">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_9" class="form-label">Custom Field 9</label>
                                <input type="text" name="custom_field_9" id="custom_field_9" class="form-control"
                                    placeholder="Custom Field 9">
                            </div>
                        </div>
                        <div class="row detail-toggle-class d-none">
                            <div class="col-md-4 col-sm-12 mb-6">
                                <label for="custom_field_10" class="form-label">Custom Field 10</label>
                                <input type="text" name="custom_field_10" id="custom_field_10" class="form-control"
                                    placeholder="Custom Field 10">
                            </div>
                        </div>
                        <hr style="opacity: 0.3;" class="detail-toggle-class d-none">
                        <div class="row mt-6 detail-toggle-class d-none">
                            <div class="col-md-5 col-sm-12">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control"
                                    style="resize: none;"></textarea>
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
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').each(function() {
            $(this).select2({ dropdownParent: $(this).parent()});
        })

        // toggle details
        $(document).on('click', '.toggle-details-btn', function() {
            $('.show_detail, .hide_detail').toggleClass('d-none');
            $('.detail-toggle-class').toggleClass('d-none');
        })
    })
    document.getElementById('contact_add_modal').addEventListener('shown.bs.modal', function() {

        tempusDominus.extend(tempusDominus.plugins.customDateFormat);

        new tempusDominus.TempusDominus(document.getElementById('dob'), {
                localization: {
                locale: "en",
                format: "dd/MM/yyyy",
            }
        });

        let modal = $('#contact_add_modal');
        let individual = modal.find('#individual');
        let individualdivs = modal.find('.individual-div');
        let business = modal.find('#business');
        let businessdiv = modal.find('.business-div');

        let show_more_info = modal.find('#show_more_info');
        let contact_type = modal.find('#contact-type');

        $(individual).on('change', function() {
            for(let i = 0; i < individualdivs.length; i++){
                business.prop('checked', false);
                $(individualdivs[i]).removeClass('hide');
                businessdiv.addClass('hide');
            }
        })

        $(business).on('change', function() {
            for(let i = 0; i < individualdivs.length; i++){
                individual.prop('checked', false);
                $(individualdivs[i]).addClass('hide');
                businessdiv.removeClass('hide');
            }
        })

        // $(show_more_info).on('click', function() {
        //     let moreInfo = modal.find("#more-info-fields");
        //     let moreInfoBtn = modal.find(".moreBtn")

        //     if (moreInfo.css("display") === "none") {
        //         moreInfo.css("display", "block");
        //         moreInfoBtn.html(`Less Informations <i class="fa-solid fa-chevron-up text-white ms-4"></i>`);
        //     } else {
        //         moreInfo.css("display", "none");
        //         moreInfoBtn.html(`More Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i>`);
        //     }
        // })

        $(contact_type).on('click', function(){
            let contactTypeSelect = modal.find('#contact-type');
            let customerGroupDiv = modal.find('.customer-group');
            let creditLimitDiv = modal.find('.credit-limit');

            if (contactTypeSelect.val() === "Customer") {
                customerGroupDiv.removeClass('hide');
                creditLimitDiv.removeClass('hide');
            } else if (contactTypeSelect.val() === "Supplier") {
                customerGroupDiv.addClass('hide');
                creditLimitDiv.addClass('hide');
            } else if (contactTypeSelect.val() === "Both") {
                customerGroupDiv.removeClass('hide');
                creditLimitDiv.removeClass('hide');
            }
        })
    });

</script>
