@extends('ecommerce::layouts.master')

@section('content')






    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Heading-->
            <div class=" mb-6">
                <!--begin::Title-->
                {{--                    <h3 class="fw-bold my-2">Mi 90 Go Elbe Luggage 24inch QB\T 2155-2018--}}
                {{--                        <span class="fs-6 fw-semibold ms-1">50+ items</span></h3>--}}
                <!--end::Title-->

                <div class="card card-flush pt-3 mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2 class="fw-bold">{{$product->name}}</h2>
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
{{--                            <a href="../../demo7/dist/apps/subscriptions/add.html" class="btn btn-light-primary">Update Product</a>--}}
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-3">
                        <!--begin::Section-->
                        <div class="mb-10">
                            <!--begin::Details-->
                            <div class="d-flex flex-wrap justify-content-start">
                                <!--begin::Row-->
                                <div class=" me-10">
                                    {{--                                            <div class="col-lg-4">--}}
                                    {{--                                                <!--begin::Overlay-->--}}
                                    {{--                                                <a class="d-block overlay" data-fslightbox="lightbox-basic" href="{{asset('assets/media/stock/900x600/23.jpg')}}">--}}
                                    {{--                                                    <!--begin::Image-->--}}
                                    {{--                                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/900x600/23.jpg')">--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <!--end::Image-->--}}

                                    {{--                                                    <!--begin::Action-->--}}
                                    {{--                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">--}}
                                    {{--                                                        <i class="bi bi-eye-fill text-white fs-3x"></i>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <!--end::Action-->--}}
                                    {{--                                                </a>--}}
                                    {{--                                                <!--end::Overlay-->--}}
                                    {{--                                            </div>--}}
                                    <img src="{{ $product->image ?? asset('assets/media/svg/files/blank-image-dark.svg') }}" class="rounded-3 mb-4 w-250px" alt="">
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class=""  x-data="data">
                                    <!--begin::Details-->
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                        <!--begin::Row-->
                                        <tbody>
                                        <tr>
                                            <td class="text-bold min-w-100px w-150px fs-3">Price:</td>
                                            <td class="text-bold fs-3">
                                                {{ $product->default_selling_price }} K
                                            </td>
                                        </tr>
                                        @if($product->has_variation == 'variable')
                                        <tr>
                                            <td class="text-bold">{{ $product->variation_template_name }}:</td>
                                            <td class="text-bold">
                                                <select  class="form-select mb-3 form-select-sm" data-control="select2" data-close-on-select="false">
                                                @foreach($product->productVariations as $variation)
                                                    <option value="">{{$variation->variationTemplateValue->name}}</option>
                                                @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @endif
                                        <!--end::Row-->
                                        <tr>

                                        </tr>


                                        <!--begin::Row-->
                                        <tr>
                                            <td>

                                            </td>
                                            <td class="text-gray-800"></td>
                                        </tr>
                                        <!--end::Row-->

                                        </tbody></table>
                                        <div class="mt-6">
                                            <div class="input-group inpDialer mb-3">
                                                <button class="btn btn-sm btn-icon btn-outline btn-active-color-danger decrease" type="button" data-kt-dialer-control="decrease" @click="count = Math.max(1, count - 1)">
                                                    <i class="fa-solid fa-minus fs-2"></i>
                                                </button>
                                                <input type="text" class="form-control form-control-sm quantity input_number form-control-sm h-10" placeholder="quantity" name="ds" data-kt-dialer-control="input"   x-model="count" >
                                                <button class="btn btn-sm btn-icon btn-outline btn-active-color-primary increase" type="button" data-kt-dialer-control="increase" @click="increaseCount">
                                                    {{--                                                        if(count < {{ $product->stock_sum_current_quantity }}) count++--}}
                                                    <i class="fa-solid fa-plus fs-2"></i>
                                                </button>
                                            </div>
                                            <button class="btn btn-success" :disabled="disableAddToCartButton" @click="pushCart({{$product}})">
                                                <span x-text="add_cart_btn_msg"></span>
                                            </button>
                                        </div>


                                    <!--end::Details-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Section-->
                        <div class="mb-0">
                            <!--begin::Title-->
                            <h3 class="mb-6">Description</h3>
                            <!--end::Title-->
                            <p class="text-gray-800">{{ $product->product_description ?? 'No descriptions' }}</p>

                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Card body-->
                </div>



            </div>
            <!--end::Heading-->
            <!--begin::Contacts-->
            <div class="row g-3 g-sm-4 g-xl-5">
            <h1>Related Products</h1>
                <div id="data-wrapper" class="tab-pane fade show active" role="tabpanel">
                    <!--begin::Wrapper-->
                    <div class="row g-3 g-sm-4 g-xl-5">
                        @include('ecommerce::product_card')
                    </div>
                </div>
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
        var pushCart_EndPoint = "{{ route('ecommerce.add-to-cart') }}" ;

        function data(){

            return {

            }
        }

{{--        count < {{ $product->stock_sum_current_quantity }}--}}

    </script>
@endpush
