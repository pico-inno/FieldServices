<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="../../../"/>
    <title>ERPPOS | Login</title>
    <meta charset="utf-8" />
    <meta name="description" content="သင့်လုပ်ငန်းနဲ့ အသင့်တော်ဆုံး PICO SBS ကိုသုံး" />
    <meta name="keywords" content="pico, picosbs, sbs, erp, pos, erppos, picoerp, picosbs" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="ERPPOS Business Software" />
    <meta property="og:site_name" content="ERPPOS" />
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
<style>
    .slider-container {
        overflow: hidden;
    }
    .slider-image {
        display: none;
    }
    .slider-bullet {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #d5d5d5;
        border-radius: 50%;
        margin: 0 5px;
        cursor: pointer;
    }
    .slider-bullet.active {
        background-color: black;
    }
    .slider-bullet.active {
        background-color: #2e8befd6;
        border-radius: 10px;
        width: 20px;
    }
</style>
<!--begin::Body-->
<body id="kt_body" class="auth-bg bgi-size-cover bgi-position-center">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page bg image-->
    <style>body { background-image: url('assets/media/auth/bg10.jpeg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/auth/bg10-dark.jpeg'); }</style>
    <!--end::Page bg image-->
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-lg-row-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                <div class="slider-container d-flex flex-column flex-center">
                    <img class="slider-image mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="assets/media/auth/agency-dark.png" alt="" />
                    <img class="slider-image mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="assets/media/illustrations/sigma-1/8.png" alt="" />
                    <img class="slider-image mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="assets/media/illustrations/sigma-1/17.png" alt="" />
                    <div class="slider-text text-gray-800 fs-1 text-center mb-10"></div>
                    <div class="slider-bullets text-center"></div>
                </div>

            </div>
            <!--end::Content-->
        </div>
        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
            <!--begin::Wrapper-->
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-center flex-column-fluid pb-15 pb-lg-20">
                        <!--begin::Form-->
                        <form action="{{route('login')}}" method="post" class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">ERP POS</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6">Login to Dashboard</div>
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Heading-->
                            @if ($errors->has('account_inactive'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('account_inactive') }}
                                </div>
                            @endif
                            @if(session('session_timeout'))
                                <div class="alert alert-danger">
                                    {{ session('session_timeout') }}
                                </div>
                            @endif
                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                <input type="text" placeholder="Username" name="username"  autocomplete="off" class="form-control bg-transparent  @error('username') is-invalid @enderror"/>
                                <!--end::Email-->
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-6">
                                <!--begin::Password-->
                                <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" />
                                <!--end::Password-->
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!--end::Input group=-->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-5">
                                    <div class="form-check-sm">
                                        <input class="form-check-input checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit"
                                        class="btn btn-primary g-recaptcha"
                                        data-sitekey="{{config('services.recaptcha.site_key')}}"
                                        data-callback='onSubmit'
                                        data-action='submit'>
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Login</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Footer-->
                    <div class="d-flex flex-center">
                        <!--begin::Links-->
                        <div class="d-flex fw-semibold text-primary fs-base gap-5">
                            <a href="../../demo7/dist/pages/team.html" target="_blank">Terms</a>
                            <a href="../../demo7/dist/pages/pricing/column.html" target="_blank">Plans</a>
                            <a href="../../demo7/dist/pages/contact.html" target="_blank">Contact Us</a>
                        </div>
                        <!--end::Links-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
<!--end::Main-->
<!--begin::Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="assets/js/custom/authentication/sign-in/general.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
        function onSubmit(token) {
        document.getElementById("kt_sign_in_form").submit();
            console.log(grecaptcha.getResponse());
        }
</script>
<script>
    const sliderImages = document.querySelectorAll('.slider-image');
    const sliderText = document.querySelector('.slider-text');
    const sliderBulletsContainer = document.querySelector('.slider-bullets');
    let currentIndex = 0;


    function showSlide(index) {

        sliderImages.forEach(image => {
            image.style.display = 'none';
        });

        sliderImages[index].style.display = 'block';
        sliderText.textContent = getTextForIndex(index);

        updateActiveBullet(index);
    }


    function getTextForIndex(index) {
        const texts = [
            'Deviceမျိုးစုံနဲ့ အရောင်းစတင်လိုက်ပါ',
            'သင့်လုပ်ငန်းတွက် အသင့်တော်ဆုံး',
            'PicoSBS ကိုသုံး'
        ];
        return texts[index];
    }

    function createSliderBullets() {
        for (let i = 0; i < sliderImages.length; i++) {
            const bullet = document.createElement('span');
            bullet.classList.add('slider-bullet');
            bullet.addEventListener('click', () => {
                showSlide(i);
            });
            sliderBulletsContainer.appendChild(bullet);
        }
    }

    function updateActiveBullet(index) {
        const bullets = document.querySelectorAll('.slider-bullet');
        bullets.forEach((bullet, i) => {
            if (i === index) {
                bullet.classList.add('active');
            } else {
                bullet.classList.remove('active');
            }
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % sliderImages.length;
        showSlide(currentIndex);
    }

    showSlide(currentIndex);
    createSliderBullets();

    setInterval(nextSlide, 3000);
</script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
