@extends('App.main.navBar')

@section('user_active','active')
@section('user_active_show','active show')
@section('role_active_show','active show')
@section('role_list_active_show','active show')

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('usermanagement/role.roles_list')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('usermanagement/role.roles')}}</li>
        <li class="breadcrumb-item text-dark">{{__('usermanagement/role.roles_list')}}</li>
    </ul>
<!--end::Breadcrumb-->
@endsection


@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
													<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
												</svg>
											</span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search role" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            @if(hasCreate('role'))
                            <!--begin::Add Role-->
                            <button onclick="window.location.href='{{route('roles.create')}}'" type="button" class="btn btn-primary">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
												</svg>
											</span>
                                <!--end::Svg Icon-->{{__('usermanagement/role.create')}}</button>
                            <!--end::Add Role-->
                            @endif
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_roles">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-105px">Role</th>
                            <th class="text-start min-w-70px">Actions</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-semibold">
                        @foreach($roles as $role)
                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Role=-->
                            <td>{{$role->name}}</td>
                            <!--end::Role=-->
                            <!--begin::Action=-->
                            <td class="text-start">
                                @if(appAdmin($role->id, $role->name))
                                @if(hasUpdate('role') || hasDelete('role'))
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
                                    @if(hasUpdate('role'))
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{route('roles.edit', $role->id)}}" class="menu-link px-3">Edit</a>
                                    </div>
                                    <!--end::Menu item-->
                                    @endif
                                    @if(hasDelete('role'))
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
{{--                                        <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>--}}
                                        <form id="delete-form" action="{{route('roles.destroy', $role->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="menu-link px-3 btn btn-sm d-inline-block">Delete
                                            </button>
                                        </form>

                                    </div>
                                    <!--end::Menu item-->
                                    @endif
                                </div>
                                <!--end::Menu-->
                                @else
                                <p>No Actions for this User</p>
                                @endif
                                @else
                                    <p>Default Role</p>
                                @endif
                            </td>
                            <!--end::Action=-->
                        </tr>
                        <!--end::Table row-->
                        @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script src="assets/js/custom/apps/user-management/roles/list/table.js"></script>
    <script src="customJs/toaster.js"></script>
    <script>
        @if(session('error'))
            Swal.fire({
                title: 'Oops...',
                text: '{{session('error')}}',
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary me-3",
                }
            });
        @elseif(session('scuuess-toastr'))
        toastr.success("{{session('scuuess-toastr')}}");
        @endif
    </script>
@endpush
