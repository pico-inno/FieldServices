<div>
    <style>
        .z-index-custom-5{
            z-index: 50 !important;
        }
        .bottomDiv{
            height: 200px;
        }
        .loaded {
            transition: background-image 0.3s ease-in-out;
        }
        .lazy-bg {
        width: 100%;
        height: 300px; /* Set a height for the container */
        background-size: cover;
        background-position: center;
        /* Optionally, add a placeholder background while the image is loading */
        background-color: #f0f0f0;
        }

        .pagination{
            justify-content: center !important;
        }
    </style>
    <div class="row">
            <div class="card mb-5">
                <div class="card-header py-1" style="min-height:fit-content !important;">
                    <div class="card-title p-1">
                        <h2>Filters</h2>
                    </div>
                </div>
                <div class="card-body p-5 p-sm-7">
                    <div class="row flex-wrap">
                        <div class="row" wire:ignore>

                            <div class="col-12 col-md-4 col-lg-3 mb-5">

                                <label class="form-label  fs-6 fw-semibold">
                                    Search</label>
                                <input type="text" class="form-control form-control-sm" wire:model.debounce.50ms.live='search' placeholder="Search...">
                            </div>
                            {{-- @if($defaultCampaignId===null) --}}
                                <div class="col-12 col-md-4 col-lg-3 mb-5">
                                    <label class="form-label  fs-6 fw-semibold">
                                        Filter By Campaign:</label>
                                    <select class="form-select form-select-sm fw-bold campaignfilter" data-allow-clear="true"
                                        data-placeholder="Select option" id="campaignfilter" data-kt-select2="true" data-kt-table-filter="outlet">
                                        <option value="all">All</option>
                                        @foreach ($campaigns as $campaign)
                                        <option value="{{$campaign['id']}}">{{ $campaign['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 col-lg-3 mb-5">
                                    <label class="form-label  fs-6 fw-semibold">
                                        Filter By Outlet:</label>
                                    <select class="form-select form-select-sm fw-bold campaignfilter" data-allow-clear="true"
                                        data-placeholder="Select option" id="outletFilter" data-kt-select2="true" data-kt-table-filter="outlet">
                                        <option value="all">All</option>
                                        @foreach ($outlets as $outlet)
                                        <option value="{{$outlet['id']}}">{{ $outlet['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 col-lg-3 mb-5">
                                    <label class="form-label  fs-6 fw-semibold">
                                        Filter By Outlet Type:</label>
                                    <select class="form-select form-select-sm fw-bold campaignfilter" data-allow-clear="true"
                                        data-placeholder="Select option" id="outletTypeFilter" data-kt-select2="true" data-kt-table-filter="outletType">
                                        <option value="all">All</option>
                                        <option value="on">On</option>
                                        <option value="off">Off</option>
                                    </select>
                                </div>

                            <div class="col-12 col-md-4 col-lg-3 mb-5">
                                <label class="form-label  fs-6 fw-semibold">
                                    <i class="fa-solid fa-circle fs-9 text-success me-1"></i>
                                    Filter By PG:</label>
                                <select class="form-select form-select-sm fw-bold pgFilter" data-allow-clear="true" data-placeholder="Select option"
                                    id="pgFilter" data-kt-select2="true" data-kt-table-filter="employee">
                                    <option value="all">All</option>
                                    @foreach ($employee as $e)
                                    <option value="{{ $e['id'] }}">{{ $e['personal_info']['initname'] }}{{ $e['personal_info']['first_name'] }}{{ $e['personal_info']['last_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($gralleries as $i=>$grallery)
            <div class="col-sm-6 col-md-4 col-lg-3  col-12" wire:key='$i'>
                @php
                    $imagesHtml='';
                    $images=json_decode($grallery['images'] ?? []);
                    $zIndex=3;
                    $padding=5;

                    foreach ($images as $index=>$image) {

                        $uniqIndex=$i;
                        $imgPath=asset('storage/gallery/'.$image);
                        $cssClass=$index>0 ? 'position-absolute  z-index-'.$zIndex.' ps-'.$padding.'' : 'z-index-custom-5';
                        $imagesHtml.='<div class="w-auto min-h-50px ps-2  '.$cssClass.'">
                            <a class="d-block overlay w-200px h-200px " data-fslightbox="lightbox-basic-'.$uniqIndex.'" href="'.$imgPath.'">
                                <div data-src="'.$imgPath.'"  class="overlay-wrapper bgi-no-repeat bg-gray-300 bgi-position-center bg-secondary bgi-size-cover card-rounded min-h-175px  w-200px h-200px lazy-bg"
                                    style="backgroun-color:gray;background-image:url('.$imgPath.')">
                                </div>
                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow ">
                                    <i class="bi bi-eye-fill text-white fs-3x"></i>
                                </div>
                            </a>
                        </div>';
                    $padding+=4;
                    $zIndex--;
                    }
                @endphp
                <div class="card mb-5 post-card">
                    <div class="card-body px-5">
                        <div class="d-flex mb-5">
                            <div class="symbol symbol-40px me-3">
                                @if($grallery['user']['personal_info']['profile_photo'] == null)
                                    <div class="symbol-label fs-3 bg-light-primary text-primary">
                                        {{$grallery['user']['username'][0]}}
                                    </div>
                                @else
                                    <img src="{{$grallery['user']['personal_info']['profile_photo']}}" width="100" heigh="100" />
                                @endif
                            </div>
                            <div class="">
                                <div class='d-flex  justify-content-start gap-2 align-items-center'>
                                    <span class="fw-semibold fs-5">{{ $e['personal_info']['initname'] }}{{ $e['personal_info']['first_name'] }}{{ $e['personal_info']['last_name'] }}</span>

                                    <div  class="text-end">
                                        @if($grallery['user']['id']==Auth::user()->id)
                                            <div class="cursor-pointer px-3" id="DropDown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-gear cursor-pointer"></i>
                                            </div>
                                            <div class="z-3">
                                                <ul class="dropdown-menu z-10 py-2 px-2 " aria-labelledby="DropDown" role="menu">
                                                    <a class="dropdown-item p-1 view_detail  fw-semibold editmodalbtn" type="button" data-id="{{$grallery->id}}">
                                                        Edit
                                                    </a>
                                                    <a class="dropdown-item p-1 view_detail text-danger  fw-semibold removePost" type="button" data-href=""
                                                        data-id="{{$grallery->id}}">
                                                        Delete
                                                    </a>
                                                </ul>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <span class="fw- fs-8">{{fdate($grallery['created_at'])}}</span><br/>

                                <span>{{$grallery['campaign']['name']}}</span>
                                @if ($grallery['campaign']['location'])
                                    (<span>{{$grallery['campaign']['location']['name']}}</span>)
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <p class="fs-7 fw-semibold text-gray-700">
                                {{$grallery['note'] }}&nbsp;
                            </p>
                        </div>
                        <div class="p-0 row  justify-content-start align-items-center gap-5 position-relative">

                            {!! $imagesHtml !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-sm-6 col-md-4 col-lg-3  col-12" wire:loading >
                <div class="card mb-5 post-card">
                    <div class="card-body px-5">
                        <div class="d-flex mb-5">
                            <div class="w-40px h-40px me-3 placeholder-glow">
                                <div class="w-40px h-40px rounded fs-3  placeholder">
                                    &nbsp;
                                </div>
                            </div>
                            <div class="">
                                <div class='d-flex  justify-content-start gap-2 align-items-center placeholder-glow'>
                                    <span class="fw-semibold fs-5 placeholder placeholder-wave">loading...</span><br/>

                                </div>
                                <div class="placeholder mt-2">

                                <span class="fw- fs-8 placeholder-glow">date....</span>
                                </div>
                            </div>
                        </div>
                        <div class="placeholder-glow">
                            <p class="fs-7 fw-semibold text-gray-700 placeholder">
                                loading...........
                            </p>
                        </div>
                        <div class="p-0 row  justify-content-start align-items-center gap-5 position-relative">
                            <div class="w-auto min-h-50px ps-2 placeholder-glow">
                                <div class="d-block overlay w-200px h-200px placeholder rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="m-auto">
        {{$gralleries->links()}}
    </div>
</div>
<script>
    $('#campaignfilter').select2().on('select2:select', function (e) {
                @this.set('campaignFilterId', $('#campaignfilter').select2("val"));
            }).on('select2:unselect', function (e) {
                @this.set('campaignFilterId','all');
            });

            $('#outletFilter').select2().on('select2:select', function (e) {
                @this.set('outletFilterId', $('#outletFilter').select2("val"));
            }).on('select2:unselect', function (e) {
                @this.set('outletFilterId','all');
            });

            $('#outletTypeFilter').select2().on('select2:select', function (e) {
                @this.set('outletTypeFilter', $('#outletTypeFilter').select2("val"));
            }).on('select2:unselect', function (e) {
                @this.set('outletTypeFilter','all');
            });

            $('#pgFilter').select2().on('select2:select', function (e) {
                    @this.set('pgFilterId', $('#pgFilter').select2("val"));
                }).on('select2:unselect', function (e) {
                    @this.set('pgFilterId', 'all');
                });
    $(document).on('click', '.editmodalbtn', function(){
        alert('hello');
        let id=$(this).data('id');

        loadingOn();
        $('.editmodal').load(`/gallery/${id}/edit`, function() {
            $(this).modal('show');
            loadingOff();
        });
    });
</script>
