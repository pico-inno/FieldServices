<div>
    <a href="{{ route('campaign.index') }}"  class="btn btn-secondary btn-sm d-md-none py-2 px-3">
        <i class="fa-solid fa-angle-left fs-5 pe-1"></i> Back
    </a>
    <div class="col-12 mb-3">
        <div id="map" class="{{$campaign->location->gps_location ?'': 'd-none';}}" style="width: 100%; height: 250px;"></div>
    </div>
    <div class="row mb-5 d-none">
        <div class="col-12 col-sm-4 col-md-3 col-lg-4 mb-3">
            <div class="card ">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="fw-semibold fs-5 text-gray-600">
                            <i class="fa-solid fa-list-check fs-5 text-primary me-1"></i>
                            Total Attendance
                        </div>
                        <div class="attendance fs-xl-1 fw-bold mt-1">
                            {{$attendanceCount ?? 0}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-3 col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="fw-semibold fs-5 text-gray-600">
                            <i class="fa-solid fa-box fs-5 text-primary me-1"></i>
                            Total Useage Product
                        </div>
                        <div class="attendance fs-xl-1 fw-bold mt-1">
                            200
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-3 col-lg-4 mb-3">
            <div class="card ">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="fw-semibold fs-5 text-gray-600">
                            <i class="fa-solid fa-dollar fs-5 text-primary me-1"></i>
                            Total Expense
                        </div>
                        <div class="attendance fs-xl-1 fw-bold mt-1">
                            {{fprice($totalExpense)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--begin::Modals-->
    <div class="card mb-5">
        <div class="card-header">
            <div class="card-title">
                {{$campaign->name}}
            </div>

            @if (hasView('campaign report'))
                <livewire:DailyReportExportBtn :campaignId="$campaign->id">
            @endif
        </div>
        <div class="card-body user-select-none">
            <!--begin::Form-->
            <form action="">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Outlet Location :
                                </span>
                            </div>
                            <div class="text-start">
                                <span class="fw-bold fs-7 text-gray-800">{{$campaign->location->name}}</span>
                            </div>
                        </div>
                        <div class="separator m-2"></div>
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Campaign Name :
                                </span>
                            </div>
                            <div class="text-start">
                                <span class="fw-bold fs-7 text-gray-800">{{$campaign->name}}</span>
                            </div>
                        </div>
                        <div class="separator m-2"></div>
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Campaign Date :
                                </span>
                            </div>
                            <div class="text-start">
                                <span class="fw-bold fs-7 text-gray-800">{{fdate($campaign->campaign_start_date,false,false)}} to {{fdate($campaign->campaign_end_date,false,false)}} </span>
                            </div>
                        </div>
                        <div class="separator m-2"></div>
                        <div class="">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Descritpion :
                                </span>
                            </div>
                            <div class="text-start">
                                <p class="fw-bold fs-7 text-gray-800">
                                    {{$campaign->description}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Campaign Leader :
                                </span>
                            </div>
                            <div class="text-start">
                                <span class="fw-bold fs-7 text-gray-800">{{$campaign->leader->username}}</span>
                            </div>
                        </div>
                        <div class="separator m-2"></div>
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Campaign Member :
                                </span>
                            </div>
                            <div class="text-start mw-400px">
                                <span class="fw-bold fs-7 text-gray-800">
                                    {{$campaignUsernames}}
                                </span>
                            </div>
                        </div>
                        <div class="separator m-2"></div>
                        <div class=" -flex justify-content-between">
                            <div class="text-start mb-2">
                                <span class="fw-semibold fs-7 text-gray-600">
                                    Campaign Status :
                                </span>
                            </div>
                            <div class="text-start mw-400px">
                                <span class="fw-bold fs-7 text-gray-800">
                                    @if ($campaign->status=='start')
                                        <span class="badge badge-light-success">Start</span>
                                    @elseif($campaign->status=='close')
                                        <span class="badge badge-light-success">Close</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-none">
                        <iframe class="rounded rounded-1"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117039.23211533853!2d95.99325067917484!3d21.940498514671503!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30cb6d23f0d27411%3A0x24146be01e4e5646!2sMandalay!5e1!3m2!1sen!2smm!4v1701410680964!5m2!1sen!2smm"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-secondary">
                                Get Geolocation
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>


    @if ($campaign->location->gps_location)

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6y-549HrO6No2H4yELrxw-phFYRHo5I0&callback=initMap&v=weekly">
    </script>
        <script>
            let glocation=@json($campaign->location->gps_location);
            if(glocation){
                let location_name="{{$campaign->location->name}}";
                geoSpllit=glocation.split('-');

                let lat=Number(geoSpllit[0]);
                let lng=Number(geoSpllit[1]);
                const myLatlng = { lat, lng };
                $("#kt_daterangepicker_1").daterangepicker();

                let geolocation=myLatlng;
                initMap();
                async function initMap() {
                    // Request needed libraries.
                    const { Map } = await google.maps.importLibrary("maps");
                    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                    const map = new Map(document.getElementById("map"), {
                    center: geolocation,
                    zoom: 10.5,
                    mapId: "4504f8b37365c3d0",
                    });
                    const marker = new AdvancedMarkerElement({
                    map,
                    position: geolocation,
                    });

                    const geocoder = new google.maps.Geocoder();
                    const geocoderRequest = { location: geolocation };
                    geocoder.geocode(geocoderRequest, (results, status) => {

                        if (status === google.maps.GeocoderStatus.OK) {
                            console.log(results);
                            const address = results[2].formatted_address;
                            $('.currentLocationName').val(address);

                        console.log("LocalAddress:", address);
                        } else {
                        console.error("Geocoding failed:", status);
                        }
                    });
                }
            }else{
                $('#map').addClass('d-none');
            }


        </script>
    @endif

</div>
