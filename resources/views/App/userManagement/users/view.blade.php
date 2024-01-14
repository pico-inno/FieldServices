@extends('App.main.navBar')

@section('user_active','active')
@section('user_active_show','active show')
@section('user_here_show','here show')
@section('user_list_active_show','active show')
@section('styles')
     {{-- css file for this page --}}
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Summary-->
                            <!--begin::User Info-->
                            <div class="d-flex flex-center flex-column py-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <div class="symbol-label fs-3 bg-light-danger text-danger">
                                       <span class="display-4">  {{substr($user->personal_info->first_name, 0,1)}}</span>
                                    </div>
{{--                                    <img src="assets/media/avatars/300-6.jpg" alt="image" />--}}
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">
                                    {{$user->personal_info->initname}}
                                    {{$user->personal_info->first_name}}
                                    {{$user->personal_info->last_name}}
                                </a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="mb-9">
                                    <!--begin::Badge-->
                                    <div class="badge badge-lg badge-light-primary d-inline">{{$user->role->name}}</div>
                                    <!--begin::Badge-->
                                </div>
                                <!--end::Position-->
                            </div>
                            <!--end::User Info-->
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                    {{__('common.details')}}
                                    <span class="ms-2 rotate-180">
													<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                        <!--end::Svg Icon-->
												</span></div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
													<a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-light-primary">
                                                        {{__('common.edit')}}
                                                    </a>
												</span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator"></div>
                            <!--begin::Details content-->
                            <div id="kt_user_view_details" class="collapse show">
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('user.username')}}</div>
                                    <div class="text-gray-600">{{$user->username}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('user.email')}}</div>
                                    <div class="text-gray-600">
                                        <a href="#" class="text-gray-600 text-hover-primary">{{$user->email}}</a>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('user.is_active')}}</div>
                                    <div class="text-gray-600">
                                        <div class="badge badge-light-{{$user->is_active == 1 ? 'primary' : 'danger'}} fw-bold">{{$user->is_active == 1 ? 'Active' : 'Inactive'}}</div>
                                    </div>
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">{{__('user.user_information')}}</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_view_overview_security">{{__('user.documents_and_note')}}</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_user_view_overview_events_and_logs_tab">{{__('user.activities')}}</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                            <!--begin::User Details-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body p-5">
                                    <!--being::More Details-->
                                   <div class="fs-4 py-3">
                                       <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_more_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                           {{__('user.more_information')}}
                                           <span class="ms-2 rotate-180">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                               <!--end::Svg Icon-->
                                        </span>
                                       </div>
                                   </div>
                                    <div class="separator"></div>
                                    <div class="collapse show" id="kt_user_view_more_details">
                                      <div class="row">
                                          <div class="col-md-6">
                                              <div class="row mb-7 mt-5">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.date_of_birth')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800">{{$user->personal_info->dob}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.gender')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold text-gray-800 fs-6">{{$user->personal_info->gender}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.marital_status')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->marital_status}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.blood_group')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->blood_group}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.phone')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800">{{$user->personal_info->contact_number}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.contact_number')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800">{{$user->personal_info->alt_number}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-md-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.family_contact_number')}}:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->family_number}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.facebook_link')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <a class="fw-semibold text-gray-800 fs-6" href="{{$user->personal_info->fb_link}}" target="_blank">{{$user->personal_info->fb_link}}</a>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.twitter_link')}}:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <a class="fw-semibold text-gray-800 fs-6" href="{{$user->personal_info->twitter_link}}" target="_blank">{{$user->personal_info->twitter_link}}</a>
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
                                                      <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->social_media_1}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.social_media')}} 2:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->social_media_2}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 1:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800">{{$user->personal_info->custom_field_1}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-7">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 2:</label>
                                                  <!--end::Label-->
                                                  <!--begin::Col-->
                                                  <div class="col-6">
                                                      <span class="fw-bold fs-6 text-gray-800">{{$user->personal_info->custom_field_2}}</span>
                                                  </div>
                                                  <!--end::Col-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 3:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->custom_field_3}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.custom_field')}} 4:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->custom_field_4}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.guardian_name')}}:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->guardian_name}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('common.language')}}:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->language}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.id_proof_name')}}:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->id_proof_name}}</span>
                                                  </div>
                                                  <!--begin::Label-->
                                              </div>
                                              <div class="row mb-10">
                                                  <!--begin::Label-->
                                                  <label class="col-6 fw-semibold text-muted">{{__('user.id_proof_number')}}:</label>
                                                  <!--begin::Label-->
                                                  <!--begin::Label-->
                                                  <div class="col-6">
                                                      <span class="fw-semibold fs-6 text-gray-800">{{$user->personal_info->id_proof_number}}</span>
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
                                                          <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->permanent_address}}</span>
                                                      </div>
                                                      <!--end::Col-->
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="row mb-7 mt-5 mb-10">
                                                      <!--begin::Label-->
                                                      <label class="col-6 fw-semibold text-muted">{{__('user.id_proof_number')}}:</label>
                                                      <!--end::Label-->
                                                      <!--begin::Col-->
                                                      <div class="col-6">
                                                          <span class="fw-bold fs-6 text-gray-800 me-2">{{$user->personal_info->current_address}}</span>
                                                      </div>
                                                      <!--end::Col-->
                                                  </div>
                                              </div>
                                          </div>
                                      </div>


                                    </div>
                                    <!--end::More Details-->
                                    <!--being::Bank Details-->
                                    <div class="fs-4 py-3">
                                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_bank_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                            {{__('user.bank_details')}}:
                                            <span class="ms-2 rotate-180">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                            <span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                                <!--end::Svg Icon-->
                                        </span>
                                        </div>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="collapse show" id="kt_user_view_bank_details">
                                        <!--begin::Row-->
                                        <div class="row mb-7 mt-5">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Account Holder's Name:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Account Number:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-semibold text-gray-800 fs-6">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Bank Name:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">  </a>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Bank Identifier Code:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Branch:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-10">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Tax Payer ID:</label>
                                            <!--begin::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-7">
                                                <span class="fw-semibold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--begin::Label-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Bank Details-->

                                    <!--being::HRM Details-->
                                    <div class="fs-4 py-3">
                                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_hrm_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">HRM Details:
                                            <span class="ms-2 rotate-180">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                            <span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                                <!--end::Svg Icon-->
                                        </span>
                                        </div>
                                    </div>
                                    <div class="separator mb-2"></div>
                                    <div class="collapse show" id="kt_user_view_hrm_details">
                                        <!--begin::Row-->
                                        <div class="row mb-7 mt-5">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Department:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Designation:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-semibold text-gray-800 fs-6">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Basic salary:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800 me-2">  </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Pay Cycle:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800 me-2">  </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-5 fw-semibold text-muted">Primary work location:</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-7">
                                                <span class="fw-bold fs-6 text-gray-800">   </span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::HRM Details-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::User Details-->
                        </div>
                        <!--end:::Tab pane-->
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0 pt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <!--begin::Search-->
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                            <span class="svg-icon svg-icon-2 position-absolute ms-6">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
													<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
												</svg>
											</span>
                                            <!--end::Svg Icon-->
                                            <input type="text" data-kt-user-table-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search user" />
                                        </div>
                                        <!--end::Search-->
                                    </div>
                                    <!--begin::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Toolbar-->
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                            <!--begin::Export-->
                                            <button type="button" class="btn btn-sm btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
													<path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor" />
													<path opacity="0.3" d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="currentColor" />
												</svg>
											</span>
                                                <!--end::Svg Icon-->Export</button>
                                            <!--end::Export-->
                                            <!--begin::Add user-->
                                            <button type="button" class="btn btn-sm btn-primary">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
												</svg>
											</span>
                                                <!--end::Svg Icon-->Add</button>
                                            <!--end::Add user-->
                                        </div>
                                        <!--end::Toolbar-->
                                        <!--begin::Group actions-->
                                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                            <div class="fw-bold me-5">
                                                <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
                                            <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete Selected</button>
                                        </div>
                                        <!--end::Group actions-->
                                        <!--begin::Modal - Adjust Balance-->
                                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                            <!--begin::Modal dialog-->
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <!--begin::Modal content-->
                                                <div class="modal-content">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header">
                                                        <!--begin::Modal title-->
                                                        <h2 class="fw-bold">Export Users</h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
																<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
																	<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
																</svg>
															</span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <!--begin::Form-->
                                                        <form id="kt_modal_export_users_form" class="form" action="#">
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-10">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mb-2">Select Roles:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select name="role" data-control="select2" data-placeholder="Select a role" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                                    <option></option>
                                                                    <option value="Administrator">Administrator</option>
                                                                    <option value="Analyst">Analyst</option>
                                                                    <option value="Developer">Developer</option>
                                                                    <option value="Support">Support</option>
                                                                    <option value="Trial">Trial</option>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-10">
                                                                <!--begin::Label-->
                                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select name="format" data-control="select2" data-placeholder="Select a format" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                                    <option></option>
                                                                    <option value="excel">Excel</option>
                                                                    <option value="pdf">PDF</option>
                                                                    <option value="cvs">CVS</option>
                                                                    <option value="zip">ZIP</option>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Actions-->
                                                            <div class="text-center">
                                                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                                                    <span class="indicator-label">Submit</span>
                                                                    <span class="indicator-progress">Please wait...
																	<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                                </button>
                                                            </div>
                                                            <!--end::Actions-->
                                                        </form>
                                                        <!--end::Form-->
                                                    </div>
                                                    <!--end::Modal body-->
                                                </div>
                                                <!--end::Modal content-->
                                            </div>
                                            <!--end::Modal dialog-->
                                        </div>
                                        <!--end::Modal - New Card-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-4">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                        <!--begin::Table head-->
                                        <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">Heading</th>
                                            <th class="min-w-100px">Created At</th>
                                            <th class="min-w-100px">Updated At</th>
                                            <th class="text-end min-w-100px">Actions</th>
                                        </tr>
                                        <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="text-gray-600 fw-semibold">
                                        <!--begin::Table row-->
                                        <tr>
                                            <!--begin::Username=-->
                                            <td>Last Item</td>
                                            <!--end::Username=-->
                                            <!--begin::Role=-->
                                            <td>12-03-2023</td>
                                            <!--end::Role=-->
                                            <!--begin::Is Active-->
                                            <td>14-03-2023</td>
                                            <!--begin::Is Active-->
                                            <!--begin::Action=-->
                                            <td class="text-end">
                                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                    <span class="svg-icon svg-icon-5 m-0">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                                    <!--end::Svg Icon--></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../../demo7/dist/apps/user-management/users/view.html" class="menu-link px-3">View</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../../demo7/dist/apps/user-management/users/view.html" class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            <!--end::Action=-->
                                        </tr>
                                        <!--end::Table row-->
                                        <!--begin::Table row-->
                                        <tr>
                                            <!--begin::Username=-->
                                            <td>Sale List Note</td>
                                            <!--end::Username=-->
                                            <!--begin::Role=-->
                                            <td>12-01-2023</td>
                                            <!--end::Role=-->
                                            <!--begin::Is Active-->
                                            <td>15-03-2023</td>
                                            <!--begin::Is Active-->
                                            <!--begin::Action=-->
                                            <td class="text-end">
                                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                    <span class="svg-icon svg-icon-5 m-0">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                                    <!--end::Svg Icon--></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../../demo7/dist/apps/user-management/users/view.html" class="menu-link px-3">View</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../../demo7/dist/apps/user-management/users/view.html" class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            <!--end::Action=-->
                                        </tr>
                                        <!--end::Table row-->
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end:::Tab pane-->
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
                            <!--begin::Login sessions-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <!--begin::Heading-->
                                    <div class="card-title">
                                        <h3>Account Activity</h3>
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <div class="my-1 me-4">
                                            <!--begin::Select-->
                                            <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                <option value="1" selected="selected">1 Hours</option>
                                                <option value="2">6 Hours</option>
                                                <option value="3">12 Hours</option>
                                                <option value="4">24 Hours</option>
                                            </select>
                                            <!--end::Select-->
                                        </div>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body p-0">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="login_logs_table">
                                            <!--begin::Thead-->
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                            <tr>
                                                <th class="min-w-200px">Timestamp</th>
                                                <th class="min-w-100px">Log Type</th>
                                                <th class="min-w-150px">Description</th>
                                                <th class="min-w-150px">Event</th>
                                                <th class="min-w-150px">Status</th>
                                                <th class="min-w-150px">By</th>
                                            </tr>
                                            </thead>
                                            <!--end::Thead-->
                                            <!--begin::Tbody-->
                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                            {{--                    <tr>--}}
                                            {{--                        <td>--}}
                                            {{--                            <a href="#" class="text-hover-primary text-gray-600">USA(5)</a>--}}
                                            {{--                        </td>--}}
                                            {{--                        <td>--}}
                                            {{--                            <span class="badge badge-light-success fs-7 fw-bold">OK</span>--}}
                                            {{--                        </td>--}}
                                            {{--                        <td>Chrome - Windows</td>--}}
                                            {{--                        <td>236.125.56.78</td>--}}
                                            {{--                        <td>2 mins ago</td>--}}
                                            {{--                    </tr>--}}
                                            </tbody>
                                            <!--end::Tbody-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Login sessions-->
                        </div>
                        <!--end:::Tab pane-->
                    </div>
                    <!--end:::Tab content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
            <!--begin::Modals-->

