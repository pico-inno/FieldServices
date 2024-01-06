@extends('App.userManagement.userProfile.navBar')
@section('profile_active_show','active')
@section('profile-content')
    <!--begin::details View-->
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <div class="card-header cursor-pointer">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{__('user.profile_details')}}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Card body-->
        <div class="card-body p-9">
            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-7 mt-5">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.date_of_birth')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800">{{$current_user->personal_info->dob}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.gender')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-semibold text-gray-800 fs-6">{{$current_user->personal_info->gender}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.marital_status')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->marital_status}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.blood_group')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->blood_group}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.phone')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800">{{$current_user->personal_info->contact_number}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.contact_number')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800">{{$current_user->personal_info->alt_number}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-md-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.family_contact_number')}}:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->family_number}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.facebook_link')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <a class="fw-semibold text-gray-800 fs-6" href="{{$current_user->personal_info->fb_link}}" target="_blank">{{$current_user->personal_info->fb_link}}</a>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.twitter_link')}}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <a class="fw-semibold text-gray-800 fs-6" href="{{$current_user->personal_info->twitter_link}}" target="_blank">{{$current_user->personal_info->twitter_link}}</a>
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mb-7 mt-5">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.social_media')}} 1:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->social_media_1}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.social_media')}} 2:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->social_media_2}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 1:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800">{{$current_user->personal_info->custom_field_1}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 2:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <span class="fw-bold fs-6 text-gray-800">{{$current_user->personal_info->custom_field_2}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 3:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->custom_field_3}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 4:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->custom_field_4}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.guardian_name')}}:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->guardian_name}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('common.language')}}:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->language}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.id_proof_name')}}:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->id_proof_name}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-6 fw-semibold text-muted">{{__('user.id_proof_number')}}:</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{$current_user->personal_info->id_proof_number}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                </div>
                <div class="separator"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-7 mt-5 mb-10">
                            <!--begin::Label-->
                            <label class="col-6 fw-semibold text-muted">{{__('user.permanent_address')}}:</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-6">
                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->permanent_address}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-7 mt-5 mb-10">
                            <!--begin::Label-->
                            <label class="col-6 fw-semibold text-muted">{{__('user.current_address')}}:</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-6">
                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$current_user->personal_info->current_address}}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Notice-->
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                <!--begin::Icon-->
                <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
												<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
												<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
											</svg>
										</span>
                <!--end::Svg Icon-->
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1">
                    <!--begin::Content-->
                    <div class="fw-semibold">
                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                        <div class="fs-6 text-gray-700">Your payment was declined. To start using tools, please
                            <a class="fw-bold" href="../../demo7/dist/account/billing.html">Add Payment Method</a>.</div>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Notice-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::details View-->
@endsection
