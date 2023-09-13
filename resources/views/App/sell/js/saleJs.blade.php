
<script>
    var productsOnSelectData=[];
    $(document).ready(function () {
        var products;
        let unique_name_id=1;
        let products_length=$('#sale_table tbody tr').length-1;
        let productQty=[];
        let setting=@json($setting);
        let currency=@json($defaultCurrency);
        let currencies=@json($currencies);
        let locations=@json($locations);
        let lotControl=setting.lot_control;
        let priceLists=@json($priceLists);
        let exchangeRates=@json($exchangeRates ?? []);
        let currentPriceList;
        var currentCurrency=@json($defaultCurrency);
        $('#currency_id').change(function(e){
            currentCurrency=currencies.find(c=>c.id==$(this).val());
            currentCurrencySymbol=currentCurrency.symbol;
            $('.currencySymbol').text(currentCurrencySymbol);
        })

        let editSaleDetails=@json($sale_details ?? []);
        let editSale=@json($sale ?? []);
        if (editSaleDetails.length>0) {
            editSaleDetails.forEach(function(sale,index){
                let secIndex;
                product= productsOnSelectData.find(function(pd,i) {
                    secIndex=i;
                    return sale.product_variation.id== pd.variation_id;
                });
                let uoms=getCurrentAndRefUom(sale.product.uom.unit_category.uom_by_category,sale.uom_id);
                let saleQty=0;
                if(uoms.currentUom){
                    saleQty=isNullOrNan(getReferenceUomInfoByCurrentUomQty(sale.quantity,uoms.currentUom,uoms.referenceUom)['qtyByReferenceUom']);
                }
                if(!product){
                    newProductData={
                        'product_id':sale.product.id,
                        'product_type':sale.product.product_type,
                        'variation_id':sale.product_variation.id,
                        'category_id':sale.product.category_id,
                        'defaultSellingPrices':sale.product_variation.default_selling_price,
                        'sellingPrices':sale.product_variation.uom_selling_price,
                        'total_current_stock_qty':editSale.status=='delivered' ? isNullOrNan(sale.stock_sum_current_quantity)+isNullOrNan(saleQty) :isNullOrNan(sale.stock_sum_current_quantity) ,
                        'validate':true,
                        'uom':sale.product.uom,
                        'uom_id':sale.uom_id,
                        'stock':sale.stock,
                    };
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }else{
                    if(editSale.status=='delivered'){
                        productsOnSelectData[secIndex].total_current_stock_qty=isNullOrNan(productsOnSelectData[secIndex].total_current_stock_qty)+ saleQty;
                    }
                }
            })
            let CurrentPriceListId=locations.find((location)=>location.id==editSale.business_location_id).price_lists_id;
            getPriceList(CurrentPriceListId);
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
                    // decimals: 2
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

        }

        unique_name_id+=products_length;

        $('#business_location_id').on('change',function(){
            $('#sale_table').find('tbody').empty();

        })
        // let rowCount = $('#sale_table tbody tr').length;

        // $('.total_item').text(rowCount-1);






        // for quick search
        let throttleTimeout;
        $('.quick-search-form input').on('input', function() {
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
                clearTimeout(throttleTimeout);
                throttleTimeout = setTimeout(function() {
                    $.ajax({
                        url: `/sell/get/product`,
                        type: 'POST',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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
                                    if (results.length == 1) {
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
        });
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

                    append_row(t[0],unique_name_id);
                    unique_name_id+=1;
                });
                return;
            }
            append_row(selected_product,unique_name_id);
            unique_name_id+=1;
            $('#searchInput').focus();


        });

        //append table row for product to sell
        function append_row(selected_product,unique_name_id) {
            if(setting.enable_row == 0){
               let checkProduct= productsOnSelectData.find(p=>p.variation_id==selected_product.product_variations.id);
               if(checkProduct){
                    // let ParentRow=$(`[data-product=${selected_product.product_variations.id}]`);
                    let selectQtyInput=document.querySelectorAll(`.quantity-${selected_product.product_variations.id}`);
                    let qtyInput=selectQtyInput[selectQtyInput.length-1];
                    let val=isNullOrNan(qtyInput.value);
                    qtyInput.value=val+1;
                    checkAndStoreSelectedProduct(selected_product);
                    return;
               }
            }
            let default_purchase_price,variation_id;
            let isStorable=selected_product.product_type=="storable";
            // let uomSetOption=""
            let uomIds=[];
            // if the item is out of stock reutrn do nothing;
            if(selected_product.total_current_stock_qty==0 && isStorable){
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
            $currentQtyText=isStorable ? `<span class="current_stock_qty_txt">${parseFloat(selected_product.total_current_stock_qty).toFixed(2)}</span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>(s/es)` : '';
            var newRow = `
                <tr class="sale_row mt-2" data-product="${selected_product.product_variations.id}">
                    <td>
                        <div class="w-250px">
                            <span>${selected_product.name}</span>
                            <span class="text-primary fw-semibold fs-5">${selected_product.variation_name?'-'+selected_product.variation_name:''}</span>
                            <br>
                            ${$currentQtyText}
                        </div>
                    </td>
                    <td class="d-none">
                        <div>
                            <input type='hidden' value="${selected_product.id}" class="product_id"  name="sale_details[${unique_name_id}][product_id]"  />
                            <input type='hidden' value="${selected_product.product_variations.id}" class="variation_id" name="sale_details[${unique_name_id}][variation_id]"  />
                            @if ($setting->lot_control=='on')
                                <input type='hidden' value="0" class="uom_set_id"  />
                            @else
                                <input type='hidden' value="${isStorable ? selected_product.stock[0].id :null}" class="uom_set_id"  />
                            @endif
                        </div>

                    </td>
                    <td>
                        <span class="text-danger-emphasis  stock_alert_${selected_product.product_variations.id} d-none fs-7 p-2">* Out of Stock</span>
                        <div class="input-group sale_dialer_${unique_name_id} w-200px" >
                            <button class="btn btn-sm btn-icon btn-outline btn-active-color-danger" type="button" data-kt-dialer-control="decrease">
                                <i class="fa-solid fa-minus fs-2"></i>
                            </button>
                            <input type="text" class="form-control form-control-sm quantity input_number form-control-sm quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="sale_details[${unique_name_id}][quantity]" value="1" data-kt-dialer-control="input"/>
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
                        </select>
                    </td>
                    <td class="{{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                        <input type="text" class="form-control form-control-sm per_item_discount" value="0" name="sale_details[${unique_name_id}][per_item_discount]" placeholder="Discount amount">
                        <div class='mt-3 d-none'>Discount : <span class="line_discount_txt">0</span>${currentCurrency.symbol}</div>
                        <input type="hidden" class="line_subtotal_discount" name="sale_details[${unique_name_id}][line_subtotal_discount]" value="0">
                        <input type="hidden" class="currency_id" name="sale_details[${unique_name_id}][currency_id]" value="0">
                    </td>
                    <th class="text-end">
                        <div class="d-flex justify-content-around align-items-center">
                            <i class="fa-solid fa-arrows-split-up-and-left fa-rotate-270 text-success p-2 pe-5 fs-6 pe-5 splitNewRow" type="button"></i>
                            <i class="fa-solid fa-trash text-danger deleteRow" type="button"></i>
                        </div>
                    </th>
                </tr>
            `;


            // new row append
            $('#sale_table tbody').prepend(newRow);
            $('.dataTables_empty').addClass('d-none');
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            numberOnly();
                $('.splitNewRow').click(function () {
                    let parent = $(this).closest('.sale_row');
                    parent.clone().appendTo(".saleDetailItems");
                    console.log(parent);
                });
            $('#searchInput').val('');
            checkAndStoreSelectedProduct(selected_product);
            let rowCount = $('#sale_table tbody tr').length;

            $('.total_item').text(rowCount-1);
            // searching disable in select 2
            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity});
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
            optionSelected(selected_product.uom_id,$(`[name="sale_details[${unique_name_id}][uom_id]"]`));

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
            'stock':newSelectedProduct.stock,
        };
        if(productsOnSelectData.length>0){
            let fileterProduct=productsOnSelectData.filter(function(p){
                return p.product_id==newSelectedProduct.id && p.variation_id==newSelectedProduct.product_variations.id

            })[0];
            if(fileterProduct){
                return
            }else{
                productsOnSelectData=[...productsOnSelectData,newProductData];
            }
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
        $(document).on('change','.discount_type',function(e) {
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
        let quantity=isNullOrNan(parent.find('.quantity').val());
        let uom_price=isNullOrNan(parent.find('.uom_price').val());
        let subtotal=parent.find('.subtotal');
        subtotal.val(uom_price * quantity);
        lineDiscountCalulation(e);
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



    function isNullOrNan(val){
        let v=parseFloat(val);
        if(v=='' || v==null || isNaN(v)){
            return 0;
        }else{
            return v;
        }
    }



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
    $('[name="price_list"]').val(locations[0].price_lists_id).trigger('change');
    $('[name="price_list"]').change(function(){
        currentPriceList=priceLists.find((p)=>{
            return p.id == $(this).val();
        });
        getPriceList($(this).val());
    })
    $('[name="contact_id"]').change(function(){
        let selectedOption = $(this).find("option:selected");
        let priceList = selectedOption.attr("priceList");
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
                    return;
                }
                mainPriceStatus = priceSetting(mainPriceList, parent);
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
                                return;
                            }
                            priceStatus = priceSetting(bp, parent);
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
                    let result=getPriceByUOM(parent,product,'',product.defaultSellingPrices);
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
    function priceSetting(priceStage,parentDom){
        let productId=parentDom.find('.product_id').val();
        let variationId=parentDom.find('.variation_id').val();
        let product=productsOnSelectData.filter(function(p){
            return p.product_id==productId && variationId == p.variation_id;
        })[0];
        if(priceStage.applied_type=='All'){
            if(!priceSettingToUi(priceStage,parentDom,product)){
                return false
            }else{
                return true;
            };
        }else if(priceStage.applied_type=='Category'){
            let categoryId=product.category_id;
            if(priceStage.applied_value==categoryId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                    return true;
                };
            }
        }else if(priceStage.applied_type=='Product'){
            if(priceStage.applied_value==productId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                return true;
            };
            }
        }else if(priceStage.applied_type=='Variation'){
            if(priceStage.applied_value==variationId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                    return true;
                };
            }
        }else{
            return false;
        }
    }
    function priceSettingToUi(priceStage,parentDom,product){
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
            console.log(fromCurrency,toCurrency);
            }
            parentDom.find('.uom_price').val(pDecimal(resultPrice));
            parentDom.find('.price_list').val(priceStage.pricelist_id).trigger('change');
            parentDom.find('.price_list_id').val(priceStage.id);
            return true;
        }else{
            let lastIndexOfStock=product.stock.length-1;
            let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
            let result=refPrice * isNullOrNan( inputUom.value);
            parentDom.find('.uom_price').val(result);
        }
        return false;
    }
    function getPriceByUOM(parentDom,product,priceStageQty=1,priceStageCalVal=''){
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
    }


    function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='') {
        let newUomInfo = uoms.find((uomItem) => uomItem.id == newUomId);
        let currentUomInfo = uoms.find((uomItem) => uomItem.id == currentUomId);
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
