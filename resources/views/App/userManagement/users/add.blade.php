@extends('App.main.navBar')


@section('user_active','active')
@section('user_active_show','active show')
@section('user_here_show','here show')
@section('user_add_active_show','active show')

@section('styles')
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Create User Account</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">User</li>
        <li class="breadcrumb-item text-dark">Create</li>
    </ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <div class="col-12" id="kt_modal_add_user">
                <div class="d-flex mb-5" data-kt-users-modal-action="close">
                    <button onclick="window.location.href='{{route('users.index')}}'" type="reset" class="btn btn-light me-3" data-kt-users-modal-action="close"><i class="fas fa-arrow-left"></i>Back</button>
                </div>
                <!--begin::Form-->
                <form action="{{route('users.store')}}" method="post" id="kt_modal_add_user_form" class="form">
                    @csrf
                <!--begin::Card-->
                <div class="card mb-3">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-4">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>User Information</h2>
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-2 fv-row">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">Prefix:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="initname" value="{{old('initname')}}" class="form-control  mb-3 mb-lg-0" placeholder="Mr / Mrs / Miss"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-5 fv-row">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">First Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="first_name" value="{{old('first_name')}}" class="form-control  mb-3 mb-lg-0" placeholder="First Name"/>
                                <!--end::Input-->
                                @error('first_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    <div data-validator="notEmpty">{{$message}}</div>
                                </div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-5 fv-row">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">Last Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="last_name" value="{{old('last_name')}}" class="form-control  mb-3 mb-lg-0" placeholder="Last Name"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Email Address</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="email" name="email" value="{{old('email')}}" class="form-control " placeholder="username@domain.xyz">
                            <!--end::Input-->
                            @error('email')
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-validator="notEmpty">{{$message}}</div>
                            </div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <div class="d-flex">
                                <!--begin::Checkbox-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input type="checkbox" name="is_active" value="1"  checked   class="form-check-input me-3" name="email_notification_1" id="kt_modal_add_is_active">
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_add_is_active">
                                        <div class="fw-bold">Is Active?</div>
                                        <div class="text-gray-600">User account is activate or deactivate</div>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Checkbox-->
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card-->
                <div class="card mb-3">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-4">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Roles and Permissions</h2>
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">User Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="username" value="{{old('username')}}" class="form-control " placeholder="Username">
                            <!--end::Input-->
                            @error('username')
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-validator="notEmpty">{{$message}}</div>
                            </div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <div class="mb-7 fv-row fv-plugins-icon-container" data-kt-password-meter="true">
                                        <!--begin::Wrapper-->
                                        <div class="mb-1">
                                            <!--begin::Label-->
                                            <label class="required form-label fw-semibold fs-6 mb-2">Password</label>
                                            <!--end::Label-->
                                            <!--begin::Input wrapper-->
                                            <div class="position-relative mb-3">
                                                <input type="password" name="password" class="form-control form-control-lg " placeholder="" autocomplete="off">
                                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                                    <i class="bi bi-eye-slash fs-2"></i>
                                                                    <i class="bi bi-eye fs-2 d-none"></i>
                                                                </span>
                                            </div>
                                            <!--end::Input wrapper-->
                                            <!--begin::Meter-->
                                            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                            </div>
                                            <!--end::Meter-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <!--begin::Hint-->
                                        <div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
                                        <!--end::Hint-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                        <label class="required form-label fw-semibold fs-6 mb-2">Confirm Password</label>
                                        <input type="password"  name="confirm_password" class="form-control form-control-lg " placeholder="" autocomplete="off">
                                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                </div>
                                <!--end::Col-->
                            </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-15">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Roles</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="role_id" aria-label="Select a Roles" data-control="select2" data-placeholder="Select a Roles..." class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                <option></option>
                                @foreach($roles as $role)
                                    <option {{old('role_id')==$role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-15">
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Access Locations</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="access_location_ids[]" class="form-select" data-control="select2" data-close-on-select="false" data-placeholder="Select an access locations" data-allow-clear="true" multiple="multiple">
                                    <option value="0">All Locations</option>
                                    @foreach($locations as $location)
                                        <option {{in_array($location->id, old('access_location_ids', [])) ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Default Location</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="default_location_id" aria-label="Select a Roles" data-control="select2" data-placeholder="Select a default locaiton" class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                    <option></option>
                                    @foreach($locations as $location)
                                        <option {{old('default_location_id')==$location->id ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card-->
                <div class="card mb-3">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-4">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>More Information</h2>
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="kt_datepicker_1">
                                    <span class="required">Date of birth</span>
                                </label>
                                <div class="input-group">
                                                        <span class="input-group-text" data-td-target="#kt_datepicker_1" data-td-toggle="datetimepicker">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                    <input class="form-control" name="dob" placeholder="Date of birth"  id="kt_datepicker_1" value="{{old(date('Y-m-d'))}}" />
                                </div>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Gender</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="gender" aria-label="Select a Gender" data-control="select2" data-placeholder="Select a Gender..." class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                    <option></option>
                                    <option {{old('gender')==1 ? 'selected' : ''}} value="1">Male</option>
                                    <option {{old('gender')==2 ? 'selected' : ''}} value="2">Female</option>
                                    <option {{old('gender')==3 ? 'selected' : ''}} value="3">Rather not say </option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Marital Status</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="marital_status" aria-label="Select a Marital Status" data-control="select2" data-placeholder="Select a Marital Status" class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                    <option></option>
                                    <option {{old('marital_status')=='Married' ? 'selected' : ''}} value="Married">Married</option>
                                    <option {{old('marital_status')=='Unmarried' ? 'selected' : ''}} value="Unmarried">Unmarried</option>
                                    <option {{old('marital_status')=='Divorced' ? 'selected' : ''}} value="3">Divorced</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label for="blood-group" class="fs-6 fw-semibold mb-2">Blood Group</label>
                                <input value="{{old('blood_group')}}" type="text" name="blood_group" class="form-control " placeholder="Blood Group">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Phone</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="phone" value="{{old('phone')}}" class="form-control " placeholder="## ### ####">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Contact Number</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="contact_number" value="{{old('contact_number')}}" class="form-control " placeholder="## ### ####">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Family contact number</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="family_number" value="{{old('family_number')}}" class="form-control " placeholder="## ### ####">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Facebook Link</label>
                                <input value="{{old('fb_link')}}" type="text" name="fb_link" class="form-control " placeholder="https://facebook.com/">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Twitter Link</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="twitter_link" value="{{old('twitter_link')}}" class="form-control " placeholder="https://twitter.com/">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Social Media 1</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="social_media_1" value="{{old('social_media_1')}}" class="form-control " placeholder="Social media 1">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Social Media 2</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="social_media_2" value="{{old('social_media_2')}}" class="form-control " placeholder="## ### ####">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Custom field 1</label>
                                <input value="{{old('custom_field_1')}}" type="text" name="custom_field_1" class="form-control " placeholder="Custom field 1">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Custom field 2</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="custom_field_2" value="{{old('custom_field_2')}}" class="form-control " placeholder="Custom field 2">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Custom field 3</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="custom_field_3" value="{{old('custom_field_3')}}" class="form-control " placeholder="Custom field 3">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Custom field 4</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="custom_field_4" value="{{old('custom_field_4')}}" class="form-control " placeholder="Custom field 4">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Guardian Name</label>
                                <input value="{{old('guardian_name')}}" type="text" name="guardian_name" class="form-control " placeholder="Custom field 1">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Language</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="language" value="{{old('language')}}" class="form-control" placeholder="Language">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">ID Proof Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="id_proof_name" value="{{old('id_proof_name')}}" class="form-control " placeholder="ID proof name">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">ID Proof Number</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="id_proof_number" value="{{old('id_proof_number')}}" class="form-control " placeholder="ID proof number">
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Permanent Address</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea name="permanent_address"  class="form-control " cols="10" rows="3">{{old('permanent_address')}}</textarea>
                            <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Current Address</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="current_address"  class="form-control " cols="10" rows="3">{{old('current_address')}}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <div class="separator my-10 mb-6"></div>
                        <!--begin::Input group-->
                        <div class="row g-9 mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Bank Details</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="bank_details" value="{{old('bank_details')}}" class="form-control " placeholder="Bank Details">
                                <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

                <div class="text-center flex-center mt-8">
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                        <span class="indicator-label">Create User Account</span>
                        <span class="indicator-progress">Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                </form>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <!--begin::Javascript-->
    <script>var hostUrl = "assets/";</script>
    <script>
        new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_date_only"), {
            display: {
                viewMode: "calendar",
                components: {
                    decades: true,
                    year: true,
                    month: true,
                    date: true,
                    hours: false,
                    minutes: false,
                    seconds: false
                }
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            // Get references to the select boxes
            var accessLocationSelect = $('[name="access_location_ids[]"]');
            var defaultLocationSelect = $('[name="default_location_id"]');

            // Clone the original options of the Default Location select box
            var defaultOptions = defaultLocationSelect.children().clone();

            // Update the Default Location options whenever Access Location selections change
            accessLocationSelect.on('change', function () {
                var selectedLocationIds = $(this).val();

                // If "All Locations" is selected, disable other options and unselect them
                var allLocationsSelected = selectedLocationIds && selectedLocationIds.includes("0");
                if (allLocationsSelected) {
                    accessLocationSelect.children('option:not([value="0"])').prop('disabled', true).prop('selected', false);
                } else {
                    accessLocationSelect.children('option:not([value="0"])').prop('disabled', false);
                }

                var filteredOptions = defaultOptions.filter(function () {
                    return selectedLocationIds.includes($(this).val()) || allLocationsSelected;
                });

                defaultLocationSelect.empty().append(filteredOptions);
            });

            // Trigger the change event on page load to initialize Default Location options
            accessLocationSelect.trigger('change');
        });
    </script>

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="assets/js/custom/apps/user-management/users/list/table.js"></script>
    <script src="assets/js/custom/apps/user-management/users/list/export-users.js"></script>
    <script src="assets/js/custom/apps/user-management/users/list/add.js"></script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->

@endpush
