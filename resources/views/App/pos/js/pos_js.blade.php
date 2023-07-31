<script src="{{ asset('customJs/debounce.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script>

    $(document).ready(function() {
        let tableBodyId = $("#invoice_side_bar").is(':hidden') ? 'invoice_with_modal' : 'invoice_with_sidebar';
        let infoPriceId = $("#invoice_side_bar").is(':hidden') ? 'info_price_with_modal' : 'info_price_with_sidebar';
        let contact_id = $("#invoice_side_bar").is(':hidden') ? 'pos_customer' : 'sb_pos_customer';
        let contact_edit_btn_id = $("#invoice_side_bar").is(':hidden') ? 'contact_edit_btn_modal' : 'contact_edit_btn';
        let contact_phone_btn_id = $("#invoice_side_bar").is(':hidden') ? 'contact_edit_phone_btn_modal' : 'contact_edit_phone_btn';

        let disableSaleButton = () => {
            let tr_count = $(`#${tableBodyId} tr`).length;
            if(tr_count == 0){
                
            }
        }

        var productsOnSelectData = [];
        let currentStockBalance = @json($currentStockBalance ?? null);
        let uoms = @json($uoms ?? null);
        let product_with_variations = [];
        let customers = [];

        let products = null;
        let isNullOrNan = (val) => {
            let v=parseFloat(val);

            if(v === '' || v === null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }

        let checkContact = () => {
            let business_location_id = $('select[name="business_location_id"]').val();
            let contact_id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();

            if(business_location_id === ''){
                warning('Select Location!');
                return false;
            }
            
            if(contact_id === '' || contact_id === null){
                warning('Select Customer!');
                return false;
            }
            return true;
        }

        let paymentRow = () => {
            return `
                <div class="payment_row">
                    <div class="mb-3">
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-5 col-5">
                                <label class="form-label">Amount:</label>
                                <input type="text" class="form-control form-control-sm mb-2 mb-md-0" name="pay_amount" placeholder="" value=""/>
                            </div>
                            <div class="col-md-3 col-sm-5 col-5">
                                <label class="form-label">Payment Method:</label>
                                <select class="form-select mb-2 form-select-sm" name="payment_method" data-control="select2" data-hide-search="true" data-placeholder="Select category">
                                    <option></option>
                                    <option value="1">Cash</option>
                                    <option value="2">Card</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-2 col-2">
                                <button class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove_payment_row">
                                    <i class="fas fa-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `
        };

        let searchNewRow = (index, productId, productType, variationId = null, name, countOrSku, variNameOrSelectAll, unit, css_class) => {
            return `
                <li data-productId="${productId}" data-index="${index}" data-productType="${productType}" data-variationId="${variationId}" id="searchItem" class="list-group-item bg-light cursor-pointer pos-product-search bg-hover-secondary p-3 ${css_class}" id="searchItem">
                    <h4 class="fs-6 ps-7 pt-3 me-4 ${css_class}">
                        ${name} - (${countOrSku}) ${variNameOrSelectAll}
                    </h4>
                    <p class="ps-7 pt-1 ${css_class}">${unit} </p>
                </li>
            `;
        }

        let invoiceSidebar = (product) => {
            let variation_value_and_name;
            // Get Variation Value and Variation Template Value Name
            if(product.variation_id){
                let pv_id = product.variation_id;

                $(product_with_variations).each(function(index, element) {
                    if(element.product_variation_id && element.product_variation_id == pv_id){
                        variation_value_and_name = element.vari_tem_name + '- ' + element.vari_tem_val_name;
                    }
                })
            }
            
            let uomByCategory=product['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })

            checkAndStoreSelectedProduct(product);

            // console.log(product)
            return `
                <tr class="fs-9 mb-3 invoiceRow invoice_row_${product.product_variations.id} cursor-pointer">
                    <input type="hidden" name="product_id" value="${product.id}" />
                    <input type="hidden" name="lot_no" value="0" />
                    <input type="hidden" name="variation_id" value="${product.product_variations.id}" />
                    <input type="hidden" name="each_selling_price" value="" />
                    <input type="hidden" name="discount_type" value="" />
                    <input type="hidden" name="per_item_discount" value="" />
                    <input type="hidden" name="subtotal_with_discount" value="" />

                    <td class=" text-break text-start fw-bold fs-6 text-gray-700 "><span class="product-name">${product.name}</span>
                        <br>
                        <span class="fs-7 fw-semibold text-gray-600 product-sku">SKU : ${product.sku}</span>
                        <br>
                        ${variation_value_and_name !== undefined ? `<span class="fs-7 fw-semibold text-gray-600 variation_value_and_name">${variation_value_and_name}</span><br>` : ''}
                        <span class="fs-7 fw-semibold text-gray-600 stock_quantity_unit_${product.product_variations.id}">${product.uom.value * 1}</span> -
                        <span class="fs-7 fw-semibold text-gray-600 stock_quantity_${product.product_variations.id}">${product.uom.name}</span>
                    </td>
                    <td class="min-w-50px ps-0 pe-0 exclude-modal">
                        <input type="text" name="selling_price[]" class="form-control form-control-sm" value="${product.product_variations.default_selling_price * 1}">
                    </td>
                    <td class="exclude-modal">
                        <div class="d-flex flex-column">
                            <div class="position-relative d-flex align-items-center mb-3 input-group flex-nowrap " >

                                <button type="button" class=" px-3 btn btn-sm btn-light btn-icon-gray-00 border-end-0" id="decrease">
                                    <i class="fas fa-minus fs-7"></i>
                                </button>

                                <input type="text" class=" form-control form-control-sm border-0 text-center  fw-bold text-gray-800" name="quantity[]" readonly value="1" />

                                <button type="button" class=" px-3 btn btn-sm btn-light btn-icon-gray-600 border-end-0" id="increase">
                                    <i class="fas fa-plus"></i>
                                </button>

                            </div>
                            <span class="text-danger-emphasis  stock_alert_${product.product_variations.id} d-none fs-7 p-2">* Out of Stock</span>
                            <select class=" form-select form-select-sm invoice_unit_select" data-control="select2">
                                ${uomsData.map(unit => `<option value="${unit.id}" ${unit.id === product.uom.id ? 'selected' : ''}>${unit.text}</option>`).join('')}
                            </select>

                        </div>
                    </td>
                    <td class="fs-6 fw-bold subtotal_price_${product.product_variations.id} subtotal_price">${product.product_variations.default_selling_price * 1}</td>
                    <td class="exclude-modal">
                        <i class="fas fa-trash me-3 text-danger cursor-pointer" id="delete-item"></i>
                    </td>
                </tr>
            `;
        }

        let clearContainer = () => {
            $('#search_container').empty();
            $('#search_container').addClass('d-none');
        }

        let toGetReferenceUOMRelate = (uom_id) => {
            let result = '';

            let filteredUom = uoms.filter( uom => uom.id == uom_id)[0]; // သည်ကနေ name နဲ့ value နဲ့ unit_type ယူမယ်
            let filteredUomCategory = uoms.filter( uom => uom.unit_category_id == filteredUom.unit_category_id);
            let referenceUom = filteredUomCategory.filter( uom => uom.unit_type === "reference")[0]; // သည်ကနေ name နဲ့ value နဲ့ unit_type

            if(filteredUom.unit_type === 'bigger'){
                result = `${referenceUom.value * 1} ${filteredUom.name} | ${filteredUom.value * 1} ${referenceUom.name}`;
            } 
            if(filteredUom.unit_type === 'smaller'){
                result = `${referenceUom.value * 1} ${referenceUom.name} | ${filteredUom.value * 1} ${filteredUom.name}`;
            }
            if(filteredUom.unit_type === 'reference'){
                result = `${referenceUom.value * 1} ${referenceUom.name}`;
            }
            return result;
        }
        
        // to delete
        let getSmallestUnitQuantity = (numbers) => {
            let output = numbers.reduce((accumulator, currentValue) => accumulator * currentValue);
            return output;
        }

        // to delete
        let allowInOrDeQty = ($element) => {
            // အကြီးဆုံး unit ဖြစ်မှ increas or decrease ခွင့်ပြု
            let biggest_unit_id = $element.closest('tr').find('input[name="biggest_unit_id"]').val();
            let selected_unit_id = $element.closest('tr').find('.invoice_unit_select').val();
            var selectedValue = $('#selling_price_group').val();

            if( selectedValue === 'default_selling_price' && biggest_unit_id === selected_unit_id){
                $element.closest('tr').find('.show_unit_error').addClass('d-none')
                return true;
            }else{
                $element.closest('tr').find('.show_unit_error').removeClass('d-none');
                return false;
            }
        }

        let calPrice = ($element) => {
            let quantity = $element.closest('tr').find('input[name="quantity[]"]').val();
            let default_price = $element.closest('tr').find('input[name="selling_price[]"]').val();
            let total_price = default_price * quantity;

            $element.closest('tr').find('.subtotal_price').text(total_price);
        }

        let totalSubtotalAmountCalculate = () => {
            let itemCount = $(`#${tableBodyId} tr`).length;
            // console.log(itemCount)
            let totalSum = 0;
            $(`#${tableBodyId} .subtotal_price`).each(function() {
                // console.log($(this).text())
                let value = isNullOrNan($(this).text());
                totalSum += value;
            });
            // console.log(totalSum)
            $('.sb-item-quantity').text(itemCount);
            $('.sb-total').text(totalSum);
        }

        let changeQtyOnUom = (e,newUomId) => {
            try {
                let parent = e.closest(`.invoiceRow`);
                let productId = parent.find('input[name="product_id"]').val();
                let variationId = parent.find('input[name="variation_id"]').val();

                let product = productsOnSelectData.filter(function(pd) {
                    return productId == pd.product_id && variationId == pd.variation_id;
                });

                product=product[0];
                let qty=isNullOrNan(product.total_current_stock_qty);
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
                currentRefQty = getReferenceUomInfoByCurrentUomQty(qty,referenceUom,referenceUom)['qtyByReferenceUom'];
                let result;
                if (refUomType === 'smaller' && newUomType === 'bigger') {
                    result = currentRefQty / newUomInfo.value;
                } else if (refUomType === 'bigger' && newUomType === 'smaller') {
                    result = currentRefQty * newUomInfo.value;
                } else if (refUomType === 'reference' && newUomType === 'bigger') {
                    result = currentRefQty / newUomInfo.value;
                } else if (refUomType === 'reference' && newUomType === 'smaller') {
                    result = currentRefQty * newUomInfo.value;
                } else if (refUomType === 'bigger' && newUomType === 'bigger') {
                    result = currentRefQty / newUomInfo.value;
                } else if (refUomType === 'smaller' && newUomType === 'smaller') {
                    result = currentRefQty * newUomInfo.value;
                } else {
                    result = currentRefQty;
                }

                let rowIndex = parent.index();
                $(`tr:eq(${rowIndex + 1}) .stock_quantity_unit_${variationId}`).text(result);
                $(`tr:eq(${rowIndex + 1}) .stock_quantity_${variationId}`).text(newUomInfo.name);

                // console.log(priceGpInput)

                var priceGpInput;
                priceGpInput = $('#selling_price_group').val();
                let has_selling_price = checkSellingPriceGroupId(parent) ? priceGpInput : 'default_selling_price';

                parent.find('input[name="each_selling_price"]').val(has_selling_price);
                priceGpInput = has_selling_price;

                if(priceGpInput !== 'default_selling_price'){
                    let price = product.sellingPrices.filter(function(prices){
                        return prices.uom_id == newUomId && prices.pricegroup_id==priceGpInput
                    })[0];

                    if(price !== undefined){
                        parent.find('input[name="selling_price[]"]').val(price.price_inc_tax * 1);
                        parent.find(`.subtotal_price_${variationId}`).text(price.price_inc_tax * 1);
                    }
                }else{
                    parent.find('input[name="selling_price[]"]').val(product.defaultSellingPrices * 1);
                    $(`.subtotal_price_${variationId}`).text(product.defaultSellingPrices * 1);
                }
            } catch (error) {
                console.log(error);
            }
        }

        let checkAndStoreSelectedProduct = (newSelectedProduct) => {
            let newProductData={
                'product_id':newSelectedProduct.id,
                'variation_id':newSelectedProduct.product_variations.id,
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
                let fileterProduct = productsOnSelectData.filter(function(p){
                    return p.product_id == newSelectedProduct.id && p.variation_id == newSelectedProduct.product_variations.id

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

        let getReferenceUomInfoByCurrentUomQty = (qty, currentUom, referenceUom) => {
            const currentUomType = currentUom.unit_type;
            const currentUomValue = currentUom.value;
            const referenceUomId = referenceUom.id;
            const referenceRoundedAmount = referenceUom.rounded_amount || 4;
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

            return {
                qtyByReferenceUom: result,
                referenceUomId: referenceUomId
            };
        }
        // console.log(getReferenceUomInfoByCurrentUomQty(4, 3, 1))
        let checkStock = (e) => {
            let tr_parent = $(e).closest('.invoiceRow');
            let parentTBody = tr_parent.parent().attr('id');

            let variationId = tr_parent.find('input[name="variation_id"]').val();
            let index;
            let product = productsOnSelectData.find(function(pd,i) {
                index = i;
                return  variationId == pd.variation_id;
            });

            const uoms = product.uom.unit_category.uom_by_category;

            const referenceUom =uoms.filter(function ($u) {
                return $u.unit_type == "reference";
            })[0];


            let result=0;
            $(`#${parentTBody} .invoice_row_${variationId}`).each(function(){
                let parent = $(`tbody#${parentTBody}`).find($(this).closest('.invoiceRow'));
                let quantity = Number(parent.find('input[name="quantity[]"]').val());
                let uom_id = Number(parent.find('.invoice_unit_select').val());
                const currentUom = uoms.filter(function ($u) {
                    return $u.id == uom_id;
                })[0];
                let refQty = getReferenceUomInfoByCurrentUomQty(quantity,currentUom,referenceUom)['qtyByReferenceUom'];
                result += isNullOrNan(refQty)
            })

            if(result > productsOnSelectData[index].total_current_stock_qty){
                productsOnSelectData[index].validate=false;
                $(`.stock_alert_${variationId}`).removeClass('d-none');
            }else{
                productsOnSelectData[index].validate=true;
                $(`.stock_alert_${variationId}`).addClass('d-none');
            }
        }

        let calDiscountPrice = (disType, disAmount, priceWithoutDis) => {
            if(!disType || !disAmount || !priceWithoutDis) return;

            if(disType === "fixed"){
                let result = priceWithoutDis - disAmount;
                return result;
            }

            if(disType === "percentage"){
                let dis_amount_with_price = priceWithoutDis * (disAmount / 100);
                result = priceWithoutDis - dis_amount_with_price;
                return result;
            }
        }

        let hideCalDisPrice = (currentRow) => {
            // per item discount တွက်တာတွေကို modal box ထဲမှာ တွက်ထားတာဖြစ်လို့၊ modal box ပိတ်တဲ့ချိန် quantity အတိုးအလျှော့မှာ discount တွက်ပေးနိုင်အောင်လို။
            let price = currentRow.closest('tr').find('input[name="selling_price[]"]').val();
            let dis_type = currentRow.closest('tr').find('input[name="discount_type"]').val();
            let dis_amount = currentRow.closest('tr').find('input[name="per_item_discount"]').val();
            let subtotal_with_discount = currentRow.closest('tr').find('input[name="subtotal_with_discount"]').val();
            let quantity = currentRow.closest('tr').find('input[name="quantity[]"]').val();

            if(dis_type != '' && dis_amount != '' && subtotal_with_discount != ''){
                let result_dis_calc = calDiscountPrice(dis_type, dis_amount, price);
                let subtotal_with_discount = isNullOrNan(result_dis_calc * quantity);
                currentRow.closest('tr').find('input[name="subtotal_with_discount"]').val(subtotal_with_discount);
            }
        }

        let totalDisPrice = () => {
            let totalDisPrice = 0;
            let subTotalPrice = 0;
            $(`#${tableBodyId} .invoiceRow`).each(function() {
                let parent = $(this).closest('tr');
                let subtotal = parent.find('.subtotal_price').text();
                let subtotal_with_discount = parent.find('input[name="subtotal_with_discount"]').val();

                if(subtotal_with_discount !== ''){
                    let result = isNullOrNan(subtotal) - isNullOrNan(subtotal_with_discount);
                    totalDisPrice += result;
                }
                subTotalPrice += isNullOrNan(subtotal);
            })

            let total_amount = subTotalPrice - totalDisPrice;
            $(`#${infoPriceId} .sb-discount`).text(totalDisPrice);
            $(`#${infoPriceId} .sb-total-amount`).text(total_amount);
        }

        let checkSellingPriceGroupId = (product) => {
            let product_id = product.find('input[name="product_id"]').val();
            let variation_id = product.find('input[name="variation_id"]').val();
            let original_sellingprice = $('#selling_price_group').val();
            let filtered_product = productsOnSelectData.filter( item => {
                return item.product_id == product_id && item.variation_id == variation_id;
            });

            let selling_price = filtered_product[0].sellingPrices;
            let price_group_ids = $.unique($.map(selling_price, item => item.pricegroup_id));

            let has_selling_price = price_group_ids.includes(parseInt(original_sellingprice));
            return has_selling_price;
        }

        let ajaxToStorePosData = (dataForSale) => {
            $.ajax({
                url: `/sell/create`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: dataForSale,
                success: function(results){
                    if(results.status==200){
                        Swal.fire({
                            text: "Successfully Sold! Thanks you.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            //sth

                            $(`#${tableBodyId} tr`).remove();
                            totalSubtotalAmountCalculate();
                            totalDisPrice();
                            $('#payment_info .print_paid').text(0);
                            $('#payment_info .print_change').text(0);
                            $('#payment_info .print_balance').text(0);
                            $('input[name="pay_amount"]').val(0);
                        });
                    }
                },
                error:function(e){
                    status=e.status;
                    if(status==405){
                        warning('Method Not Allow!');
                    }else if(status==419){
                        error('Session Expired')
                    }else{
                        error(' Something Went Wrong! Error Status: '+status )
                    };
                },

            })
        }

        let datasForSale = (status) => {
            let business_location_id = $('select[name="business_location_id"]').val();
            let contact_id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();
            let pos_register_id = null;
            let sale_amount = $(`#${infoPriceId} .sb-total`).text();
            let total_item_discount = $(`#${infoPriceId} .sb-discount`).text();
            let extra_discount_type = null;
            let extra_discount_amount = null;
            let total_sale_amount = $(`#${infoPriceId} .sb-total-amount`).text();
            let paid_amount = $('.print_paid').text();
            let balance_amount = total_sale_amount - paid_amount;
            let currency_id = null;

            let sales ={
                    'business_location_id': business_location_id,
                    'contact_id': contact_id,
                    'status': status,
                    'pos_register_id': pos_register_id,
                    'sale_amount': sale_amount,
                    'total_item_discount': total_item_discount,
                    'extra_discount_type': extra_discount_type,
                    'extra_discount_amount': extra_discount_amount,
                    'total_sale_amount': total_sale_amount,
                    'paid_amount': paid_amount,
                    'balance_amount': balance_amount,
                    'currency_id': currency_id
                }


            let sale_details = [];
            $(`#${tableBodyId} .invoiceRow`).each(function() {
                let parent = $(this).closest('tr');
                let product_id = parent.find('input[name="product_id"]').val();
                let variation_id = parent.find('input[name="variation_id"]').val();
                let uom_id = parent.find('.invoice_unit_select').val();
                let quantity = parent.find('input[name="quantity[]"]').val();
                let selling_price_group_id = parent.find('input[name="each_selling_price"]').val();
                let uom_price = parent.find('input[name="selling_price[]"]').val();
                let subtotal = parent.find('.subtotal_price').text();
                let discount_type = parent.find('input[name="discount_type"]').val();
                let per_item_discount = parent.find('input[name="per_item_discount"]').val();
                let subtotal_with_discount = parent.find('input[name="subtotal_with_discount"]').val();

                let raw_sale_details = {
                    'product_id': product_id,
                    'variation_id': variation_id,
                    'uom_id': uom_id,
                    'quantity': quantity,
                    'selling_price_group_id': selling_price_group_id,
                    'uom_price': uom_price,
                    'subtotal': subtotal,
                    'discount_type': discount_type,
                    'per_item_discount': per_item_discount,
                    'subtotal_with_discount': subtotal_with_discount,
                    'tax_amount': null,
                    'subtotal_with_tax': null,
                    'currency_id': null,
                    'delivered_quantity': null
                };

                sale_details.push(raw_sale_details);
            })
            let type = 'pos';
            let data = {...sales,
                 'sale_details':sale_details,
                 type}
            return data;
        }

        let getContacts = (id = null) => {
            $.ajax({
                method: 'GET',
                url: '/pos/contacts',
                success: function(result) {
                    customers = result;

                    let selectedElement = $(`#${contact_id}`);

                    // Clear existing options
                    selectedElement.empty();

                    $.each(result, function(index, item) {
                        var option = $("<option>")
                            .val(item.id)
                            .text(item.full_name);
                            
                        selectedElement.append(option);
                    });

                    selectedElement.val(id).trigger("change");
                },
                error: function(error) {
                    //
                }
            });
        }
        
        // !IMPORTANT => PRODUCT VARIATIONS ICON TO SHOW 
        let getProductVariations = () => {
            $.ajax({
                method: 'GET',
                url: '/pos/product-variations',
                success: function(result) {
                    product_with_variations = result;

                    // Insert the dynamic content into the container
                    var productsHtml = '';

                    // Loop through each item in the response data
                    $.each(result, function(index, item) {
                        productsHtml += `
                            <div class="card flex-row-fluid p-2 col-lg-2 col-md-2 col-2 min-w-125px cursor-pointer each_product">
                                <input type="hidden" name="category_id" value="${item.parent_category_id}">
                                <input type="hidden" name="sub_category_id" value="${item.sub_category_id}">
                                <input type="hidden" name="brand_id" value="${item.brand_id}">
                                <input type="hidden" name="manufacturer_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="generic_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="product_id" value="${item.id}">
                                <input type="hidden" name="product_variation_id" value="${item.product_variation_id}">
                                <div class="card-body text-center p-3">
                                    ${item.image ? `<img src="/storage/product-image/${item.image}" class="rounded-3 mb-4 w-80px h-80px w-xxl-100px h-xxl-100px" alt="" />` :
                                    `<div class="rounded-3 mb-4 w-80px h-80px w-xxl-100px h-xxl-100px"></div>`}
                                    <div class="mb-2">
                                        <div class="text-center">
                                            <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-7 mb-3 pos-product-name">${item.name}</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-8 mt-n1">${item.vari_tem_name ? item.vari_tem_name : ''} - ${item.vari_tem_val_name ? item.vari_tem_val_name : ''}</span>
                                        </div>
                                    </div>
                                    <span class="text-primary text-end fw-bold fs-6">${item.default_selling_price}</span>
                                </div>
                            </div>
                        `;
                    });
                    // Insert the HTML content into the container
                    $('#all_product_list').html(productsHtml);
                },
                error: function(error) {
                    //
                }
            });
        }

        let isGetProductVariations = false;
        if(!isGetProductVariations){
            getProductVariations();
            isGetProductVariations = true;
        }

        let isGetContact = false;
        if(!isGetContact){
            getContacts(1);
            isGetContact = true;
        }

        //================================================================================================//
        //================================================================================================//

        // if product search empty
        $(document).on('input', 'input[name="pos_product_search"]', function() {
            if($(this).val() === ''){
                clearContainer();
            }else{
                $('#search_container').removeClass('d-none');
            }
        })

        // product search
        $(document).on('input', 'input[name="pos_product_search"]', debounce(function() {
            let query = $(this).val().trim();
            if (query === '') return;
            $('#search_container').empty();
            products = null;

            let business_location_id = $('#business_location_id').val();

            let data = {
                business_location_id, query
            }

            $.ajax({
                url: `/sell/get/product`,
                type: 'POST',
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
                        error(' Something Went Wrong! Error Status: '+status )
                    };
                },
                success: function(results){
                    products = results;
                    // console.log(products)
                    if(results.length > 0){
                        $(results).each(function(index, element){
                            // console.log(element)
                            let css_class = element.total_current_stock_qty !== 0 ? " " : " text-gray-500 order-3 not-use";

                            let product_countOrSku = element.product_type === 'variable' ? element.product_variations.length : element.sku;
                            // let stock_qty = element.total_current_stock_qty !== 0 ? element.total_current_stock_qty * 1 + ' ' + element.smallest_unit : 'Out of Stocks';
                            let vari_name_or_selectAll = element.product_type === 'sub_variable' ? element.variation_name : 'select all';
                            let unit = element.uom.name;

                            $('#search_container').append(searchNewRow(index, element.id, element.product_type, element.variation_id, element.name, product_countOrSku, vari_name_or_selectAll, unit, css_class));
                        })
                    }
                },

            })

        }))

        // remove product search container when click another
        $(document).click(function(event) {
            if (!$(event.target).closest('#search_container').length && !$(event.target).closest('#searchItem').length) {
                clearContainer();
            }
        });

        // when select search item / add new tr to tbody
        $('#search_container').on('click', '.pos-product-search', function(event) {
            if($(event.target).hasClass('not-use')) return;

            $(this).closest(`.pos-product-search`).remove();
            let index = $(this).attr('data-index');

            let selected_product = products[index];

            if(selected_product.total_current_stock_qty === 0 || selected_product.total_current_stock_qty === null){
                return;
            }

            if(selected_product.product_type === 'variable'){
                let variation = selected_product.product_variations;
                variation.forEach(variation => {
                    let filteredId = products.filter( p => p.variation_id === variation.id);
                    let newInvoiceSidebar = $(invoiceSidebar(filteredId[0]));
                    $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                    // $('#invoice_with_sidebar').append(newInvoiceSidebar);
                    // $('#invoice_with_modal').append(newInvoiceSidebar.clone());
                    newInvoiceSidebar.find('.invoice_unit_select').trigger('change');
                });
                totalSubtotalAmountCalculate();
                totalDisPrice();
                return;
            }
            let newInvoiceSidebar = $(invoiceSidebar(selected_product));
            // $('#invoice_with_sidebar').append(newInvoiceSidebar);
            // $('#invoice_with_modal').append(newInvoiceSidebar.clone());
            $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
            $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
            newInvoiceSidebar.find('.invoice_unit_select').trigger('change');
            totalSubtotalAmountCalculate();
            totalDisPrice();
        })

        $('#all_product_list').on('click', '.each_product', function(e) {
            var selectedLocation = $('#business_location_id').val();
            if(selectedLocation === ''){
                warning('Select location!')
                return;
            }
            let business_location_id = selectedLocation;
            let query = $(this).find('.pos-product-name').text();
            let variation_id = $(this).find('input[name="product_variation_id"]').val();
            let data = {
                business_location_id, query, variation_id
            }

            $.ajax({
                url: `/sell/get/product`,
                type: 'POST',
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
                        error(' Something Went Wrong! Error Status: '+status )
                    };
                },
                success: function(results){
                    if(results[0].total_current_stock_qty === 0 || results[0].total_current_stock_qty === ''){
                        error('Out of stock');
                        return;
                    }

                    if(results[0].product_type === "single"){
                        let newInvoiceSidebar = $(invoiceSidebar(results[0]));
                        $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                        $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                        newInvoiceSidebar.find('.invoice_unit_select').trigger('change');
                        totalSubtotalAmountCalculate();
                        totalDisPrice();
                        return;
                    }
                    let newInvoiceSidebar = $(invoiceSidebar(results[1]));
                    $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                    newInvoiceSidebar.find('.invoice_unit_select').trigger('change');
                    totalSubtotalAmountCalculate();
                    totalDisPrice();
                },

            })
        })

        $(document).on('input', 'input[name="selling_price[]"]', function() {
            calPrice($(this));
            totalSubtotalAmountCalculate();
            totalDisPrice();
        })

        $(document).on('change', '.invoice_unit_select', function(e) {
            let selected_uom_id = $(this).val();
            let product_id = $(this).closest('tr').find('input[name="product_id"]').val();
            checkStock($(this));
            changeQtyOnUom($(this), selected_uom_id);
            calPrice($(this));
            totalSubtotalAmountCalculate();
            hideCalDisPrice($(this));
            totalDisPrice();
        })

        // for increase and decrease SERVICE ITEM QUANTITY
        $(document).on('click', '#increase', function() {
            let incVal = $(this).closest('tr').find('input[name="quantity[]"]');
            let value = parseInt(incVal.val()) + 1;
            incVal.val(value);
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
        })

        $(document).on('click', '#decrease', function() {
            let decVal = $(this).closest('tr').find('input[name="quantity[]"]');
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
        })

        // Begin::Discount Modal
        let current_tr;
        $(document).on('click', '.invoiceRow td:not(.exclude-modal)', function(event) {
            event.stopPropagation();
            current_tr = $(this).closest('tr');
            let each_selling_price = current_tr.find('input[name="each_selling_price"]').val();
            let dis_type = current_tr.find('input[name="discount_type"]').val();
            let per_item_dis = current_tr.find('input[name="per_item_discount"]').val();
            let subtotal_with_dis = current_tr.find('input[name="subtotal_with_discount"]').val();
            let product_name = current_tr.find('.product-name').text();
            let product_sku = current_tr.find('.product-sku').text();
            let price = current_tr.find('input[name="selling_price[]"]').val();
            let product_id = current_tr.find('input[name="product_id"]').val();
            let variation_id = current_tr.find('input[name="variation_id"]').val();
            let uom_id = current_tr.find('.invoice_unit_select').val();

            let filtered_product = productsOnSelectData.filter( item => item.product_id == product_id && item.variation_id == variation_id);

            // Begin => current tr ရဲ့ product က selling price မသတ်မှတ်ထားရင် selling price option တွေကို disabled လုပ်
            $('select[name="each_selling_price"] option').prop('disabled', false); // reset select option
            if(filtered_product[0].sellingPrices.length > 0){
                let selling_price = filtered_product[0].sellingPrices;
                let price_group_ids = $.unique($.map(selling_price, item => item.pricegroup_id));
                $('select[name="each_selling_price"] option:not([value="default_selling_price"])').each(function() {
                    let optionValue = parseInt($(this).val());
                    if(!price_group_ids.includes(optionValue)){
                        $(this).prop('disabled', true)
                    }
                })
            }else{
                $('select[name="each_selling_price"] option:not([value="default_selling_price"])').prop('disabled', true);
            }
            // End
            $('#invoice_row_discount').find('input[name="discount_amount"]').val(per_item_dis);
            $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').val(subtotal_with_dis);
            $('#invoice_row_discount').find('.modal-title').text(`${product_name} - ${product_sku}`);
            $('#invoice_row_discount').find('select[name="each_selling_price"]').val(each_selling_price).trigger('change');
            $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price);

            if(dis_type !== ''){
                $('#invoice_row_discount').find('select[name="invoice_row_discount_type"]').val(dis_type).trigger('change');
            }

            $('#invoice_row_discount').modal('show');

            $(document).on('change', 'select[name="invoice_row_discount_type"]', function(e){
                $('#invoice_row_discount').find('input[name="discount_amount"]').trigger('input');
            })

            $(document).on('input', 'input[name="discount_amount"]', function(e){
                let discount_amount = $(this).val();
                let current_discount_type = $('#invoice_row_discount').find('select[name="invoice_row_discount_type"]').val();
                let current_price = $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val();
                let quantity = current_tr.find('input[name="quantity[]"]').val();

                let result_dis_calc = calDiscountPrice(current_discount_type, discount_amount, current_price);
                let subtotal_with_discount = isNullOrNan(result_dis_calc * quantity);
                // current_tr.find('input[name="subtotal_with_discount"]').val(subtotal_with_discount);
                // current_tr.find('input[name="per_item_discount"]').val(discount_amount);
                $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').val(subtotal_with_discount);
            })

            $(document).on('change', 'select[name="each_selling_price"]', function(e){
                let pricegroup_id = $(this).val();
                // current_tr.find('input[name="each_selling_price"]').val(pricegroup_id); // set selling_price_id to each invoice row

                if(pricegroup_id === "default_selling_price"){
                    let price = filtered_product[0].defaultSellingPrices;
                    // current_tr.find('input[name="selling_price[]"]').val(price);
                    // calPrice(current_tr);
                    $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price);
                    return;
                }

                let price = filtered_product[0].sellingPrices.filter(function(prices){
                    return prices.uom_id == uom_id && prices.pricegroup_id == pricegroup_id;
                })[0];

                if(price !== undefined){
                    // current_tr.find('input[name="selling_price[]"]').val(price.price_inc_tax * 1);
                    // calPrice(current_tr);
                    $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price.price_inc_tax * 1);
                }

                $('#invoice_row_discount').find('input[name="discount_amount"]').trigger('input');
            })
        })
        $('#invoice_row_discount').on('hidden.bs.modal', function(event) {
            let selling_price_group = $(this).find('select[name="each_selling_price"]').val();
            let uom_price = $(this).find('input[name="modal_price_without_dis"]').val();
            let dis_type = $(this).find('select[name="invoice_row_discount_type"]').val();
            let dis_amount = $(this).find('input[name="discount_amount"]').val();
            let subtotal_with_dis = $(this).find('input[name="subtotal_with_discount"]').val();

            current_tr.find('input[name="selling_price[]"]').val(uom_price);
            current_tr.find('input[name="each_selling_price"]').val(selling_price_group);
            current_tr.find('input[name="discount_type"]').val(dis_type);
            current_tr.find('input[name="per_item_discount"]').val(dis_amount);
            current_tr.find('input[name="subtotal_with_discount"]').val(subtotal_with_dis);

            calPrice(current_tr);
            totalDisPrice();
        });
        // End

        // for delete item
        $(document).on('click', '#delete-item', function() {
            $(this).closest('tr').remove();
            checkStock($(this));
            totalSubtotalAmountCalculate();
            totalDisPrice();
            check();
        })

        // for small and medium size table
        let isTrue = false;
        $('#pos_shopping_cart').on('click', function() {
            isTrue = !isTrue;

            if(isTrue){
                $('#pos_kt_content').addClass('d-none');
                $('#pos_second_content').removeClass('d-none');
            }
            if(!isTrue){
                $('#pos_kt_content').removeClass('d-none');
                $('#pos_second_content').addClass('d-none');
            }
        })

        // ======================> Begin Filter Process <========================
        // Parent Category
        $('#filter_category .modal-footer .save-parent-category').on('click', function() {
            var selectedValue = $('#filter_category input[name="filter_parent_category_id"]:checked').val();

            $('#all_product_list .each_product').each(function() {
                var categoryID = $(this).find('input[name="category_id"]').val();

                if (categoryID != selectedValue) {
                    $(this).addClass('d-none');
                }else {
                    $(this).removeClass('d-none');
                }
            });

            $('#filter_category').modal('hide');
        });

        // Sub Category
        $('#filter_sub_category .modal-footer .save-sub-category').on('click', function() {
            var selectedValue = $('#filter_sub_category input[name="filter_child_category_id"]:checked').val();

            $('#all_product_list .each_product').each(function() {
                var subCategoryID = $(this).find('input[name="sub_category_id"]').val();

                if (subCategoryID != selectedValue) {
                    $(this).addClass('d-none');
                }else {
                    $(this).removeClass('d-none');
                }
            });

            $('#filter_sub_category').modal('hide');
        });

        // Brand
        $('#filter_brand .modal-footer .save-brand').on('click', function() {
            var selectedValue = $('#filter_brand input[name="filter_brand_id"]:checked').val();

            $('#all_product_list .each_product').each(function() {
                var brandID = $(this).find('input[name="brand_id"]').val();

                if (brandID != selectedValue) {
                    $(this).addClass('d-none');
                }else {
                    $(this).removeClass('d-none');
                }
            });

            $('#filter_brand').modal('hide');
        });

        // Manufacturer
        $('#filter_manufacturer .modal-footer .save-manufacturer').on('click', function() {
            var selectedValue = $('#filter_manufacturer input[name="filter_manufacturer_id"]:checked').val();
            // console.log(selectedValue)
            $('#all_product_list .each_product').each(function() {
                var manufacturerID = $(this).find('input[name="manufacturer_id"]').val();

                if (manufacturerID != selectedValue) {
                    $(this).addClass('d-none');
                }else {
                    $(this).removeClass('d-none');
                }
            });

            $('#filter_manufacturer').modal('hide');
        });

        // Generic
        $('#filter_generic .modal-footer .save-generic').on('click', function() {
            var selectedValue = $('#filter_generic input[name="filter_generic_id"]:checked').val();

            $('#all_product_list .each_product').each(function() {
                var genericID = $(this).find('input[name="generic_id"]').val();

                if (genericID != selectedValue) {
                    $(this).addClass('d-none');
                }else {
                    $(this).removeClass('d-none');
                }
            });

            $('#filter_generic').modal('hide');
        });

        // Clear All Filter
        $(document).on('click', '.clear_all_filter', function() {
            $('#all_product_list .each_product').each(function() {
                if($(this).hasClass('d-none')){
                    $(this).removeClass('d-none');
                }
            });

            $(this).closest('.modal').modal('hide');
        })

        // For Payment Print
        $('#payment_print').on('click', function(){
            if(checkContact()){
                let invoice_row_data = [];
                $(`#${tableBodyId} .invoiceRow`).each(function() {
                    let current_row = $(this).closest('tr');
                    let current_uom_id = current_row.find('.invoice_unit_select').val();
                    let referenceUom = toGetReferenceUOMRelate(current_uom_id);
                    let product_name = current_row.find('.product-name').text();
                    let variation = current_row.find('.variation_value_and_name').text();
                    let quantity = current_row.find('input[name="quantity[]"]').val();
                    let uomName = uoms.filter(uom => uom.id == current_uom_id)[0].name;
                    let price = current_row.find('input[name="selling_price[]"]').val();
                    let subtotal = current_row.find('.subtotal_price').text();

                    let rowData = { referenceUom, product_name, variation, quantity, uomName, price, subtotal };

                    invoice_row_data.push(rowData);
                });

                let total = $(`#${infoPriceId} .sb-total`).text();
                let discount = $(`#${infoPriceId} .sb-discount`).text();
                let paid = $('.print_paid').text();
                let balance = $('.print_balance').text();
                let change = $('.print_change').text();

                let business_location = $('select[name="business_location_id"] option:selected').text();
                let customer_name = $(`#${contact_id} option:selected`).text();
                let customer_id = $(`#${contact_id} option:selected`).val();
                let filtered_customer = customers.filter( customer => customer.id === parseInt(customer_id))[0];
                let customer_mobile = filtered_customer.mobile ? filtered_customer.mobile : '';

                let totalPriceAndOtherData = {total, discount, paid, balance, change, business_location, customer_name, customer_mobile};                

                let dataForSale = datasForSale('delivered');
                $.ajax({
                    url: `/sell/create`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: dataForSale,
                    success: function(results){
                        if(results.status==200){
                            let invoice_no = results.data;
                            $(`#${tableBodyId} tr`).remove();
                            totalSubtotalAmountCalculate();
                            totalDisPrice();
                            $('#payment_info .print_paid').text(0);
                            $('#payment_info .print_change').text(0);
                            $('#payment_info .print_balance').text(0);
                            $('input[name="pay_amount"]').val(0);
                            
                            let data = { invoice_row_data, totalPriceAndOtherData , invoice_no };
                            $.ajax({
                                url: '/pos/payment-print-layout',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: data,
                                success: function(response){
                                    var iframe = $('<iframe>', {
                                        'height': '0px',
                                        'width': '0px',
                                        'frameborder': '0',
                                        'css': {
                                            'display': 'none'
                                        }
                                    }).appendTo('body')[0];
                                    // Write the invoice HTML and styles to the iframe document
                                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                                    iframeDoc.open();
                                    iframeDoc.write(response.html);
                                    iframeDoc.close();
                        
                                    // Trigger the print dialog
                                    iframe.contentWindow.focus();
                                    setTimeout(() => {
                                        iframe.contentWindow.print();
                                    }, 500);
                                }
                            })
                        }
                    },
                    error:function(e){
                        status=e.status;
                        if(status==405){
                            warning('Method Not Allow!');
                        }else if(status==419){
                            error('Session Expired')
                        }else{
                            error(' Something Went Wrong! Error Status: '+status )
                        };
                    },
                })
            }
        })
        

        // Form Repeater payment amount add
        $(document).on('click', '.add-payment-row', function(){
            $('#payment_row_body').append(paymentRow);
            $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
        })

        // Form Repeater payment amount remove
        $(document).on('click', '.remove_payment_row', function(){
            $(this).closest('.payment_row').remove();

            let payable_amount = $(`#${infoPriceId} .sb-total-amount`).text();

            let pay_amount = 0;
            $('#payment_row_body .payment_row').each(function() {
                let parent = $(this).closest('.payment_row');
                let each_amount = parent.find('input[name="pay_amount"]').val();
                pay_amount += isNullOrNan(each_amount);
            })

            let change = Math.abs(isNullOrNan(payable_amount) - pay_amount);
            $('#payment_info .print_paid').text(pay_amount);
            $('#payment_info .print_change').text(change);
            $('#payment_info .print_balance').text(balance);
        })

        // when opening payment info modal box
        let isPaymentRowAppended = false;
        $('#payment_info').on('shown.bs.modal', function() {
            if(!isPaymentRowAppended){
                $('#payment_row_body').append(paymentRow);
                $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                isPaymentRowAppended = true;
            }

            let payable_amount = $(`#${infoPriceId} .sb-total-amount`).text();
            let pay_amount = 0;
            $(document).on('input', 'input[name="pay_amount"]', function() {
                pay_amount = 0;
                $('#payment_row_body .payment_row').each(function() {
                    let parent = $(this).closest('.payment_row');
                    let each_amount = parent.find('input[name="pay_amount"]').val();
                    pay_amount += isNullOrNan(each_amount);
                })

                // let change = Math.abs(isNullOrNan(payable_amount) - pay_amount);
                let change = isNullOrNan(payable_amount) < pay_amount ? pay_amount - isNullOrNan(payable_amount) : '0';
                let balance = isNullOrNan(payable_amount) > pay_amount ? isNullOrNan(payable_amount) - pay_amount : '0';
                $('#payment_info .print_paid').text(pay_amount);
                $('#payment_info .print_change').text(change);
                $('#payment_info .print_balance').text(balance);
            })

            $('#payment_info .print_payable_amount').text(payable_amount);
        })

        // Sale With Credit
        $(document).on('click', '.sale_credit', function() {
            if(checkContact()){
                let dataForSale = datasForSale('delivered');
                ajaxToStorePosData(dataForSale);
            }
        })

        // Sale With Order
        $(document).on('click', '.sale_order', function() {
            if(checkContact()){
                let dataForSale = datasForSale('order');
                ajaxToStorePosData(dataForSale);
            }
        })

        // Sale With Draft
        $(document).on('click', '.sale_draft', function() {
            if(checkContact()){
                let dataForSale = datasForSale('draft');
                ajaxToStorePosData(dataForSale);
            }
        })

        // Sale With Payment
        $(document).on('click', '.payment_save_btn', function() {
            if(checkContact()){
                let dataForSale = datasForSale('delivered');
                ajaxToStorePosData(dataForSale);
            }
        })

        // Begin::Contact Add Modal Box
        $('form#add_contact_form').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                success: function(response){
                    if (response.success == true) {
                        $('#contact_add_modal').modal('hide');
                        success(response.msg);

                        // Clear the input fields in the modal form
                        $('#add_contact_form')[0].reset();

                        getContacts(response.new_contact_id);
                    }
                },
                error: function(result) {
                    error(result.responseJSON.errors, 'Something went wrong');
                }
            })
        })
        // End

        // Begin::Contact Edit Modal Box
        $(document).on('change', `#${contact_id}`, function() {
            let id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();
            var newHref = "{{ route('pos.contact.edit', ':id') }}";
            newHref = newHref.replace(':id', id);
            $('.contact_edit_btn').attr('contact-href', newHref);
        })

        $(document).on('click', `#${contact_edit_btn_id}`, function() {
            if(checkContact()){
                let href = $(this).attr('contact-href');
                $('#edit_contact_modal').load(href, function() {
                    $(this).modal('show');

                    $('form#edit_customer_form').submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            method: 'PUT',
                            url: $(this).attr('action'),
                            dataType: 'json',
                            data: data,

                            success: function(result) {
                                if (result.success == true) {
                                    $('#edit_contact_modal').modal('hide');
                                    success(result.msg);

                                     // Clear the input fields in the modal form
                                    $('#edit_customer_form')[0].reset();

                                    getContacts(result.id);
                                }
                            },
                            error: function(result) {
                                error(result.responseJSON.errors, 'Something went wrong');
                            }
                        });
                    });
                })
            }
        })
        // End

        // Begin:: contact edit phone
        $(document).on('click', `#${contact_phone_btn_id}`, function() {
            if(checkContact()){
                $('#contact_edit_phone').modal('show');
                let contact_name = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer option:selected').text() : $('#sb_pos_customer option:selected').text();
                let contact_id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();

                // add action route
                var form = $('#contact_edit_phone').find('#edit_customer_phone_form');
                var actionRoute = `/pos/contact-phone/update/${contact_id}`;
                form.attr('action', actionRoute);

                let filtered_customer = customers.filter( customer => customer.id === parseInt(contact_id))[0];
                let customer_mobile = filtered_customer.mobile ? filtered_customer.mobile : '';

                $('#contact_edit_phone input[name="customer_name"]').val(contact_name); // set customer name
                $('#contact_edit_phone input[name="customer_phone"]').val(customer_mobile); // set customer phone
            }
        })

        $('form#edit_customer_phone_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: 'PUT',
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,

                success: function(result) {
                    if (result.success == true) {
                        $('#contact_edit_phone').modal('hide');
                        success(result.msg);
                        getContacts(result.id);
                    }
                },
                error: function(result) {
                    //
                }
            });
        });
        // End

        // Begin:: quick add product
        $('form#quick_add_product_form').submit(function(e) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    if (response.success == true) {
                        $('#quick_add_product_modal').modal('hide');
                        success(response.message);

                        // Clear the input fields in the modal form
                        $('#quick_add_product_form')[0].reset();

                        getProductVariations();
                    }
                },
                error: function(result) {
                    //
                }
            })
        })
        // End
    });
</script>
