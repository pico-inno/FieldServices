@extends('ecommerce::layouts.master')
@section('dashboard_show', 'active show')
@section('dashboard_active', 'active ')
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container" x-data="cards()">
            <div class="col-12 my-2 fv-row fv-plugins-icon-container">
                <div class="input-group col-12">
                    <div class="input-group-text">
                              <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                        </svg>
                              </span>
                    </div>
                    <div class="overflow-hidden  flex-grow-1">
                        <input type="text" data-kt-barcode-table-filter="search" class="form-control rounded-0 rounded-end-3 ps-3" placeholder="Searchs">
                    </div>
                    <div class="fv-plugins-message-container invalid-feedback"></div></div>
            </div>
            <!--begin::Heading-->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <!--begin::Title-->
                <h3 class="fw-bold my-2">Available
                    <span class="fs-6 fw-semibold ms-1">50+ items</span></h3>
                <!--end::Title-->
                <!--begin::Controls-->
                <div class=" my-2">
                    {{--                    <label for="">Sort By:</label>--}}
                    {{--                    <!--begin::Select-->--}}
                    {{--                    <select name="status" data-control="select2" data-hide-search="true" class="form-select form-select-sm border-body bg-body w-125px">--}}
                    {{--                        <option value="Online" selected="selected">Online</option>--}}
                    {{--                        <option value="Pending">Pending</option>--}}
                    {{--                        <option value="Declined">Declined</option>--}}
                    {{--                        <option value="Accepted">Accepted</option>--}}
                    {{--                    </select>--}}
                    <!--end::Select-->
                </div>
                <!--end::Controls-->
            </div>
            <!--end::Heading-->
            <!--begin::Contacts-->

            <div class="tab-content">
                <!--begin::Tap pane-->
                <div class="tab-pane fade show active" id="kt_pos_food_content_1" role="tabpanel" x-data="{ products: [], page: 1 }" x-init="fetchProduct()">
                    <!--begin::Wrapper-->
                    <div class="row g-3 g-sm-4 g-xl-5"  x-ref="productContainer">

                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>

                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>

                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <!--begin::Card-->
                            <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100">
                                <!--begin::Body-->
                                <div class="card-body p-0 text-center">
                                    <!--begin::Food img-->
                                    <img src="https://firebasestorage.googleapis.com/v0/b/piti-commerce.appspot.com/o/commerce%2Fsweetconnect%2Fitems%2F1696054315483b8238085addf6b7d422b70c99455dcb9?alt=media&token=915ff477-c86e-4ba1-afb6-a014b82b5a34" class="rounded-3 mb-4 w-100" alt="">
                                    <!--end::Food img-->
                                    <!--begin::Info-->
                                    <div class="mb-2">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">Xiaomi Smart Band 8 M2239B1 Global</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-7 mt-n1">16 Stock</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Total-->
                                    <span class="text-end fw-bold fs-4">109,000 K</span>
                                    <!--end::Total-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>

                    </div>


                    <!--end::Wrapper-->
                </div>
                <!--end::Tap pane-->
            </div>
            <!--end::Contacts-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>

    </script>
@endpush
