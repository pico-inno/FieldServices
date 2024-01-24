<!DOCTYPE html>
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
    <!--begin::Authentication - Two-stes -->
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
                    <!--begin::Further link-->
                    <div class="m-0">
                        <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="two-step-head-desc">Didn’t get the code ?</span>
                        <a href="../../demo7/dist/authentication/layouts/fancy/sign-in.html" class="link-primary fw-bold fs-5" data-kt-translate="two-step-head-resend">Resend</a>
                        <span class="text-gray-400 fw-bold fs-5 mx-1" data-kt-translate="two-step-head-or">or</span>
                        <a href="#" class="link-primary fw-bold fs-5" data-kt-translate="two-step-head-call-us">Call Us</a>
                    </div>
                    <!--end::Further link=-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="pb-10 pt-20">
                    <!--begin::Form-->
                        <form class="form w-100 mb-10" novalidate="novalidate" id="kt_sing_in_two_steps_form" method="post" action="{{ route('ecommerce.verification.confirm') }}">
                            @csrf
                        <!--begin::Icon-->
                        <div class="text-center mb-10">
                            <img alt="Logo" class="theme-light-show mh-125px" src="assets/media/svg/misc/smartphone-2.svg" />
                            <img alt="Logo" class="theme-dark-show mh-125px" src="assets/media/svg/misc/smartphone-2-dark.svg" />
                        </div>
                        <!--end::Icon-->
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3" data-kt-translate="two-step-title">Two Step Verification</h1>
                            <!--end::Title-->
                            <!--begin::Sub-title-->
                            <div class="text-muted fw-semibold fs-5 mb-5" data-kt-translate="two-step-deck">Enter the verification code we sent to</div>
                            <!--end::Sub-title-->
                            <!--begin::Mobile no-->
                            <div class="fw-bold text-dark fs-3">
                                    ******<span>{{ substr($phone_number, -4) }}</span>
                            </div>
                            <!--end::Mobile no-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Section-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            @if($errors->has('warning'))<div class="fw-bold text-start text-danger fs-6 mb-1 ms-1" data-kt-translate="two-step-label">{{ $errors->first('warning') }}</div>@endif
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-wrap flex-stack justify-content-md-evenly">
{{--                                <input type="hidden" name="first_name" value="{{old('first_name', $phone_number)}}">--}}
{{--                                <input type="hidden" name="last_name" value="{{old('last_name', $phone_number)}}">--}}
                                <input type="hidden" name="phone" value="{{old('phone', $phone_number)}}">
                                <input type="text" name="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value=""
                                       x-data="{ code1: '' }"
                                       x-on:input="focusNextInput($event, 'code_2')"
                                       x-model="code1"/>
                                <input type="text" name="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value=""
                                       x-data="{ code2: '' }"
                                       x-on:input="focusNextInput($event, 'code_3')"
                                       x-model="code2" />
                                <input type="text" name="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value=""
                                       x-data="{ code3: '' }"
                                       x-on:input="focusNextInput($event, 'code_4')"
                                       x-model="code3" />
                                <input type="text" name="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value=""
                                       x-model="code4"/>
{{--                                <input type="text" name="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value="" />--}}
{{--                                <input type="text" name="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-40px w-40px h-xl-60px w-xl-60px fs-2qx text-center border-primary border-hover mx-1 my-2" value="" />--}}
                            </div>
                            <!--begin::Input group-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Actions-->
                        <div class="text-center">
                            <!--begin::Submit-->
                            <button id="kt_sing_in_two_steps_submit" class="btn btn-primary" data-kt-translate="two-step-submit">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Submit</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Verifying...
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
    <!--end::Authentication - Two-stes-->
</div>
<!--end::Root-->
<!--end::Main-->
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(mandatory for all pages)-->

<script src="customJs/toaster.js"></script>

<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<!-- Include Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

<script>

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toastr-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>


@if(isset($success))
    <script>
        toastr.success("{{$success}}");
    </script>
@endif
<script>
    function focusNextInput(event, nextInput) {
        if (event.target.value.length > 0) {
            document.querySelector(`[name="${nextInput}"]`).focus();
        }
    }
</script>

<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
{{--<script src="{{asset('assets/js/custom/authentication/sign-in/two-steps.js)}}"></script>--}}
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
