@foreach($products as $product)
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <!--begin::Card-->
        <div class="card card-flush flex-row-fluid p-3 pb-5 mw-100 mb-2">
            <!--begin::Body-->
            <div class="card-body p-0 text-center">
                <!--begin::Food img-->
                <img src="{{asset('assets/media/svg/files/blank-image-dark.svg')}}" class="rounded-3 mb-4 w-100" alt="">
                <!--end::Food img-->
                <!--begin::Info-->
                <div class="mb-2">
                    <!--begin::Title-->
                    <div class="text-center">
                        <a href="{{ route('ecommerce.product-details', $product->id) }}" class="fw-bold text-gray-780 cursor-pointer text-hover-primary fs-6 fs-xl-5">{{ Illuminate\Support\Str::limit(Illuminate\Support\Str::words($product->name, 4, '...'), 20, '...') }}</a>
                        <span class="text-gray-600 fw-semibold d-block fs-7 mt-2">@if($product->stock_sum_current_quantity <= 0  && $product->product_type == 'storable') <span class="text-danger">Out of stock</span> @else{{ intval($product->stock_sum_current_quantity) }} items @endif</span>
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Info-->
                <!--begin::Total-->
                <span class="text-end fw-bold fs-4 text-success">{{ $product->has_variation == "single" ? $product->productVariations[0]->default_selling_price : 0 }} K</span>
                <!--end::Total-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card-->
    </div>
@endforeach