{{--Coming--}}
            <!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script>

        $(document).ready(function() {
            let id = {{$user->id}};

            var dataTable = $('#login_logs_table').DataTable({
                serverSide: true,
                processing: true,
                order: [[5, 'desc']],
                ajax: {
                    url: `/logs/current/select/all/${id}`,
                    type: 'GET',
                },
                columns: [

                    { data: 'created_at' },
                    { data: 'log_name' },
                    { data: 'description' },
                    { data: 'event' },
                    { data: 'status' },
                    { data: 'created_user' },

                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                const dateTime = new Date(data);
                                const formattedDateTime = dateTime.toLocaleString();
                                return formattedDateTime;
                            }
                            return data;
                        }
                    },

                    {
                        targets: 4,
                        render: function (data, type, row) {
                            let badgeClass = 'badge-light-success';

                            if (data === 'fail') {
                                badgeClass = 'badge-light-danger';
                            } else if (data === 'warn') {
                                badgeClass = 'badge-light-warning';
                            }

                            return `<span class="badge ${badgeClass} fs-7 fw-bold">${data}</span>`;
                        }

                    },
                    {
                        targets: 5,
                        render: function (data, type, row) {
                            $username = data.username;
                            return $username;
                        }
                    }
                ],
            });
        });


    </script>
@endpush

