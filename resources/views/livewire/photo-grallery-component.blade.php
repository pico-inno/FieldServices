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
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Search...">
                </div>
            </div>
            @foreach ($gralleries as $i=>$grallery)
            <div class="col-sm-6 col-md-4 col-lg-3  col-12">
                @php
                    $imagesHtml='';
                    $images=json_decode($grallery['images'] ?? []);
                    $zIndex=3;
                    $padding=5;

                    foreach ($images as $index=>$image) {
                        $imgPath=asset('storage/gallery/'.$image);
                        $cssClass=$index>0 ? 'position-absolute  z-index-'.$zIndex.' ps-'.$padding.'' : 'z-index-custom-5';
                        $imagesHtml.='<div class="w-auto min-h-50px ps-2  '.$cssClass.'">
                            <a class="d-block overlay w-200px h-200px " data-fslightbox="lightbox-basic-'.$index.'" href="'.$imgPath.'">
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
                                    <span class="fw-semibold fs-5">{{$grallery->user->username}}</span>

                                    <div  class="text-end">
                                        @if($grallery['user']['id']==Auth::user()->id)
                                            <div class="cursor-pointer px-3" id="DropDown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-gear cursor-pointer"></i>
                                            </div>
                                            <div class="z-3">
                                                <ul class="dropdown-menu z-10 py-2 px-2 " aria-labelledby="DropDown" role="menu">
                                                    <a class="dropdown-item p-1 view_detail  fw-semibold editmodalbtn" type="button" data-id="${d.id}">
                                                        Edit
                                                    </a>
                                                    <a class="dropdown-item p-1 view_detail text-danger  fw-semibold removePost" type="button" data-href=""
                                                        data-id="${d.id}">
                                                        Delete
                                                    </a>
                                                </ul>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <span class="fw- fs-8">{{$grallery['created_at']}}</span>
                            </div>
                        </div>
                        <div class="">
                            <p class="fs-7 fw-semibold text-gray-700">
                                {{$grallery['note']}}
                            </p>
                        </div>
                        <div class="p-0 row  justify-content-start align-items-center gap-5 position-relative">

                            {!! $imagesHtml !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
    </div>
    <div class="m-auto">
        {{$gralleries->links()}}
    </div>
</div>
