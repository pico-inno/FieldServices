<div>
    @if(hasUpload('campaign'))
    <button class="btn btn-primary btn-sm mb-5" id="addPhoto" >
        <i class="fa-solid fa-plus-circle"></i>   {{__('fieldservice::actions.add_post')}}
    </button>
    <div class="modal fade " tabindex="-1" id="modal">
        <div class="modal-dialog w-md-600px w-100 modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content mb-20">
                <div class="modal-header py-2">
                    <h3 class="modal-title"> {{__('fieldservice::actions.add_post')}}</h3>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="{{route('gallery.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea class="form-control" data-kt-autosize="true" name="note" placeholder="Add Note"></textarea>
                        <div class="fv-row">
                            <div class="dropzone mt-3 p-4" id="">
                                <label for="images" class="dz-message cursor-pointer">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <i class="ki-duotone ki-some-files fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        <div class="ms-4">
                                            <h3 class="fs-6 fw-bold text-gray-900 mb-1" id="fileText"> click to upload image.</h3>
                                        </div>
                                    </div>
                                </label>
                                <input type="file" id="images" class=" d-none" name="images[]" accept=".jpg,.png" multiple>
                                <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                            </div>
                        </div>
                        <div class="preview   row mt-3 p-5 g-1 d-flex flex-nowrap overflow-x-scroll gap-1">

                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{__('fieldservice::actions.post_save')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endif
<div class="gallery">

</div>
<div class="galleries">

</div>
<div class="card mb-5 d-none" id="loadingPost">
    <div class="card-body px-5">
        <div class="d-flex mb-5">
            <div class="symbol symbol-40px me-3 placeholder-glow">
                <div class="w-40px h-40px rounded-1 fs-3 placeholder">

                </div>
            </div>
            <div class="col-md-1 col-5">
                <p class="card-text placeholder-glow">
                    <span class="placeholder col-12"></span>
                    <span class="placeholder col-8"></span>
                </p>
            </div>
        </div>
        <div class="placeholder-glow py-2">
                <span class="placeholder col-md-1 col-6"></span>
        </div>
        <div class="p-0 row  justify-content-start align-items-center gap-5 position-relative">

            <div class="w-auto z-index-2 ps-2 placeholder-glow  rounded-2">
                <!--begin::Overlay-->
                <div class="d-block overlay w-200px h-200px placeholder rounded-2">

                </div>
                <!--end::Overlay-->
            </div>
        </div>
    </div>
</div>
<div class="" id="bottomDiv"></div>

</div>
