<script src="{{ asset('customJs/debounce.js') }}"></script>
<script>
    var productsOnSelectData=[];
    $(document).ready(function () {
        var products;
        var allSelectedProduct=[];
        let unique_name_id=1;
        let products_length=$('#sale_table tbody tr').length-1;
        let productQty=[];
        let setting=@json($setting);
        let currency=@json($defaultCurrency);
        let currencies=@json($currencies);
        let locations=@json($locations);
        let defaultPriceListId={{$defaultPriceListId}}
        let lotControl=setting.lot_control;
        var suggestionProduct=[];
        function isNullOrNan(val){
            let v=parseFloat(val);
            if(v=='' || v==null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }
        let priceLists=@json($priceLists);
        let exchangeRates=@json($exchangeRates ?? []);
        let currentPriceList;
        var currentCurrency=@json($defaultCurrency);
        $('[name="contact_id"]').val(3).trigger('change');
        $('#currency_id').change(function(e){
            currentCurrency=currencies.find(c=>c.id==$(this).val());
            currentCurrencySymbol=currentCurrency.symbol;
            $('.currencySymbol').text(currentCurrencySymbol);
        })

        let editSaleDetails=@json($sale_details ?? []);
        let editSale=@json($sale ?? []);
        if (editSaleDetails.length>0) {
            editSaleDetails.forEach(function(saledetail,index){
                let secIndex;
                product= productsOnSelectData.find(function(pd,i) {
                    secIndex=i;
                    return saledetail.product_variation.id== pd.variation_id;
                });
                let uoms=getCurrentAndRefUom(saledetail.product.uom.unit_category.uom_by_category,saledetail.uom_id);
                let saleQty=0;
                if(uoms.currentUom){
                    saleQty=isNullOrNan(getReferenceUomInfoByCurrentUomQty(saledetail.quantity,uoms.currentUom,uoms.referenceUom)['qtyByReferenceUom']);
                }
                newProductData={
                    'product_id':saledetail.product.id,
                    'product_type':saledetail.product.product_type,
                    'variation_id':saledetail.product_variation.id,
                    'category_id':saledetail.product.category_id,
                    'defaultSellingPrices':saledetail.product_variation.default_selling_price,
                    'sellingPrices':saledetail.product_variation.uom_selling_price,
                    'total_current_stock_qty':editSale.status=='delivered' ? isNullOrNan(saledetail.stock_sum_current_quantity)+isNullOrNan(saleQty) :isNullOrNan(saledetail.stock_sum_current_quantity) ,
                    'validate':true,
                    'additional_product':saledetail.product_variation.additional_product,
                    'uom':saledetail.product.uom,
                    'uom_id':saledetail.uom_id,
                    'stock':saledetail.stock,
                };
                const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newProductData.id && p.variation_id === newProductData.product_variations.id);
                if(indexToReplace !== -1){
                    productsOnSelectData[indexToReplace] = newProductData;
                }else{
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }
            })
            let CurrentPriceListId=locations.find((location)=>location.id==editSale.business_location_id).price_lists_id;
            getPriceList(CurrentPriceListId);
            suggestionProductEvent();
            $('.price_list_input').val(CurrentPriceListId).trigger('change');
            editSaleDetails.forEach(function(sale,index){
                let uom=$(`[name="sale_details[${index}][uom_id]"]`);
                uom.select2();
                let uom_select=uom.val();
                getPrice(uom);
                changeQtyOnUom(uom,uom_select);
                // lineDiscountCalulation(uom);
                extraDiscCal();
            })
            let dia=document.querySelectorAll('.dialer_obj')
            dia.forEach(e => {
                let diaO = new KTDialer(e, {
                    min: 0,
                    step: 1,
                    decimals: 2
                });

                diaO.on('kt.dialer.change',function(ev) {
                    let unit=$('.unit_input').val();
                    // checkStockSaleQty($(ev.inputElement));
                    subtotalCalculation($(ev.inputElement));
                    sale_amount_cal();
                    cal_total_sale_amount();
                    checkStock($(ev.inputElement));
                    cal_balance_amount();
                    getPrice($(ev.inputElement));
                })
            });
                         $('.sale_row').hover(
                function() {
                    let unid=$(this).data('unid');
                    $(`[data-unid=${unid}]`).addClass('bg-light');
                },
                function() {
                    let unid=$(this).data('unid')
                    $(`[data-unid=${unid}]`).removeClass('bg-light')
                }
            );
        }

        unique_name_id+=products_length;

        $('#business_location_id').on('change',function(){
            $('#sale_table').find('tbody').empty();

        })
        // let rowCount = $('#sale_table tbody tr').length;

        // $('.total_item').text(rowCount-1);






        // for quick search
        let throttleTimeout;
        $('.quick-search-form input').on('input',debounce( function() {
            var query = $(this).val().trim();
            let business_location_id = $('#business_location_id').val();
            let data = {
                business_location_id,
                query //text from search bar
            }
            if (query.length >= 2) {
                $('.quick-search-results').removeClass('d-none');
                $('.quick-search-results').html(`
                <div class="quick-search-result result cursor-pointer">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
                </div>
                `);
                // clearTimeout(throttleTimeout);
                // throttleTimeout =
                setTimeout(function() {
                    $.ajax({
                        url: `/sell/get/product`,
                        type: 'GET',
                        delay: 150,
                        data: {
                            data,
                        },
                        error:function(e){
                            status=e.status;
                            if(status==405){
                                warning('Method Not Allow!');
                            }else if(status==419){
                                error('Session Expired')
                            }else{
                                console.log(e);
                                console.log(' Something Went Wrong! Error Status: '+status )
                            };
                        },
                        success: function(results){
                            // console.log(results);
                            products=results;
                                var html = '';
                                    if (results.length > 0) {
                                        results.forEach(function(result,key) {
                                            if(result.has_variation =='variable' && results.length== 2){
                                                return;
                                            }
                                            let total_current_stock_qty=Number(result.total_current_stock_qty);
                                            let css_class=result.total_current_stock_qty<=0 && result.product_type=="storable" ?" text-gray-600 order-3":'';

                                            html += `<div class="quick-search-result result  mt-1 mb-1 bg-hover-light p-2 ${css_class} " data-id=${key} data-name="${result.name}" style="z-index:300;">`;
                                            html += `<h4 class="fs-6  pt-3 ${css_class}">
                                                    ${result.name} ${result.has_variation==='variable'?'-('+result.product_variations.length+') select all' :result.sku??'' }`;
                                                    if(result.has_variation=='sub_variable'){
                                                        html +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">(${result.variation_name??''})</span>`;
                                                    }
                                            html+='</h4>'
                                            if(result.product_type=="storable"){
                                                if(result.total_current_stock_qty>0){
                                                    html += `<p>${total_current_stock_qty.toFixed()} ${result.uom.name}(s/es)</p>`;
                                                }else{
                                                    html += '<p>Out of Stocks</p>';
                                                }
                                            }
                                            html += '</div>';
                                    });
                                    if (results.length == 1 || (results[0].has_variation =='variable' && results.length== 2)) {
                                        $('.quick-search-results').show();
                                        if(results[0].total_current_stock_qty>0 || results[0].product_type!="storable"){
                                            setTimeout(() => {
                                                $(`.result[data-name|='${results[0].name}']`).click();
                                                $('.quick-search-results').hide();
                                            }, 100);
                                        }
                                    } else {
                                        $('.quick-search-results').show();
                                    }
                                } else{
                                    html = '<p>No results found.</p>';
                                    $('.quick-search-results').show();
                                }
                                $('.quick-search-results').removeClass('d-none')
                                $('.quick-search-results').html(html);
                                $(document).click(function(event) {
                                if (!$(event.target).closest('.quick-search-results').length) {
                                    $('.quick-search-results').addClass('d-none')
                                }
                                });


                        },
                    })
                },300)

            }else {
                $('.quick-search-results').addClass('d-none');
                $('.quick-search-results').empty();
            }
        }));
        $('input').off('focus').on('focus', function() {
            // Select the text in the input field
            $(this).select();
        });
        $('#autocomplete').on('click', '.result', function() {
            let id = $(this).attr('data-id');
            let selected_product= products[id];
            let isStorable=selected_product.product_type=="storable";
            if((selected_product.total_current_stock_qty==0 || selected_product.total_current_stock_qty==null) && isStorable){
                return;
            }

            $('.dataTables_empty').addClass('d-none');
            if(selected_product.has_variation==='variable')
            {
                let variation=selected_product.product_variations;
                variation.forEach(v => {
                    let t=products.filter(p=>{
                        return p.variation_id==v.id
                    });
                    append_row(t[0],false);
                    unique_name_id+=1;
                    $('#searchInput').focus();
                });
                return;
            }
            append_row(selected_product,false);
            unique_name_id+=1;
            $('#searchInput').focus();


        });
        function showSuggestion(additionalProduct,parentUniqueNameId,parentSaleDetailId=null) {
            if(additionalProduct.length>0){
                $('#suggestionProducts').html('');
                var modal = new bootstrap.Modal($('#suggestionModal'));
                additionalProduct.forEach(ap => {
                    let productInfo=ap.product_variation.product;
                    let product={name:productInfo.name,id:productInfo.id};
                    let variation_id=ap.product_variation.id;
                    let total_current_stock_qty=ap.total_current_stock_qty;
                    let variation_template_value=ap.product_variation.variation_template_value;
                    let qty=ap.quantity;
                    let uom=ap.uom;
                    let uomId=ap.uom_id;
                    let checkProduct =suggestionProduct.find((sp)=>{
                        return sp.variation_id==variation_id && sp.qty==qty && sp.uomId ==uomId;
                    })
                    if(!checkProduct){
                        // <i class="fa-solid fa-xmark-circle me-3"></i>
                        //  <span
                        //     class="btn w-auto min-w-175px text-center bg-light rounded-1 fw-semibold fs-7 pe-4 py-1 text-gray-800 shadow-md suggestionProduct"
                        //     data-productid="${product.id}" data-varid="${variation_id}" data-qty="${qty}"  data-uomid="${uomId}">
                        //      ${product.name} ${variation_template_value ? '(' + variation_template_value.name  + ')' :''} x ${qty} ${uom.short_name}
                        // </span>
                        let badge=`
                        <div class="position-relative main_div" data-productid="${product.id}" data-varid="${variation_id}" data-qty="${qty}"  data-uomid="${uomId}" data-parentSaleDetailId=${parentSaleDetailId}>
                            <div class="disappearing-div  cursor-pointer  border border-1 rounded px-2 py-3 d-flex mb-2 suggestionProduct sgp_${unique_name_id}" >
                                <div class="img bg-light w-50px h-50px rounded">

                                </div>
                                <div class="product-info ms-4 pt-1">
                                    <span class="fw-bold text-gray-800">${product.name} <span class="text-gray-700 fw-semibold">
                                        ${variation_template_value ? '(' + variation_template_value.name + ')' :''}</span></span>
                                    <span class="fw-bold text-gray-700 pt-2 d-block">Qty : <span class="text-gray-900"> ${ap.quantity} ${uom.short_name}</span></span>
                                </div>
                            </div>
                        </div>
                        `
                        $('#suggestionProducts').append(badge);
                        $('.main_div').off('click').on('click',function(){
                            let suggestionProductDiv=$(this);
                            let variationId=$(this).data('varid');
                            let dataUomId=$(this).data('uomid');
                            let productId=$(this).data('productid');
                            let parentSaleDetailId=$(this).data('parentsaledetailid');
                            let locationId=$('[name="business_location_id"]').val();
                            let qty=$(this).data('qty');

                            // $('.suggesstion_click_count').text(parseFloat($('.suggesstion_click_count').text())+1);
                            if(suggestionProduct.length >0){
                                // suggestionProduct.forEach((sp,i)=>{
                                //     if(sp.variation_id==variationId && sp.qty==qty && sp.uomId ==dataUomId){
                                //         suggestionProduct.splice(i,1);
                                //         return i;
                                //     }else{
                                //         return null;
                                //     }
                                // })
                            }
                            $.ajax({
                                url:'/sell/get/suggestion/product',
                                data:{
                                    locationId,
                                    variationId,
                                    productId
                                },
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
                                    append_row(results,false,qty,dataUomId,parentUniqueNameId,parentSaleDetailId);
                                    unique_name_id++;

                                }
                            })
                            // suggestionProductDiv.remove();
                            if($('.suggestionProduct').length <=0){
                                modal.hide();
                                $('.modal-backdrop').remove();
                            }

                        })
                        $(`.sgp_${unique_name_id}`).off('click').on('click',function(){
                            var clone=$(this).clone();
                                $(this).closest('.main_div').append(clone);
                                clone.css({
                                    "position":'absolute',
                                    "width":'100%',
                                    "animation-name": "example",
                                    "animation-duration": "0.5s",
                                });
                                setTimeout(()=>{
                                    clone.remove();
                                },400)
                        })

                        // suggestionProduct=[...suggestionProduct,{variation_id,qty,uomId}];
                    }

                });
                modal.show();
            }
        }


        //append table row for product to sell
        function append_row(selected_product,forceSplit=true,qty='1',suggestUom=null,parentUniqueNameId=false,parentSaleDetailId=null) {
            console.log(selected_product);
            allSelectedProduct[selected_product.product_variations.id]=selected_product;
            if(setting.enable_row == 0 && !forceSplit){
               let checkProduct= productsOnSelectData.find(p=>p.variation_id==selected_product.product_variations.id);
               if(checkProduct){
                    let selectQtyInput=document.querySelectorAll(`.quantity-${selected_product.product_variations.id}`);
                    let qtyInput=selectQtyInput[0];
                    let val=isNullOrNan(qtyInput.value);
                    qtyInput.value=val+1;
                    checkAndStoreSelectedProduct(selected_product);
                    $('.quick-search-results').addClass('d-none');
                    $('.quick-search-results').empty();
                    return;
               }
            }
            let default_purchase_price,variation_id;
            let isStorable=selected_product.product_type=="storable";

            let additionalProduct=selected_product.product_variations.additional_product;
            showSuggestion(additionalProduct,unique_name_id);
            // let uomSetOption=""
            let uomIds=[];
            // if the item is out of stock reutrn do nothing;
            if(selected_product.total_current_stock_qty==0 && isStorable){
                warning('Products are out of stock')
                return;
            }
            let uomByCategory=selected_product['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                    uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })
            var lotSelector;
            if(lotControl=="on"){
                let stock=selected_product.stock[0];
                let lotOption='';
                // selected_product.stock.forEach((s,key) => {
                //     lotOption+=` <option value="${s.id}" >Lot-${s.lot_serial_no ?? '-'}</option>`
                // });
                // lotSelector=`
                //     <td>
                //         <select name="sale_details[${unique_name_id}][stock_id_by_lot_serial_no]" id="" class="form-select form-select-sm mt-3 lot_no" data-lot-select-${unique_name_id}="select2"  data-hide-search="true">
                //             ${lotOption}
                //         </select>
                //     </td>
                //     `
            }else{
                // let value=selected_product.lot_serial_nos[0];


                // lotSelector=`

                //     <input type="hidden" name="sale_details[${unique_name_id}][stock_id_by_lot_serial_no]" class="lot_no" value="${value.id}">
                // `
            }
            variation=selected_product.product_variations;
            let packagingOption='';
            if(variation.packaging){
                variation.packaging.forEach((pk)=>{
                    packagingOption+=`
                        <option value="${pk.id}" data-qty="${pk.quantity}" data-uomid="${pk.uom_id}">${pk.packaging_name}</option>
                    `;
                })
            }
            $currentQtyText=isStorable ? `<span class="current_stock_qty_txt">${parseFloat(selected_product.total_current_stock_qty).toFixed(2)}</span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>(s/es)` : '';
            let splitRow=setting.enable_row != 1 ?`<i class="fa-solid fa-arrows-split-up-and-left  text-success p-2 pe-5 fs-6 pe-5 splitNewRow splitNewRow_${unique_name_id}" type="button"></i>`: '';
            var newRow = `
                <tr class="sale_row mt-2 sale_row_${unique_name_id}" data-unid="${parentUniqueNameId !=false ?parentUniqueNameId: unique_name_id}" data-product="${selected_product.product_variations.id}">
                    <td class="d-flex ps-2">

                        <div class="w-300px">
                            <span>${selected_product.name}</span>
                            <span class="text-primary fw-semibold fs-5">${selected_product.variation_name?'-'+selected_product.variation_name:''}</span>
                            <br>
                            ${$currentQtyText}
                            ${additionalProduct.length >0 ?
                                `
                                <div class="cursor-pointer me-1 suggestProductBtn text-decoration-underline text-primary user-select-none" data-varid="${selected_product.product_variations.id}"
                                    data-uniqueNameId="${unique_name_id}">
                                    Additional Product
                                </div>
                                <input type="hidden" value="${unique_name_id}" name="sale_details[${unique_name_id}][isParent]" />
                                `
                                :`
                                <input type="hidden" value="${parentUniqueNameId}" name="sale_details[${unique_name_id}][parentUniqueNameId]" />
                                <input type="hidden" value="${parentSaleDetailId}" name="sale_details[${unique_name_id}][parentSaleDetailId]" />
                                `
                                }
                        </div>
                    </td>
                    <td class="d-none">
                        <div>
                            <input type='hidden' value="${selected_product.id}" class="product_id"  name="sale_details[${unique_name_id}][product_id]"  />
                            <input type='hidden' value="${selected_product.product_variations.id}" class="variation_id" name="sale_details[${unique_name_id}][variation_id]"  />
                        </div>

                    </td>
                    <td>
                        <span class="text-danger-emphasis  stock_alert_${selected_product.product_variations.id} d-none fs-7 p-2">* Out of Stock</span>
                        <div class="input-group sale_dialer_${unique_name_id} w-200px" >
                            <button class="btn btn-sm btn-icon btn-outline btn-active-color-danger" type="button" data-kt-dialer-control="decrease">
                                <i class="fa-solid fa-minus fs-2"></i>
                            </button>
                            <input type="text" class="form-control form-control-sm quantity input_number form-control-sm quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="sale_details[${unique_name_id}][quantity]" value="${qty}" data-kt-dialer-control="input"/>
                            <button class="btn btn-sm btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
                                <i class="fa-solid fa-plus fs-2"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <select name="sale_details[${unique_name_id}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>

                        </select>
                    </td>
                    <td class="fv-row">
                        <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                            name="sale_details[${unique_name_id}][packaging_quantity]" value="1.00">
                    </td>
                    <td class="fv-row">
                        <select name="sale_details[${unique_name_id}][packaging_id]" class="form-select form-select-sm package_id"
                            data-kt-repeater="package_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                            data-placeholder="Select Package" placeholder="select Package" required>
                            <option value="">Select Package</option>
                            ${packagingOption}
                        </select>
                    </td>
                    <td class="fv-row">
                        <select   class="form-select form-select-sm price_group price_list w-180px" data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price" readonly disabled>
                            <option value="default_selling_price">defalut selling price</option>
                            @foreach ($priceLists as $PriceList)
                                <option value="{{$PriceList->id}}">{{$PriceList->name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" class="price_list_id" name="sale_details[${unique_name_id}][price_list_id]" value='default_selling_price'/>
                    </td>
                    <td class=" fv-row">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm uom_price input_number" value="0" name="sale_details[${unique_name_id}][uom_price]">
                            <span class="input-group-text currencySymbol">${currentCurrency.symbol}</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="subtotal form-control form-control-sm input_number" name="sale_details[${unique_name_id}][subtotal]" readonly >
                            <span class="input-group-text currencySymbol">${currentCurrency.symbol}</span>
                        </div>
                    </td>
                    <td class="{{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                        <select name="sale_details[${unique_name_id}][discount_type]" id="" class="form-select form-select-sm discount_type" data-kt-repeater="select2"  data-hide-search="true">
                            <option value="fixed">fixed</option>
                            <option value="percentage">Percentage</option>
                            <option value="foc">FOC</option>
                        </select>
                    </td>
                    <td class="{{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                        <input type="text" class="form-control form-control-sm per_item_discount" value="0" name="sale_details[${unique_name_id}][per_item_discount]" placeholder="Discount amount">
                        <div class='mt-3 d-none'>Discount : <span class="line_discount_txt">0</span>${currentCurrency.symbol}</div>
                        <input type="hidden" class="line_subtotal_discount" name="sale_details[${unique_name_id}][line_subtotal_discount]" value="0">
                        <input type="hidden" class="currency_id" name="sale_details[${unique_name_id}][currency_id]" value="0">
                    </td>
                    <th class="text-end">
                        <div class="d-flex  ${!setting.enable_row ? 'justify-content-around -items-center' :'align-items-center justify-content-end'}">
                            ${splitRow}
                            <i class="fa-solid fa-trash text-danger deleteRow" type="button"></i>
                        </div>
                    </th>
                </tr>
            `;
             $('.sale_row').hover(
                function() {
                    let unid=$(this).data('unid');
                    $(`[data-unid=${unid}]`).addClass('bg-light');
                },
                function() {
                    let unid=$(this).data('unid')
                    $(`[data-unid=${unid}]`).removeClass('bg-light')
                }
            );
            suggestionProductEvent();


            // new row append
            if(parentUniqueNameId == false){
                $('#sale_table tbody').prepend(newRow);
            }else{
                $(`.sale_row_${parentUniqueNameId}`).after(newRow);
            }
            $('.dataTables_empty').addClass('d-none');
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            numberOnly();
                $(`.splitNewRow_${unique_name_id}`).click(function () {
                    let parent = $(this).closest('.sale_row');
                    let variationId=parent.find('.variation_id').val();
                    let product=allSelectedProduct[variationId];
                    append_row(product,true);
                    unique_name_id+=1;
                });
            $('#searchInput').val('');
            checkAndStoreSelectedProduct(selected_product);
            let rowCount = $('#sale_table tbody tr').length;

            $('.total_item').text(rowCount-1);
            // searching disable in select 2
            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity});
            $(`[data-kt-repeater=package_select_${unique_name_id}]`).select2({
            minimumResultsForSearch: Infinity
            });
            // $(`[data-uomSet-select-${unique_name_id}="select2"]`).select2();
            $(`[data-lot-select-${unique_name_id}="select2"]`).select2({
                // data,
                minimumResultsForSearch: Infinity
                })
            // Dialer container element
            let name=`.sale_dialer_${unique_name_id}`;
            let dialerElement = document.querySelector(name);

            // Create dialer object and initialize a new instance
            let dialerObject = new KTDialer(dialerElement, {
                min: 0,
                step: 1,
                // decimals: 2
            });
                    // <td>
                    //     <div>
                    //         <span class="subtotal_discount fs-6 fw-semibold">0</span>${currency['symbol']}
                    //     </div>
                    // </td>
                    // <td>
                    //     <span class="final_sub_text fs-6 fw-semibold">0</span>
                    //     <input  type="hidden" value="0" class="sale_price form-control form-control-sm final_sub" name="sale_details[${unique_name_id}][final_sale_price]" />
                    // </td>
            dialerObject.on('kt.dialer.change',function(e) {
                // checkStock($(`[name="sale_details[${unique_name_id}][quantity]"]`));
                sale_amount_cal() ;
                cal_total_sale_amount();
                subtotalCalculation($(e.inputElement))
                checkStock($(e.inputElement));
                cal_balance_amount();
                getPrice($(e.inputElement));


            })
            $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
                minimumResultsForSearch: Infinity,
                data:uomsData,
            });
            // optionSelected(selected_product.lot_serial_nos[0],$(`[data-lot-select-${unique_name_id}="select2"]`));
            // optionSelected(selected_product.uom_id,$(`[name="purchase_details[${unique_name_id}][purchase_uom_id]"]`));
            if(suggestUom){
                optionSelected(suggestUom,$(`[name="sale_details[${unique_name_id}][uom_id]"]`));
            }else{
                optionSelected(selected_product.uom_id,$(`[name="sale_details[${unique_name_id}][uom_id]"]`));
            }

            // getLotByUom($(`[data-uomSet-select-${unique_name_id}="select2"]`));
            setTimeout(() => {
                sale_amount_cal() ;
                cal_total_sale_amount();
                cal_balance_amount();
                getPrice($(`[name="sale_details[${unique_name_id}][uom_id]"]`));
                // if(lotControl=="off"){
                //     $('.lot_no').val(selected_product.lot_serial_nos[0].id);
                // }
                // getSalePrice($(`[name="sale_details[${unique_name_id}][quantity]"]`));
            }, 100);


                // changeQtyOnUom($(`[name="sale_details[${unique_name_id}][quantity]"]`),selected_product.uom_id);

             if ($('#sale_table tbody tr').length > 1) {
                $('.deleteRow').removeClass('disable');
                $('.deleteRow').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }
            $('.price_group').select2({minimumResultsForSearch: Infinity});
            $('input').off('focus').on('focus', function() {
                // Select the text in the input field
                $(this).select();
                });
        }


    function suggestionProductEvent() {
            $('.suggestProductBtn').off('click').on('click',function(){
                let variationId=$(this).data('varid');
                let parentuiqId=$(this).data('uniquenameid');
                let parentSaleDetailId=$(this).data('parentsaledetailid')?? null;
                let product = productsOnSelectData.find(function(pd) {
                    return  variationId == pd.variation_id;
                });
                let additionalProduct=product.additional_product;
                showSuggestion(additionalProduct,parentuiqId,parentSaleDetailId);
            })

    }
    $(document).on('change','.package_id',function(){
        packaging($(this));
    })
    $(document).on('input','.quantity',function(){
        packaging($(this),'/');
    })
    $(document).on('change','.uom_select',function(){
        packaging($(this),'/');
    })
    $(document).on('input','.package_qty',function(){
        packaging($(this),'*');
    })
    const packaging=(e,operator)=>{
        let parent = $(e).closest('.sale_row');
        let unitQty=parent.find('.quantity').val();
        let selectedOption =parent.find('.package_id').find(':selected');
        let packageInputQty=parent.find('.package_qty').val();
        let packagingUom=selectedOption.data('uomid');
        let packageQtyForCal = selectedOption.data('qty');

        let currentUomId=parent.find('.uom_select').val();
        let variation_id=parent.find('.variation_id').val();
        let product=productsOnSelectData.find((pod)=>pod.variation_id==variation_id);
        let uoms=product.uom.unit_category.uom_by_category;
        if(packageQtyForCal && packagingUom){
            if(operator=='/'){
                // function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='')
                let unitQtyValByUom=changeQtyOnUom2(currentUomId,packagingUom,unitQty,uoms).resultQty;
                parent.find('.package_qty').val(qDecimal(isNullOrNan(unitQtyValByUom) / isNullOrNan(packageQtyForCal)));
            }else{
                // function changeQtyOnUom(uoms,currentQty,currentUomId,newUomId) {
                let result=isNullOrNan(packageQtyForCal) * isNullOrNan(packageInputQty);
                let qtyByCurrentUnit= changeQtyOnUom2(packagingUom,currentUomId,result,uoms).resultQty;
                parent.find('.quantity').val(qDecimal(qtyByCurrentUnit));
            }
        }
    }

    function optionSelected(value,select){
        // Set the value to be selected
        var valueToSelect = value;
        // Get the Select2 instance
        var $select = select;
        // Set the value
        $select.val(valueToSelect);
        // Get the option that corresponds to the value
        var $option = $select.find('option[value="' + valueToSelect + '"]');
        // Mark the option as selected
        $option.prop('selected', true);
        // Trigger the change event to update Select2
        $select.trigger('change');
    }

    function checkAndStoreSelectedProduct(newSelectedProduct) {
        let newProductData={
            'product_id':newSelectedProduct.id,
            'product_type':newSelectedProduct.product_type,
            'variation_id':newSelectedProduct.product_variations.id,
            'category_id':newSelectedProduct.category_id,
            'defaultSellingPrices':newSelectedProduct.product_variations.default_selling_price,
            'sellingPrices':newSelectedProduct.product_variations.uom_selling_price,
            'total_current_stock_qty':newSelectedProduct.total_current_stock_qty,
            'aviable_qty':newSelectedProduct.total_current_stock_qty,
            'validate':true,
            'uom':newSelectedProduct.uom,
            'uom_id':newSelectedProduct.uom_id,
            'additional_product':newSelectedProduct.product_variations.additional_product,
            'stock':newSelectedProduct.stock,
        };
        const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newSelectedProduct.id && p.variation_id === newSelectedProduct.product_variations.id);
        if(indexToReplace !== -1){
            productsOnSelectData[indexToReplace] = newProductData;
        }else{
            productsOnSelectData=[...productsOnSelectData,newProductData];
        }
    }

    function changeQtyOnUom(e,newUomId) {
       try {
            let parent = e.closest('.sale_row');
            let productId=parent.find('.product_id').val();
            let variationId=parent.find('.variation_id').val();
            let product = productsOnSelectData.filter(function(pd) {
                return productId == pd.product_id && variationId == pd.variation_id;
            });
            product=product[0];
            let qty=product.total_current_stock_qty;
            let result=changeQty(e,newUomId,qty,product)
            parent.find('.current_stock_qty_txt').text(result.roundedResult);
            parent.find('.smallest_unit_txt').text(result.newUomInfo.name);
       } catch (error) {
            console.log(error);
       }

    }
    function changeQty(e,newUomId,refQty,product){
            let parent = e.closest('.sale_row');;

            const uoms=product.uom.unit_category.uom_by_category;
            const newUomInfo = uoms.filter(function(nu){
                return nu.id==newUomId;
            })[0];
            const newUomType = newUomInfo.unit_type;

            const referenceUom =uoms.filter(function ($u) {
                return $u.unit_type == "reference";
            })[0];
            const refUomType =referenceUom.unit_type;
            const refUomId =referenceUom.id;

            let result=0;
            if (refUomType === 'reference' && newUomType === 'bigger') {
                result = refQty / newUomInfo.value;
            } else if (refUomType === 'reference' && newUomType === 'smaller') {
                result = refQty * newUomInfo.value;
            } else {
                result = refQty;
            }
            // result=result.toFixed(4)
            let rounded_amount=newUomInfo.rounded_amount ?? 2;
            let roundedResult= floor(isNullOrNan(result),rounded_amount) ;
            return {
                roundedResult,
                newUomInfo
            };
    }

    function getSellingPrice(e) {
            // let parent = e.closest('.sale_row');
            // let productId=parent.find('.product_id').val();
            // let variationId=parent.find('.variation_id').val()
            // let priceGpInput=parent.find('.price_group').val();
            // let uomId=parent.find('.uom_select').val();
            // let product = productsOnSelectData.filter(function(pd) {
            //     return productId == pd.product_id && variationId == pd.variation_id;
            // })[0];
            // if(priceGpInput!='default_selling_price'){
            //     let price=product.sellingPrices.filter(function(prices){
            //         return prices.uom_id==uomId && prices.pricegroup_id==priceGpInput
            //     })[0];
            //     if(price){
            //         parent.find('.uom_price').val(price.price_inc_tax ?? 0);
            //     }
            // }else{
            //     parent.find('.uom_price').val(product.defaultSellingPrices ?? 0);
            // }
            subtotalCalculation(e);
    }

    function getReferenceUomInfoByCurrentUomQty(qty, currentUom, referenceUom) {
        const currentUomType = currentUom.unit_type;
        const currentUomValue = currentUom.value;
        const referenceUomId = referenceUom.id;
        const referenceRoundedAmount = isNullOrNan(referenceUom.rounded_amount,4) ;
        const referenceValue = referenceUom.value;

        let result;
        if (currentUomType === 'reference') {
            result = qty * referenceValue;
        } else if (currentUomType === 'bigger') {
            result = qty * currentUomValue;
        } else if (currentUomType === 'smaller') {
            result = qty / currentUomValue;
        } else {
            result = qty;
        }
        let roundedResult=result;
        return {
            qtyByReferenceUom: roundedResult,
            referenceUomId: referenceUomId
        };
    }

    function checkStock(e){
        let parent = e.closest('.sale_row');
        let variationId=parent.find('.variation_id').val();
        let index;
        let product = productsOnSelectData.find(function(pd,i) {
            index=i;
            return  variationId == pd.variation_id;
        });

        const uoms=product.uom.unit_category.uom_by_category;
        const referenceUom =uoms.filter(function ($u) {
            return $u.unit_type == "reference";
        })[0];
        // let refQty=getReferenceUomInfoByCurrentUomQty(quantity,uom_select,referenceUom)['qtyByReferenceUom'];
        let result=0;
        $(`.quantity-${variationId}`).each(function(){
            let parent =  $(this).closest('.sale_row');
            let quantity=Number(parent.find('.quantity').val());
            let uom_id=Number(parent.find('.uom_select').val());
            const currentUom =uoms.filter(function ($u) {
                return $u.id ==uom_id;
            })[0];
            let refQty=getReferenceUomInfoByCurrentUomQty(quantity,currentUom,referenceUom)['qtyByReferenceUom'];
            let saleQty=getReferenceUomInfoByCurrentUomQty(quantity,currentUom,referenceUom)['qtyByReferenceUom'];
            result+=isNullOrNan(refQty)

        })
        if(product.product_type =='storable'){
            if(result > productsOnSelectData[index].total_current_stock_qty){
                        productsOnSelectData[index].validate=false;
                $(`.stock_alert_${variationId}`).removeClass('d-none');
            }else{
                productsOnSelectData[index].validate=true;
                $(`.stock_alert_${variationId}`).addClass('d-none');
            }
        }

    }

    function getCurrentAndRefUom(uoms,currentUomId){
        const currentUom =uoms.filter(function ($u) {
            return $u.id ==currentUomId;
        })[0];
        const referenceUom =uoms.filter(function ($u) {
            return $u.unit_type == "reference";
        })[0];
        return {currentUom,referenceUom};
    }



