@extends('App.main.navBar')

@section('user_active','active')
@section('user_active_show','active show')
@section('role_active_show','active show')
@section('role_add_active_show','active show')

@section('styles')
@endsection

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('role.create_role')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('role.roles_list')}}</li>
        <li class="breadcrumb-item text-dark">{{__('common.create')}}</li>
    </ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <div class="col-12" id="kt_add_role">
                <div class="d-flex mb-5" data-kt-users-modal-action="close">
                    <button onclick="window.location.href='{{route('roles.index')}}'" type="reset" class="btn btn-light me-3"><i class="fas fa-arrow-left"></i>{{__('common.back')}}</button>
                </div>
                    <!--begin::Form-->
                    <form action="{{route('roles.store')}}" method="post" id="kt_modal_add_role_form" class="form">
                        @csrf
                        <!--begin::Card-->
                        <div class="card mb-3">
                            <!--begin::Card body-->
                            <div class="card-body py-4">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="fs-5 fw-bold form-label mb-2">
                                            <span class="required">{{__('role.role_name')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" placeholder="Enter a role name" name="name" value="{{old('name')}}" />
                                        <!--end::Input-->
                                        @error('name')
                                        <div class="fv-plugins-message-container invalid-feedback">
                                            <div data-validator="notEmpty">{{$message}}</div>
                                        </div>
                                        @enderror
                                    </div>
                                    <!--end::Input group-->

                                <!--begin::Permissions-->
                                <div class="fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">{{__('role.role_permissions')}}</label>
                                    <!--end::Label-->
                                    @if (session('permissionError'))
                                        <div class="alert alert-danger">{{session('permissionError')}}</div>
                                    @endif
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-semibold">
                                            <!--begin::Table row-->
                                            <tr>
                                                <td class="text-gray-800 fs-4">Administrator Access
                                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Allows a full access to the system"></i></td>
                                                <td>
                                                    <!--begin::Checkbox-->
                                                    <label class="form-check form-check-custom form-check-solid me-9">
                                                        <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                        <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                </td>
                                            </tr>
                                            <!--end::Table row-->
                                            @foreach($features as $feature)
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">{{ucwords($feature->name)}}</td>
                                                <!--end::Label-->

                                                <!--begin::Options-->
                                                <td>

                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex user-select-none">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-10 mb-4">
                                                            <input class="form-check-input feature-select-all" type="checkbox" value="{{$feature->name}}" />
                                                            <span class="form-check-label">Select all</span>
                                                        </label>
                                                        @foreach ($feature->permissions as $permission)
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-10 mb-4">
                                                            <input class="form-check-input" @checked(old($feature->name.'_'.$permission->name)==$permission->id) type="checkbox" value="{{$permission->id}}" name="{{$feature->name}}_{{$permission->name}}" />
                                                            <span class="form-check-label">{{ucwords($permission->name)}}</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        @endforeach
                                                    </div>
                                                    <!--end::Wrapper-->

                                                </td>
                                                <!--end::Options-->

                                            </tr>
                                            <!--end::Table row-->
                                            @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Permissions-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">

                                    <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                        <span class="indicator-label">{{__('common.create')}}</span>
                                        <span class="indicator-progress">{{__('common.please_wait')}}...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </form>
                    <!--end::Form-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
@push('scripts')
    <script src="assets/js/custom/apps/user-management/roles/list/add.js"></script>

@endpush
