


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
{{-- <nav class="navbar bg-primary">
    <div class="container-fluid">
        <a class="btn btn-sm btn-light " href="{{route('posList') }}"><i
                class="fa-solid fa-caret-left fs-4 me-1"></i>Back</a>
        <a class="navbar-brand fw-bold fs-3 text-white" href="#">Select POS</a>
        <a class="navbar-brand" href="#"></a>
    </div>
</nav> --}}
<div class="d-flex flex-column flex-root">
    <div class="container m-auto mt-10 mb-10 row justify">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Card-->
                    {{-- <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-5"
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
                    </div> --}}
                    <!--end::Card-->
                    <!--begin::Card-->
                    <div class="card card-flush d-none">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Form-->
                            <form class="form" id="appConfiguration">
                                <!--begin::Stepper-->
                                <div class="stepper stepper-pills" id="kt_stepper_example_basic">
                                    <!--begin::Nav-->
                                    <div class="stepper-nav flex-center flex-wrap mb-10">
                                        <!--begin::Step 1-->
                                        <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                                            <!--begin::Wrapper-->
                                            <div class="stepper-wrapper d-flex align-items-center">
                                                <!--begin::Icon-->
                                                <div class="stepper-icon w-40px h-40px">
                                                    <i class="stepper-check fas fa-check"></i>
                                                    <span class="stepper-number">1</span>
                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Label-->
                                                <div class="stepper-label">
                                                    <h3 class="stepper-title">
                                                        Step 1
                                                    </h3>

                                                    <div class="stepper-desc">
                                                        Application Detail
                                                    </div>
                                                </div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Line-->
                                            <div class="stepper-line h-40px"></div>
                                            <!--end::Line-->
                                        </div>
                                        <!--end::Step 1-->

                                        <!--begin::Step 2-->
                                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                                            <!--begin::Wrapper-->
                                            <div class="stepper-wrapper d-flex align-items-center">
                                                <!--begin::Icon-->
                                                <div class="stepper-icon w-40px h-40px">
                                                    <i class="stepper-check fas fa-check"></i>
                                                    <span class="stepper-number">2</span>
                                                </div>
                                                <!--begin::Icon-->

                                                <!--begin::Label-->
                                                <div class="stepper-label">
                                                    <h3 class="stepper-title">
                                                        Step 2
                                                    </h3>

                                                    <div class="stepper-desc">
                                                        Database Confiuration
                                                    </div>
                                                </div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Line-->
                                            <div class="stepper-line h-40px"></div>
                                            <!--end::Line-->
                                        </div>
                                        <!--end::Step 2-->

                                        <!--begin::Step 3-->
                                        {{-- <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                                            <!--begin::Wrapper-->
                                            <div class="stepper-wrapper d-flex align-items-center">
                                                <!--begin::Icon-->
                                                <div class="stepper-icon w-40px h-40px">
                                                    <i class="stepper-check fas fa-check"></i>
                                                    <span class="stepper-number">3</span>
                                                </div>
                                                <!--begin::Icon-->

                                                <!--begin::Label-->
                                                <div class="stepper-label">
                                                    <h3 class="stepper-title">
                                                        Run
                                                    </h3>

                                                    <div class="stepper-desc">
                                                        Description
                                                    </div>
                                                </div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Line-->
                                            <div class="stepper-line h-40px"></div>
                                            <!--end::Line-->
                                        </div> --}}
                                        <!--end::Step 3-->
                                    </div>
                                    <!--end::Nav-->

                                    <!--begin::Form-->
                                    <form class="form w-lg-500px mx-auto" novalidate="novalidate"
                                        id="kt_stepper_example_basic_form">
                                        <!--begin::Group-->
                                        <div class="mb-5">
                                            <!--begin::Step 1-->
                                            <div class="flex-column current" data-kt-stepper-element="content">
                                                <h2 class="py-4 mb-10 text-active-gray-600">Application Detail</h2>
                                                <!--begin::Input group-->
                                                <div class="fv-row row mb-10">
                                                    <!--begin::Col-->
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold">Application Name</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('APP_NAME')}}" name="APP_NAME"
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
                                                        <label class="fs-6 fw-semibold">Application Title</label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-md-9">
                                                        <!--begin::Input-->
                                                        <input type="text" value="{{env('APP_TITLE')}}" name="APP_TITLE"
                                                            class="form-control form-control-sm">
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--begin::Step 1-->

                                            <!--begin::Step 1-->
                                            <div class="flex-column" data-kt-stepper-element="content">
                                                <h2 class="py-4 mb-10 text-active-gray-600">Database Detail</h2>
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
                                                        <input type="text" value="{{env('DB_DATABASE')}}"
                                                            name="DB_DATABASE" class="form-control form-control-sm">
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
                                                        <input type="text" value="{{env('DB_USERNAME')}}"
                                                            name="DB_USERNAME" class="form-control form-control-sm">
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
                                                <!--end::Input group-->

                                            </div>
                                            <!--begin::Step 1-->

                                            <!--begin::Step 1-->
                                            {{-- <div class="flex-column" data-kt-stepper-element="content">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label d-flex align-items-center">
                                                        <span class="required">Input 1</span>
                                                        <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                            data-bs-toggle="tooltip" title="Example tooltip"></i>
                                                    </label>
                                                    <!--end::Label-->

                                                    <!--begin::Input-->
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="input1" placeholder="" value="" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->

                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label">
                                                        Input 2
                                                    </label>
                                                    <!--end::Label-->

                                                    <!--begin::Input-->
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="input2" placeholder="" value="" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                            </div> --}}
                                            <!--begin::Step 1-->

                                        </div>
                                        <!--end::Group-->

                                        <!--begin::Actions-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Wrapper-->
                                            <div class="me-2">
                                                <button type="button" class="btn btn-light btn-active-light-primary"
                                                    data-kt-stepper-action="previous">
                                                    <i class="ki-outline ki-arrow-left fs-3 me-1 ms-0"></i> Back
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Wrapper-->
                                            <div>
                                                <button type="button" class="btn btn-primary"
                                                    data-kt-stepper-action="submit" id="save">
                                                    <span class="indicator-label">
                                                        Submit
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>

                                                <button type="button" class="btn btn-primary"
                                                    data-kt-stepper-action="next">
                                                    Continue <i class="ki-outline ki-arrow-right fs-3 ms-1 me-0"></i>
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Stepper-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card body-->
                        {{-- <div class="card-footer text-end">
                            <button class="btn btn-sm btn-primary" id="save">
                                Save
                                <i class="ki-outline ki-arrow-right fs-3 ms-1 me-0"></i>
                            </button>
                        </div> --}}
                    </div>
                    <!--end::Card-->
                    <div class="card col-6 m-auto">

                        <div class="card-body">
                            <div class="text-center p-5 justify-content-center d-flex flex-column">
                                <div class="mb-5">
                                    <i class="fa-regular fa-circle-check text-center fs-5x text-success"></i>
                                </div>
                                <div class="">
                                    <h4 class="text-gray-600">
                                        Successfully Configured!
                                    </h4>
                                </div>
                            </div>
                            <div class="fv-row row mb-10 mt-5 justify-content-center">
                                <div class="d-flex gap-3">
                                    <button class="btn btn-sm btn-primary">
                                        Run Migration
                                    </button>
                                    <button class="btn btn-sm btn-success">
                                        Add Preset Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
    </div>
</div>
<script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
<script src={{asset("assets/js/scripts.bundle.js")}}></script>
<script src={{asset("assets/js/custom/utilities/modals/create-app.js")}}></script>
@include('App.alert.alert')
</body>
<!--end::Body-->
<script>
    // Stepper lement
    var element = document.querySelector("#kt_stepper_example_basic");

    // Initialize Stepper
    var stepper = new KTStepper(element);

    // Handle next step
    stepper.on("kt.stepper.next", function (stepper) {
        stepper.goNext(); // go next step
    });

    // Handle previous step
    stepper.on("kt.stepper.previous", function (stepper) {
        stepper.goPrevious(); // go previous step
    });
    $('#save').click(function(){
        let data=$('#appConfiguration').serialize();
        let url=@json(route('envConfigure.store'));
          $.ajax({
                url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data,
                success: function(results){
                    if(results.success){
                        success(results.success);
                    }
                    if(results.error){
                        error(results.error);
                    }
                }
          })
    })
</script>

</html>
