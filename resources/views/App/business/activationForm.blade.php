<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>POS For Table</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
</head>
<!--end::Head-->
<style>

</style>
<!--begin::Body-->
<!--begin::Theme mode setup on page load-->
<!--begin::Theme mode setup on page load-->
        <script>
            var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }
        </script>

<body id="kt_body" style="background-image:" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-enabled" data-kt-aside-minimize="on">
    <div class="d-flex flex-column flex-root">
        <div class="container m-auto mt-15 row justify">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Heading-->
                            <div class="card-px text-center pt-15 pb-15">
                                <!--begin::Title-->
                                <div class="image">
                                    <img src="https://picosbs.com/img/logo.png" alt="" width="100px" height="100px">
                                </div>
                                <h2 class="fs-2x fw-bold mb-0 text-primary-emphasis header-text">PICO SBS (ERP POS)</h2>
                                <!--end::Title-->
                                <!--begin::Description-->
                                <p class="text-gray-400 fs-4 fw-semibold py-7 intro-text">Click on the below buttons to launch
                                    <br />create app modal example.
                                </p>
                                <!--end::Description-->
                                <!--begin::Action-->
                                <a href="#" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal" id="createAppBtn"
                                    data-bs-target="#kt_modal_create_business">Create A Business</a>
                                <!--end::Action-->
                                <span class="spinner-border  spinner-border align-middle fw-bold fs-1 d-none loading"></span>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Illustration-->
                            <div class="text-center pb-15 px-5">
                                {{-- <img src="assets/media/illustrations/sketchy-1/15.png" alt="" class="mw-100 h-250px" /> --}}
                                <img src="https://picosbs.com/img/intro-img.svg" alt="" class="mw-100 h-250px">
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_create_business" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog  mw-900px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Create App</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-lg-10 px-lg-10">
                    <!--begin::Stepper-->
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid  "
                        id="kt_modal_create_business_stepper">
                        <!--begin::Aside-->
                        <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px ">
                            <!--begin::Nav-->
                            <div class="stepper-nav ps-lg-10">
                                <!--begin::Step 1-->
                                <div class="stepper-item current" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-outline ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">1</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Buseiness Details</h3>
                                            <div class="stepper-desc">Name your Business</div>
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
                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-outline ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">2</span>
                                        </div>
                                        <!--begin::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Business Settings</h3>
                                            <div class="stepper-desc">Configure your business setting</div>
                                        </div>
                                        <!--begin::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                    <!--end::Line-->
                                </div>
                                <!--end::Step 2-->
                                <!--begin::Step 3-->
                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-outline ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">3</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Business Owner</h3>
                                            <div class="stepper-desc">Create Owner For business</div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                    <!--end::Line-->
                                </div>
                                <!--end::Step 3-->
                                <!--begin::Step 4-->
                                {{-- <div class="stepper-item" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-outline ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">4</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Billing</h3>
                                            <div class="stepper-desc">Provide payment details</div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                    <!--end::Line-->
                                </div> --}}
                                <!--end::Step 4-->
                                <!--begin::Step 5-->
                                {{-- <div class="stepper-item mark-completed" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-outline ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">5</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Completed</h3>
                                            <div class="stepper-desc">Review and Submit</div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div> --}}
                                <!--end::Step 5-->
                            </div>
                            <!--end::Nav-->
                        </div>
                        <!--begin::Aside-->
                        <!--begin::Content-->
                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <!--begin::Form-->
                            <form class="form" novalidate="novalidate" id="kt_modal_create_business_form"
                                data-dropdown-parent="kt_modal_create_business" enctype="multipart/form-data">
                                <!--begin::Step 1-->
                                <div class="current" data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Business Name</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Specify your unique business name">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm " name="business[name]"
                                                placeholder="" value="" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-4">
                                                <span class="">Start Date</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Select your app category">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin:Options-->
                                            <div class="fv-row">
                                                <input class="form-control form-control-sm" name="business[start_date]"
                                                    placeholder="Pick a date" data-td-toggle="datetimepicker"
                                                    id="kt_datepicker_1" value="{{date('Y-m-d')}}" />
                                            </div>
                                            <!--end:Options-->
                                        </div>
                                        <!--end::Input group-->
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-4">
                                                <span class="">Business Logo</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Select your app category">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <div class="input-group browseLogo">
                                                <input type="file" class="form-control form-control-sm" id="update_logo"
                                                    name="business[logo]">
                                                <button type="button" class="btn btn-sm btn-danger d-none"
                                                    id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                                <label class="input-group-text btn btn-sm btn-primary rounded-end"
                                                    for="update_logo">
                                                    Browse
                                                    <i class="fa-regular fa-folder-open"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-4">
                                                <span class="required">Currency</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Select your app category">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin:Options-->
                                            <div class="fv-row">
                                                <select name="business[currency]" id="" class="form-select form-select-sm"
                                                    data-kt-select2='true' placeholder="Select Currency" data-allow-clear="true"
                                                    data-placeholder="Select Currency">
                                                    <option value="1">Kyats</option>
                                                    <option value="2">Dollar</option>
                                                    <option value="3">Baht</option>
                                                    <option value="4">Yuan</option>
                                                </select>
                                            </div>
                                            <!--end:Options-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Step 1-->
                                <!--begin::Step 2-->
                                <div data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="fv-row mb-10">
                                            <!--begin::Input-->
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3" for="stock_accounting_method">
                                                <span
                                                    class="">{{__('business_settings.stock_accounting_method')}}</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                    title="Set the title of the store for SEO."></i>
                                            </label>
                                            <!--end::Label-->
                                            <div class="input-group flex-nowrap input-group-sm">
                                                <span class="input-group-text">
                                                    <i class="fa-solid fa-calculator"></i>
                                                </span>
                                                <div class="overflow-hidden flex-grow-1">
                                                    <select name="business[accounting_method]" data-hide-search="true"
                                                        id="stock_accounting_method"
                                                        class="form-select rounded-start-0 form-select-sm"
                                                        data-control="select2" data-placeholder="">
                                                        <option value="fifo">FIFO (First In First Out)
                                                        </option>
                                                        <option value="lifo">LIFO (Last In First Out)
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="fs-6 fw-semibold form-label mt-3 cursor-pointer required" for="use_payment_account">
                                                <span>Finanical Year Start Month</span>
                                            </label>
                                            <div class="form-check form-check-custom user-select-none">
                                                <select name="business[finanical_year_start_month]" class="form-select  form-select-sm" id="finanical_year_start_month" data-control="select2" data-placeholder="Select month">
                                                    <option></option>
                                                    <option value="january">January</option>
                                                    <option value="february">February</option>
                                                    <option value="march">March</option>
                                                    <option value="april">April</option>
                                                    <option value="may">May</option>
                                                    <option value="june">June</option>
                                                    <option value="july">July</option>
                                                    <option value="august">August</option>
                                                    <option value="september">September</option>
                                                    <option value="october">October</option>
                                                    <option value="november">November</option>
                                                    <option value="december">December</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--end::Step 2-->
                                <!--begin::Step 3-->
                                <div data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class=" fs-5 fw-semibold mb-2">Prefix</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-sm "
                                                name="businessUser[prefix]" placeholder="Mr/Mrs/ဉီး/‌ဒေါ် " value="" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10 col-md-6">
                                                <!--begin::Label-->
                                                <label class="required fs-5 fw-semibold mb-2">First Name</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-sm "
                                                    name="businessUser[firstName]" placeholder=" " value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10 col-md-6">
                                                <!--begin::Label-->
                                                <label class=" fs-5 fw-semibold mb-2">Last Name</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-sm "
                                                    name="businessUser[lastName]" placeholder=" " value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10 col-md-6">
                                                <!--begin::Label-->
                                                <label class="required fs-5 fw-semibold mb-2">Username</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-sm "
                                                    name="businessUser[username]" placeholder="username" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10 col-md-6">
                                                <!--begin::Label-->
                                                <label class="required fs-5 fw-semibold mb-2">Email</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-sm "
                                                    name="businessUser[email]" placeholder="username@email.com " value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class=" fv-row">
                                            <div class="mb-7 fv-row fv-plugins-icon-container"
                                                data-kt-password-meter="true">
                                                <!--begin::Wrapper-->
                                                <div class="mb-1">
                                                    <!--begin::Label-->
                                                    <label
                                                        class="required form-label fw-semibold fs-6 mb-2">Password</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input wrapper-->
                                                    <div class="position-relative mb-3">
                                                        <input type="password" name="businessUser[password]"
                                                            class="form-control form-control-sm " placeholder=""
                                                            autocomplete="off">
                                                        <span
                                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                            data-kt-password-meter-control="visibility">
                                                            <i class="bi bi-eye-slash fs-2"></i>
                                                            <i class="bi bi-eye fs-2 d-none"></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Input wrapper-->
                                                    <!--begin::Meter-->
                                                    <div class="d-flex align-items-center mb-3"
                                                        data-kt-password-meter-control="highlight">
                                                        <div
                                                            class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                        </div>
                                                        <div
                                                            class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                        </div>
                                                        <div
                                                            class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                        </div>
                                                        <div
                                                            class="flex-grow-1 bg-secondary bg-active-success rounded h-5px">
                                                        </div>
                                                    </div>
                                                    <!--end::Meter-->
                                                </div>
                                                <!--end::Wrapper-->
                                                <!--begin::Hint-->
                                                <div class="text-muted">Use 8 or more characters with a mix of letters,
                                                    numbers &amp; symbols.</div>
                                                <!--end::Hint-->
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <!--begin::Col-->
                                        <div class=" fv-row">
                                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                                <label class="required form-label fw-semibold fs-6 mb-2">Confirm
                                                    Password</label>
                                                <input type="password" name="confirm_password"
                                                    class="form-control form-control-sm " placeholder="" autocomplete="off">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <!--end::Step 3-->
                                <!--begin::Step 4-->
                                {{-- <div data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                <span class="required">Name On Card</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Specify a card holder's name">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <input type="text" class="form-control " placeholder="" name="card_name"
                                                value="Max Doe" />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <!--begin::Label-->
                                            <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                                            <!--end::Label-->
                                            <!--begin::Input wrapper-->
                                            <div class="position-relative">
                                                <!--begin::Input-->
                                                <input type="text" class="form-control " placeholder="Enter card number"
                                                    name="card_number" value="4111 1111 1111 1111" />
                                                <!--end::Input-->
                                                <!--begin::Card logos-->
                                                <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                                    <img src="assets/media/svg/card-logos/visa.svg" alt="" class="h-25px" />
                                                    <img src="assets/media/svg/card-logos/mastercard.svg" alt=""
                                                        class="h-25px" />
                                                    <img src="assets/media/svg/card-logos/american-express.svg" alt=""
                                                        class="h-25px" />
                                                </div>
                                                <!--end::Card logos-->
                                            </div>
                                            <!--end::Input wrapper-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-10">
                                            <!--begin::Col-->
                                            <div class="col-md-8 fv-row">
                                                <!--begin::Label-->
                                                <label class="required fs-6 fw-semibold form-label mb-2">Expiration
                                                    Date</label>
                                                <!--end::Label-->
                                                <!--begin::Row-->
                                                <div class="row fv-row">
                                                    <!--begin::Col-->
                                                    <div class="col-6">
                                                        <select name="card_expiry_month"
                                                            class="form-select form-select-solid" data-control="select2"
                                                            data-hide-search="true" data-placeholder="Month">
                                                            <option></option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                        </select>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-6">
                                                        <select name="card_expiry_year"
                                                            class="form-select form-select-solid" data-control="select2"
                                                            data-hide-search="true" data-placeholder="Year">
                                                            <option></option>
                                                            <option value="2023">2023</option>
                                                            <option value="2024">2024</option>
                                                            <option value="2025">2025</option>
                                                            <option value="2026">2026</option>
                                                            <option value="2027">2027</option>
                                                            <option value="2028">2028</option>
                                                            <option value="2029">2029</option>
                                                            <option value="2030">2030</option>
                                                            <option value="2031">2031</option>
                                                            <option value="2032">2032</option>
                                                            <option value="2033">2033</option>
                                                        </select>
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-md-4 fv-row">
                                                <!--begin::Label-->
                                                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">CVV</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Enter a card CVV code">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Input wrapper-->
                                                <div class="position-relative">
                                                    <!--begin::Input-->
                                                    <input type="text" class="form-control " minlength="3" maxlength="4"
                                                        placeholder="CVV" name="card_cvv" />
                                                    <!--end::Input-->
                                                    <!--begin::CVV icon-->
                                                    <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                                        <i class="ki-outline ki-credit-cart fs-2hx"></i>
                                                    </div>
                                                    <!--end::CVV icon-->
                                                </div>
                                                <!--end::Input wrapper-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Label-->
                                            <div class="me-5">
                                                <label class="fs-6 fw-semibold form-label">Save Card for further
                                                    billing?</label>
                                                <div class="fs-7 fw-semibold text-muted">If you need more info, please check
                                                    budget planning</div>
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Switch-->
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    checked="checked" />
                                                <span class="form-check-label fw-semibold text-muted">Save Card</span>
                                            </label>
                                            <!--end::Switch-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div> --}}
                                <!--end::Step 4-->
                                <!--begin::Step 5-->
                                {{-- <div data-kt-stepper-element="content">
                                    <div class="w-100 text-center">
                                        <!--begin::Heading-->
                                        <h1 class="fw-bold text-dark mb-3">Release!</h1>
                                        <!--end::Heading-->
                                        <!--begin::Description-->
                                        <div class="text-muted fw-semibold fs-3">Submit your app to kickstart your project.
                                        </div>
                                        <!--end::Description-->
                                        <!--begin::Illustration-->
                                        <div class="text-center px-4 py-15">
                                            <img src="assets/media/illustrations/sketchy-1/9.png" alt=""
                                                class="mw-100 mh-300px" />
                                        </div>
                                        <!--end::Illustration-->
                                    </div>
                                </div> --}}
                                <!--end::Step 5-->
                                <!--begin::Actions-->
                                <div class="d-flex flex-stack pt-10">
                                    <!--begin::Wrapper-->
                                    <div class="me-2">
                                        <button type="button" class="btn btn-lg btn-light-primary me-3"
                                            data-kt-stepper-action="previous">
                                            <i class="ki-outline ki-arrow-left fs-3 me-1"></i>Back</button>
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Wrapper-->
                                    <div>
                                        <button type="button" class="btn btn-lg btn-primary"
                                            data-kt-stepper-action="submit">
                                            <span class="indicator-label">Submit
                                                <i class="ki-outline ki-arrow-right fs-3 ms-2 me-0"></i></span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                        <button type="button" class="btn btn-lg btn-primary"
                                            data-kt-stepper-action="next">Continue
                                            <i class="ki-outline ki-arrow-right fs-3 ms-1 me-0"></i></button>
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Stepper-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
</body>
    <script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
    <script src={{asset("assets/js/scripts.bundle.js")}}></script>
    <script src={{asset("customJs/businessActivate/activate.js")}}></script>
    <script src={{asset('customJs/customFileInput.js')}}></script>
    @include('App.alert.alert')
</body>
<!--end::Body-->
<script>
    $("#kt_datepicker_1").flatpickr({
        dateFormat: "d-m-Y",
    });
</script>
</html>