// ----------------------------------------------------------------   Start::Sale Calculation  -----------------------------------------------------------

    //============================================================== Start: Event to use calculation function ===========================================


        $(document).on('change','.lot_no',function (e) {
            let parent = $(this).closest('.sale_row');
            let uom_select=parent.find('.uom_select').val();
            changeQtyOnUom($(this),uom_select);
            getSellingPrice($(this));
            subtotalCalculation($(this));
        })

        $(document).on('input','.uom_price,.discount_type,.per_item_discount',function(e) {
            sale_amount_cal() ;
            cal_total_sale_amount();
            cal_balance_amount();
            subtotalCalculation($(this));
            lineDiscountCalulation($(this));

        })

    function setFoc(e) {
        let parent = e.closest('.sale_row');
        let discount_type=parent.find('.discount_type').val();
        let uomPrice=parent.find('.uom_price');
        let subtotal=parent.find('.subtotal');
        let discount_amount=parent.find('.discount_amount');
        if(discount_type=='foc'){
            uomPrice.val(0);
            subtotal.val(0);
            uomPrice.attr('disabled',true);
            subtotal.attr('disabled',true);
            subtotal.attr('disabled',true);
        }else{
            uomPrice.attr('disabled',false);
            subtotal.attr('disabled',false);
            discount_amount.attr('disabled',false);
        }


    }
        $(document).on('change','.discount_type',function(e) {
            setFoc($(this));
            sale_amount_cal() ;
            cal_total_sale_amount();
            cal_balance_amount();
            subtotalCalculation($(this));
            lineDiscountCalulation($(this));
        })
        $(document).on('change','.uom_select',function(e){
            changeQtyOnUom($(this),$(this).val());
            getSellingPrice($(this));
            checkStock($(this));
            getPrice($(this));
            subtotalCalculation($(this));
            sale_amount_cal();
            setTimeout(() => {
                cal_total_sale_amount();
            }, 200);
        })



        // Attach click event listener to all delete buttons in the table
        $(document).on('click', '#sale_table .deleteRow', function (e) {
                if ($('#sale_table tbody tr').length-1 == 1) {
                    $(this).css({
                        'cursor': 'not-allowed',
                        'opacity': 0.5
                    });
                    event.preventDefault();
                    return false;
                }
                e.preventDefault();
                        // let id = $(this).data('id');
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You want to remove it!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#f1416c',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                // Get the parent row (tr) of the clicked button
                                var row = $(this).closest('tr');
                                // Get the data-id attribute value of the row
                                var id = row.attr('data-id');
                                // Get the data in the row
                                var name = row.find('td[data-id="' + id + '"]').text();

                                // Do something with the data, e.g. display in console

                                // Remove the row from the table
                                var rowCount = $('#sale_table tbody tr').length;
                                if (rowCount == 2) {
                                    $('.dataTables_empty').removeClass('d-none');
                                }
                                row.remove();
                                checkStock($(this));
                                rowCount = $('#sale_table tbody tr').length;
                                $('.total_item').text(rowCount-1);
                            }
                        });
        });

        $(document).on('input','.extra_discount_amount',function(){
            cal_total_sale_amount();
            cal_balance_amount();
            sale_amount_cal();
        })
        $(document).on('change','.extra_discount_type',function(){
            cal_total_sale_amount();
            cal_balance_amount();
            sale_amount_cal()
        })
        $(document).on('input','.paid_amount_input',function(){
            cal_balance_amount();

        })
        $(document).on('change','.price_group',function(){
            getSellingPrice($(this));
            sale_amount_cal();

            cal_total_sale_amount();
        })
        $(document).on('change','.unit_input',function(){
            let parent=$(this).closest('.sale_row');
            let unit_selcted_txt=parent.find('.unit_input option:selected').text();
            let smallest_unit_txt=parent.find('.smallest_unit_txt');
            smallest_unit_txt.text(unit_selcted_txt);

            sale_amount_cal();


            checkStock($(this));
            cal_total_sale_amount();
            // getSalePrice($(this));
            // getCurrentQtyOnUnit($(this));
        })
        $(document).on('change','.uom_set',function () {
            getLotByUom($(this));
            cal_total_sale_amount();
        })


    //
    //  This calculation is to calculate subtotal (quantity * uom_price)
    //
    function subtotalCalculation(e) {
        let parent = e.closest('.sale_row');
        let disType=parent.find('.discount_type').val();
        if(disType!='foc'){
            let quantity=isNullOrNan(parent.find('.quantity').val());
            let uom_price=isNullOrNan(parent.find('.uom_price').val());
            let subtotal=parent.find('.subtotal');
            subtotal.val(uom_price * quantity);
            lineDiscountCalulation(e);
        }else{
           setFoc(e);
        }
    }

    //
    //  This calculation is to calculate line discount amout to cal discount total amount
    //
    function lineDiscountCalulation(e) {
        let parent = e.closest('.sale_row');
        let quantity=isNullOrNan(parent.find('.quantity').val());
        let uom_price=isNullOrNan(parent.find('.uom_price').val());
        let discount_type=parent.find('.discount_type').val();
        let per_item_discount=isNullOrNan(parent.find('.per_item_discount').val());
        let line_discount_txt=parent.find('.line_discount_txt');
        let line_subtotal_discount=parent.find('.line_subtotal_discount');
        if(discount_type == 'fixed'){

            price_with_discount=uom_price - per_item_discount;
            disAmount=per_item_discount;
            line_discount_txt.text(disAmount);
            line_subtotal_discount.val(disAmount * quantity);

        }else if(discount_type=='percentage'){

            disAmount=uom_price * (per_item_discount/100);
            price_with_discount=(uom_price - disAmount);
            line_discount_txt.text(disAmount);
            line_subtotal_discount.val(disAmount * quantity);

        }
        totalLineDisAmountCal();
    }


    //
    //  This calculation is to calculate Total Item Discount (total line discount)
    //
    function totalLineDisAmountCal() {
        let total_line_amount=0;
        $('.line_subtotal_discount').each(function() {
            var value = isNullOrNan($(this).val());
            total_line_amount += value;
        });
        $('.total_item_discount').val(total_line_amount)
        sale_amount_cal();
    }

    //
    //  This calculation is to calculate sale total amount
    //
    function cal_total_sale_amount() {
        setTimeout(() => {

            let sale_amount=isNullOrNan($('.sale_amount_input').val());
            let total_item_discount=isNullOrNan($('.total_item_discount').val())
            let extra_discount_type=$('.extra_discount_type').val();
            let price_after_discount;
            let extra_discount=extraDiscCal();
            price_after_discount=sale_amount - (extra_discount+total_item_discount);

            $('.total_sale_amount').val(price_after_discount);

        }, 110);

    }
    function extraDiscCal(){
        let subtotal=isNullOrNan($('.subtotal').val());
        let extra_discount_type=$('.extra_discount_type').val();
        let extra_discount_amount=isNullOrNan($('.extra_discount_amount').val());
        let extraDiscount;
        if(extra_discount_type == 'fixed'){
            $('.extra_discount').val(extra_discount_amount);
            extraDiscount=extra_discount_amount;
        }else if(extra_discount_type =='percentage'){
            percentage_amount=subtotal * (extra_discount_amount/100);
            $('.extra_discount').val(percentage_amount);
            extraDiscount=percentage_amount;
        }
        return extraDiscount;
    }
    //
    //  This calculation is to calculate  sale amount that means sum off all sale amount
    //
    function sale_amount_cal() {
        setTimeout(() => {
            let total=0;
            $('.subtotal').each(function() {
                var value = isNullOrNan($(this).val());
                total += value;
            });
            $('.sale_amount_input').val(total);
        }, 100);
    }


    //
    //  This calculation is to calculate  cal balance amount
    //
    function cal_balance_amount() {
        setTimeout(() => {
            let total_sale_amount=isNullOrNan($('.total_sale_amount').val());
            let paid_amount_input=isNullOrNan($('.paid_amount_input').val());
            let balance_amount_input=$('.balance_amount_input');
            let sale_amount_input=isNullOrNan($('.sale_amount_input').val());
            let balance_amount_result;

            balance_amount_result=total_sale_amount-paid_amount_input;
            balance_amount_input.val(balance_amount_result);

        }, 200);

    }


