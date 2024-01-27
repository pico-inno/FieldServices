@extends('App.main.navBar')

@section('styles')
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <div class="col-12" id="kt_modal_add_user">
                <div class="d-flex mb-5" data-kt-users-modal-action="close">
                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"><i class="fas fa-arrow-left"></i>Back</button>
                </div>
                <!--begin::Form-->
                <form id="kt_modal_add_user_form" class="form" action="#" method="post">
                <!--begin::Card-->
                <div class="card mb-3">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-4">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Add Note</h2>
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Heading</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="heading" class="form-control form-control-solid" placeholder="">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Description</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea id="kt_docs_tinymce_hidden" name="kt_docs_tinymce_hidden" class="tox-target">
                            </textarea>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Documents</label>
                            <!--end::Label-->
                            <!--begin::Dropzone-->
                            <div class="dropzone" id="kt_dropzone_document">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <!--begin::Icon-->
                                    <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                    <!--end::Icon-->

                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                        <span class="fs-7 fw-semibold text-gray-400">Upload up to 10 files</span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <!--end::Dropzone-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <div class="text-center flex-center mt-8">
                    <!--begin::Button-->
                        <span class="indicator-label">Save Note</span>
                        <span class="indicator-progress">Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                </form>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <!--begin::Javascript-->
    <script>var hostUrl = "assets/";</script>
    <script src="{{asset('assets/plugins/custom/tinymce/tinymce.bundle.js')}}"></script>


    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/table.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/export-users.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/list/add.js')}}"></script>
    <!--end::Custom Javascript-->


    <script>

        // Retrieve the data-theme-mode value from local storage
        var themeMode = localStorage.getItem("data-theme");

        // Define the options for the editor
        var editorOptions = {
            selector: "#kt_docs_tinymce_hidden",
            height: "480",
            toolbar: [
                "undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify",
                "bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview | code"
            ],
            plugins: "advlist autolink link image lists charmap print preview code"
        };

        // If the theme mode is dark, update the options for the editor to use the dark theme
        if (themeMode === "dark") {
            editorOptions.skin = "oxide-dark";
            editorOptions.content_css = "dark";
        }

        // Initialize the editor with the chosen options
        tinymce.init(editorOptions);



        var myDropzone = new Dropzone("#kt_dropzone_document", {
            url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 10,
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            accept: function(file, done) {
                if (file.name == "wow.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            }
        });
    </script>
    <!--end::Javascript-->
@endpush
