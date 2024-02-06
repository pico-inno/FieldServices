@extends('App.main.navBar')

@section('barcode_active','active')
@section('barcode_active_show','active show')
@section('barcode_generate_active_show','here show')
@section('location_add_nav','active')

@section('styles')
<style>

</style>

@endsection
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Barcode Print</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    {{-- <li class="breadcrumb-item text-muted">Setting</li> --}}
    <li class="breadcrumb-item text-muted">
        Qr
    </li>
    <li class="breadcrumb-item text-muted">
        Barcode
    </li>
    <li class="breadcrumb-item text-dark">Print</li>
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
                <form class="form" action="{{route('barcode.print')}}" method="POST" id="template_form">
                    @csrf
                    <div class="row ">
                        <div class="col-12 col-md-6 mb-10 mb-md-2" style="">
                            <div class="mt-4">
                                <h3>Add Product Sku</h3>
                            </div>
                            <div class="mt-5 position-relative">
                                <select name="price_list" id="price_list" class="form-select form-select-sm form-select-solid mb-3" id="" data-control="select2" placeholder="Select Price List">
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
                        <div class="col-12 col-md-6">
                            <div class="mt-4">
                                <h3>Label Setting</h3>
                            </div>
                            <div class="mt-5 fv-row">
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
                                        <input type="text" class="form-control form-control-sm" placeholder="Font-size" name="date_fs" value="10" aria-label="Font-size"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-sm btn-primary" id="submit" type="submit">Print</button>
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
<script src="{{ asset('customJs/debounce.js') }}"></script>
<script>
    let throttleTimeout;
    var product;
    var productsOnSelectData=[];
    var rowKey=1;
    var currentCurrency=@json($business->currency);
    var businessName=@json($business->name);
    $('#searchInput').on('input', debounce(function() {
        let input=$(this);
        var query = $(this).val().trim();
        if (query.length >= 2) {
            $('.quick-search-results').removeClass('d-none');
            $('.quick-search-results').html(`<div class="quick-search-result result cursor-pointer p-2 ps-10 fw-senubold fs-5">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
            </div>`);
            // clearTimeout(throttleTimeout);
            // throttleTimeout =
            setTimeout(function() {
                $.ajax({
                    url: `/purchase/get/product`,
                    type: 'GET',
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

    }));
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
        $('#searchInput').val('');
       let list= $('.productlist').prepend(`
        <div class="p-2 pb-3 border border-start-0 border-top-0 border-end-0 border-bottom-1  border-dashed item_row" key="${rowKey}">
            <div class="row justify-content-center align-items-center">
                <input name="index[]" value='index' class="d-none" />
                <div class="col-12 col-md-4 ">
                    <div>
                        <span class="fw-bold">${selected_product.name}</span>
                        <input name="product_name[]" class="d-none" value="${selected_product.name}" />
                        <input name="variation_name[]" class="d-none" value="${selected_product.has_variation !='single' ?selected_product.variation_name :''}" />
                        <input type="hidden" name="product_id" class="product_id" value=${selected_product.id} />
                        <input type="hidden" name="variation_id" class="variation_id" value=${selected_product.variation_id} />
                    </div>
                    <div class="d-flex d-md-block justify-content-between align-items-center mb-3">
                        <div class="text-gray-500 mt-2 ">
                            sku : ${selected_product.sku}
                            <input name="product_sku[]" class="d-none" value="${selected_product.sku}" />
                        </div>
                        <div class="text-gray-500 mt-2">
                            price : <span class="price_txt"></span>
                            <input name="product_price[]" class="d-none price_input" value="${selected_product.has_variation =='single' ?selected_product.product_variations[0].default_selling_price :'0'}" />
                        </div>
                    </div>
                </div>
                <div class="actions col-12 col-md-8 row ">
                    <div class="col-5 col-md-5">
                        <input placeholder="Label count to print" class="form-control form-control-sm form-control-solid" name="count[]" value='1' />
                    </div>
                    <div class="col-6 col-md-5">
                        <input placeholder="Select Packaging Date" name="packaging_date[]" value="{{now()}}" class='date form-control form-control-sm form-control-solid' />

                    </div>
                    <div class="col-1 pt-1 col-md-2 cursor-pointer removeItem" id=""> <i class="fa-solid fa-trash text-danger"></i></div>
                </div>
            </div>
        </div>
        `);
        $(".date").flatpickr();
        $('.removeItem').click(function(){
            let parent=$(this).closest('.item_row');
            parent.remove();
        })
        let newProductData = {
            'product_id':selected_product.id,
            'variation_id':selected_product.variation_id,
            'qty':1,
            'uom_id':selected_product.uom_id,
        }
        const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newProductData.id && p.variation_id === newProductData.product_variations.id);
        if(indexToReplace !== -1){
            productsOnSelectData[indexToReplace] = newProductData;
        }else{
            productsOnSelectData=[...productsOnSelectData,newProductData];
        }

        getPrice($(`[key="${rowKey}"]`));
        rowKey++;
    }








    var priceList;
    var currentPriceList=$('#price_list').val();
    var exchangeRates=@json($exchangeRates ?? []);
    getPriceList($('#price_list').val());
    $('[name="price_list"]').change(function(){
        currentPriceList=priceLists.find((p)=>{
            return p.id == $(this).val();
        });
        getPriceList($(this).val());
    })
    function getPriceList(priceListId){
        $.ajax({
            url: `/sell/${priceListId}/price/list`,
            type: 'GET',
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
            success: function(results){
                priceList=results;
            }
        })
    }
    function getPrice(e){
        if(priceList){
            let parent = $(e);
            let mainPriceLists = priceList.mainPriceList ?? [];
            let mainPriceStatus=false;
            mainPriceLists.forEach((mainPriceList) => {
                if (mainPriceStatus == true) {
                    priceSetting(mainPriceList, parent,false);
                }else{

                mainPriceStatus = priceSetting(mainPriceList, parent,true);
                }
            })

            let basePriceLists=priceList.basePriceList ?? [];
            if(!mainPriceStatus){
                if(basePriceLists.length > 0){
                    let i = 0;
                    while (i < basePriceLists.length) {
                        let basePriceList = basePriceLists[i];
                        let priceStatus = false;
                        basePriceList.forEach((bp) => {
                            if (priceStatus == true) {
                                priceSetting(bp, parent,false);
                                return;
                            }
                            priceStatus = priceSetting(bp, parent,true);
                        })
                        if (priceStatus) {
                            break;
                        }
                        i++;
                    }
                }else{
                    let productId=parent.find('.product_id').val();
                    let variationId=parent.find('.variation_id').val();
                    let product=productsOnSelectData.filter(function(p){
                        return p.product_id==productId && variationId == p.variation_id;
                    })[0];
                    let result=product?product.default_selling_price:0;
                    let price=isNullOrNan(result);
                    if(price == 0){
                        let uomPirce=parent.find('.uom_price').val();
                        parent.find('.uom_price').val(uomPirce ?? 0);

                    }else{
                        parent.find('.uom_price').val(price);
                    }
                }
            }
        }
    }
    function priceSetting(priceStage,parentDom,dfs){
        let productId=parentDom.find('.product_id').val();
        let variationId=parentDom.find('.variation_id').val();
        let product=productsOnSelectData.filter(function(p){
            return p.product_id==productId && variationId == p.variation_id;
        });
        if(priceStage.applied_type=='All'){
            if(!priceSettingToUi(priceStage,parentDom,product,dfs)){
                return false
            }else{
                return true;
            };
        }else if(priceStage.applied_type=='Category'){
            let categoryId=product.category_id;
            if(priceStage.applied_value==categoryId){
                if(!priceSettingToUi(priceStage,parentDom,product,dfs)){
                    return false
                }else{
                    return true;
                };
            }
        }else if(priceStage.applied_type=='Product'){
            if(priceStage.applied_value==productId){
                if(!priceSettingToUi(priceStage,parentDom,product,dfs)){
                    return false
                }else{
                return true;
            };
            }
        }else if(priceStage.applied_type=='Variation'){
            if(priceStage.applied_value==variationId){
                if(!priceSettingToUi(priceStage,parentDom,product,dfs)){
                    return false
                }else{
                    return true;
                };
            }
        }else{
            return false;
        }
    }
    function priceSettingToUi(priceStage,parentDom,product,dfs=true){
        let checkDate=checkAvailableDate(priceStage);
        if(!checkDate){
            return false;
        }
        let quantity=isNullOrNan(1);
        let price=priceStage.cal_value;
        if (priceStage.cal_type == 'percentage') {
            let basePriceLists=priceList.basePriceList;
            let i = 0;
            let finalBasePrice=null;//final base price means when current price is  percentage on base price, the loop reach the base price that is fix price;
            let calPers=[];
            while (i < basePriceLists.length) {
                let bps = basePriceLists[i];//base prices
                i++;
                let bp = null;
                bps.forEach(basePrice => {
                    if (basePrice.applied_type == 'All') {
                        bp = basePrice;
                        return;
                    } else if (basePrice.applied_type == 'Category') {
                        let categoryId=product.category_id;
                        if (basePrice.applied_value == categoryId) {
                            bp = basePrice;
                            return;
                        }
                    } else if (basePrice.applied_type == 'Product') {
                        let productId=product.product_id;
                        if (basePrice.applied_value == productId) {
                            bp = basePrice;
                            return;
                        }
                    } else if (basePrice.applied_type == 'Variation') {
                        let variationId=product.variation_id;
                        if (basePrice.applied_value == variationId) {
                            bp = basePrice;
                            return;
                        }
                    }
                });
                if(bp){
                    if(bp.cal_type=='percentage'){
                        if(bp.id==priceStage.id){
                            calPers=[];
                        }else{
                            calPers=[bp.cal_value,...calPers];
                        }
                    }else{
                        if(calPers.length>0){
                            let currentPrcie=bp.cal_value;
                            calPers.forEach(per => {
                                percentagePrice=currentPrcie * (per/100);
                                currentPrcie=isNullOrNan(currentPrcie)+isNullOrNan(percentagePrice);
                            });
                        finalBasePrice= currentPrcie
                        }else{
                            finalBasePrice=bp.cal_value;
                        }
                        break;
                    }
                }
            }
            if(finalBasePrice !== null){
                percentagePrice=finalBasePrice * (priceStage.cal_value/100);
                price = isNullOrNan(finalBasePrice) + isNullOrNan(percentagePrice);

            }else{
                let lastIndexOfStock=product.stock.length-1;
                let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
                percentagePrice=refPrice * (priceStage.cal_value/100);
                price = isNullOrNan(refPrice) + isNullOrNan(percentagePrice);
            }
        }

        let qtyByPriceStage=isNullOrNan(priceStage.min_qty);
        // const uoms=product.uom.unit_category.uom_by_category;
        // let inputUomId=parentDom.find('.uom_select').val();
        // const inputUom =uoms.filter(function ($u) {
        //         return $u.id ==inputUomId;
        // })[0];
        let resultPrice=price;
        console.log(quantity , qtyByPriceStage);
        if(quantity >= qtyByPriceStage){
            console.log(currentPriceList);
            if(currentPriceList.currency_id != currentCurrency.id){
                console.log(currentPriceList.currency_id , currentCurrency.id);
                let fromCurrency=exchangeRates.find(xr=>xr.currency_id==currentPriceList.currency_id);
                let toCurrency=exchangeRates.find(xr=>xr.currency_id==currentCurrency.id);
                if(fromCurrency !=null && toCurrency != null){
                    let adjustCurrency = price / fromCurrency.rate;
                    let convertedAmount = adjustCurrency * toCurrency.rate ;
                    resultPrice = convertedAmount;
                }
            }
            console.log(pDecimal(resultPrice));
            parentDom.find('.price_input').val(pDecimal(resultPrice));
            parentDom.find('.price_txt').text(pDecimal(resultPrice));
            return true;
        }else{
            // if(dfs){
            //     let lastIndexOfStock=product.stock.length-1;
            //     let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
            //     let result=refPrice * isNullOrNan( inputUom.value);
            //     parentDom.find('.uom_price').val(result);
            //     console.log(pDecimal(result));
            // }
        }
        return false;
    }




    const checkAvailableDate = (pricelist) => {
        let availableDate = false;
        const from_date = pricelist.from_date === null ? null : new Date(pricelist.from_date);
        const to_date = pricelist.to_date === null ? null : new Date(pricelist.to_date);
        const current_date = new Date();
        if (current_date >= from_date && current_date <= to_date) {
            availableDate = true;
        } else if(from_date === null && to_date === null){
            availableDate = true;
        }else if(from_date === null && current_date <= to_date){
            availableDate = true;
        }else if(current_date >= from_date && to_date === null){
            availableDate = true;
        }else {
            availableDate = false;
        }
        return availableDate;
    }









    // validation

// Define form element
const form = document.getElementById('template_form');
    // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'template_type': {
                validators: {
                    notEmpty: {
                        message: 'Please select a template'
                    }
                }
            }
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('submit');
submitButton.addEventListener('click', function (e) {

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            if (status == 'Valid') {
                e.currentTarget=true;
                return true;
            } else {
                e.preventDefault();
                // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    }
});
</script>
@endpush
