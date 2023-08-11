<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="../../../"/>
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
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<nav class="navbar bg-secondary">
    <div class="container-fluid">
    <a class="btn btn-sm btn-light " href="{{route('posList') }}" ><i class="fa-solid fa-caret-left fs-4 me-1"></i>Back</a>
      <a class="navbar-brand fw-bold fs-3" href="#">Tables</a>
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
    <div class="container m-auto mt-20 row justify" >
        {{-- <form action="{{route('pos.sessionStore',$posRegisteredId)}}" method="POST"> --}}
            @foreach ($tables as $t)
                <div class="col-md-2  col-4 mb-5">
                    <a href="{{route('pos.create',['pos_register_id'=>request('pos_register_id'),'table_id'=>$t->id,'table_no'=>$t->table_no])}}">
                        <div class="card d-flex justify-content-center align-items-center p-3 bg-hover-light-primary position-relative">
                                @if ($t->status=='open')
                                    <div class="position-absolute top-0" style="right: 10px">
                                        {{-- <i class="fa-solid fa-circle text-success fs-10  animation-blink start-100"></i> --}}
                                    </div>
                                @endif
                                <div class="rounded-3  text-center h-75px pt-5">
                                    {{-- <i class="fa-solid fa-display fs-2tx "></i> --}}
                                    <span class="fs-4 fw-bold text-dark">{{$t->table_no}}</span>
                                </div>
                                <div class="mb-2">
                                    <div class="text-center">
                                        <span class="fw-bold fs-7 mb-1 badge badge-sm badge-primary">seats-{{$t->seats}}</span>
                                    </div>
                                </div>
                        </div>
                    </a>
                </div>
            @endforeach
            @if(count($tables) <=0)
                <div class="d-flex justify-content-center">
                    <div class="card col-6">
                        <div class="card-body text-center">
                            <i class="ki-solid ki-screen fs-5tx"></i>
                            <h4 class="mt-3 text-muted">Opps! There Is No POS Counter</h4>
                        </div>
                    </div>
                </div>
            @endif

        </form>
    </div>
</div>

<script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
<script src={{asset("assets/js/scripts.bundle.js")}}></script>
</body>
<!--end::Body-->
</html>
