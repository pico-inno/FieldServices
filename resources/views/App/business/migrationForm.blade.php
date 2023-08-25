<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
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

                        <!--end::Card-->
                        <div class="card col-md-10 col-lg-6 m-auto mt-20">

                            <div class="card-body">
                                <div class="text-center p-5 justify-content-center d-flex flex-column">
                                    <div class="mb-5">
                                        <i class="fa-regular fa-circle-check text-center fs-5x text-success"></i>
                                    </div>
                                    <div class="">
                                        <h4 class="text-gray-600">
                                            Successfully Configured!
                                        </h4>
                                        <div class="fs-5 fw-bold">
                                            Start <a href="{{url('/login')}}" class="text-decoration-underline"> Create A Business</a>
                                        </div>
                                        {{-- <div class="alert-message">
                                            <span class="fw-bold text-gray-600 mt-5 d-block">Migration file is running.... </span>
                                            <span class="text-danger d-inline-block mt-3"> Please, Do Not Refresh The Page !</span>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="fv-row row  justify-content-center">
                                    <div class="d-flex gap-3">
                                        <table class="table">
                                            <thead>
                                                <tr class="fw-bold">
                                                    <th>
                                                        Seed Preset Datas
                                                    </th>
                                                    <th class="text-end">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold">

                                                {{-- <tr>
                                                    <td>
                                                        User
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary seeding" data-seed='user'>
                                                            seed
                                                        </button>
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <td>
                                                        Contact
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary seeding" data-seed='contact'>
                                                            seed
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        UOM
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary seeding" data-seed='uom'>
                                                            seed
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Brand
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary seeding" data-seed='brand'>
                                                            seed
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Category
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary seeding" data-seed='category'>
                                                            seed
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
        $('.seeding').click(function(){
            let data=$(this).data('seed');
            let url=@json(route('envConfigure.dataSeed'));
            let btn=$(this);
            $(this).text('seeding....');
            // $(this).attr('disabled',true);
            $.ajax({
                    url,
                    type: 'POST',
                    data:{field:data},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    success: function(results){
                        if(results.success){
                            btn.text('seed');
                            btn.attr('disabled',false);
                            success(results.success);
                        }
                        if(results.error){
                            error(results.error);
                        }
                    }
            })
        })
        // $('#save').click(function(){
        //     let data=$('#appConfiguration').serialize();
        //     let url=@json(route('envConfigure.store'));
        //     $.ajax({
        //             url,
        //             type: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             dataType:'json',
        //             data,
        //             success: function(results){
        //                 if(results.success){
        //                     success(results.success);
        //                 }
        //                 if(results.error){
        //                     error(results.error);
        //                 }
        //             }
        //     })
        // })
    </script>

</html>
