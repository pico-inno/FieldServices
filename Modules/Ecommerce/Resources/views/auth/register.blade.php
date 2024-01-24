<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="../../../"/>
    <title>PICO | Sign Up</title>
    <meta charset="utf-8" />
    <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Login | PICO" />
{{--    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />--}}
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
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
    <!--begin::Authentication - Sign-up -->
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
            <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                <!--begin::Header-->
                <div class="d-flex flex-stack py-2">
                    <!--begin::Back link-->
                    <div class="me-2">
                        <a href="{{route('ecommerce.login')}}" class="btn btn-icon bg-light rounded-circle">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr002.svg-->
                            <span class="svg-icon svg-icon-2 svg-icon-gray-800">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.60001 11H21C21.6 11 22 11.4 22 12C22 12.6 21.6 13 21 13H9.60001V11Z" fill="currentColor" />
											<path opacity="0.3" d="M9.6 20V4L2.3 11.3C1.9 11.7 1.9 12.3 2.3 12.7L9.6 20Z" fill="currentColor" />
										</svg>
									</span>
                            <!--end::Svg Icon-->
                        </a>
                    </div>
                    <!--end::Back link-->
                    <!--begin::Sign Up link-->
                    <div class="m-0">
                        <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-up-head-desc">Already a member ?</span>
                        <a href="{{route('ecommerce.login')}}" class="link-primary fw-bold fs-5" data-kt-translate="sign-up-head-link">Login Now</a>
                    </div>
                    <!--end::Sign Up link=-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-20">
                    <!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="get" action="{{ route('ecommerce.request.code', 'register') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-start mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-up-title">Create an Account</h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Input group-->
                        <div class="row fv-row mb-7">
                            <!--begin::Col-->
                            <div class="col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="First Name" name="first_name" autocomplete="off" data-kt-translate="sign-up-input-first-name" />
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Last Name" name="last_name" autocomplete="off" data-kt-translate="sign-up-input-last-name" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <input class="form-control form-control-lg form-control-solid" type="email" placeholder="09 ### ### ###" name="phone" autocomplete="off" data-kt-translate="sign-up-input-email" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Actions-->
                        <div class="d-flex flex-stack">
                            <!--begin::Submit-->
                            <button id="kt_sign_up_submit" class="btn btn-primary" data-kt-translate="sign-up-submit">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Register</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->
                            </button>
                            <!--end::Submit-->

                        </div>
                        <!--end::Actions-->
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
    <!--end::Authentication - Sign-up-->
</div>
<!--end::Root-->
<!--end::Main-->
<!--begin::Javascript-->

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{asset('assets/js/custom/authentication/sign-up/general.js')}}"></script>
<script src="{{asset('assets/js/custom/authentication/sign-in/i18n.js')}}"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
