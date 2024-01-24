@extends('ecommerce::layouts.master')
@section('dashboard_show', 'active show')
@section('dashboard_active', 'active ')
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <div class="card  min-h-300px min-h-md-375px min-h-xxl-450px mb-10 "
                 style="
                 background:url('https://t4.ftcdn.net/jpg/03/65/85/47/360_F_365854716_ZHB0YN3i3s0H7NjI9hiezH53D5nvoF0E.jpg');
                 background-position: center;
                 background-size: cover;
                 background-repeat: no-repeat;
            ">

            </div>

            <h1 class="mb-5 fs-1">Welcome {{ \Illuminate\Support\Facades\Auth::guard('customer')->user()?->customer?->first_name }} {{ \Illuminate\Support\Facades\Auth::guard('customer')->user()?->customer?->last_name }}</h1>
            <!--begin::Heading-->
            <div class="d-flex flex-wrap flex-stack mb-6 flex-row-reverse" >
                <!--begin::Title-->
                <h3 class="fw-bold my-2">Available
                    <span class="fs-6 fw-semibold ms-1">{{$productsCount}}+ items</span></h3>
                <!--end::Title-->
                <!--begin::Controls-->
                <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
{{--                    <label for="">Sort By:</label>--}}
                    <!--begin::Select-->
                    <select name="price_filter" data-control="select2" data-hide-search="true" class="form-select form-select-sm border-body bg-body ">
                        <option value="release_new_to_old" selected="selected">Release (New to Old)</option>
                        <option value="price_low_to_high">Price (Low to High)</option>
                        <option value="price_high_to_low">Price (High to Low)</option>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Controls-->
            </div>
            <!--end::Heading-->
            <!--begin::Contacts-->

            <div class="tab-content">
                <!--begin::Tap pane-->
                <div id="data-wrapper" class="tab-pane fade show active" role="tabpanel">
                    <!--begin::Wrapper-->
                    <div class="row g-3 g-sm-4 g-xl-5">
                        @include('ecommerce::product_card')
                    </div>

                    <div class="bottomDiv"></div>
                    <div class="" x-show="pageLoading">
                        <div class="card">
                            <div class="card-body text-center py-3">
                                <span class="fs-6 text-gray-700">Loading ...</span>
                            </div>
                        </div>
                    </div>

                    <!--end::Wrapper-->
                </div>
{{--                <div class="loader text-center mt-5" style="display: none;">--}}
{{--                    <div class="d-flex justify-content-center">--}}
{{--                        <div class="spinner-border" role="status">--}}
{{--                            <span class="visually-hidden">Loading ...</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
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
        var page = 1;
        var url = "{{ route('ecommerce.home') }}";
        $(window).scroll(function (){
           if($(window).scrollTop() + $(window).height() >= ($(window).height() + 500)){
               page++;
                LoadMore(page);
           }
        });

        $('#quickSearchInput').on('input', function () {
            page = 1;
            $('#data-wrapper').empty();
            quickSearch($(this).val());
        });

        $("[name='price_filter']").on('change', function () {
            page = 1;
            $('#data-wrapper').empty();
            applyFilters();
        });

        $('[name="brand_filter"]').on('change', function (){
            page = 1;
            $('#data-wrapper').empty();
            applyFilters();
        });

        function applyFilters() {
            var query = $('#quickSearchInput').val();
            var priceFilter = $("[name='price_filter']").val();
            var brandFilter = $('[name="brand_filter"]').val();

            $.ajax({
                url: "{{ route('ecommerce.home') }}",
                data: { query: query,
                    price_filter: priceFilter,
                    brand_filter: brandFilter,
                },
                datatype: "html",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('.loader').show();
                }
            })
                .done(function (response) {
                    $('.loader').hide();
                    $('#data-wrapper').html("<div class='row g-3 g-sm-4 g-xl-5'>" + response.html + "</div>");
                })
                .fail(function () {
                    console.log('Server error occurred');
                });
        }


        function quickSearch(query) {
            $.ajax({
                url: "{{ route('ecommerce.home') }}",
                data: { query: query },
                datatype: "html",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('.loader').show();
                }
            })
                .done(function (response) {
                    $('.loader').hide();
                    $('#data-wrapper').html("<div class='row g-3 g-sm-4 g-xl-5'>" + response.html + "</div>");
                })
                .fail(function () {
                    console.log('Server error occurred');
                });
        }


        function LoadMore(page){
            console.log($(window).height())
            console.log($(window).scrollTop() + $(window).height())

            $.ajax({
                url: url + "?page=" + page,
                datatype: "html",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function (){
                    $('.loader').show();
                }
            })
                .done(function (response){

                    if(response.html == ''){
                        $('.loader').html("No more products");
                        return;
                    }
                    $('.loader').hide();
                    $('#data-wrapper').append("<div class='row g-3 g-sm-4 g-xl-5'>" + response.html + "</div>");
                })
                .fail(function (){
                    console.log('Server error occured');
                });
        }

    </script>
@endpush
