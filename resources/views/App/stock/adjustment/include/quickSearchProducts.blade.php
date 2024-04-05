<script src="{{ asset('customJs/debounce.js') }}"></script>
<script>
    var productsOnSelectData=[];
    $(document).ready(function () {
        var products;
        let unique_name_id=1;
        var searchTypeBtn = $('#search-input-type');
        var searchType = "Keyword";
        let products_length=$('#adjustment_table tbody tr').length-1;

        //Edit Session
        let editAdjustmentDetails=@json($adjustment_details ?? []);
        let editTransfer=@json($stockAdjustment ?? []);
        console.log(editAdjustmentDetails);
        if (editAdjustmentDetails.length>0) {
            editAdjustmentDetails.forEach(function(detail,index){

                let secIndex;
                product= productsOnSelectData.find(function(pd,i) {
                    secIndex=i;
                    return detail.product_variation.id== pd.variation_id;
                });
                let uoms=getCurrentAndRefUom(detail.product.uom.unit_category.uom_by_category,detail.uom_id)
                let adjQty=isNullOrNan(getReferenceUomInfoByCurrentUomQty(detail.quantity,uoms.currentUom,uoms.referenceUom)['qtyByReferenceUom']);
                if(!product){
                    newProductData={
                        'product_id':detail.product_id,
                        'variation_id':detail.variation_id,
                        'total_current_stock_qty':detail.stock_sum_current_quantity,
                        'validate':true,
                        'uom':detail.uom,
                        'uom_id':detail.uom_id,
                        'stock':detail.stock,
                    };
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }else{
                    if(detail.status=='delivered'){
                        productsOnSelectData[secIndex].total_current_stock_qty=isNullOrNan(productsOnSelectData[secIndex].total_current_stock_qty)+ adjQty;
                    }
                }
            })
            //Edit Session

            editAdjustmentDetails.forEach(function(detail,index){
                let uom=$(`[name="adjustment_details[${index}][uom_id]"]`);
                let initPackage = $(`[name="adjustment_details[${index}][packaging_id]"]`);
                initPackage.select2();
                uom.select2();
                let uom_select=uom.val();


                changeQtyOnUom(uom,uom_select);
            })


        }



        unique_name_id+=products_length;



        $('#business_location_id').on('change',function(){
            $('#adjustment_table').find('tbody').empty();
            productsOnSelectData = [];
            $('#searchInput').prop('disabled', false);
        })
        if (editAdjustmentDetails.length < 0) {
            $('#searchInput').prop('disabled', true);
        }



        searchTypeBtn.on('click', function (){
            searchType = $(this).text();

            if(searchType === 'Keyword'){
                $(this).text('Serial');
                searchType = $(this).text();
                $('#searchInput').val('');
                $('.search-keyword-block').addClass('d-none');
            }else {
                $(this).text('Keyword');
                searchType = $(this).text();
                $('#searchInput').val('');
                $('.search-keyword-block').removeClass('d-none');
            }

        })



        // for quick search
        let throttleTimeout;
        $('.quick-search-form input').on('input', debounce(function() {

            const query = $(this).val().trim();
            const search_type = searchType;
            const business_location_id = $('#business_location_id').val();
            const psku_kw = $('#psku_kw').is(':checked');
            const vsku_kw = $('#vsku_kw').is(':checked');
            const pgbc_kw = $('#pgbc_kw').is(':checked');

            const data = {
                business_location_id,
                search_type,
                query,
                psku_kw,
                vsku_kw,
                pgbc_kw
            };

            if (query.length >= 2) {
                $('.quick-search-results').removeClass('d-none');
                $('.quick-search-results').html(`
                <div class="quick-search-result result cursor-pointer">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
                </div>
                `);

                setTimeout(function() {
                    $.ajax({
                        url: `/adjustment/get/product/v3`,
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
                            console.log(results);
                            products=results;
                            var html = '';
                            if (results.length > 0) {

                                console.log(results,'res')
                                let sku;
                                let addedSku=[];
                                results.forEach(function(result,key) {

                                    let checkSku = addedSku.includes(result.sku);
                                    let has_stock= result.stock.length;

                                    let selectAllCss =  has_stock == 0 ? '' : 'order:-1;'

                                    if(sku && result.sku==sku && !checkSku){
                                        html += `<div class="quick-search-result rounded-3 result cursor-pointer mt-1 mb-1 bg-hover-light p-2" style="${selectAllCss}" data-id="selectAll" data-productid='${result.id}' data-name="${result.name}" style="z-index:100;">`;
                                        html += `<h4 class="fs-6 text-primary ps-10 pt-3">${result.name} (Select All)</h4>`;
                                        html += '</div>';

                                        addedSku.push(result.sku);
                                        $('.quick-search-results').html(html);
                                    }else{
                                        sku=result.sku;
                                    }


                                    if(result.has_variation =='variable' && results.length== 1){
                                        return;
                                    }



                                    let css_class= has_stock == 0 && result.product_type=="storable" ?" text-gray-600 ":'';

                                    html += `<div class="quick-search-result rounded-3 result ps-10  mt-1 mb-1 bg-hover-light p-2 ${css_class} " data-id=${key} data-name="${result.name}" style="z-index:300;">`;
                                    html += `<h4 class="fs-6  pt-3 ${css_class} ">${result.name} `;
                                    if(result.has_variation=='variable'){
                                        html +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">(${result.variation_name??''})</span>`;
                                    }
                                    html+='</h4>'
                                    if(searchType === "Serial"){

                                        html+=`<span class=" pt-3 text-gray-600 fw-bold fs-8">${'SN : '+result.lot_serial_no} </span>`

                                    }else{
                                        html+=`<span class=" pt-3 text-gray-600 fw-bold fs-8">${result.has_variation=='variable'?'SKU : '+result.variation_sku :'SKU : '+result.sku} </span>`
                                    }

                                    html += '</div>';
                                });

                                if (results.length == 1 || (results[0].has_variation == 'variable' && results.length== 1)) {
                                    $('.quick-search-results').show();
                                    if(results[0].stock_sum_current_quantity > 0 || results[0].product_type != "storable"){
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

        $('#autocomplete').on('click', '.result', function() {

            $('.quick-search-results').addClass('d-none')
            let id = $(this).attr('data-id');
            let selected_product;
            if(id!="selectAll"){
                selected_product= products[id];
                let isStorable=selected_product.product_type=="storable";
                if((selected_product.stock.length == 0 || selected_product.stock_sum_current_quantity==null) && isStorable){
                    return;
                }
            }
            if(id=="selectAll")
            {
                let productid=$(this).data('productid');
                let pds=products.filter(p=>{
                    return p.id==productid
                });
                pds.forEach(p => {
                    append_row(p,unique_name_id);
                    unique_name_id+=1;
                });
                return;
            }
            append_row(selected_product, unique_name_id);
            unique_name_id+=1;
            $('#searchInput').focus();

        });

        //append table row for product to sell
        function append_row(selected_product,unique_name_id) {

            console.log(selected_product,'append products');

            if(selected_product.stock.length == 0){
                return;
            }

            let uomByCategory=selected_product['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })

            variation=selected_product.product_variations;
            let packagingOption='';
            if(variation.packaging){
                variation.packaging.forEach((pk)=>{
                    let package_uom_name = uomByCategory.find((item) => item.id === pk.uom_id).short_name;
                    packagingOption+=`
                        <option value="${pk.id}" data-qty="${pk.quantity}" data-uomid="${pk.uom_id}">${pk.packaging_name} (${Number(pk.quantity).toFixed(2)} ${package_uom_name})</option>
                    `;
                })
            }

            let serialInput = selected_product.serial_data == true ? `
                    <input type="hidden" class="serial_data" name="adjustment_details[${unique_name_id}][serial_data]" value="1">
                    <input type='hidden' value="${selected_product.lot_serial_no}" name="adjustment_details[${unique_name_id}][lot_serial_no]"  />
                            <input type='hidden' value="${selected_product.lot_serial_type}" name="adjustment_details[${unique_name_id}][lot_serial_type]"  />
                    ` : '';


            var newRow = `
                <tr class="adjustment_row" id="${unique_name_id}">
                    <td class="adjustment_col1" aria-hidden="true" data-bs-target="#new_price_modal_${unique_name_id}">
                        <div class="my-5 product-name mt-2" >
                            <span>${selected_product.name}</span>
                            <span class="text-primary fw-semibold fs-5">${selected_product.variation_name?'-'+selected_product.variation_name:''}</span>
                        </div>
                    </td>
                    <td>
                         <span class="current_stock_qty_txt" id="current_stock_qty_text_${unique_name_id}">${parseFloat(selected_product.stock_sum_current_quantity).toFixed(2)}</span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>
                          <input type="hidden" class="balance_qty" value="${selected_product.stock_sum_current_quantity}"  name="adjustment_details[${unique_name_id}][balance_quantity]"  />
                     </td>
                    <td class="d-none">
                        <div>
                               ${serialInput}
                            <input type='hidden' value="${selected_product.id}" class="product_id"  name="adjustment_details[${unique_name_id}][product_id]"  />
                            <input type='hidden' value="${selected_product.variation_id}" class="variation_id" name="adjustment_details[${unique_name_id}][variation_id]"  />
                            <input type='hidden' value="${selected_product.stock[0].id}" class="uom_set_id"  />
                        </div>
                    </td>
                    <td>
                            <input type="text" value="${selected_product.stock_sum_current_quantity}" class="form-control form-control-sm gnd_quantity form-control-sm gnd_quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="adjustment_details[${unique_name_id}][gnd_quantity]" data-kt-dialer-control="input"/>
                    </td>
                    <td>

                    <input class="form-control form-control-sm adj_quantity" type="text" name="adjustment_details[${unique_name_id}][adj_quantity]">
                    </td>
                    <td>
                        <select name="adjustment_details[${unique_name_id}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>
                        </select>
                        <input type="hidden" name="adjustment_details[${unique_name_id}][ref_uom_id]" value="${selected_product.stock[0].ref_uom_id}">
                    </td>
                     <td class="fv-row">
                        <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                            name="adjustment_details[${unique_name_id}][packaging_quantity]" value="1.00">
                    </td>
                          <td class="fv-row">
                        <select name="adjustment_details[${unique_name_id}][packaging_id]" class="form-select form-select-sm package_id"
                            data-kt-repeater="package_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                            data-placeholder="Select Package" placeholder="select Package" >
                            <option value="">Select Package</option>
                            ${packagingOption}
                        </select>
                    </td>
                    <td class="fv-row">
                        <input type="text" class="form-control form-control-sm mb-1"
                            name="adjustment_details[${unique_name_id}][remark]" value="">
                        <input type="hidden" name="adjustment_details[${unique_name_id}][new_uom_price]" value="${ selected_product.serial_data == true ? selected_product.ref_uom_price : selected_product.default_selling_price}">
                    </td>
                    <th><i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i></th>
                </tr>
            `;


            let modalTemplate = `
                                <div class="modal fade" id="new_price_modal_${unique_name_id}" data-row-id="${unique_name_id}" tabindex="-1"  tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog mw-400px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">New Price</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <input type="text" class="form-control form-control-sm new-price-input"  value="${ selected_product.serial_data == true ? selected_product.ref_uom_price : selected_product.default_selling_price}"/>
                                                    </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-primary price-save-changes">Update Price</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
            // new row append
            $('#adjustment_table tbody').prepend(newRow);
            $('body').append(modalTemplate);
            $('.dataTables_empty').addClass('d-none');
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');

            checkAndStoreSelectedProduct(selected_product);

            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity});


            $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
                minimumResultsForSearch: Infinity,
                data:uomsData,
            });
            $(`[data-kt-repeater=package_select_${unique_name_id}]`).select2({
                minimumResultsForSearch: Infinity
            });

            optionSelected(selected_product.uom_id,$(`[name="adjustment_details[${unique_name_id}][uom_id]"]`));
            $(`[name="adjustment_details[${unique_name_id}][gnd_quantity]"]`).val($(`[id="current_stock_qty_text_${unique_name_id}"]`).text());
            $(`[name="adjustment_details[${unique_name_id}][adj_quantity]"]`).val(0);
            // $('.adj_quantity').val(0);


            if ($('#adjustment_table tbody tr').length > 1) {
                $('.deleteRow').removeClass('disable');
                $('.deleteRow').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }


        }


        $(document).on('click', '.price-save-changes', function() {
            const modal = $(this).closest('.modal');
            const unique_name_id = modal.attr('data-row-id');

            let newprice = modal.find('.new-price-input').val();

            if (newprice) {
                $(`[name="adjustment_details[${unique_name_id}][new_uom_price]"]`).val(newprice);
            }

            modal.modal('hide');
        });




        function optionSelected(value, select) {
            var $select = $(select);
            $select.val(value).trigger('change');
        }


        function checkAndStoreSelectedProduct(newSelectedProduct) {
            console.log('checkSele')
            let newProductData={
                'product_id':newSelectedProduct.id,
                'variation_id':newSelectedProduct.variation_id,
                'defaultSellingPrices':newSelectedProduct.product_variations.default_selling_price,
                'sellingPrices':newSelectedProduct.product_variations.uom_selling_price,
                'total_current_stock_qty':newSelectedProduct.stock_sum_current_quantity,
                'aviable_qty':newSelectedProduct.stock_sum_current_quantity,
                'validate':true,
                'uom':newSelectedProduct.uom,
                'uom_id':newSelectedProduct.uom_id,
                'stock':newSelectedProduct.stock,
            };
            if(productsOnSelectData.length>0){
                let fileterProduct=productsOnSelectData.filter(function(p){
                    return p.product_id==newSelectedProduct.id && p.variation_id==newSelectedProduct.product_variations.id;

                })[0];
                if(fileterProduct){
                    // return
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }else{
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }
            }else{
                productsOnSelectData=[...productsOnSelectData,newProductData];
            }
            console.log(productsOnSelectData,  'onsel');
        }


        $(document).on('change','.package_id',function(){
            packaging($(this));
            changeQtyOnUom($(this));
            calDifferenceQty($(this));
        })
        $(document).on('change','.uom_select',function(){
            packaging($(this),'/');

        })
        $(document).on('input','.package_qty',function(){

            let inputElement = $(this);
            let sanitizedValue = sanitizeNumericInput(inputElement);

            let parent = inputElement.closest('.adjustment_row');
            let serialDataValue = parent.find('.serial_data').val();

            if (serialDataValue == 1) {
                inputElement.prop('readonly', true);
            } else {
                inputElement.prop('readonly', false);
            }

            packaging($(this),'*');

            changeQtyOnUom($(this));
            // calDifferenceQty($(this));
        })

        $(document).on('input', '.gnd_quantity', function (){
            let inputElement = $(this);
            let sanitizedValue = sanitizeNumericInput(inputElement);

            let parent = inputElement.closest('.adjustment_row');
            let serialDataValue = parent.find('.serial_data').val();

            if (serialDataValue == 1 && sanitizedValue > 1) {
                toastr.error("SN product's ground qty can't greater than 1");
                inputElement.val(1);
            }
            packaging($(this),'/');
            changeQtyOnUom($(this));
            calDifferenceQty($(this));
            priceModalEnable($(this));
        })

        function priceModalEnable(e){
            let parent = e.closest('.adjustment_row');
            let adjustment_col1 = parent.find('.adjustment_col1');

            setTimeout(function (){
                let adjustQty = parseFloat(parent.find('.adj_quantity').val());

                if (parseFloat(adjustQty) > 0) {

                    adjustment_col1.attr('data-bs-toggle', 'modal');
                } else {
                    adjustment_col1.attr('data-bs-toggle', 'false');
                }

            }, 900);
        }
        const packaging=(e,operator)=>{

            let parent = $(e).closest('.adjustment_row');
            let unitQty=parent.find('.gnd_quantity').val();
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
                    let unitQtyValByUom=changeQtyOnUom2(currentUomId,packagingUom,unitQty,uoms).resultQty;
                    console.log(unitQty+'unitQtyUOM');
                    console.log(packageQtyForCal+'packageQty');
                    parent.find('.package_qty').val(qDecimal(isNullOrNan(unitQtyValByUom) / isNullOrNan(packageQtyForCal)));
                }else{
                    let result=isNullOrNan(packageQtyForCal) * isNullOrNan(packageInputQty);
                    let qtyByCurrentUnit= changeQtyOnUom2(packagingUom,currentUomId,result,uoms).resultQty;

                    parent.find('.gnd_quantity').val(qDecimal(qtyByCurrentUnit));
                }
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


        function inputs(e) {
            let parent = $(e).closest('.adjustment_row');
            let gnd_quantity = parent.find('.gnd_quantity');
            let before_edit_quantity = parent.find('.before_edit_quantity');
            let smallest_unit_txt = parent.find('.smallest_unit_txt');
            let current_stock_qty_txt = parent.find('.current_stock_qty_txt');
            let adj_quantity_text = parent.find('.adj_quantity_text');
            let adj_quantity = parent.find('.adj_quantity');

            return {
                parent,
                gnd_quantity,
                before_edit_quantity,
                smallest_unit_txt,
                current_stock_qty_txt,
                adj_quantity_text,
                adj_quantity
            }
        }


        $(document).on('input', '.adj_quantity', function () {
            let inputElement = $(this);
            let sanitizedValue = sanitizeNumericInput(inputElement);

            let parent = inputElement.closest('.adjustment_row');
            let serialDataValue = parent.find('.serial_data').val();

            if (serialDataValue == 1 && sanitizedValue > 0) {
                toastr.error("SN product's adjust qty can't greater than 0");
                inputElement.val(0);
            }

            calGndQuantity($(this));
            priceModalEnable($(this));
        });

        function calGndQuantity(e) {
            const i = inputs(e);
            var adj_quantity = Number(i.adj_quantity.val());
            var current_stock_qty_txt = Number(i.current_stock_qty_txt.text());

            setTimeout(function () {
                var gnd_quantity = adj_quantity + current_stock_qty_txt;
                console.log(gnd_quantity);
                i.gnd_quantity.val(gnd_quantity);
            }, 600);
        }

        function calDifferenceQty(e) {
            const i = inputs(e);
            var gnd_quantity = Number(i.gnd_quantity.val());
            var current_stock_qty_txt = Number(i.current_stock_qty_txt.text());


            setTimeout(function() {
                var difference_qty =  gnd_quantity - current_stock_qty_txt;
                i.adj_quantity_text.text(difference_qty);
                i.adj_quantity.val(difference_qty);
            }, 400)


        }

        function changeQtyOnUom(e,newUomId) {

            try {
                let parent = e.closest('.adjustment_row');
                let productId=parent.find('.product_id').val();
                let variationId=parent.find('.variation_id').val();
                console.log(productsOnSelectData,productId , variationId,'ddd');
                let product = productsOnSelectData.filter(function(pd) {
                    return productId == pd.product_id && variationId == pd.variation_id;
                });
                product=product[0]
                console.log(product,'product');
                let qty=product.total_current_stock_qty;

                let currentUomId;
                let currentUom;

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
                let currentRefQty;

                currentRefQty =getReferenceUomInfoByCurrentUomQty(qty,referenceUom,referenceUom)['qtyByReferenceUom'];



                let result=0;
                if (refUomType === 'reference' && newUomType === 'bigger') {
                    result = currentRefQty / newUomInfo.value;
                } else if (refUomType === 'reference' && newUomType === 'smaller') {
                    result = currentRefQty * newUomInfo.value;
                } else {
                    result = currentRefQty;
                }

                let rounded_amount=newUomInfo.rounded_amount ?? 2;
                let roundedResult= floor(isNullOrNan(result),rounded_amount) ;

                parent.find('.current_stock_qty_txt').text(roundedResult);
                parent.find('.balance_qty').val(roundedResult);
                parent.find('.smallest_unit_txt').text(newUomInfo.name);

            } catch (error) {
                console.log(error);
            }

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



        $(document).on('change','.uom_select',function(e){
            changeQtyOnUom($(this),$(this).val());
        })



        // Attach click event listener to all delete buttons in the table
        $(document).on('click', '#adjustment_table .deleteRow', function (e) {
            if ($('#adjustment_table tbody tr').length-1 == 1) {
                $(this).css({
                    'cursor': 'not-allowed',
                    'opacity': 0.5
                });
                event.preventDefault();
                return false;
            }
            e.preventDefault();
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
                    var row = $(this).closest('tr');
                    var id = row.attr('data-id');
                    var name = row.find('td[data-id="' + id + '"]').text();

                    var rowCount = $('#adjustment_table tbody tr').length;
                    if (rowCount == 2) {
                        $('.dataTables_empty').removeClass('d-none');
                    }
                    row.remove();
                }
            });
        });


        $(document).on('change','.unit_input',function(){
            let parent=$(this).closest('.adjustment_row');
            let unit_selcted_txt=parent.find('.unit_input option:selected').text();
            let smallest_unit_txt=parent.find('.smallest_unit_txt');
            smallest_unit_txt.text(unit_selcted_txt);

        })


// ----------------------------------------------------------------   End::Sale Calculation  -----------------------------------------------------------

        function sanitizeNumericInput(inputElement) {
            var inputText = inputElement.val().trim();
            var sanitizedInput = inputText.replace(/[^-0-9.]/g, '');
            inputElement.val(sanitizedInput);
            return parseFloat(sanitizedInput);
        }

        function isNullOrNan(val){
            let v=parseFloat(val);

            if (isNaN(v)) {
                return 0;
            } else {
                return v;
            }
        }






    });
</script>
