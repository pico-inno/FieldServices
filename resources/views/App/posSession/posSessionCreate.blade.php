<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="../../../"/>
    <title>Pos Session Creat</title>
    <meta charset="utf-8" />
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

</style>
<!--begin::Body-->
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--end::Page bg image-->
    <div class="col-6 m-auto mt-20">
        <form action="{{route('pos.sessionStore',$posRegisteredId)}}" method="POST">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header d-flex">
                    <div class="card-title">
                        <a class="btn btn-light btn-sm" href="{{ url()->previous() }}" ><i class="fa-solid fa-caret-left fs-5 me-2"></i>Back</a>
                    </div>
                    <div class="card-title fw-bold text-primary">POS Session Opening</div>
                </div>
                <div class="card-body">
                    @if(isUsePaymnetAcc())
                        <label for="" class="form-label">From Account</label>
                        <select name="tx_account" id="" class="form-select mb-5" data-control="select2"  placeholder="From Account"  data-placeholder="From Account">
                        @if ($paymentAccounts)
                                @foreach ($paymentAccounts as $p)
                                    <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                @endforeach
                        @endif
                        </select>
                    @endif

                    <input name="opening_amount" type="text" class="form-control mb-5" placeholder="Opening Amount">

                    @if(isUsePaymnetAcc())
                        <label for="" class="form-label">To Account</label>
                        <select name="rx_account" id="" class="form-select" data-control="select2" placeholder="To Account"  data-placeholder="To Account">
                            @if ($paymentAccountForRegister)
                                @foreach ($paymentAccountForRegister as $p)
                                    <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                @endforeach
                            @endif
                        </select>
                    @endif
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-sm btn-primary">Open</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
<script src={{asset("assets/js/scripts.bundle.js")}}></script>
</body>
<!--end::Body-->
</html>
