@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="col-12" id="kt_view_note">
                <div class="d-flex mb-5">
                    <button type="reset" class="btn btn-light me-3"><i class="fas fa-arrow-left"></i>Back</button>
                </div>
                <!--begin::Card-->
                <div class="card mb-3">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-4">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Note Heading</h2>
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        <div class="separator my-5 mx-3"></div>
                        <h3>Documents</h3>
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen006.svg-->
                            <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.3" d="M22 5V19C22 19.6 21.6 20 21 20H19.5L11.9 12.4C11.5 12 10.9 12 10.5 12.4L3 20C2.5 20 2 19.5 2 19V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5ZM7.5 7C6.7 7 6 7.7 6 8.5C6 9.3 6.7 10 7.5 10C8.3 10 9 9.3 9 8.5C9 7.7 8.3 7 7.5 7Z" fill="currentColor"></path>
																<path d="M19.1 10C18.7 9.60001 18.1 9.60001 17.7 10L10.7 17H2V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V12.9L19.1 10Z" fill="currentColor"></path>
															</svg>
														</span>
                            <!--end::Svg Icon-->
                            <a href="#" class="text-gray-800 text-hover-primary">pattern-5.jpg</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/files/fil003.svg-->
                            <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"></path>
																<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
															</svg>
														</span>
                            <!--end::Svg Icon-->
                            <a href="#" class="text-gray-800 text-hover-primary">api-keys.html</a>
                        </div>
                    </div>
                    <!--end::Card body-->
                    <div class="card-footer">
                        <p>Add By: Admin <span>2023</span></p>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
@endpush
