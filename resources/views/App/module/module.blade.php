@extends('App.main.navBar')

@section('module_active', 'active')
@section('modules_list_active', 'active')
@section('module_show', 'active show')

@php
    $currency_id=getSettingValue('currency_id');
@endphp
@section('styles')
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <style>

            .table-responsive{
                    min-height: 60vh;
                }
        </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Module Management</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Module</li>
        <li class="breadcrumb-item text-dark">List</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10" style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('assets/media/illustrations/sigma-1/4.png')">
            <!--begin::Card header-->
            <div class="card-header pt-10">
                <div class="d-flex align-items-center">
                    <!--begin::Icon-->
                    <div class="symbol symbol-circle me-5">
                        <div class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                            <!--begin::Svg Icon | path: icons/duotune/abstract/abs020.svg-->
                            {{-- <span class="svg-icon svg-icon-2x svg-icon-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.302 11.35L12.002 20.55H21.202C21.802 20.55 22.202 19.85 21.902 19.35L17.302 11.35Z" fill="currentColor" />
                                    <path opacity="0.3" d="M12.002 20.55H2.802C2.202 20.55 1.80202 19.85 2.10202 19.35L6.70203 11.45L12.002 20.55ZM11.302 3.45L6.70203 11.35H17.302L12.702 3.45C12.402 2.85 11.602 2.85 11.302 3.45Z" fill="currentColor" />
                                </svg>
                            </span> --}}
                            <i class="ki-solid ki-parcel fs-2x text-primary"></i>
                            <!--end::Svg Icon-->
                        </div>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Title-->
                    <div class="d-flex flex-column">
                        <h2 class="mb-1">Module Manager</h2>
                        <div class="text-muted fw-bold">
                            <a href="#">Pico SBS</a>
                            <span class="mx-3">|</span>
                            <a href="#">Module Manager</a>
                            {{-- <span class="mx-3">|</span>2.6 GB
                            <span class="mx-3">|</span>758 items --}}
                        </div>
                    </div>
                    <!--end::Title-->
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pb-0">
                <!--begin::Navs-->
                <div class="d-flex overflow-auto h-10px">
                    {{-- <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-semibold flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 active" href="../../demo7/dist/apps/file-manager/folders.html">Files</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6" href="../../demo7/dist/apps/file-manager/settings.html">Settings</a>
                        </li>
                    </ul> --}}
                </div>
                <!--begin::Navs-->
            </div>
            <!--end::Card body-->
        </div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-8">
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
                        <input type="text" data-kt-filemanager-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Module Folders" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-filemanager-table-toolbar="base">
                        <!--begin::Export-->

                        <!--end::Export-->
                        <!--begin::Add customer-->
                        @if(hasUpload('Module'))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_upload">
                        <!--begin::Svg Icon | path: icons/duotune/files/fil018.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor" />
                                <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM16 11.6L12.7 8.29999C12.3 7.89999 11.7 7.89999 11.3 8.29999L8 11.6H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H16Z" fill="currentColor" />
                                <path opacity="0.3" d="M11 11.6V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H11Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->Upload Files</button>
                        @endif
                        <!--end::Add customer-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-filemanager-table-toolbar="selected">
                        <div class="fw-bold me-5">
                        <span class="me-2" data-kt-filemanager-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger" data-kt-filemanager-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">

                <!--begin::Table-->
                <table id="kt_file_manager_list"  class="table align-middle table-row-dashed fs-6 gy-5">

                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0 d-none">
                            <th class="min-w-150px">Name</th>
                            <th class="" >Actions</th>
                            <th></th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold">
                        @foreach ($modules as $m)
                        <tr>
                            <!--begin::Name=-->
                            <td data-order="account">
                                <div class="d-flex align-items-center">
                                    <!--begin::Svg Icon | path: icons/duotune/files/fil012.svg-->
                                    <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor" />
                                            <path d="M9.2 3H3C2.4 3 2 3.4 2 4V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H12L10.4 3.60001C10.2 3.20001 9.7 3 9.2 3Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    {{-- <i class="fa-solid fa-folder-plus fs-2 text-primary me-4"></i> --}}
                                    {{-- <i class="fa-regular fa-hospital"></i> --}}
                                    <!--end::Svg Icon-->

                                    <a  class="text-gray-800 text-hover-primary">{{$m->getName()}}{{getModuleVer($m) ?'_('.getModuleVer($m) .')' : ''}}</a>
                                </div>
                            </td>
                            <!--end::Name=-->
                            <!--begin::Actions-->
                            <td class="text-end" >
                                @if (isEnableModule($m))
                                    @if(hasUninstall('Module'))
                                    <a class="btn btn-warning btn-sm p-2 fs-9" href="{{route('module.uninstall',['module_name'=>encrypt($m->getName())])}}">
                                        Uninstall
                                    </a>
                                    @endif
                                @else
                                    @if(hasInstall('Module'))
                                    <a class="btn btn-primary btn-sm p-2 fs-9" href="{{route('module.install',['module_name'=>encrypt($m->getName())])}}" >
                                        Install
                                    </a>
                                    @endif
                                @endif
                            </td>

                            <td class="text-start">
                                @if(hasDelete('Module'))
                                <form action="{{route('module.delete',['module_name'=>encrypt($m->getName())])}}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-2 fs-9 module_delete" >
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            <!--end::Actions-->
                        </tr>

                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>

		<!--begin::Modal - Upload File-->
		<div class="modal fade" id="kt_modal_upload" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-dialog-centered mw-650px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Form-->
					{{-- <form class="form" action="none" id="kt_modal_upload_form"> --}}
						<!--begin::Modal header-->
						<div class="modal-header">
							<!--begin::Modal title-->
							<h2 class="fw-bold">Upload files</h2>
							<!--end::Modal title-->
							<!--begin::Close-->
							<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
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
						<div class="modal-body pt-10 pb-15 px-lg-17">
                            <!--begin::Form-->
                            <form class="form" action="{{route('module.upload')}}" method="POST" enctype="multipart/form-data">
                                <!--begin::Input group-->
                                <div class="fv-row">
                                    <!--begin::Dropzone-->
                                    <div class="dropzone" id="">
                                        <label for="module_zip" class="dz-message cursor-pointer">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                                <div class="ms-4">
                                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1"> click to upload.</h3>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="file" id="module_zip" class=" d-none" name="module_zip" accept=".zip">
                                    </div>
                                    <!--end::Dropzone-->
                                </div>
                                @csrf
                                <button type="submint" class="btn btn-sm btn-primary mt-3">Upload</button>
                                <!--end::Input group-->
                            </form>
                            <!--end::Form-->
						</div>
						<!--end::Modal body-->
					{{-- </form> --}}
					<!--end::Form-->
				</div>
			</div>
		</div>


    </div>
    <!--end::Container-->
</div>

{{-- <div class="modal modal-lg fade" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal">

</div> --}}
@endsection

@push('scripts')

{{-- <script src={{asset("assets/plugins/custom/datatables/datatables.bundle.js")}}></script> --}}
<script>
$(document).ready(function(){
    $('.module_delete').on('click',function(e){
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            text: "Are You Sure To Remove This Package",
            icon: "warning",
            buttonsStyling: false,
            cancleButtonText: "cancle",
            confirmButtonText: "Ok, Delete it!",
            showCancelButton: true,
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                form.submit();
            }
        });
    })
})
</script>
<script src={{asset("assets/js/custom/apps/file-manager/list.js")}}></script>
@endpush


