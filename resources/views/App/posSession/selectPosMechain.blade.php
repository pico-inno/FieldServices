<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>POS For Table</title>
    <meta charset="utf-8" />
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
<nav class="navbar bg-primary">
    <div class="container-fluid">
        <a class="btn btn-sm btn-light " href="{{route('posList') }}"><i
                class="fa-solid fa-caret-left fs-4 me-1"></i>Back</a>
        <a class="navbar-brand fw-bold fs-3 text-white" href="#">Select POS</a>
        <a class="navbar-brand" href="#"></a>
    </div>
</nav>
<div class="d-flex flex-column flex-root">
    <!--end::Page bg image-->

    {{-- <div class="text-center mt-10">
        <span class=" badge badge-info fs-1">
            Tables
        </span>
    </div> --}}
    <div class="container m-auto mt-20 row justify">
        {{-- <form action="{{route('pos.sessionStore',$posRegisteredId)}}" method="POST"> --}}
            @foreach ($posRegisters as $pr)
            <div class="col-md-2  col-4 mb-5">
                <a href="{{route('pos.sessionCheck',$pr->id)}}">
                    <div
                        class="card d-flex justify-content-center align-items-center p-3 bg-hover-light-primary position-relative">
                        @if ($pr->status=='open')
                        <div class="position-absolute top-0 pt-1" style="right: 10px">
                            <i class="fa-solid fa-circle text-success fs-9  animation-blink start-100"></i>
                        </div>
                        @endif
                        <div class="rounded-3 p-5 text-center">
                            <i class="fa-solid fa-display fs-2tx "></i>
                        </div>
                        <div class="mb-2">
                            <div class="text-center">
                                <span class="fw-bold text-gray-800 fs-6 mb-3">{{$pr->name}}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
            @if(count($posRegisters) <=0)
            <div class="d-flex justify-content-start">
                <div class="col-md-2  col-4 mb-5">
                    <a href="#" class="openModal" id="add" data-href="{{route('posCreate')}}">
                        <div class="card d-flex justify-content-center align-items-center p-3 bg-hover-light-primary position-relative">
                            <div class="rounded-3 p-5 text-center">
                                <i class="fa-solid fa-plus text-primary fs-2x "></i>
                            </div>
                            <div class="mb-2">
                                <div class="text-center">
                                    <span class="fw-bold text-gray-800 fs-6 mb-3">
                                        Create POS
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- <div class="card col-6">
                    <div class="card-body text-center">
                        <i class="ki-solid ki-screen fs-5tx"></i>
                        <h4 class="mt-3 text-muted">Opps! There Is No POS Counter</h4>
                    </div>
                </div> --}}
                @endif
    </div>
</div>
<div class="">
    <div id="spinnerWrapper" class="spinner-wrapper">
        <div class="spinner">
        </div>
    </div>
</div>
<div class="modal fade modal-lg" tabindex="-1" id="modal">

</div>

<script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
<script src={{asset("assets/js/scripts.bundle.js")}}></script>
<script src={{asset('customJs/loading/miniLoading.js')}}></script>
</body>
<!--end::Body-->
<script>
    $(document).on('click', '.openModal', function(e){
    e.preventDefault();
    loadingOn()
    $('#modal').load($(this).data('href'), function() {
        loadingOff();
        $(this).modal('show');
    });
});
</script>
</html>
