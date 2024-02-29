<div>
    <div class="card card-flush" >
        <div class="card min-h-300px min-h-md-375px min-h-xxl-450px m-5"
             style="background-position: center; background-size: cover; background-repeat: no-repeat; position: relative;">
            <img src="{{ $imageUrl }}"  alt="Banner Image" class="img-fluid w-100 rounded-3 ">
        </div>

        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Form-->
            <form class="form" wire:submit.prevent="updateConfiguration"  enctype="multipart/form-data">
                @csrf
                <!--begin::Input group-->
                <div class="fv-row row mb-15">
                    <!--begin::Col-->
                    <div class="col-md-3 d-flex align-items-center">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold">Banner Image</label>
                        <!--end::Label-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-9">
                        <!--begin::Dropzone-->
                        <div class="dropzone" id="fileDropzone">
                            <label for="main_banner_image" class="dz-message cursor-pointer">
                                <div class="d-flex justify-content-center align-items-center">
                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/keenthemes/metronic/docs/core/html/src/media/icons/duotune/general/gen006.svg-->
                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M22 5V19C22 19.6 21.6 20 21 20H19.5L11.9 12.4C11.5 12 10.9 12 10.5 12.4L3 20C2.5 20 2 19.5 2 19V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5ZM7.5 7C6.7 7 6 7.7 6 8.5C6 9.3 6.7 10 7.5 10C8.3 10 9 9.3 9 8.5C9 7.7 8.3 7 7.5 7Z" fill="currentColor"/>
                                            <path d="M19.1 10C18.7 9.60001 18.1 9.60001 17.7 10L10.7 17H2V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V12.9L19.1 10Z" fill="currentColor"/>
                                            </svg>
                                            </span>
                                    <!--end::Svg Icon-->
                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1" id="fileText" wire:model="fileName">{{ $fileName }}</h3>
                                    </div>
                                </div>
                            </label>
                            <input type="file" class="d-none"  wire:model="mainBannerImage" accept=".jpg,.jpeg"
                                   id="main_banner_image"
                            >
                        </div>
                        <!--end::Dropzone-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Action buttons-->
                <div class="row mt-12">
                    <div class="col-md-9 offset-md-3">
                        <!--begin::Cancel-->
                        <button type="button" class="btn btn-light me-3">Cancel</button>
                        <!--end::Cancel-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary" id="kt_file_manager_settings_submit">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--begin::Action buttons-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Card body-->
    </div>
</div>

