<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Form-->
        <div class="collapse" id="productFilter" wire:ignore>
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Filters</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Product Type:</label>
                                <select id="productTypeFilter" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-allow-clear="true" data-placeholder="Select an option">
                                    <option value="all" selected>All</option>
                                    @foreach($product_types as $product_type)
                                        <option value="{{$product_type}}">{{$product_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Category:</label>
                                <select id="categoryFilterId" class="form-select form-select-sm" data-control="select2" data-hide-search="false"  data-allow-clear="true"  data-placeholder="Select an option">
                                    <option value="all">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Brand:</label>
                                <select id="brandId" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-hide-search="false" data-placeholder="Select an option">
                                    <option value="all">All</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand['id']}}">{{$brand['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Generic:</label>
                                <select id="genericId" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-hide-search="false" data-placeholder="Select an option">
                                    <option value="all">All</option>
                                    @if($generics->count() > 0)
                                        @foreach($generics as $generic)
                                            <option value="{{$generic['id']}}">{{$generic['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Manufacture:</label>
                                <select id="manufactureId" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-hide-search="false" data-placeholder="Select an option">
                                    @if($manufactures->count() > 0)
                                        <option value="all">All</option>
                                        @foreach($manufactures as $manufacture)
                                            <option value="{{$manufacture['id']}}">{{$manufacture['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-5">
                                <label class="h5" for="">Location:</label>
                                <select id="location_filter" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-hide-search="false" data-placeholder="Select an option">
                                    @if($locations->count() > 0)
                                        <option value="all">All</option>
                                        @foreach($locations as $location)
                                            <option value="{{$location->name}}">{{$location->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>

                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" />
                            <label class="form-check-label" for="flexCheckChecked">
                                <strong class="">Not for selling</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/products.html"> --}}
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin::Tab pane-->
                    <div class="tab-pane fade show active" id="remove_kt_ecommerce_add_product_general" role="tab-panel">
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>All Products</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                {{-- <div class="card-body pt-0">
                                    <div class="card  card-flush"> --}}
                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                            <div class="card-title">
                                                <!--begin::Search-->
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <input type="search" id="search" wire:model.live.debounce.50ms='search' data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search Product" />
                                                </div>
                                                <!--end::Search-->
                                                <!--begin::Export buttons-->
                                                <div id="kt_datatable_example_1_export" class="d-none"></div>
                                                <!--end::Export buttons-->
                                            </div>
                                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                <button type="button" class="btn btn-light-primary collapsed btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#productFilter" aria-expanded="false" aria-controls="productFilter">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Filter
                                                </button>
                                                @if(hasExport('product'))
                                                <!--begin::Export dropdown-->
                                                <button type="button" class="btn btn-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    Export Products List
                                                </button>
                                                <!--begin::Menu-->
                                                <div id="kt_datatable_example_export_menu" class="btn-sm menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    {{-- <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                        Copy to clipboard
                                                        </a>
                                                    </div> --}}
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('export-productlist') }}" class="menu-link px-3" data-kt-export="excel">
                                                        Export as Excel
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    {{-- <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                        Export as CSV
                                                        </a>
                                                    </div> --}}
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    {{-- <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                        Export as PDF
                                                        </a>
                                                    </div> --}}
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                                <!--end::Export dropdown-->

                                                <!--begin::Hide default export buttons-->
                                                <div id="kt_datatable_example_buttons" class="d-none"></div>
                                                <!--end::Hide default export buttons-->
                                                @endif
                                                @if(hasCreate('product'))
                                                <a href="{{ url('product/add') }}" class="text-light btn btn-primary btn-sm">Add Product</a>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            {{-- <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 " wire:loading='products' style="top: 40px;left:50%;">
                                                <h2 class="text-primary">Loading....</h2>
                                            </div> --}}
                                            {{-- <img src="{{ asset('/storage/product-image/1680624705_anime-girl.jpg') }}" alt="image" />	 --}}
                                            <div class="table-responsive">
                                                <table class="table border-1 Datatable-tb align-middle  rounded table-row-dashed fs-6 g-5" id="kt_datatable_example" >
                                                    <!--begin::Table head-->
                                                    <thead>
                                                        <!--begin::Table row-->
                                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                            {{-- <th class="w-10px pe-2">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
                                                                </div>
                                                            </th> --}}
                                                            <th>
                                                                <div class="form-check form-check-sm form-check-custom  me-3">
                                                                    <input class="form-check-input" data-checked="selectAll" id="selectAll" type="checkbox" data-kt-check="true"
                                                                        data-kt-check-target="#kt_saleItem_table .form-check-input" value="" />
                                                                </div>
                                                            </th>
                                                            {{-- <th></th> --}}
                                                            <th class="text-start min-w-100px">{{ __('product/product.action') }}</th>
                                                            <th class="min-w-150px">{{ __('product/product.product') }}</th>
                                                            {{-- <th class="text-start min-w-150px">
                                                                Business Location <i class="fas fa-exclamation-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                title="Product will be available only in this business locations"></i>
                                                            </th> --}}
                                                            <th class="text-start min-w-150px">{{ __('product/product.sku') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.assign_location') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.purchase_price') }}</th>
                                                            <th class="text-start min-w-100px">{{ __('product/product.sell_price') }}</th>
                                                            {{-- <th class="text-start min-w-150px">Current Stock</th> --}}
                                                            <th class="text-start min-w-100px">{{ __('product/product.product_type') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.category') }}</th>
                                                            <th class="text-start min-w-100px">{{ __('product/product.brand') }}</th>
                                                            <th class="text-start min-w-100px">{{ __('product/product.generic') }}</th>
                                                            <th class="text-start min-w-100px">{{ __('product/product.manufacturer') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.custom_field_1') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.custom_field_2') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.custom_field_3') }}</th>
                                                            <th class="text-start min-w-150px">{{ __('product/product.custom_field_4') }}</th>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    </thead>
                                                    <!--end::Table head-->
                                                    <!--begin::Table body-->
                                                    <tbody class="fw-semibold text-gray-600">
                                                        @foreach ($products as $product)
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom ">
                                                                    <input class="form-check-input checkAssign" type="checkbox" data-checked="assign" value="{{$product['id']}}" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    @if ($updatePermission || $deletePermission)
                                                                        <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            Actions
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                            @if ($updatePermission)
                                                                                <li>
                                                                                    <a href="/product/edit/{{$product['id']}}" class="dropdown-item p-2 edit-brand px-3" >
                                                                                        <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}
                                                                                    </a>
                                                                                </li>
                                                                        @endif
                                                                        @if ($deletePermission)
                                                                            <li>
                                                                                <div class="dropdown-item p-2 product-delete-confirm cursor-pointer px-3" data-id="{{$product['id']}}" >
                                                                                    <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}
                                                                                </div>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                    @else
                                                                    <button class="btn btn-sm btn-dark btn-active-light-primary fw-semibold fs-9" type="button">
                                                                        No Permissions
                                                                    </button>

                                                                   @endif
                                                                </div>
                                                            </td>
                                                            <td>{{$product['name']}}</td>
                                                            <td>{{$product['sku']}}</td>
                                                            <td>
                                                            @php
                                                                $count=App\Models\locationProduct::where('product_id',$product->id)->count();
                                                                $data = App\Models\locationProduct::where('product_id',$product->id)
                                                                    ->with(['location:id,name'])
                                                                    ->limit(3)
                                                                    ->get()
                                                                    ->pluck('location.name')
                                                                    ->toArray();
                                                                $result = implode(', ', $data);
                                                                $strEnd=strlen('result')>80 || $count >3?'.....':'';
                                                            @endphp
                                                            @if ($count >1)
                                                                {{substr($result,0,80).$strEnd}}
                                                            @else
                                                            -

                                                            @endif
                                                            </td>
                                                            <td>
                                                                @if ($product->has_variation === "single")
                                                                    {{$product->productVariations[0]->default_purchase_price ?? 0}}
                                                                    @else
                                                                    Variations
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($product->has_variation === "single")
                                                                    {{$product->productVariations[0]->default_selling_price ?? 0}}
                                                                @else
                                                                    Variations
                                                                @endif
                                                            </td>

                                                            <td>
                                                                {{$product->product_type ?? ''}}
                                                            </td>
                                                            <td>
                                                                {{$product->categoryName}}
                                                                {{$product->subCategoryName !='' ? ', '.$product->subCategoryName :''}}
                                                            </td>
                                                            <td>
                                                                {{$product->brandName}}
                                                            </td>
                                                            <td>
                                                                {{$product->genericName}}
                                                            </td>
                                                            <td>
                                                                {{$product->manufacturerName ?? ''}}
                                                            </td>
                                                            <td>
                                                                {{$product->product_custom_field1}}
                                                            </td>

                                                            <td>
                                                                {{$product->product_custom_field2}}
                                                            </td>

                                                            <td>
                                                                {{$product->product_custom_field3}}
                                                            </td>

                                                            <td>
                                                                {{$product->product_custom_field4}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>

                                            </div>

                                            <div class="row justify-content-center mt-3  justify-content-md-between">
                                                <div class="col-md-6 col-12 mb-3 ">
                                                    <div class="w-auto">
                                                        <select name="" id="" wire:model.change="perPage" class="form-select form-select-sm w-auto m-auto m-md-0">
                                                            @foreach ($aviablePerPages as $page)
                                                            <option value="{{$page}}">{{$page}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    {{$products->links()}}
                                                </div>
                                            </div>

                                            <div class="d-flex gap-4 mt-5">
                                                <button class="btn btn-primary btn-sm" id="assignBtn">Assign Selected Products To location</button>
                                                <button class="btn btn-warning btn-sm" id="removeAssignBtn">Removed Selected Products From location</button>
                                            </div>

                                        {{-- </div>
                                    </div> --}}
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::General options-->
                        </div>
                    </div>
                    <!--end::Tab pane-->
                    <!--begin::Tab pane-->
                </div>
                <!--end::Tab content-->
            </div>
            <!--end::Main column-->
        {{-- </form> --}}
        <!--end::Form-->
    </div>
    <!--end::Container-->
</div>

@script
<script wire:ignore>
    // productTypeFilter
    $('#productTypeFilter').select2().on('select2:select', function (e) {
        @this.set('productTypeFilter', $('#productTypeFilter').select2("val"));
    }).on('select2:unselect',function(){
        @this.set('productTypeFilter','all');
    });


    $('#categoryFilterId').select2().on('select2:select', function (e) {
        @this.set('categoryFilterId', $('#categoryFilterId').select2("val"));
    }).on('select2:unselect',function(){
        @this.set('categoryFilterId','all');
    });

    $('#brandId').select2().on('select2:select', function (e) {
        @this.set('brandId', $('#brandId').select2("val"));
    }).on('select2:unselect',function(){
        @this.set('brandId','all');
    });
    $('#genericId').select2().on('select2:select', function (e) {
        @this.set('genericId', $('#genericId').select2("val"));
    }).on('select2:unselect',function(){
        @this.set('genericId','all');
    });

    $('#manufactureId').select2().on('select2:select', function (e) {
        @this.set('manufactureId', $('#manufactureId').select2("val"));
    }).on('select2:unselect',function(){
        @this.set('manufactureId','all');
    });



    const modal = new bootstrap.Modal($('#locationSelect'));
    const removeModal = new bootstrap.Modal($('#locationRemove'));
    // assign product to location
    let locations=@json($locations ?? []);
    const transformedObject = Object.fromEntries(locations.map(({ id, name }) => [id, name]));
    var options=locations.map((l)=>{
        return {'id':l.id,'text':l.name}
    })
    $(document).on('click','#selectAll',function() {
        let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
        checkBoxs.forEach(c => {
            $(c).prop('checked', $(this).prop('checked'))
        });
    });
    $('#assignBtn').click(()=>{
        let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
        let checkCount=0;
        let productIds=[];
        checkBoxs.forEach(c => {
            if (c.checked) {
                checkCount++;
                let productId=$(c).val();
                productIds=[...productIds,productId];
            }
        });
        if(checkCount <1){
            Swal.fire({
                title: 'Please Select The Products',
                // text: "You want to delete it!",
                icon: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            })
        }else{
            $('#locationSelect2').select2({data:options});
            modal.show();
            $(document).off().on('click','#locationAssignChanges',async function(){
                let locationIds=$('#locationSelect2').val();
                console.log(locationIds);
                if(locationIds.length > 0){
                    $('#locationAssignChanges').prop('disabled', true).text('loading.....');
                    await assign(locationIds,productIds);
                    $('#locationAssignChanges').prop('disabled', false).text('Save');
                    $('#locationSelect2').val('');
                    modal.hide();
                }

            })
        }
    })
    function assign(locationIds,productIds){
        return new Promise((resolve, reject)=>{
            $.ajax({
                url:'/location-product/store',
                type: 'GET',
                data:{
                    locationIds,
                    productIds,
                },
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(s) {
                    Swal.fire({
                        title: 'Successfully Assigned',
                        icon: "success",
                        confirmButton:true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    })
                    $('.checkAssign').prop('checked',false);
                    resolve();
                },
                error:function(){
                    resolve();
                }
            })
        })
    }
    function remove(locationIds,productIds){
        return new Promise((resolve, reject)=>{
            $.ajax({
                url:'/location-product/remove',
                type: 'GET',
                data:{
                    locationIds,
                    productIds,
                },
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(s) {
                    Swal.fire({
                        title: 'Successfully Removed',
                        icon: "success",
                        confirmButton:true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    })
                    resolve();
                    $('.checkAssign').prop('checked',false);
                },
                error:function(){
                    resolve();
                }
            })
        })
    }
    $('#removeAssignBtn').click(()=>{
        let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
        let checkCount=0;
        let productIds=[];
        checkBoxs.forEach(c => {
            if (c.checked) {
                checkCount++;
                let productId=$(c).val();
                productIds=[...productIds,productId];
            }
        });
        if(checkCount <1){
            Swal.fire({
                title: 'Please Select The Products',
                // text: "You want to delete it!",
                icon: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            })
        }else{
            $('#locationRemoveSelect2').select2({data:options});
            removeModal.show();
            $(document).off('click').on('click','#locationRemoveChanges',async function(){
                let locationIds=$('#locationRemoveSelect2').val();
                if(locationIds.length > 0){
                    $('#locationRemoveChanges').prop('disabled', true).text('loading.....');
                    await remove(locationIds,productIds);
                    $('#locationRemoveChanges').prop('disabled', false).text('Save');
                    $('#locationRemoveSelect2').val('');
                    removeModal.hide();
                }

            })

        }
    })


    // Product - DELETE
    $(document).on('click', '.product-delete-confirm', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    // $wire.dispatch('product-deleted');
                    if (result.value) {
                        $.ajax({
                            url: '/product/delete/' + id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function(response) {
                                if(response.message){
                                    success(response.message);
                                }

                                if(response.error){
                                    Swal.fire({
                                        text: response.error,
                                        icon: "error",
                                        buttonsStyling: false,
                                        showCancelButton: false,
                                        confirmButtonText: "Ok",
                                        cancelButtonText: "Delete",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    });
                                }
                                $wire.dispatch('product-deleted');
                            }
                        })
                    }
                });

            });
</script>
@endscript
