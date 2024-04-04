@extends('App.userManagement.userProfile.navBar')
@section('profile_settings_active_show','active')
@section('profile-content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{__('user.profile_details')}}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">

            <!--begin::Form-->
            <form action="{{route('profile.info.update', $current_user->id)}}" method="post" enctype="multipart/form-data" id="kt_account_profile_details_form" class="form">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.avatar')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{asset('assets/media/svg/avatars/blank.svg')}})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{$current_user->personal_info->profile_photo === null ? asset('assets/media/svg/avatars/blank.svg') : $current_user->personal_info->profile_photo }})"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" />
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
                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{__('user.full_name')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-3 fv-row">
                                    <input type="text" name="initname" class="form-control form-control-lg mb-3 mb-lg-0" placeholder="Prefix" value="{{old('initname', $current_user->personal_info->initname)}}" />
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-5 fv-row">
                                    <input type="text" name="first_name" class="form-control form-control-lg mb-3 mb-lg-0" placeholder="First name" value="{{old('first_name', $current_user->personal_info->first_name)}}" />
                                    @error('first_name')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        <div data-validator="notEmpty">{{$message}}</div>
                                    </div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4 fv-row">
                                    <input type="text" name="last_name" class="form-control form-control-lg" placeholder="Last name" value="{{old('last_name', $current_user->personal_info->last_name)}}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{__('user.username')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" value="{{old('username', $current_user->username)}}" />
                            @error('username')
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-validator="notEmpty">{{$message}}</div>
                            </div>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">{{__('user.date_of_birth')}}</span>
                        </label>
                        <div class="col-lg-8 fv-row">
                        <div class="input-group">
                                                        <span class="input-group-text" data-td-target="#kt_datepicker_1" data-td-toggle="datetimepicker">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                            <input class="form-control form-control-lg" name="dob" placeholder="Date of birth"  id="kt_datepicker_1" value="{{old('dob', $current_user->personal_info->dob)}}" />
                        </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.gender')}}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="col-lg-8 fv-row">
                            <select name="gender" aria-label="Select a Gender" data-control="select2" data-placeholder="Select a Gender..." class="form-select form-select-lg" data-hide-search="true">
                                <option></option>
                                <option {{old('gender', $current_user->personal_info->gender)==1 ? 'selected' : ''}} value="1">Male</option>
                                <option {{old('gender', $current_user->personal_info->gender)==2 ? 'selected' : ''}} value="2">Female</option>
                                <option {{old('gender', $current_user->personal_info->gender)==3 ? 'selected' : ''}} value="3">Rather not say </option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.marital')}}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="col-lg-8 fv-row">
                            <select name="marital_status" aria-label="Select a Marital Status" data-control="select2" data-placeholder="Select a Marital Status" class="form-select form-select-lg" data-hide-search="true">
                                <option></option>
                                <option {{old('marital_status', $current_user->personal_info->marital_status)=='Married' ? 'selected' : ''}} value="Married">Married</option>
                                <option {{old('marital_status', $current_user->personal_info->marital_status)=='Unmarried' ? 'selected' : ''}} value="Unmarried">Unmarried</option>
                                <option {{old('marital_status', $current_user->personal_info->marital_status)=='Divorced' ? 'selected' : ''}} value="3">Divorced</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.blood_group')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="blood_group" class="form-control form-control-lg" placeholder="Blood Group" value="{{old('blood_group', $current_user->personal_info->blood_group)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('common.language')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <!--begin::Input-->
                            <select name="language" aria-label="Select a Language" data-control="select2" data-placeholder="Select a language..." class="form-select form-select-lg">
                                <option value="">Select a Language...</option>
                                <option data-kt-flag="flags/indonesia.svg" value="id">Bahasa Indonesia - Indonesian</option>
                                <option data-kt-flag="flags/malaysia.svg" value="msa">Bahasa Melayu - Malay</option>
                                <option data-kt-flag="flags/canada.svg" value="ca">Català - Catalan</option>
                                <option data-kt-flag="flags/czech-republic.svg" value="cs">Čeština - Czech</option>
                                <option data-kt-flag="flags/netherlands.svg" value="da">Dansk - Danish</option>
                                <option data-kt-flag="flags/germany.svg" value="de">Deutsch - German</option>
                                <option @selected($current_user->personal_info->language == 'en' || old('language') == 'en') data-kt-flag="flags/united-kingdom.svg" value="en">English</option>
                                <option data-kt-flag="flags/united-kingdom.svg" value="en-gb">English UK - British English</option>
                                <option data-kt-flag="flags/spain.svg" value="es">Español - Spanish</option>
                                <option data-kt-flag="flags/philippines.svg" value="fil">Filipino</option>
                                <option data-kt-flag="flags/france.svg" value="fr">Français - French</option>
                                <option data-kt-flag="flags/gabon.svg" value="ga">Gaeilge - Irish (beta)</option>
                                <option data-kt-flag="flags/greenland.svg" value="gl">Galego - Galician (beta)</option>
                                <option data-kt-flag="flags/croatia.svg" value="hr">Hrvatski - Croatian</option>
                                <option data-kt-flag="flags/italy.svg" value="it">Italiano - Italian</option>
                                <option data-kt-flag="flags/hungary.svg" value="hu">Magyar - Hungarian</option>
                                <option @selected($current_user->personal_info->language == 'mm' || old('language') == 'mm') data-kt-flag="flags/myanmar.svg" value="mm">Myanmar - Burmese</option>
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
                            <!--begin::Hint-->
                            <div class="form-text">Please select a preferred language, including date, time, and number formatting.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.phone')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="contact_number" class="form-control form-control-lg" placeholder="Phone number" value="{{old('contact_number', $current_user->personal_info->contact_number)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.contact_number')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="alt_number" class="form-control form-control-lg" placeholder="Phone number" value="{{old('alt_number', $current_user->personal_info->alt_number)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.family_contact_number')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="family_number" class="form-control form-control-lg" placeholder="Phone number" value="{{old('family_number', $current_user->personal_info->family_number)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.facebook_link')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="fb_link" class="form-control form-control-lg" placeholder="Facebook Link" value="{{old('fb_link', $current_user->personal_info->fb_link)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.twitter_link')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="twitter_link" class="form-control form-control-lg" placeholder="Twitter Link" value="{{old('twitter_link', $current_user->personal_info->twitter_link)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.social_media')}} 1</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="social_media_1" class="form-control form-control-lg" placeholder="Social Media 1" value="{{old('social_media_1', $current_user->personal_info->social_media_1)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.social_media')}} 2</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="social_media_2" class="form-control form-control-lg" placeholder="Social Media 2" value="{{old('social_media_2', $current_user->personal_info->social_media_2)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.custom_field')}} 1</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="custom_field_1" class="form-control form-control-lg" placeholder="Custom field 1" value="{{old('custom_field_1', $current_user->personal_info->custom_field_1)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.custom_field')}} 2</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="custom_field_2" class="form-control form-control-lg" placeholder="Custom field 2" value="{{old('custom_field_2', $current_user->personal_info->custom_field_2)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.custom_field')}} 3</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="custom_field_3" class="form-control form-control-lg" placeholder="Custom field 3" value="{{old('custom_field_3', $current_user->personal_info->custom_field_3)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.custom_field')}} 4</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="custom_field_4" class="form-control form-control-lg" placeholder="Custom field 4" value="{{old('custom_field_4', $current_user->personal_info->custom_field_4)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.guardian_name')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="guardian_name" class="form-control form-control-lg" placeholder="Guardian Name" value="{{old('guardian_name', $current_user->personal_info->guardian_name)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.id_proof_name')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="id_proof_name" class="form-control form-control-lg" placeholder="ID Proof Name" value="{{old('id_proof_name', $current_user->personal_info->id_proof_name)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.id_proof_number')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="id_proof_number" class="form-control form-control-lg" placeholder="ID Proof Number" value="{{old('id_proof_number', $current_user->personal_info->id_proof_number)}}" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.permanent_address')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <textarea name="permanent_address"  class="form-control " cols="10" rows="3">{{old('permanent_address', $current_user->personal_info->permanent_address)}}</textarea>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">{{__('user.current_address')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <textarea name="current_address"  class="form-control " cols="10" rows="3">{{old('current_address', $current_user->personal_info->current_address)}}</textarea>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->



                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
{{--                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>--}}
                    <button type="submit" class="btn btn-primary" >{{__('common.save_changes')}}</button>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    <!--begin::Sign-in Method-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{__('user.sign_in_method')}}</h3>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_signin_method" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Email LocalAddress-->
                <div class="d-flex flex-wrap align-items-center">
                    <!--begin::Label-->
                    <div id="kt_signin_email">
                        <div class="fs-6 fw-bold mb-1">{{__('user.email')}}</div>
                        <div class="fw-semibold text-gray-600">{{$current_user->email}}</div>
                    </div>
                    <!--end::Label-->
                    <!--begin::Edit-->
                    <div id="kt_signin_email_edit" class="flex-row-fluid d-none">
                        <!--begin::Form-->
                        <form action="{{route('profile.email.update', $current_user->id)}}" method="post" id="kt_signin_change_email" class="form" novalidate="novalidate">
                            @csrf
                            @method('put')
                            <div class="row mb-6">
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div class="fv-row mb-0">
                                        <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">{{__('user.new_email')}}</label>
                                        <input type="email" class="form-control form-control-lg form-control-solid" id="emailaddress" placeholder="Email Address" name="email" value="{{old('email')}}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="fv-row mb-0">
                                        <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">{{__('user.confirm_password')}}</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid" name="password" id="password" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button id="kt_signin_submit" type="button" class="btn btn-primary me-2 px-6">{{__('common.update')}}</button>
                                <button id="kt_signin_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">{{__('common.cancel')}}</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Edit-->
                    <!--begin::Action-->
                    <div id="kt_signin_email_button" class="ms-auto">
                        <button class="btn btn-light btn-active-light-primary">{{__('user.change_email')}}</button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Email LocalAddress-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-6"></div>
                <!--end::Separator-->
                <!--begin::Password-->
                <div class="d-flex flex-wrap align-items-center mb-10">
                    <!--begin::Label-->
                    <div id="kt_signin_password">
                        <div class="fs-6 fw-bold mb-1">{{__('user.password')}}</div>
                        <div class="fw-semibold text-gray-600">************</div>
                    </div>
                    <!--end::Label-->
                    <!--begin::Edit-->
                    <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
                        <!--begin::Form-->
                        <form action="{{route('profile.password.update', $current_user->id)}}" method="post" id="kt_signin_change_password" class="form" novalidate="novalidate">
                            @csrf
                            @method('put')
                            <div class="row mb-1">
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">{{__('user.current_password')}}</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid" name="currentpassword" id="currentpassword" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="newpassword" class="form-label fs-6 fw-bold mb-3">{{__('user.new_password')}}</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid" name="newpassword" id="newpassword" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">{{__('user.confirm_new_password')}}</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid" name="confirmpassword" id="confirmpassword" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-5">{{__('user.password_info')}}</div>
                            <div class="d-flex">
                                <button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6">{{__('common.update')}}</button>
                                <button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">{{__('common.cancel')}}</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Edit-->
                    <!--begin::Action-->
                    <div id="kt_signin_password_button" class="ms-auto">
                        <button class="btn btn-light btn-active-light-primary">{{__('user.reset_password')}}</button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Password-->

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Sign-in Method-->

    <!--begin::Deactivate Account-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_deactivate" aria-expanded="true" aria-controls="kt_account_deactivate">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{__('user.deactivate_account')}}</h3>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_deactivate" class="collapse show">
            <!--begin::Form-->
            <form action="{{route('profile.deactivate', $current_user->id)}}" method="post" id="kt_account_deactivate_form" class="form">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Notice-->
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
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
                                <h4 class="text-gray-900 fw-bold">You Are Deactivating Your Account</h4>
                                <div class="fs-6 text-gray-700">For extra security, this requires you to confirm your email or phone number when you reset yousignr password.
                                    <br />
                                    <a class="fw-bold" href="#">Learn more</a></div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Notice-->
                    <!--begin::Form input row-->
                    <div class="form-check form-check-solid fv-row">
                        <input name="deactivate" class="form-check-input" type="checkbox" value="0" id="deactivate" />
                        <label class="form-check-label fw-semibold ps-2 fs-6" for="deactivate">{{__('user.account_delete_confirm_msg')}}</label>
                    </div>
                    <!--end::Form input row-->
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button id="kt_account_deactivate_account_submit" type="submit" class="btn btn-danger fw-semibold">{{__('user.deactivate_account')}}</button>
                </div>
                <!--end::Card footer-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Deactivate Account-->
@endsection
