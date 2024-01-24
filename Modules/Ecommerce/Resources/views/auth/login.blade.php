<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic
Product Version: 8.1.7
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->
<head><base href="../../../"/>
    <title>Metronic - The World's #1 Selling Bootstrap Admin Template by Keenthemes</title>
    <meta charset="utf-8" />
    <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="auth-bg">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Logo-->
        <a href="../../demo7/dist/index.html" class="d-block d-lg-none mx-auto py-20">
            <img alt="Logo" src="assets/media/logos/default.svg" class="theme-light-show h-25px" />
            <img alt="Logo" src="assets/media/logos/default-dark.svg" class="theme-dark-show h-25px" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
            <!--begin::Wrapper-->
            <div class="d-flex justify-content-evenly flex-column-fluid flex-column w-100 mw-450px">
                <!--begin::Header-->
                <div class="d-flex flex-stack py-2">
                    <!--begin::Back link-->
                    <div class="me-2"></div>
                    <!--end::Back link-->
                    <!--begin::Sign Up link-->
                    <div class="m-0">
{{--                        <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-in-head-desc">Not a Member yet?</span>--}}
{{--                        <a href="{{route('ecommerce.register')}}" class="link-primary fw-bold fs-5" data-kt-translate="sign-in-head-link">Register</a>--}}
                    </div>
                    <!--end::Sign Up link=-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="pb-20 pt-5">
                    <!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="get" action="{{ route('ecommerce.request.code', 'login') }}">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Heading-->
                            <div class="text-start mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-in-title">Login</h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                <input type="text" placeholder="Phone (start with 0)" name="phone" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                                <div></div>
                                <!--begin::Link-->
                                <div>
                                    <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-in-head-desc">Not a Member yet?</span>
                                    <a href="{{route('ecommerce.register')}}" class="link-primary fw-bold fs-5" data-kt-translate="sign-in-head-link">Register</a>
                                </div>
{{--                                <a href="../../demo7/dist/authentication/layouts/fancy/reset-password.html" class="link-primary" data-kt-translate="sign-in-forgot-password">Forgot Password ?</a>--}}
                                <!--end::Link-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-stack">
                                <!--begin::Submit-->
                                <button id="kt_sign_in_submit" class="btn btn-primary me-2 flex-shrink-0">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label" data-kt-translate="sign-in-submit">Login</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">
												<span data-kt-translate="general-progress">Please wait...</span>
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
											</span>
                                    <!--end::Indicator progress-->
                                </button>
                                <!--end::Submit-->

                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--begin::Body-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Aside-->
        <!--begin::Body-->
        <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat" style="background-image: url(assets/media/auth/bg11.png)"></div>
        <!--begin::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
<!--end::Main-->

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{asset('assets/js/custom/authentication/sign-in/general.js')}}"></script>

<script>
    @if($errors->has('warning'))
    Swal.fire({
        text: '{{ $errors->first('warning') }}',
        icon: "warning",
        showCancelButton: false,
        buttonsStyling: false,
        confirmButtonText: "Register",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-primary",
            // cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            window.location.href = '/register';
        }
    });
    @endif
</script>
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
