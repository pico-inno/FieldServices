<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>POS For Table</title>
    <meta charset="utf-8" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
</head>
<!--end::Head-->
<style>

</style>
<!--begin::Body-->
<!--begin::Theme mode setup on page load-->
<script>
    var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }
</script>

    <body>
<div class="d-flex flex-column flex-root">
        <div class="container m-auto mt-10 mb-10 row justify">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <!--begin::Card-->
                        <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-5"
                            style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('assets/media/illustrations/sketchy-1/4.png')">
                            <!--begin::Card header-->
                            <div class="card-header pt-10">
                                <div class="d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="symbol symbol-circle me-5">
                                        <div
                                            class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                                            <i class="ki-outline ki-abstract-47 fs-2x text-primary"></i>
                                        </div>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Title-->
                                    <div class="d-flex flex-column">
                                        <h2 class="mb-1">Applications</h2>
                                        <div class="text-muted fw-bold">
                                            <a href="#">PICO SBS</a>
                                            <span class="mx-3">|</span>
                                            <a href="#">File Manager</a>
                                            <span class="mx-3">|</span>2.6 GB
                                            <span class="mx-3">|</span>758 items
                                        </div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pb-0">
                                <!--begin::Navs-->
                                <div class="d-flex overflow-auto h-55px">
                                    <ul
                                        class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-semibold flex-nowrap">
                                        <li class="nav-item">
                                            <a class="nav-link text-active-primary me-6 active"
                                                href="../../demo55/dist/apps/file-manager/settings.html">Application
                                                Detail</a>
                                        </li>
                                        <!--end::Nav item-->
                                    </ul>
                                </div>
                                <!--begin::Navs-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Form-->
                                <form class="" action="{{route('envConfigure.store')}}" method="POST" id="configureForm">
                                    @csrf
                                    <div class="stepper stepper-pills" id="kt_stepper_example_basic">
                                        <div class="mb-5">
                                            <!--begin::Step 1-->
                                            <div class="flex-column current" data-kt-stepper-element="content">
                                                <h2 class="py-4 mb-10 text-primary">Application Details</h2>
                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <label class="fs-6 fw-semibold">Application Name</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" value="{{env('APP_NAME')}}" name="APP_NAME"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->

                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <label class="fs-6 fw-semibold">Application Title</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" value="{{env('APP_TITLE')}}" name="APP_TITLE"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <h2 class="py-4 mb-10 text-active-gray-600 text-primary">Database Detail
                                                </h2>
                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">Database HOST</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('DB_HOST')}}" name="DB_HOST"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">DataBase Port</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('DB_PORT')}}" name="DB_PORT"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">DataBase Name</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('DB_DATABASE')}}" name="DB_DATABASE"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">DataBase UserName</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('DB_USERNAME')}}" name="DB_USERNAME"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">DataBase Password</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="password" value="" name="DB_PASSWORD"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--begin::Step 1-->

                                        </div>
                                        <!--end::Group-->
                                        <div class="d-flex flex-stack text-center justify-content-center">
                                            <!--begin::Wrapper-->
                                                <button class="btn btn-sm btn-primary" id="submit" type="submit">
                                                    <span class="indicator-label">
                                                        Save
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            <!--end::Wrapper-->
                                        </div>
                                    </div>
                                    <!--end::Stepper-->
                                </form>
                                <!--end::Form-->
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>
    <script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
    <script src={{asset("assets/js/scripts.bundle.js")}}></script>
    @include('App.alert.alert')
    </body>
<!--end::Body-->
<script>

// Define form element
const form = document.getElementById('configureForm');

    // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
    var validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'APP_NAME': {
                    validators: {
                        notEmpty: {
                            message: 'APP name is required'
                        }
                    }
                },
                // 'APP_TITLE': {
                //     validators: {
                //         notEmpty: {
                //             message: 'App address is required'
                //         }
                //     }
                // },
                'DB_HOST': {
                    validators: {
                        notEmpty: {
                            message: 'DB Host field is required'
                        }
                    }
                },
                'DB_PORT': {
                    validators: {
                        notEmpty: {
                            message: 'DB Port field address is required'
                        }
                    }
                },
                'DB_DATABASE': {
                    validators: {
                        notEmpty: {
                            message: 'DB Database field  is required'
                        }
                    }
                },
                'DB_PORT': {
                    validators: {
                        notEmpty: {
                            message: 'DB Port Field is required'
                        }
                    }
                },
                'DB_USERNAME': {
                    validators: {
                        notEmpty: {
                            message: 'DB Username Field is required'
                        }
                    }
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            }
        }
    );

    // Submit button handler
    const submitButton = document.getElementById('submit');
    submitButton.addEventListener('click', function (e) {

        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
        if (validator) {
            validator.validate().then(function (status) {

                if (status == 'Valid') {
                    e.preventDefault=false;
                    submitButton.disabled = false;
                }else{
                    // Prevent default button action
                    e.preventDefault();
                    Swal.fire({
                        text: "Something Wrong!",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                }
            });
        }
    });
</script>
</html>
