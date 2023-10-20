@extends('App.main.navBar')

@section('barcode_active','active')
@section('barcode_active_show','active show')
@section('barcode_template_list_active_show','here show')
@section('location_add_nav','active')

@section('styles')
<style>

</style>

@endsection
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Add Barcode Template</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Setting</li>
    <li class="breadcrumb-item text-muted">
        <a href="" class="text-muted text-hover-primary">Barcode</a>
    </li>
    <li class="breadcrumb-item text-dark">Add Barcode </li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="location">

        <!--begin::Modals-->
        <div class="card">
            <div class="card-body user-select-none">
                <!--begin::Form-->
                <form class="form" action="{{route('barcode.print')}}" method="POST" id="location_form">
                    @csrf
                    <div class="row">
                        <div class="col-6" style="">
                            <div class="mt-4">
                                <h3>Add Product Sku</h3>
                            </div>
                            <div class="mt-5 position-relative">
                                <select name="price_list" class="form-select form-select-sm form-select-solid mb-3" id="" data-control="select2" placeholder="Select Price List">
                                    @foreach ($priceLists as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="searchInput"
                                    placeholder="Search Product To Add">
                                <div class="quick-search-results overflow-scroll d-none  position-absolute  card w-100 mt-5  card z-index-1 autocomplete shadow"
                                    id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                            </div>
                            <div class="mt-10 productlist">
                                {{-- @foreach ([1,2,3,4,5] as $i)

                                <div class="p-2 pb-3 border border-start-0 border-top-0 border-end-0  border-dotted">
                                    <div class="row justify-content-center align-items-center">
                                        <div class=" col-4">
                                            <div>
                                                Keyboard
                                            </div>
                                            <div class="text-gray-500 mt-2">
                                                sku : 00123234
                                            </div>
                                        </div>
                                        <div class="actions col-8 row ">
                                            <div class="col-6">
                                                <x-forms.input placeholder="Label count to print" name=""></x-forms.input>
                                            </div>
                                            <div class="col-6">
                                                <x-forms.input placeholder="Select Packaging Date" name="" class='date'></x-forms.input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach --}}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-4">
                                <h3>Label Setting</h3>
                            </div>
                            <div class="mt-5">
                               <select name="template_type" id="" placeholder="Select Paper Layout" data-placeholder="Select Paper Layout" class="form-select form-select-sm form-select-solid" data-control="select2" >
                                @foreach ($barcodeTemplates as $bt)
                                    <option value="{{$bt->id}}">{{$bt->name}}</option>
                                @endforeach
                               </select>
                            </div>
                            <div class="mt-10 ">

                                <div class="row">
                                    <label for="" class="form-label text-gray-600">Business Name (Px)</label>
                                    <div class="input-group input-group-sm input-group-solid mb-5">
                                        <div class="input-group-text " id="basic-addon1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="on" name="business_name"  id="flexCheckDefault" />
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Font-size" value="10" name="business_name_fs" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label text-gray-600">Product Name (Px)</label>
                                    <div class="input-group input-group-sm input-group-solid mb-5">
                                        <div class="input-group-text " id="basic-addon1">
                                            <div class="form-check">
                                                <input class="form-check-input" checked type="checkbox" value="on" name="product" id="" />
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" name="product_fs" value="10" placeholder="Font-size" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label text-gray-600">Variation (Px)</label>
                                    <div class="input-group input-group-sm input-group-solid mb-5">
                                        <div class="input-group-text " id="basic-addon1">
                                            <div class="form-check">
                                                <input class="form-check-input" checked type="checkbox" value="on" name="variation" id="flexCheckDefault" />
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Font-size" value="10" name="variation_fs" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label text-gray-600">Price (Px)</label>
                                    <div class="input-group input-group-sm input-group-solid mb-5">
                                        <div class="input-group-text " id="basic-addon1">
                                            <div class="form-check">
                                                <input class="form-check-input" checked type="checkbox" value="on"  name="price" id="flexCheckDefault" />
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Font-size" value="10" name="price_fs" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label text-gray-600">Packaging Date (Px)</label>
                                    <div class="input-group input-group-sm input-group-solid mb-5">
                                        <div class="input-group-text " id="basic-addon1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="on" id="flexCheckDefault" name="date" />
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Font-size" name="date_fs" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>

                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')
<script src="{{Module::asset('Barcode:Resources/assets/js/app.js')}}"></script>
<script>
    let throttleTimeout;
    var product;
    var productOnSelect;
    $('#searchInput').on('input', function() {
        let input=$(this);
        var query = $(this).val().trim();
        if (query.length >= 2) {
            $('.quick-search-results').removeClass('d-none');
            $('.quick-search-results').html(`<div class="quick-search-result result cursor-pointer p-2 ps-10 fw-senubold fs-5">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
            </div>
            `);
            clearTimeout(throttleTimeout);
            throttleTimeout = setTimeout(function() {
                $.ajax({
                    url: `/purchase/get/product`,
                    type: 'POST',
                    delay: 150,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data:query
                    },
                    error:function(e){
                        status=e.status;
                        if(status==405){
                            warning('Method Not Allow!');
                        }else if(status==419){
                            error('Session Expired')
                        }else{
                            console.log(' Something Went Wrong! Error Status: '+status )
                        };
                    },
                    success:function(e){
                        results=e;
                        products=e;
                        var html = '';
                        input.val('');
                        // products=results;
                        // console.log(results);
                        if (results.length > 0 && Array.isArray(results)) {
                            results.forEach(function(result,key) {
                                html += `<div class="quick-search-result result cursor-pointer mt-1 mb-1 bg-hover-light p-2" data-id=${key} data-name="${result.name}" style="z-index:100;">`;
                                html += `<h4 class="fs-6 ps-10 pt-3">
                                    ${result.name} ${result.has_variation==='variable'?'-('+result.product_variations.length+') select all' :''}`;
                                if(result.has_variation=='sub_variable'){
                                    html +=  `<span class="text-gray-700 fw-semibold p-1 fs-5">(${result.variation_name??''})</span>`;
                                }

                                html+='</h4>'
                                html+=`<span class="ps-10 pt-3 text-gray-700">${result.sku?'SKU : '+result.sku :''} </span>`

                                html += '</div>';

                                //
                            });
                            if (results.length == 1) {
                            $('.quick-search-results').show();
                                setTimeout(() => {
                                    $(`.result[data-name|='${results[0].name}']`).click();
                                    $('.quick-search-results').hide();
                                }, 100);
                            } else {
                                $('.quick-search-results').show();
                            }
                        } else {
                            $('.quick-search-results').show();
                            html = '<p class="ps-10 pt-5 pb-2 fs-6 m-0 fw-semibold text-gray-800">No results found.</p>';
                        }
                        $('.quick-search-results').removeClass('d-none')
                        $('.quick-search-results').html(html);

                    }
                });
            },300)


        } else {
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
        }
        $(document).click(function(event) {
            if (!$(event.target).closest('.quick-search-results').length) {
                $('.quick-search-results').addClass('d-none')
            }
        });

    });
    $('#autocomplete').on('click', '.result', function() {
         $('.dataTables_empty').remove();
        $('.quick-search-results').addClass('d-none')
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        let selected_product= results[id] ?? results[0];
        if(selected_product.has_variation==='variable')
        {
            let variation=selected_product.product_variations;
            variation.forEach(v => {
                let t=products.filter(p=>{
                    return p.variation_id==v.id
                });
                append_row(t[0]);

            });
            return;
        }
        append_row(selected_product)

    });
    function append_row(selected_product){
        $('.productlist').append(`
        <div class="p-2 pb-3 border border-start-0 border-top-0 border-end-0  border-dotted item_row">
            <div class="row justify-content-center align-items-center">
                <input name="index[]" value='index' class="d-none" />
                <div class=" col-4 d-flex">
                    <div>
                        <div>
                            ${selected_product.name}
                            <input name="product_name[]" class="d-none" value="${selected_product.name}" />
                            <input name="variation_name[]" class="d-none" value="${selected_product.has_variation !='single' ?selected_product.variation_name :''}" />
                        </div>
                        <div class="text-gray-500 mt-2">
                            sku : ${selected_product.sku}
                            <input name="product_sku[]" class="d-none" value="${selected_product.sku}" />
                        </div>
                    </div>
                </div>
                <div class="actions col-8 row ">
                    <div class="col-5">
                        <x-forms.input placeholder="Label count to print" name="count[]" value='1'></x-forms.input>
                    </div>
                    <div class="col-5">
                        <x-forms.input placeholder="Select Packaging Date" name="date[]" value="{{now()}}" class='date'>
                        </x-forms.input>
                    </div>
                    <div class="col-2 cursor-pointer removeItem" id=""> <i class="fa-solid fa-trash text-danger"></i></div>
                </div>
            </div>
        </div>
        `);
        $(".date").flatpickr();
        $('.removeItem').click(function(){
            let parent=$(this).closest('.item_row');
            parent.remove();
        })
    }
</script>
@endpush
