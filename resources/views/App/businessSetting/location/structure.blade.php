@extends('App.main.navBar')

@section('setting_active','active')
@section('setting_active_show','active show')
@section('location_here_show','here show')
@section('location_structure','active')

@section('styles')
        <link href="{{asset("assets/plugins/custom/jstree/jstree.bundle.css")}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">  Location Structure View</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Locations</li>
        <li class="breadcrumb-item text-dark">
            <a href="" class="text-muted text-hover-primary">Structure</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="location">
        <div class="card" >
            <div class="card-body user-select-none">
                <div id="kt_docs_jstree_basic" class="fs-6 fw-bold">
                    @foreach ($locations as $location)

                    <ul>
                        <li data-jstree='{ "icon" : "ki-solid ki-geolocation text-primary fs-4","opened" : true }'>
                            {{$location->name}}
                            <x-subLocationTreeComponent :location="$location" level='1' />
                            {{-- <ul>
                                <li data-jstree='{ "icon" : "ki-outline ki-geolocation text-success fs-4" }' class="mt-3">
                                    Location 3
                                </li>
                            </ul> --}}
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')

<script src="{{asset("assets/plugins/custom/jstree/jstree.bundle.js")}}"></script>
<script>
    $('#kt_docs_jstree_basic').jstree({
    "core" : {
        "themes" : {
            "responsive": false
        }
    },
    "types" : {
        "default" : {
            "icon" : "ki-solid ki-route"
        },
        "file" : {
            "icon" : "ki-solid ki-geolocation"
        }
    },
    "plugins": ["types"]
});
</script>
@endpush
