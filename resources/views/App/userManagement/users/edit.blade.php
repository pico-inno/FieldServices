@extends('App.main.navBar')

@section('user_active','active')
@section('user_active_show','active show')
@section('user_here_show','here show')
@section('user_list_active_show','active show')
@section('styles')
@endsection

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('user.edit_user')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('user.users_list')}}</li>
        <li class="breadcrumb-item text-dark">{{__('common.edit')}}</li>
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
                    <button onclick="window.location.href='{{route('users.index')}}'" type="reset" class="btn btn-light me-3" data-kt-users-modal-action="close"><i class="fas fa-arrow-left"></i>{{__('common.back')}}</button>
                </div>
                <!--begin::Form-->
                <form action="{{route('users.update', $user->id)}}" method="post" id="kt_modal_add_user_form" class="form">
                    @csrf
                    @method('put')
                    <!--begin::Card-->
                    <div class="card mb-3">
                        <!--begin::Card header-->
                        <div class="card-header border-0 pt-4">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{__('user.user_information')}}</h2>
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
                                    <label class="fw-semibold fs-6 mb-2">{{__('common.prefix')}}:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="initname" value="{{old('initname', $user->personal_info->initname)}}" class="form-control  mb-3 mb-lg-0" placeholder="Mr / Mrs / Miss"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-5 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fw-semibold fs-6 mb-2">{{__('user.first_name')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="first_name" value="{{old('first_name', $user->personal_info->first_name)}}" class="form-control  mb-3 mb-lg-0" placeholder="First Name"/>
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
                                    <label class="fw-semibold fs-6 mb-2">{{__('user.last_name')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="last_name" value="{{old('last_name', $user->personal_info->last_name)}}" class="form-control  mb-3 mb-lg-0" placeholder="Last Name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{__('user.email')}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="email" name="email" value="{{old('email', $user->email)}}" class="form-control " placeholder="username@domain.xyz">
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
                                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$user->is_active))  class="form-check-input me-3" name="email_notification_1" id="kt_modal_add_is_active">
                                        <!--end::Input-->
                                        <!--begin::Label-->
                                        <label class="form-check-label" for="kt_modal_add_is_active">
                                            <div class="fw-bold">{{__('user.is_active')}}</div>
                                            <div class="text-gray-600">{{__('user.is_active_description')}}</div>
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
                                <h2>{{__('user.roles_and_permissions')}}</h2>
                            </div>
                            <!--begin::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-4">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{__('user.user_name')}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="username" value="{{old('username', $user->username)}}" class="form-control " placeholder="Username">
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
                                            <label class="required form-label fw-semibold fs-6 mb-2">{{__('user.password')}}</label>
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
                                        <div class="text-muted">{{__('user.password_info')}}</div>
                                        <!--end::Hint-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                        <label class="required form-label fw-semibold fs-6 mb-2">{{__('user.confirm_password')}}</label>
                                        <input type="password"  name="confirm_password" class="form-control form-control-lg " placeholder="" autocomplete="off">
                                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-15">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{__('role.roles')}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="role_id" aria-label="Select a Roles" data-control="select2" data-placeholder="Select a Roles..." class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                    <option></option>
                                    @foreach($roles as $role)
                                        <option {{old('role_id', $user->role_id)==$role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-15">
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">{{__('user.access_locations')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="access_location_ids[]" class="form-select" data-control="select2" data-close-on-select="false" data-placeholder="Select an access locations" data-allow-clear="true" multiple="multiple">
                                        <option {{in_array(0, old('access_location_ids', unserialize($user->access_location_ids))) ? 'selected' : ''}} value="0">All Locations</option>
                                        @foreach($locations as $location)
{{--                                            <option {{in_array($location->id, old('access_location_ids', [])) ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>--}}
                                            <option {{in_array($location->id, old('access_location_ids', unserialize($user->access_location_ids))) ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">{{__('user.default_location')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="default_location_id" aria-label="Select a Roles" data-control="select2" data-placeholder="Select a default locaiton" class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                        <option></option>
                                        @foreach($locations as $location)
                                            <option {{old('default_location_id')==$location->id || $user->default_location_id == $location->id ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>
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
                                <h2>{{__('user.more_information')}}</h2>
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
                                        <span class="required">{{__('user.date_of_birth')}}</span>
                                    </label>
                                    <div class="input-group">
                                                        <span class="input-group-text" data-td-target="#kt_datepicker_1" data-td-toggle="datetimepicker">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                        <input class="form-control" name="dob" placeholder="Date of birth"  id="kt_datepicker_1" value="{{old('blood_group', $user->personal_info->blood_group)}}{{old('dob', $user->personal_info->dob)}}" />
                                    </div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.gender')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="gender" aria-label="Select a Gender" data-control="select2" data-placeholder="Select a Gender..." class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                        <option></option>
                                        <option {{old('gender', $user->personal_info->gender)==1 ? 'selected' : ''}} value="1">Male</option>
                                        <option {{old('gender', $user->personal_info->gender)==2 ? 'selected' : ''}} value="2">Female</option>
                                        <option {{old('gender', $user->personal_info->gender)==3 ? 'selected' : ''}} value="3">Rather not say </option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.marital_status')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="marital_status" aria-label="Select a Marital Status" data-control="select2" data-placeholder="Select a Marital Status" class="form-select" data-hide-search="true" data-dropdown-parent="#kt_modal_add_user">
                                        <option></option>
                                        <option {{old('marital_status', $user->personal_info->marital_status)=='Married' ? 'selected' : ''}} value="Married">Married</option>
                                        <option {{old('marital_status', $user->personal_info->marital_status)=='Unmarried' ? 'selected' : ''}} value="Unmarried">Unmarried</option>
                                        <option {{old('marital_status', $user->personal_info->marital_status)=='Divorced' ? 'selected' : ''}} value="3">Divorced</option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <label for="blood-group" class="fs-6 fw-semibold mb-2">{{__('user.blood_group')}}</label>
                                    <input value="{{old('blood_group', $user->personal_info->blood_group)}}" type="text" name="blood_group" class="form-control " placeholder="Blood Group">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.phone')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="contact_number" value="{{old('contact_number', $user->personal_info->contact_number)}}" class="form-control " placeholder="## ### ####">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.contact_number')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="alt_number" value="{{old('alt_number', $user->personal_info->alt_number)}}" class="form-control " placeholder="## ### ####">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.family_contact_number')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="family_number" value="{{old('family_number', $user->personal_info->family_number)}}" class="form-control " placeholder="## ### ####">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.facebook_link')}}</label>
                                    <input value="{{old('fb_link', $user->personal_info->fb_link)}}" type="text" name="fb_link" class="form-control " placeholder="https://facebook.com/">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.twitter_link')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="twitter_link" value="{{old('twitter_link', $user->personal_info->twitter_link)}}" class="form-control " placeholder="https://twitter.com/">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.social_media')}} 1</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="social_media_1" value="{{old('social_media_1', $user->personal_info->social_media_1)}}" class="form-control " placeholder="Social media 1">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.social_media')}} 2</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="social_media_2" value="{{old('social_media_2', $user->personal_info->social_media_2)}}" class="form-control " placeholder="## ### ####">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.custom_field')}} 1</label>
                                    <input value="{{old('custom_field_1', $user->personal_info->custom_field_1)}}" type="text" name="custom_field_1" class="form-control " placeholder="Custom field 1">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.custom_field')}} 2</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="custom_field_2" value="{{old('custom_field_2', $user->personal_info->custom_field_2)}}" class="form-control " placeholder="Custom field 2">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.custom_field')}} 3</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="custom_field_3" value="{{old('custom_field_3', $user->personal_info->custom_field_3)}}" class="form-control " placeholder="Custom field 3">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.custom_field')}} 4</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="custom_field_4" value="{{old('custom_field_4', $user->personal_info->custom_field_4)}}" class="form-control " placeholder="Custom field 4">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-3 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.guardian_name')}}</label>
                                    <input value="{{old('guardian_name', $user->personal_info->guardian_name)}}" type="text" name="guardian_name" class="form-control " placeholder="Custom field 1">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-4 fv-row">
                                        <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('common.language')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="fv-row">
                                            <!--begin::Input-->
                                            <select name="language" aria-label="Select a Language" data-control="select2" data-placeholder="Select a language..." class="form-select">
                                                <option value="">Select a Language...</option>
                                                <option data-kt-flag="flags/indonesia.svg" value="id">Bahasa Indonesia - Indonesian</option>
                                                <option data-kt-flag="flags/malaysia.svg" value="msa">Bahasa Melayu - Malay</option>
                                                <option data-kt-flag="flags/canada.svg" value="ca">Català - Catalan</option>
                                                <option data-kt-flag="flags/czech-republic.svg" value="cs">Čeština - Czech</option>
                                                <option data-kt-flag="flags/netherlands.svg" value="da">Dansk - Danish</option>
                                                <option data-kt-flag="flags/germany.svg" value="de">Deutsch - German</option>
                                                <option data-kt-flag="flags/united-kingdom.svg" value="en">English</option>
                                                <option data-kt-flag="flags/united-kingdom.svg" value="en-gb">English UK - British English</option>
                                                <option data-kt-flag="flags/spain.svg" value="es">Español - Spanish</option>
                                                <option data-kt-flag="flags/philippines.svg" value="fil">Filipino</option>
                                                <option data-kt-flag="flags/france.svg" value="fr">Français - French</option>
                                                <option data-kt-flag="flags/gabon.svg" value="ga">Gaeilge - Irish (beta)</option>
                                                <option data-kt-flag="flags/greenland.svg" value="gl">Galego - Galician (beta)</option>
                                                <option data-kt-flag="flags/croatia.svg" value="hr">Hrvatski - Croatian</option>
                                                <option data-kt-flag="flags/italy.svg" value="it">Italiano - Italian</option>
                                                <option data-kt-flag="flags/hungary.svg" value="hu">Magyar - Hungarian</option>
                                                <option data-kt-flag="flags/myanmar.svg" value="mm">Myanmar - Burmese</option>
                                                <option data-kt-flag="flags/netherlands.svg" value="nl">Nederlands - Dutch</option>
                                                <option data-kt-flag="flags/norway.svg" value="no">Norsk - Norwegian</option>
                                                <option data-kt-flag="flags/poland.svg" value="pl">Polski - Polish</option>
                                                <option data-kt-flag="flags/portugal.svg" value="pt">Português - Portuguese</option>
                                                <option data-kt-flag="flags/romania.svg" value="ro">Română - Romanian</option>
                                                <option data-kt-flag="flags/slovakia.svg" value="sk">Slovenčina - Slovak</option>
                                                <option data-kt-flag="flags/finland.svg" value="fi">Suomi - Finnish</option>
                                                <option data-kt-flag="flags/el-salvador.svg" value="sv">Svenska - Swedish</option>
                                                <option data-kt-flag="flags/virgin-islands.svg" value="vi">Tiếng Việt - Vietnamese</option>
                                                <option data-kt-flag="flags/turkey.svg" value="tr">Türkçe - Turkish</option>
                                                <option data-kt-flag="flags/greece.svg" value="el">Ελληνικά - Greek</option>
                                                <option data-kt-flag="flags/bulgaria.svg" value="bg">Български език - Bulgarian</option>
                                                <option data-kt-flag="flags/russia.svg" value="ru">Русский - Russian</option>
                                                <option data-kt-flag="flags/suriname.svg" value="sr">Српски - Serbian</option>
                                                <option data-kt-flag="flags/ukraine.svg" value="uk">Українська мова - Ukrainian</option>
                                                <option data-kt-flag="flags/israel.svg" value="he">עִבְרִית - Hebrew</option>
                                                <option data-kt-flag="flags/pakistan.svg" value="ur">اردو - Urdu (beta)</option>
                                                <option data-kt-flag="flags/argentina.svg" value="ar">العربية - Arabic</option>
                                                <option data-kt-flag="flags/argentina.svg" value="fa">فارسی - Persian</option>
                                                <option data-kt-flag="flags/mauritania.svg" value="mr">मराठी - Marathi</option>
                                                <option data-kt-flag="flags/india.svg" value="hi">हिन्दी - Hindi</option>
                                                <option data-kt-flag="flags/bangladesh.svg" value="bn">বাংলা - Bangla</option>
                                                <option data-kt-flag="flags/guam.svg" value="gu">ગુજરાતી - Gujarati</option>
                                                <option data-kt-flag="flags/india.svg" value="ta">தமிழ் - Tamil</option>
                                                <option data-kt-flag="flags/saint-kitts-and-nevis.svg" value="kn">ಕನ್ನಡ - Kannada</option>
                                                <option data-kt-flag="flags/thailand.svg" value="th">ภาษาไทย - Thai</option>
                                                <option data-kt-flag="flags/south-korea.svg" value="ko">한국어 - Korean</option>
                                                <option data-kt-flag="flags/japan.svg" value="ja">日本語 - Japanese</option>
                                                <option data-kt-flag="flags/china.svg" value="zh-cn">简体中文 - Simplified Chinese</option>
                                                <option data-kt-flag="flags/taiwan.svg" value="zh-tw">繁體中文 - Traditional Chinese</option>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Col-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-4 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.id_proof_name')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="id_proof_name" value="{{old('id_proof_name', $user->personal_info->id_proof_name)}}" class="form-control " placeholder="ID proof name">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-4 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.id_proof_number')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="id_proof_number" value="{{old('id_proof_number', $user->personal_info->id_proof_number)}}" class="form-control " placeholder="ID proof number">
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
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.permanent_address')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea name="permanent_address"  class="form-control " cols="10" rows="3">{{old('permanent_address', $user->personal_info->permanent_address)}}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">{{__('user.current_address')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea name="current_address"  class="form-control " cols="10" rows="3">{{old('current_address', $user->personal_info->current_address)}}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <div class="separator my-10 mb-6"></div>
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{__('user.bank_details')}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="bank_details" value="{{old('bank_details', $user->personal_info->bank_details)}}" class="form-control " placeholder="Bank Details">
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
                        <span class="indicator-label">{{__('common.update')}}</span>
                        <span class="indicator-progress">{{__('common.please_wait')}}...
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
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/table.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/export-users.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/edit.js')}}"></script>


    <script>
        $("#kt_datepicker_1").flatpickr({
            dateFormat: "Y-m-d",
        });
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
@endpush