// ----------------------------------------------------------------   End::Sale Calculation  -----------------------------------------------------------






    $('[name="business_location_id"]').on('change',function(){
        limitStatusBylocation($(this).val());
        let CurrentPriceListId=locations.find((location)=>location.id==$(this).val()).price_lists_id;
        $('[name="price_list"]').val(CurrentPriceListId).trigger('change');
        getPriceList(CurrentPriceListId);
    });

    function limitStatusBylocation($id) {
        let location=locations.filter(l => {
            return l.id==$id;
        })[0];
        if(location.allow_sale_order=='1'){
            $('[data-status="filter"] option:disabled').prop('disabled', false);
            $('[data-status="filter"] option[value="delivered"]').prop('disabled', true);
            // $('[data-status="filter"] option[value="confirmed"]').prop('disabled', true);
            $('#saleStatus').val('order').trigger('change');
        }else{
            $('[data-status="filter"] option:disabled').prop('disabled', false);
            // $('[data-status="filter"] option[value="partial"]').prop('disabled', true);
            $('#saleStatus').val('delivered').trigger('change');
        }

    }



    var priceList;
    getPriceList(locations[0].price_lists_id);
    $('[name="price_list"]').val(locations[0].price_lists_id ?? defaultPriceListId).trigger('change');
    $('[name="price_list"]').change(function(){
        currentPriceList=priceLists.find((p)=>{
            return p.id == $(this).val();
        });
        getPriceList($(this).val());
    })
    $('[name="contact_id"]').change(function(){
        let selectedOption = $(this).find("option:selected");
        let priceList = selectedOption.attr("priceList") ?? defaultPriceListId;
        if(priceList && priceList != 'default_selling_price'){
            $('[name="price_list"]').val(priceList).trigger('change');
            getPriceList(priceList);
        }
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
            let parent = e.closest('tr');
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
                    let result=getPriceByUOM(parent,product,'',product?product.defaultSellingPrices:0);
                    let price=isNullOrNan(result.resultPrice);
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
        })[0];
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
        let quantity=isNullOrNan(parentDom.find('.quantity').val());
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
        let resultAfterUomChange=getPriceByUOM(parentDom,product,priceStage.min_qty,price);
        let qtyByPriceStage=resultAfterUomChange.resultQty;
        const uoms=product.uom.unit_category.uom_by_category;
        let inputUomId=parentDom.find('.uom_select').val();
        const inputUom =uoms.filter(function ($u) {
                return $u.id ==inputUomId;
        })[0];
        let resultPrice=resultAfterUomChange.resultPrice;
        if(quantity >= qtyByPriceStage){
            if(currentPriceList.currency_id != currentCurrency.id){
                let fromCurrency=exchangeRates.find(xr=>xr.currency_id==currentPriceList.currency_id);
                let toCurrency=exchangeRates.find(xr=>xr.currency_id==currentCurrency.id);
                if(fromCurrency !=null && toCurrency != null){
                    let adjustCurrency = price / fromCurrency.rate;
                    let convertedAmount = adjustCurrency * toCurrency.rate ;
                    resultPrice = convertedAmount;
                }
            }
            parentDom.find('.uom_price').val(pDecimal(resultPrice));
            parentDom.find('.price_list').val(priceStage.pricelist_id).trigger('change');
            parentDom.find('.price_list_id').val(priceStage.id);
            return true;
        }else{
            if(dfs){
                let lastIndexOfStock=product.stock.length-1;
                let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
                let result=refPrice * isNullOrNan( inputUom.value);
                parentDom.find('.uom_price').val(result);
            }
        }
        return false;
    }
    function getPriceByUOM(parentDom,product,priceStageQty=1,priceStageCalVal=''){
        if(product){
            const uoms=product.uom.unit_category.uom_by_category;
            let inputUomId=parentDom.find('.uom_select').val();
            let uomIdForSale=product.uom_id;
            const uomForSale =uoms.filter(function ($u) {
            return $u.id ==uomIdForSale;
            })[0];

            const refUOM =uoms.filter(function ($u) {
            return $u.unit_type =="reference";
            })[0];

            return changeQtyOnUom2(uomIdForSale,inputUomId,priceStageQty,uoms,priceStageCalVal);
        }else{
            return {resultPrice:0};
        }
    }


    function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='') {
        let newUomInfo = uoms.find((uomItem) => uomItem.id == newUomId);
        let currentUomInfo = uoms.find((uomItem) => uomItem.id == currentUomId);
        console.log(newUomInfo,currentUomInfo,newUomId,currentUomId,'--');
        let refUomInfo = uoms.find((uomItem) => uomItem.unit_type =="reference");
        let currentRefQty = isNullOrNan(getReferenceUomInfoByCurrentUomQty(currentQty,currentUomInfo,refUomInfo).qtyByReferenceUom);
        let currentUomType = currentUomInfo.unit_type;
        let newUomType = newUomInfo.unit_type;
        let newUomRounded = newUomInfo.rounded_amount || 1;
        let newUomValue=newUomInfo.value;
        let currentUomValue=currentUomInfo.value;
        let resultQty;
        let resultPrice;

        if ( newUomType == 'bigger') {
            resultQty = currentRefQty / newUomInfo.value;
        } else if (newUomType == 'smaller') {
            resultQty = currentRefQty * newUomInfo.value;
        } else {
            resultQty = currentRefQty;
        }
        resultPrice='';
        if(currentUomPrice != ''){
            if(currentUomId==newUomId){
                resultPrice = currentUomPrice ;
                return {resultQty,resultPrice};
            }
            if (currentUomType == 'reference' && newUomType == 'smaller') {
                resultPrice = currentUomPrice /(newUomInfo.value * currentUomInfo.value);
            }else if (currentUomType == 'reference' && newUomType == 'bigger') {
                resultPrice = currentUomPrice * newUomValue;
            }else if (currentUomType == 'bigger' && newUomType == 'reference') {
                resultPrice = currentUomPrice / currentUomValue;
            }else if (currentUomType == 'bigger' && newUomType == 'bigger') {
                resultPrice = currentUomPrice *( newUomInfo / currentUomValue);
            }else if (currentUomType == 'smaller' && newUomType == 'bigger') {
                resultPrice = currentUomPrice * (currentUomValue * newUomValue) ;
            }else if (currentUomType == 'smaller' && newUomType == 'smaller') {
                resultPrice = currentUomPrice / newUomValue;
            }else if (currentUomType == 'smaller' && newUomType == 'reference') {
                resultPrice = currentUomPrice * currentUomValue ;
            }else{
                resultPrice = currentUomPrice  ;
            }
        }
        return {resultQty,resultPrice};
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
  });
</script>


{{--
    bigger =>bigger = pass;
    bigger =>reference = pass;
    reference=>bigger =pass;

    smaller=>smaller = pass
    smaller=>bigger = pass
    reference=>smaller =pass;
    smaller=>reference =pass;
    --}}
