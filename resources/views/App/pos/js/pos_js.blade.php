<script>
    let defaultPriceListId=@json(getSystemData('defaultPriceListId'));
    let defaultCustomerId=@json(getSystemData('defaultCustomer'));
    let locations=@json($locations);
    let setting=@json($setting);
    let symbol=@json($currencySymbol);
    var price_lists_with_location = [];
    var uoms = @json($uoms ?? null);
    var suggestionProduct=[];
    var posRegisterId=@json($posRegisterId);
    var sessionId=@json(request('sessionId'));
    var posRegister=@json($posRegister);
    var layoutId=@json($posRegister->invoice_layout_id);
    var editSale=@json($sale ?? []);
    var saleId=editSale ? editSale.id :'';
    var route=null;
    var getProductVariations;
    var creditLimit=0;
    var receiveAbleAmount=0;
    var uniqueNameId=1;
    var defaultCustomer=defaultCustomerId ?? 1;
    var contactId=editSale.contact_id ?? defaultCustomerId ;
    let isGetContact =editSale ? false:true;

    var accounting_method=setting.accounting_method;
    var lotControl=setting.lot_control;
    @if(isset($sale))
        route="{{route('update_sale',$sale->id)}}"
    @endif
    let productsOnSelectData=[];
</script>
<script src="{{ asset('customJs/debounce.js') }}"></script>
{{-- <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script> --}}
{{-- <script src="{{ asset('customJs/pos/calc_pos.js') }}"></script> --}}
<script src="{{ asset('customJs/pos/priceStageCal.js') }}"></script>
{{-- <script src="{{ asset('customJs/pos/sale.js') }}"></script> --}}
<script src="{{ asset('customJs/pos/recent.js') }}"></script>
<script src="{{ asset('customJs/pos/filter_products.js') }}"></script>

<script>
    const myModalEl = document.getElementById('suggestionModal')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $('.modal-backdrop').remove();
    })

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
    $(document).ready(function() {
        var editSaleDetails=@json($saleDetails ?? []) ?? [];
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

        let currentStockBalance = @json($currentStockBalance ?? null);
        let product_with_variations = [];
        let customers = [];
        let customer_price_list = null;
        $('.tableSelect').select2();
        // let price_lists_with_location = [];

        let products = null;
        let isNullOrNan = (val,defVal=0) => {
            let v=parseFloat(val);

            if(v === '' || v === null || isNaN(v)){
                return defVal;
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
                            <div class=" {{isUsePaymnetAcc() ? 'col-md-5 col-sm-5 col-12 ' : 'col-12'}}">
                                <label class="form-label fw-bold">Amount:</label>
                                <input type="text" class="form-control form-control-sm mb-2 mb-md-0" name="pay_amount" placeholder="" value=""/>
                            </div>
                            @if (isUsePaymnetAcc())
                                <div class="col-md-5 col-12 ">
                                    <label class="form-label fw-bold">Payment Account:</label>
                                    <select class="form-select mb-2 form-select-sm paymentRepeaterAccount payment_account" name="payment_account"  data-control="select2" data-hide-search="true" data-placeholder="Select Payment Account">
                                        <option></option>
                                        @foreach ($paymentAcc as $acc)
                                            <option value="{{$acc->id}}">{{$acc->name}} ({{$acc->account_number}})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 col-sm-2 col-2 ">
                                    <button class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove_payment_row">
                                        <i class="fas fa-trash fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            `
        };

        let searchNewRow = (index, productId, productType, variationId = null, name, countOrSku, variNameOrSelectAll, unit, css_class,stock) => {
            return `
                <li data-productId="${productId}" data-index="${index}" data-productType="${productType}" data-variationId="${variationId}" id="searchItem" class="list-group-item bg-light cursor-pointer pos-product-search bg-hover-secondary p-3 ${css_class}" id="searchItem">
                    <h4 class="fs-6 ps-7 pt-3 me-4 ${css_class}">
                        ${name} - (${countOrSku}) ${variNameOrSelectAll}
                    </h4>
                    <p class="ps-7 pt-1 ${css_class}">${unit} </p>
                    <span class="ps-7  ${stock== 0 || stock == null ? '' :'d-none'} text-danger">Out Of Stocks</span>
                </li>
            `;
        }

        let invoiceSidebar = (product,forceSplit=false,parentUniqueNameId,parentSaleDetailId=null) => {
            checkAndStoreSelectedProduct(product);
            let variation_value_and_name;
            // Get Variation Value and Variation Template Value Name
            if(product.id){
                let pv_id = product.id;
                $(product_with_variations).each(function(index, element) {
                    if(element.product_variation_id && element.product_variation_id == pv_id){
                        variation_value_and_name = element.vari_tem_name + '- ' + element.vari_tem_val_name;
                    }
                })
            }

            let uomByCategory=product['product']['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })
            let additionalProduct=product.additional_product;
            showSuggestion(additionalProduct,uniqueNameId);
            // console.log(product)
            // getProducts(1, );
            let packaging_id,packagingUom,packaging_quantity,packageQtyForCal,pkgname;
            if(product.product.product_packaging){
                // let packaging=product.product.product_packaging;
                // packaging_id=packaging.id,
                // packagingUom=packaging.uom_id,
                // packaging_quantity=1,
                // packageQtyForCal=packaging.quantity,
                // pkgname=packaging.packaging_name
            }
            let romTags='';
            if(product.product.rom){
                let rom=product.product.rom;
                if(rom){
                    rom.rom_details.forEach(rd=>{
                        romTags+=
                        `
                        <span class="badge badge-secondary">
                            ${rd.product_variation.product.name} x ${rd.quantity} ${rd.uom.short_name}
                        </span>
                        `;
                    })
                }
            }
            let additionProductLink=additionalProduct.length >0 ?
             `
                <div class="cursor-pointer me-1 suggestProductBtn text-decoration-underline text-primary user-select-none"
                    data-varid="${product.id}" data-uniqueNameId="${uniqueNameId}" data-parentUniqueNameId=${parentUniqueNameId}>
                    Additional Product
                </div>
                <input type="hidden" value="${uniqueNameId ?? null}" name="isParent" />
            `:`
            <input type="hidden" value="${parentUniqueNameId ?? null}" name="parentUniqueNameId" />
            <input type="hidden" value="${parentSaleDetailId ?? null}" name="parentSaleDetailId" />
            `;
            let stockBalanceText=product.product_type =="storable" ?
            `<span class="fs-7 fw-semibold text-gray-600 stock_quantity_unit stock_quantity_unit_${product.id}">
                ${product.stock_sum_current_quantity * 1}</span> - <span class="fs-7 fw-semibold text-gray-600 stock_quantity_name stock_quantity_${product.variation_id}">
                    ${product.product.uom.name}</span>` : ''
            return `
                <tr class="p-5 fs-9  invoiceRow invoice_row_${product.id} cursor-pointer invoice_sidebar_row_${uniqueNameId} " >
                    <input type="hidden" name="product_id" value="${product.product_id}" />
                    <input type="hidden" name="lot_no" value="0" />
                    <input type="hidden" name="variation_id" value="${product.id}" />
                    <input type="hidden" name="each_selling_price" value="" />
                    <input type="hidden" name="lot_serial_val" value='${accounting_method}'/>
                    <input type="hidden" name="discount_type" value="" />
                    <input type="hidden" name="per_item_discount" value="" />
                    <input type="hidden" name="subtotal_with_discount" value="" />
                    <input type="hidden" name="item_detail_note" value="" />
                    <input type="hidden" name="packaging_quantity" class='packaging_quantity' value="${isNullOrNan(packaging_quantity,1)}" />
                    <input type="hidden" name="packaging_id" class='packaging_id' value="${packaging_id ?? ''}" />
                    <input type="hidden" name="packagingUom" class="form-control packagingUom" value="${packagingUom ?? ''}">
                    <input type="hidden" name="packageQtyForCal" class="form-control packageQtyForCal" value="${packageQtyForCal ?? ''}">
                    <input type="hidden" name="pkgname" class="form-control pkgname" value=${pkgname ?? ''}>
                    <input type="hidden" name="cost_price" value="${product.stock[0] ?product.stock[0].ref_uom_price : 0}" />
                    <input type="hidden" name="_default_sell_price" value="${product.default_selling_price * 1}" />

                    <td class=" text-break text-start fw-bold fs-6 text-gray-700 ">
                        <span class="product-name">
                            ${product.name}
                        </span>
                        <div>${romTags}</div>
                        <div>${stockBalanceText}</div>
                        ${product.has_variation !== 'single' ? `<span class="fs-7 fw-semibold text-gray-600 variation_value_and_name">${variation_value_and_name}</span><br>` : ''}
                        <span class="fs-7 fw-semibold text-gray-600 product-sku">SKU : ${product.has_variation == 'single' ?product.sku : product.variation_sku}</span>
                        <br>
                        <div>
                            <span class="pkg-qty">${packaging_quantity?? ''}</span>
                            <span class="pkg">${pkgname?? ''}</span>
                        </div>
                    </td>
                    <td class="min-w-50px ps-0 pe-0 exclude-modal">
                        <input type="text" name="selling_price[]" class="form-control form-control-sm" value="${product.default_selling_price * 1}">
                    </td>
                    <td class="exclude-modal">
                        <div class="d-flex flex-column">
                            <div class="position-relative d-flex align-items-center mb-3 input-group flex-nowrap " >

                                <button type="button" class=" px-3 btn btn-sm btn-light btn-icon-gray-00 border-end-0" id="decrease">
                                    <i class="fas fa-minus fs-7"></i>
                                </button>

                                <input type="text" class=" form-control form-control-sm border-0 text-center  fw-bold text-gray-800 quantity_input qtyInp" name="quantity[]"  value="${isNullOrNan(packageQtyForCal,1) }" />

                                <button type="button" class=" px-3 btn btn-sm btn-light btn-icon-gray-600 border-end-0" id="increase">
                                    <i class="fas fa-plus"></i>
                                </button>

                            </div>
                            <span class="text-danger-emphasis stock_alert stock_alert_${product.id} d-none fs-7 p-2">* Out of Stock</span>
                            <select class=" form-select form-select-sm invoice_unit_select" data-control="select2">
                                ${uomsData.map(unit => `<option value="${unit.id}" ${unit.id === (packagingUom ?? product.product.uom.id) ? 'selected' : ''}>${unit.text}</option>`).join('')}
                            </select>

                        </div>
                    </td>
                    <td class="fs-6 fw-bold subtotal_price_${product.id} "><span class="subtotal_price">${product.default_selling_price * 1}</span> ${symbol}</td>
                    <td class="exclude-modal text-end" id="delete-item">
                            <div>
                            <i class="fas fa-trash me-3 text-danger cursor-pointer" ></i>
                            </div>
                            ${additionProductLink}
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





        let calPrice = ($element) => {
            let quantity = $element.closest('tr').find('input[name="quantity[]"]').val();
            console.log(quantity ?? 'haha','-dsfwie');
            let default_price = $element.closest('tr').find('input[name="selling_price[]"]').val();
            let perItemDis = $element.closest('tr').find('input[name="per_item_discount"]').val();
            let disType = $element.closest('tr').find('input[name="discount_type"]').val();
            let total_price = default_price * quantity;
            let perItemDiscounts=isNullOrNan(perItemDis) * isNullOrNan(quantity);;
            if(disType === "fixed"){
                $element.closest('tr').find('input[name="subtotal_with_discount"]').val(total_price - perItemDiscounts);
            }
            if(disType === "percentage" || disType === "FOC"){
                let dis_amount_with_price = default_price * (perItemDiscounts / 100);
                $element.closest('tr').find('input[name="subtotal_with_discount"]').val(total_price - dis_amount_with_price);
            }
            $element.closest('tr').find('.subtotal_price').text(total_price);
        }

        let totalSubtotalAmountCalculate = () => {
            let totalSum = 0;
            $(`#${tableBodyId} .subtotal_price`).each(function() {
                let value = isNullOrNan($(this).text());
                totalSum += value;
            });

            $(`#${infoPriceId} .sb-total`).text(pDecimal(totalSum));
            itemCal();
        }

        // cal
        const itemCal=()=>{
            let total=0;
            $(`#${tableBodyId} .invoiceRow`).each(function() {
                let parent = $(this).closest('tr');
                let quantity = parent.find('.quantity_input').val();
                total+=isNullOrNan($(this).find('.qtyInp').val());
            })
            $('.badgeQtyCount').text(total);
            $(`#${infoPriceId} .sb-item-quantity`).text(total);
        }

        // let getPrice = () => {
        //     // console.log(productsOnSelectData)
        //     let contact_pricelist_id = customer_price_list;
        //     let all_pricelist_id = $('#selling_price_group option:selected').val();

        //     let pricelist_id = contact_pricelist_id ? contact_pricelist_id : all_pricelist_id;
        //     $(`#${tableBodyId} tr`).each(function() {
        //         let parent_row = $(this).closest('tr');
        //         let product_variation_id = parent_row.find('input[name="variation_id"]').val();
        //         let uom_id = parent_row.find('.invoice_unit_select option:selected').val();

        //         parent_row.find('input[name="each_selling_price"]').val(pricelist_id);

        //         let quantity = 0;
        //         $(`#${tableBodyId} tr`).each(function() {
        //             let each_uom_id = $(this).closest('tr').find('.invoice_unit_select option:selected').val();
        //             let variation_id = $(this).closest('tr').find('input[name="variation_id"]').val();

        //             if(variation_id == product_variation_id && each_uom_id == uom_id){
        //                 let qty = $(this).closest('tr').find('input[name="quantity[]"]').val();
        //                 quantity += qtyByReferenceUom(each_uom_id, qty);
        //             }
        //         });
        //         let datas = { pricelist_id, product_variation_id, quantity, uom_id };
        //         // getProducts2(datas);
        //         // let price_info = getProducts(datas);
        //         let result_pricelist_id, price;
        //         if(price_info == undefined){
        //             let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
        //             result_pricelist_id = pricelist_id;
        //             price = default_sell_price * 1;
        //         }else{
        //             let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
        //             result_pricelist_id = price_info.price_id;
        //             price = isNaN(price_info.price) ? default_sell_price * 1 : price_info.price;
        //         }

        //         if(price === undefined){
        //             let cost_price = parent_row.find('input[name="cost_price"]').val();
        //             let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
        //             if(all_pricelist_id == 'default_selling_price'){
        //                 parent_row.find('input[name="selling_price[]"]').val(default_sell_price * 1);
        //                 calPrice(parent_row);
        //             }else {
        //                 parent_row.find('input[name="selling_price[]"]').val(cost_price * 1);
        //                 calPrice(parent_row);
        //             }
        //         }

        //         if(price !== undefined && !isNaN(price)){
        //             parent_row.find('input[name="each_selling_price"]').val(result_pricelist_id);
        //             $(`#${tableBodyId} tr`).each(()=>{
        //                 let each_uom_id = $(this).closest('tr').find('.invoice_unit_select option:selected').val();
        //                 let variation_id = $(this).closest('tr').find('input[name="variation_id"]').val();
        //                 if(price==0){
        //                    price=isNullOrNan($(this).closest('tr').find('input[name="selling_price[]"]').val());
        //                 }
        //                 if(variation_id == product_variation_id && each_uom_id == uom_id){
        //                     $(this).closest('tr').find('input[name="selling_price[]"]').val(price * 1);
        //                 }
        //                 calPrice($(this));
        //             });
        //         }
        //     })
        // }

        // let getPriceByEachRow = () => {
        //     let contact_pricelist_id = customer_price_list;
        //     let all_pricelist_id = $('#selling_price_group option:selected').val();
        //     let pricelist_id = contact_pricelist_id ? contact_pricelist_id : all_pricelist_id;

        //     $(`#${tableBodyId} tr`).each(function() {
        //         let parent_row = $(this).closest('tr');
        //         let product_variation_id = parent_row.find('input[name="variation_id"]').val();
        //         let uom_id = parent_row.find('.invoice_unit_select option:selected').val();
        //         let raw_quantity = parent_row.find('input[name="quantity[]"]').val();
        //         let quantity = qtyByReferenceUom(uom_id, raw_quantity);

        //         parent_row.find('input[name="each_selling_price"]').val(pricelist_id);

        //         let datas = { pricelist_id, product_variation_id, quantity, uom_id };
        //         // let price_info = getProducts(datas);
        //         let result_pricelist_id, price;
        //         if(price_info == undefined){
        //             let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
        //             result_pricelist_id = pricelist_id;
        //             price = default_sell_price * 1;
        //         }else{
        //             result_pricelist_id = price_info.price_id;
        //             price = price_info.price;
        //         }

        //         if(price === undefined){
        //             let cost_price = parent_row.find('input[name="cost_price"]').val();
        //             let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
        //             if(all_pricelist_id == 'default_selling_price'){
        //                 parent_row.find('input[name="selling_price[]"]').val(default_sell_price * 1);
        //                 calPrice(parent_row);
        //             }else {
        //                 parent_row.find('input[name="selling_price[]"]').val(cost_price * 1);
        //                 calPrice(parent_row);
        //             }
        //         }

        //         if(price !== undefined && !isNaN(price)){
        //             parent_row.find('input[name="each_selling_price"]').val(result_pricelist_id);
        //             parent_row.find('input[name="selling_price[]"]').val(price * 1);
        //             calPrice(parent_row);
        //         }
        //     })
        // }

        let changeQtyOnUom = (e,newUomId) => {
            let parent = $(`#${tableBodyId}`).find(e).closest(`.invoiceRow`);
            let productId = parent.find('input[name="product_id"]').val();
            let variationId = parent.find('input[name="variation_id"]').val();
            console.log(variationId,'sdf-kkfsri-skdfj' , productsOnSelectData);
            let product = productsOnSelectData.find( product => product.variation_id == variationId );
            let quantity = isNullOrNan(product.stock_sum_current_quantity);

            let lotSerialVal = parent.find('input[name="lot_serial_val"]').val();
            if(lotSerialVal && lotSerialVal!=='fifo' && lotSerialVal!=='lifo'){
                if(product.stock.length >0){

                    let stock=product.stock.find((s)=>s.id==lotSerialVal);
                    quantity=stock.ref_uom_quantity;
                }
            }
            const uoms = product.uom.unit_category.uom_by_category;

            const newUomInfo = uoms.filter(uom => uom.id == newUomId)[0];
            const newUomType = newUomInfo.unit_type;

            const referenceUom = uoms.filter(uom => uom.unit_type == 'reference')[0];
            const refUomType =referenceUom.unit_type;

            let currentRefQty;
            currentRefQty = getReferenceUomInfoByCurrentUomQty(quantity,referenceUom,referenceUom)['qtyByReferenceUom'];

            let result;
            if (refUomType === 'reference' && newUomType === 'bigger') {
                result = currentRefQty / newUomInfo.value;
            } else if (refUomType === 'reference' && newUomType === 'smaller') {
                result = currentRefQty * newUomInfo.value;
            } else {
                result = currentRefQty;
            }

            // let currentStock=parent.find('.stock_quantity_unit').text();
            // console.log(currentStock,'========');
            // let lot_serial_modal_input_val = parent.find('select[name="lot_serial_modal_input"]').val();
            // let selectedLotOption =parent.find('#lot_serial_modal_input').find(':selected');
            // let lotUom=selectedLotOption.data('uomid');
            // let lotQtyForCal = selectedLotOption.data('qty');


            // let variation_id=current_tr.find('input[name="variation_id"]').val();
            // let product=productsOnSelectData.find((pod)=>pod.variation_id==variation_id);
            // let uoms=product.uom.unit_category.uom_by_category;
            // let currentUomId=current_tr.find('.invoice_unit_select').val();


            parent.find('.stock_quantity_unit').text(result);
            parent.find('.stock_quantity_name').text(newUomInfo.name);
            getPrice(parent);

            // false ? getPriceByEachRow() : getPrice();
        }

        let checkAndStoreSelectedProduct = (newSelectedProduct) => {
            console.log(newSelectedProduct,'====sdf');
            let newProductData={
                'product_id':newSelectedProduct.product_id,
                'product_type':newSelectedProduct.product_type,
                'cateogry_id':newSelectedProduct.cateogry_id,
                'product_name':newSelectedProduct.name,
                'variation_name':newSelectedProduct.variation_name,
                'variation_id':newSelectedProduct.id,
                'defaultSellingPrices':newSelectedProduct.default_selling_price,
                'defaultPurchasePrice':newSelectedProduct.default_purchase_price,
                'sellingPrices':newSelectedProduct.uom_selling_price,
                'stock_sum_current_quantity':newSelectedProduct.stock_sum_current_quantity,
                'aviable_qty':newSelectedProduct.stock_sum_current_quantity,
                'validate':true,
                'uom':newSelectedProduct.product.uom,
                'uom_id':newSelectedProduct.product.uom_id,
                'stock':newSelectedProduct.stock,
                'additional_product':newSelectedProduct.product.additional_product,
                'packaging':newSelectedProduct.packaging,
            };
            if(productsOnSelectData.length>0){
                const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newSelectedProduct.product_id && p.variation_id === newSelectedProduct.id);
                if(indexToReplace !== -1){
                    productsOnSelectData[indexToReplace] = newProductData;
                }else{
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }
            }else{
                productsOnSelectData=[...productsOnSelectData,newProductData];
            }
        }


        let checkStock = (e) => {
            let tr_parent = $(e).closest('.invoiceRow');
            let parentTBody = tr_parent.parent().attr('id');

            let variationId = tr_parent.find('input[name="variation_id"]').val();
            let index;
            console.log(productsOnSelectData,'--');
            let product = productsOnSelectData.find(function(pd,i) {
                index = i;
                return  variationId == pd.variation_id;
            });

            const uoms = product.uom.unit_category.uom_by_category;

            const referenceUom =uoms.filter(function ($u) {
                return $u.unit_type == "reference";
            })[0];


                let result=0;
                let resultByStock=[];
                $(`#${parentTBody} .invoice_row_${variationId}`).each(function(){
                    let parent = $(`tbody#${parentTBody}`).find($(this).closest('.invoiceRow'));
                    let quantity = Number(parent.find('input[name="quantity[]"]').val());
                    let uom_id = Number(parent.find('.invoice_unit_select').val());
                    const currentUom = uoms.filter(function ($u) {
                        return $u.id == uom_id;
                    })[0];
                    let refQty = getReferenceUomInfoByCurrentUomQty(quantity,currentUom,referenceUom)['qtyByReferenceUom'];

                    if(product.sale_qty){
                        const saleUoM = uoms.filter(function ($u) {
                            return $u.id == product.uom_id;
                        })[0];
                        refQty -= getReferenceUomInfoByCurrentUomQty(product.sale_qty,saleUoM,referenceUom)['qtyByReferenceUom'];
                    }
                    result += isNullOrNan(refQty);
                    if(lotControl == 'on'){

                        let lot_serial_val = parent.find('input[name="lot_serial_val"]').val();
                        if(lot_serial_val && lot_serial_val!='fifo' && lot_serial_val!='lifo'){
                            let resultLot=resultByStock.findIndex(r=>r.id==lot_serial_val);
                            if(resultLot == -1){
                                console.log('heredeer');
                                if(product.stock){
                                   let stock= product.stock.find(s=>s.id==lot_serial_val);
                                   if(stock){
                                        let stoctLeftQty=stock.current_quantity -refQty
                                        resultByStock=[{
                                            id:stock.id,
                                            current_quantity:stoctLeftQty,
                                        },...resultByStock];
                                   }

                                }
                            }else{
                                resultByStock[0].current_quantity-=refQty;
                            }
                        };
                    }
                })

                // console.log()
                if(product.product_type == 'storable'){
                    let qty=isNullOrNan(productsOnSelectData[index].stock_sum_current_quantity);
                    if(result > productsOnSelectData[index].stock_sum_current_quantity){
                        productsOnSelectData[index].validate=false;
                        $(`.stock_alert_${variationId}`).removeClass('d-none');
                    }else{
                        productsOnSelectData[index].validate=true;
                        $(`.stock_alert_${variationId}`).addClass('d-none');
                    }
                }

                if(lotControl == 'on' ){
                    let checkBylot=resultByStock.find(rs=>rs.current_quantity<0);
                    if(checkBylot){
                        $('input[type="hidden"][name="lot_serial_val"][value='+checkBylot.id+']').closest('.invoiceRow').find(`.stock_alert`).removeClass('d-none');
                    }
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
            let price = isNullOrNan(currentRow.closest('tr').find('input[name="selling_price[]"]').val());
            let dis_type = isNullOrNan(currentRow.closest('tr').find('input[name="discount_type"]').val());
            let dis_amount = isNullOrNan(currentRow.closest('tr').find('input[name="per_item_discount"]').val());
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
                let quantity = parent.find('.quantity_input').val();
                let subtotal_with_discount = parent.find('input[name="subtotal_with_discount"]').val();
                if(subtotal_with_discount !== ''){
                    let result =isNullOrNan(subtotal) - isNullOrNan(subtotal_with_discount);
                    totalDisPrice += result;
                }
                subTotalPrice += isNullOrNan(subtotal);
            })

            let total_amount = subTotalPrice - totalDisPrice;
            $(`#${infoPriceId} .sb-discount`).text(pDecimal(totalDisPrice));
            $(`#${infoPriceId} .sb-total-amount`).text(pDecimal(total_amount));
        }

        let ajaxToStorePosData = (dataForSale) => {
            let qtyValidate = productsOnSelectData.find(function (pd) {
                return pd.validate==false;
            });
            if(qtyValidate){
                error('Products Are Out Of Stock');
                return;
            }
            let url=saleId ?route : `/sell/create`;
            $.ajax({
                url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: dataForSale,
                success: function(results){
                    if(results.status==200){
                        if(saleId){
                            Swal.fire({
                                text: "Successfully Update! Thanks you.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                //sth

                                window.history.back();

                            });
                        }else{
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
                                $('#payment_info .print_paid').text(pDecimal(0));
                                $('#payment_info .print_change').text(pDecimal(0));
                                $('#payment_info .print_balance').text(pDecimal(0));
                                $('input[name="pay_amount"]').val(pDecimal(0));
                                $('#payment_row_body').html('');
                                $('#payment_row_body').append(paymentRow);
                                $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                                $('.reservation_id').val('').trigger('change')
                                $('#paymentForm')[0].reset();
                                ajaxOnContactChange($(`#${contact_id}`).val());
                            });
                        }

                    }
                },
                error:function(e){
                    console.log(e,'--------------');
                    status=e.status;
                    if(status==405){
                        warning('Method Not Allow!');
                    }else if(status==419){
                        error('Session Expired')
                    }else if(status==422){
                        error(e.responseJSON.message)
                    }else{
                        error(' Something Went Wrong! Error Status: '+status )
                    };
                },

            })
        }

        let datasForSale = (status,onlySale=false,payment=false,folio=false) => {
            let business_location_id = $('select[name="business_location_id"]').val();
            let table_id=$('.table_id').val();
            let contact_id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();
            let services=$('#services').val();
            let pos_register_id = posRegisterId;
            let sale_amount = $(`#${infoPriceId} .sb-total`).text();
            let lot_serial_val=$('.lot_serial_val').val();
            let total_item_discount = $(`#${infoPriceId} .sb-discount`).text();
            let extra_discount_type = null;
            let extra_discount_amount = null;
            let total_sale_amount = $(`#${infoPriceId} .sb-total-amount`).text();
            let paid_amount = 0;
            let balance_amount = total_sale_amount;
            let currency_id = null;
            let multiPayment=[];
            let reservation_id=null;
            if(payment){
                paid_amount = isNullOrNan($('.print_paid').text());
                balance_amount = total_sale_amount - paid_amount;
                let paymentAmountRepeater=$('#payment_amount_repeater');
                let paymentAmountFromRep=paymentAmountRepeater.find('input[name="pay_amount"]');
                let paymentAccountFromRep=paymentAmountRepeater.find('select[name="payment_account"]');
                paymentAmountFromRep.each((i,p) => {
                    multiPayment=[...multiPayment,{
                        payment_amount:$(p).val(),
                        payment_account_id:$(paymentAccountFromRep[i]).val()
                    }]
                });
            }
            if(folio){
                reservation_id=$(`select[name="reservation_id_from_${status}"]`).val();
            }

            let sales ={
                    'saleId':saleId,
                    'business_location_id': business_location_id,
                    'contact_id': contact_id,
                    'status': status,
                    'pos_register_id': pos_register_id,
                    'table_id':table_id,
                    'sale_amount': sale_amount,
                    'total_item_discount': total_item_discount,
                    'extra_discount_type': extra_discount_type,
                    'extra_discount_amount': extra_discount_amount,
                    'total_sale_amount': total_sale_amount,
                    'paid_amount': paid_amount,
                    'balance_amount': balance_amount,
                    'currency_id': currency_id,
                    'services':services,
                    'sessionId':sessionId,
                    'multiPayment':multiPayment,
                    'reservation_id':reservation_id,
                    'lot_serial_val':lot_serial_val,
                }
            if(onlySale==true){
                return sales;
            }


            let sale_details = [];
            $(`#${tableBodyId} .invoiceRow`).each(function() {
                let parent = $(this).closest('tr');
                let product_id = parent.find('input[name="product_id"]').val();
                let sale_detail_id = parent.find('input[name="saleDetail_id"]').val();
                let variation_id = parent.find('input[name="variation_id"]').val();
                let uom_id = parent.find('.invoice_unit_select').val();
                let quantity = parent.find('input[name="quantity[]"]').val();
                let price_list_id = parent.find('input[name="each_selling_price"]').val();
                let uom_price = parent.find('input[name="selling_price[]"]').val();
                let subtotal = parent.find('.subtotal_price').text();
                let discount_type = parent.find('input[name="discount_type"]').val();
                let per_item_discount = parent.find('input[name="per_item_discount"]').val();
                let subtotal_with_discount = parent.find('input[name="subtotal_with_discount"]').val();
                let item_detail_note = parent.find('input[name="item_detail_note"]').val();
                let lot_serial_val = parent.find('input[name="lot_serial_val"]').val();

                let packaging_id = parent.find('input[name="packaging_id"]').val();
                let packaging_quantity = parent.find('input[name="packaging_quantity"]').val();

                let isParent = parent.find('input[name="isParent"]') ? parent.find('input[name="isParent"]').val():null;
                let parentUniqueNameId = parent.find('input[name="parentUniqueNameId"]')?parent.find('input[name="parentUniqueNameId"]').val():'';
                let parentSaleDetailId = parent.find('input[name="parentSaleDetailId"]')?parent.find('input[name="parentSaleDetailId"]').val():'';
                let raw_sale_details = {
                    sale_detail_id,
                    item_detail_note,
                    isParent,
                    parentUniqueNameId,
                    parentSaleDetailId,
                    packaging_quantity,
                    packaging_id,
                    lot_serial_val,
                    'product_id': product_id,
                    'variation_id': variation_id,
                    'uom_id': uom_id,
                    'quantity': quantity,
                    'price_list_id': price_list_id,
                    'uom_price': uom_price,
                    'subtotal': subtotal,
                    'discount_type': discount_type,
                    'per_item_discount': per_item_discount,
                    'subtotal_with_discount': subtotal_with_discount ?? subtotal,
                    'tax_amount': null,
                    'subtotal_with_tax': null,
                    'currency_id': null,
                    'delivered_quantity': null,

                };
                sale_details.push(raw_sale_details);
            })
            let type = 'pos';
            let data = {...sales,
                 'sale_details':sale_details,
                 type}
            return data;
        }

        let setReceivableAmount = (amount) => {
            let price = isNullOrNan(amount);
            receiveAbleAmount=amount;
            $(document).find('.receivable-amount').text(pDecimal(price));
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
                        let text=item.full_name+'-'+'('+ `${item.mobile !=null ? item.mobile :'-'} ` +')';
                        var option = $("<option>")
                            .val(item.id)
                            .text(text);
                        selectedElement.append(option);
                    });
                    selectedElement.val(id).trigger("change");
                },
                error: function(error) {
                    //
                }
            }).then(()=>{
                setTimeout(() => {
                    let currentCustomer=customers.find(c=>c.id==id) ?? '';
                    let priceListId=currentCustomer.price_list_id ?? defaultPriceListId;
                    $('#selling_price_group').val(priceListId).trigger('change');
                }, 500);
            });
        }

        // !IMPORTANT => PRODUCT VARIATIONS ICON TO SHOW
        getProductVariations = () => {
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
                        <div class="p-1 col-lg-2 col-md-2 col-6 min-w-125px cursor-pointer each_product user-select-none">
                            <div class="card h-100 clickable-card">
                                <input type="hidden" name="category_id" value="${item.category_id}">
                                <input type="hidden" name="sub_category_id" value="${item.sub_category_id}">
                                <input type="hidden" name="brand_id" value="${item.brand_id}">
                                <input type="hidden" name="manufacturer_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="generic_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="product_id" value="${item.id}">
                                <input type="hidden" name="product_variation_id" value="${item.product_variation_id}">
                                <div class="card-body text-center p-3">
                                  ${item.image ? `<img src="/storage/product-image/${item.image}" class="rounded-3 mb-4 w-60px h-60px w-xxl-100px h-xxl-100px" alt="" />` :
                                    `<img src="{{asset('assets/media/svg/files/blank-image.svg')}}" class="rounded-3 theme-light-show mb-4 w-60px h-60px w-xxl-100px h-xxl-100px" alt="" />
                                    <img src="{{asset('assets/media/svg/files/blank-image-dark.svg')}}" class="rounded-3 theme-dark-show mb-4 w-60px h-60px w-xxl-100px h-xxl-100px" alt="" />`}
                                    <div class="mb-2">
                                        <div class="text-center">
                                            ${item.receipe_of_material_id?'<i class="fa-solid fa-cubes text-gray-500 fs-9 "></i>':''}
                                            <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-7 mb-3 pos-product-name">${item.name}</span>
                                            <span class="text-gray-400 fw-semibold d-block fs-8 mt-n1">${item.vari_tem_name ? item.vari_tem_name : ''} - ${item.vari_tem_val_name ? item.vari_tem_val_name : ''}</span>
                                        </div>
                                    </div>
                                    <span class="text-primary text-end fw-bold fs-6">${item.default_selling_price ?? ''}</span>

                                </div>
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
        $(document).on('click','.each_product',function(){
            $(this).find('.clickable-card').addClass("border border-1 border-primary m-1");
            setTimeout(() => {
                $(this).find('.clickable-card').removeClass("border border-1 border-primary m-1");
            }, 100);
        })

        let isGetProductVariations = false;
        if(!isGetProductVariations){
            getProductVariations();
            isGetProductVariations = true;
        }

        if(!isGetContact){
            getContacts(contactId);
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
                url: `/sell/get/product/v2`,
                type: 'GET',
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
                    if(results.length > 0){
                        $(results).each(function(index, element){
                            let css_class='';
                            if(element.product_type=='storable'){
                                 css_class = element.stock_sum_current_quantity !== 0  ? " " : " text-gray-500 order-3 not-use";
                            }
                            let product_countOrSku = element.has_variation === 'variable' ? element.variation_sku : element.sku;
                            // let stock_qty = element.stock_sum_current_quantity !== 0 ? element.stock_sum_current_quantity * 1 + ' ' + element.smallest_unit : 'Out of Stocks';
                            let vari_name_or_selectAll = element.has_variation === 'variable' ? element.variation_name : 'select all';
                            let unit = element.product.uom.name;

                            $('#search_container').append(searchNewRow(index, element.product_id, element.has_variation, element.id, element.name, product_countOrSku, vari_name_or_selectAll, unit, css_class,element.stock_sum_current_quantity));
                            if(results.length==1){
                                setTimeout(() => {
                                    $(`[data-index="${index}"]`).click();
                                }, 500);

                            }
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
            let index = $(this).attr('data-index');

            let selected_product = products[index];
            if(selected_product.product_type =='storable'
            && (selected_product.stock_sum_current_quantity == 0 || selected_product.stock_sum_current_quantity === null)){
                error('Out Of Stock');
                return;
            }
            $('input[name="pos_product_search"]').val('');
            $(this).closest(`.pos-product-search`).remove();

            // if(selected_product.has_variation === 'variable'){
            //     let variation = selected_product.product_variations;
            //     variation.forEach(variation => {
            //         let filteredId = products.filter( p => p.variation_id === variation.id);
            //         let newInvoiceSidebar = $(invoiceSidebar(filteredId[0]));
            //         $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
            //         suggestionProductEvent();
            //         uniqueNameId++;
            //         $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
            //         changeQtyOnUom(newInvoiceSidebar, filteredId[0].uom.id);
            //     });
            //     totalSubtotalAmountCalculate();
            //     totalDisPrice();
            //     return;
            // }
            let newInvoiceSidebar = $(invoiceSidebar(selected_product));
            $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
            suggestionProductEvent();
            uniqueNameId++;
            $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
            changeQtyOnUom(newInvoiceSidebar, selected_product.product.uom.id);
            totalSubtotalAmountCalculate();
            totalDisPrice();
            checkStock(newInvoiceSidebar);
        })

        $('#all_product_list').on('click', '.each_product', function(e) {
            let variation_id = $(this).find('input[name="product_variation_id"]').val();
            let checkProduct= productsOnSelectData.find(p=>p.variation_id==variation_id);
            let ParentRow=$(`.invoice_row_${variation_id}`);
            // calPrice(ParentRow);
            if(setting.enable_row == 0 ){
               if(checkProduct && ParentRow.length){
                    let qtyInput=ParentRow.find(`.quantity_input`);
                    let selectQtyInputVal=qtyInput.val();
                    let val=isNullOrNan(selectQtyInputVal);
                    qtyInput.val(val+1);
                    // alert(val);
                    getPrice(ParentRow);
                    calPrice(ParentRow);
                    totalSubtotalAmountCalculate();
                    checkStock(ParentRow);
                    hideCalDisPrice(ParentRow);
                    totalDisPrice();
                    return ;
               }
            }
            var selectedLocation = $('#business_location_id').val();
            if(selectedLocation === ''){
                warning('Select location!')
                return;
            }
            let business_location_id = selectedLocation;
            let query = $(this).find('.pos-product-name').text();
            let data = {
                business_location_id, query, variation_id
            }

            $.ajax({
                url: `/sell/get/product/v2`,
                type: 'GET',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
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
                    console.log(results);
                    if(results.length>0 && results[0].product_type=="storable"){
                        if(results[0].stock_sum_current_quantity == 0 || results[0].stock_sum_current_quantity == '' || results[0].stock_sum_current_quantity == null){
                            error('Out of stock');
                            return;
                        }
                    }
                    if(results[0].has_variation === "single"){
                        let product = results[0];
                        let newInvoiceSidebar = $(invoiceSidebar(product));
                        $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                        uniqueNameId++;
                        suggestionProductEvent();
                        $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                        changeQtyOnUom(newInvoiceSidebar, product.product.uom.id);
                        calPrice(newInvoiceSidebar);
                        totalSubtotalAmountCalculate();
                        totalDisPrice();
                        return;
                    }
                    let product = results[0];
                    let newInvoiceSidebar = $(invoiceSidebar(product));
                    uniqueNameId++;
                    $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                    suggestionProductEvent();
                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                    changeQtyOnUom(newInvoiceSidebar, product.product.uom.id);
                    calPrice(newInvoiceSidebar);
                    totalSubtotalAmountCalculate();
                    totalDisPrice();
                },
                error:function(e){
                    console.log(e);
                }

            })
        })
         function suggestionProductEvent() {
            $('.suggestProductBtn').off('click').on('click',function(){
                let variationId=$(this).data('varid');
                let parentuiqId=$(this).data('uniquenameid');
                let parentSaleDetailId=$(this).data('parentsaledetailid')?? null;
                let product = productsOnSelectData.find(function(pd) {
                    return  variationId == pd.variation_id;
                });
                let adp=product.additional_product;
                showSuggestion(adp,parentuiqId,parentSaleDetailId);
            })

        }

        function showSuggestion(additionalProduct,unique_name_id,parentSaleDetailId=null) {
            let modal = $('#suggestionModal');
            let isModalOpen = modal.hasClass('show');
            if(additionalProduct.length>0 && !isModalOpen){
                $('#suggestionProducts').html('');
                modal = new bootstrap.Modal($('#suggestionModal'));
                additionalProduct.forEach(ap => {
                    let productInfo=ap.product_variation.product;
                    let product={name:productInfo.name,id:productInfo.id};
                    let variation_id=ap.product_variation.id;
                    let stock_sum_current_quantity=ap.stock_sum_current_quantity;
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
                        <div class="position-relative main_div" data-productid="${product.id}" data-varid="${variation_id}" data-qty="${qty}"  data-uomid="${uomId}" data-parentsaledetailid=${parentSaleDetailId}>
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
                            let locationId=$('[name="business_location_id"]').val();
                            let parentSaleDetailId=$(this).data('parentsaledetailid');
                            let qty=$(this).data('qty');

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
                                    let product=results;
                                    if(results.total_current_stock_qty == 0 ||results.total_current_stock_qty == null || results.total_current_stock_qty ==undefined){
                                        error('Product Out Of Stock');
                                        return;
                                    }
                                    let newInvoiceSidebar = $(invoiceSidebar(product,false,unique_name_id,parentSaleDetailId));
                                    $(`#${tableBodyId} .invoice_sidebar_row_${unique_name_id}`).after(newInvoiceSidebar);
                                    // $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                                    uniqueNameId++;
                                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                                    setTimeout(() => {
                                        changeQtyOnUom(newInvoiceSidebar, product.uom.id);
                                        suggestionProductEvent()
                                        totalSubtotalAmountCalculate();
                                        totalDisPrice();
                                    }, 50);
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
        $(document).on('input', 'input[name="selling_price[]"]', function() {
            calPrice($(this));
            totalSubtotalAmountCalculate();
            totalDisPrice();
        })

        $(document).on('change', '.invoice_unit_select', function(e) {
            let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
            let selected_uom_id = $(this).val();
            let product_id = parent.find('input[name="product_id"]').val();
            packaging($(this),'*');
            checkStock($(this));
            changeQtyOnUom($(this), selected_uom_id);
            calPrice($(this));
            totalSubtotalAmountCalculate();
            hideCalDisPrice($(this));
            totalDisPrice();
        })

        // for increase and decrease SERVICE ITEM QUANTITY
        $(document).on('click', '#increase', function() {
            let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
            let incVal = parent.find('input[name="quantity[]"]');
            let value = parseInt(incVal.val()) + 1;
            incVal.val(value);
            packaging($(this),'/');
            getPrice($(this));
            // false ? getPriceByEachRow() : getPrice();
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
            itemCal();
        })
        $(document).on('input', '.quantity_input', function() {
            packaging($(this),'/');
            getPrice($(this));
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
            itemCal();

        })
        $(document).on('click', '#decrease', function() {
            let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
            let decVal = parent.find('input[name="quantity[]"]');
            getPrice(parent);
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);
            // false ? getPriceByEachRow() : getPrice();
            packaging($(this),'/');
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
            itemCal();
        })
        function processTableRows() {
            $(`#${tableBodyId} tr`).each(function() {
                let parent = $(this).closest('tr');
                getPrice(parent);
                calPrice(parent);
                totalSubtotalAmountCalculate();
                totalDisPrice();
            });
        }
        // change price list
        $(document).on('change', '#selling_price_group', function() {
            getPriceList($(this).val(),processTableRows);
        })



        // Begin::Discount Modal
        let current_tr;
        $(document).on('click', '.invoiceRow td:not(.exclude-modal)', function(event) {
            event.stopPropagation();
            current_tr = $(this).closest('tr');
            let status = current_tr.data('status');
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
            let item_detail_note = current_tr.find('input[name="item_detail_note"]').val();

            let packaging_id = current_tr.find('input[name="packaging_id"]').val();
            let packaging_quantity = current_tr.find('input[name="packaging_quantity"]').val();
            let lot_serial_val = current_tr.find('input[name="lot_serial_val"]').val();
            // let filtered_product = productsOnSelectData.filter( item => item.product_id == product_id && item.variation_id == variation_id);
            $('#packaging_modal').empty();
            $('#lot_serial_modal_input').empty();
            $('#lot_serial_modal_input').empty();
            let product=productsOnSelectData.find(p=>p.variation_id==variation_id);

            let batchOption='';
            let defaultStockOption=`<option value='${accounting_method}' selected data-expiredDate=""  data-qty="lifo" data-uomid="ref">${accounting_method.toUpperCase()}</option>`;
            if(product.stock){
                product.stock.forEach(s => {
                    batchOption+=`
                        <option value="${s.id}" ${lot_serial_val == s.id ?'selected' :''}  data-expiredDate="${s.expired_date ?? ''}" data-qty="${s.current_quantity ?? 0}" data-uomid="${s.ref_uom_id}" >
                            ${s.lot_serial_no}
                        </option>
                    `;
                });
            }
            $('#lot_serial_modal_input').append(defaultStockOption);
            $('#lot_serial_modal_input').append(batchOption);
                // Format options
                const optionFormat = (item) => {
                    if (!item.id) {
                        return item.text;
                    }

                    var span = document.createElement('span');
                    var template = '';

                    template += '<div class="d-flex align-items-center rounded rounded-1" data-qty="hello">';
                    template += '<div class="d-flex flex-column">'
                    template += '<span class="fw-bold fs-7">' + item.text + '</span>';
                    template += '<span class="text-muted fs-8 fw-bold text-dark">' + item.element.getAttribute('data-expiredDate') + '</span>';
                    template += '</div>';
                    template += '</div>';

                    span.innerHTML = template;

                    return $(span);
                }
                $('#lot_serial_modal_input').select2({
                    placeholder: "Select an option",
                    // minimumResultsForSearch: Infinity,
                    templateSelection: optionFormat,
                    templateResult: optionFormat
                });



            let packagingOption='';
            console.log(product,'product.packaging product.packaging  product.packaging');
            if(product.packaging){
                product.packaging.forEach((pk)=>{
                    packagingOption+=`
                        <option value="${pk.id}" ${packaging_id == pk.id ?'selected' :''} data-qty="${pk.quantity}" data-uomid="${pk.uom_id}" data-pkgname="${pk.packaging_name}">${pk.packaging_name}</option>
                    `;
                })
            }
            let defaultOption="<option  disabled selected>Select Packaging</option>"
            $('#packaging_modal').append(defaultOption)
            $('#packaging_modal').append(packagingOption)
            // $('#packaging_modal').select2({
            //     data: productPackaging
            // })
            $('#invoice_row_discount').find('input[name="discount_amount"]').val(per_item_dis!='' ?per_item_dis: 0);
            $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').val(subtotal_with_dis !='' ?subtotal_with_dis: price);
            $('#invoice_row_discount').find('.modal-title').text(`${product_name} - ${product_sku}`);
            $('#invoice_row_discount').find('select[name="each_selling_price"]').val(each_selling_price).trigger('change');
            $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price);
            $('#invoice_row_discount').find('#item_detail_note_input').val(item_detail_note);
            $('#invoice_row_discount').find('.packaging_quantity').val(packaging_quantity);

            if(dis_type !== ''){
                $('#invoice_row_discount').find('select[name="invoice_row_discount_type"]').val(dis_type).trigger('change');
            }

            $('#invoice_row_discount').modal('show');

            $(document).on('change', 'select[name="invoice_row_discount_type"]', function(e){
                $('#invoice_row_discount').find('input[name="discount_amount"]').trigger('input');
                    let disAmt=$('#invoice_row_discount').find('input[name="discount_amount"]');
                if($(this).val()=='foc'){$('.percSymbol').addClass('d-none');
                    $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(0);
                    $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').attr('disabled',true);
                    disAmt.val(0);
                    disAmt.attr('disabled',true);
                }else if($(this).val()=='percentage'){
                    $('.percSymbol').removeClass('d-none');
                    $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').attr('disabled',false);
                    disAmt.attr('disabled',false);
                }
                else{
                    $('.percSymbol').addClass('d-none');
                    $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').attr('disabled',false);
                    disAmt.attr('disabled',false);
                }
            })

            $(document).on('input', 'input[name="discount_amount"]', function(e){
                let discount_amount = $(this).val();
                let current_discount_type = $('#invoice_row_discount').find('select[name="invoice_row_discount_type"]').val();
                let current_price = $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val();
                let quantity = current_tr.find('input[name="quantity[]"]').val();

                let result_dis_calc = calDiscountPrice(current_discount_type, discount_amount, current_price);
                let subtotal_with_discount = isNullOrNan(result_dis_calc );
                $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').val(subtotal_with_discount);
            })

            // $(document).on('change', 'select[name="each_selling_price"]', function(e){
            //     let pricegroup_id = $(this).val();
            //     // current_tr.find('input[name="each_selling_price"]').val(pricegroup_id); // set selling_price_id to each invoice row

            //     if(pricegroup_id === "default_selling_price"){
            //         let price = filtered_product[0].defaultSellingPrices;
            //         // current_tr.find('input[name="selling_price[]"]').val(price);
            //         // calPrice(current_tr);
            //         $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price);
            //         return;
            //     }

            //     let price = filtered_product[0].sellingPrices.filter(function(prices){
            //         return prices.uom_id == uom_id && prices.pricegroup_id == pricegroup_id;
            //     })[0];

            //     if(price !== undefined){
            //         // current_tr.find('input[name="selling_price[]"]').val(price.price_inc_tax * 1);
            //         // calPrice(current_tr);
            //         $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price.price_inc_tax * 1);
            //     }

            //     $('#invoice_row_discount').find('input[name="discount_amount"]').trigger('input');
            // })
        })
        $('#invoice_row_discount #saveExtraSetting').on('click',function(event) {
            // let selling_price_group = $(this).find('select[name="each_selling_price"]').val();
            let parent=$('#invoice_row_discount');
            let uom_price = parent.find('input[name="modal_price_without_dis"]').val();
            let item_detail_note = parent.find('#item_detail_note_input').val();
            let dis_type = parent.find('select[name="invoice_row_discount_type"]').val();
            let dis_amount = parent.find('input[name="discount_amount"]').val();
            let subtotal_with_dis = parent.find('input[name="subtotal_with_discount"]').val();
            let packaging_id = parent.find('select[name="packaging_id"]').val();
            let packaging_quantity = parent.find('input[name="packaging_quantity"]').val();

            let selectedOption =parent.find('select[name="packaging_id"]').find(':selected');
            let packagingUom=selectedOption.data('uomid');
            let packageQtyForCal = selectedOption.data('qty');
            let pkgname = selectedOption.data('pkgname');

            let lot_serial_modal_input_val = parent.find('select[name="lot_serial_modal_input"]').val();
            let selectedLotOption =parent.find('#lot_serial_modal_input').find(':selected');
            let lotUom=selectedLotOption.data('uomid');
            let lotQtyForCal = selectedLotOption.data('qty');


            let variation_id=current_tr.find('input[name="variation_id"]').val();
            let product=productsOnSelectData.find((pod)=>pod.variation_id==variation_id);
            let uoms=product.uom.unit_category.uom_by_category;
            let currentUomId=current_tr.find('.invoice_unit_select').val();

            // let unitQtyValByUom=changeQtyOnUom2(currentUomId,packagingUom,unitQty,uoms).resultQty;
            // console.log(currentUomId,lotUom,lotQtyForCal,uoms);
            if(lot_serial_modal_input_val != 'fifo' && lot_serial_modal_input_val !='lifo' && lotControl == 'on'){
                let qtyByCurrentUnit= changeQtyOnUom2(lotUom,currentUomId,lotQtyForCal,uoms).resultQty;
                current_tr.find('.stock_quantity_unit').text(qtyByCurrentUnit);
            }else{
                let qtyByCurrentUnit= changeQtyOnUom2(product.uom_id,currentUomId,product.stock_sum_current_quantity,uoms).resultQty;
                current_tr.find('.stock_quantity_unit').text(qtyByCurrentUnit);
            }

            // current_tr.find('input[name="each_selling_price"]').val(selling_price_group);
            current_tr.find('input[name="discount_type"]').val(dis_type);
            if(dis_type.toLowerCase() == 'foc'){
                current_tr.find('input[name="per_item_discount"]').val(0);
                current_tr.find('input[name="subtotal_with_discount"]').val(0);
                current_tr.find('input[name="selling_price[]"]').val(0).attr('disabled',true);
            }else{
                current_tr.find('input[name="selling_price[]"]').val(pDecimal(uom_price)).attr('disabled',false);
                current_tr.find('input[name="per_item_discount"]').val(dis_amount);
                current_tr.find('input[name="subtotal_with_discount"]').val(subtotal_with_dis);
                current_tr.find('input[name="item_detail_note"]').val(item_detail_note);
            }
            current_tr.find('input[name="packaging_id"]').val(packaging_id);
            current_tr.find('input[name="lot_serial_val"]').val(lot_serial_modal_input_val);
            current_tr.find('input[name="packaging_quantity"]').val(packaging_quantity);
            current_tr.find('input[name="packagingUom"]').val(packagingUom);
            current_tr.find('input[name="packageQtyForCal"]').val(packageQtyForCal);
            current_tr.find('input[name="pkgname"]').val(pkgname);
            packaging(current_tr,'*');
            calPrice(current_tr);
            totalDisPrice();
            itemCal();
        });
        // End
        const packaging=(e,operator)=>{
            let parent = $(e).closest('tr');
            let unitQty=parent.find('.quantity_input').val();
            let packagingUom=parent.find('input[name="packagingUom"]').val();
            let packageQtyForCal = parent.find('input[name="packageQtyForCal"]').val();
            let packageInputQty=parent.find('.packaging_quantity').val();
            let pkgname=parent.find('.pkgname').val();

            let currentUomId=parent.find('.invoice_unit_select').val();
            let variation_id=parent.find('input[name="variation_id"]').val();
            let product=productsOnSelectData.find((pod)=>pod.variation_id==variation_id);
            let uoms=product.uom.unit_category.uom_by_category;
            if(packageQtyForCal && packagingUom){
                if(operator=='/'){
                    // function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='')
                    let unitQtyValByUom=changeQtyOnUom2(currentUomId,packagingUom,unitQty,uoms).resultQty;
                    let result=qDecimal(isNullOrNan(unitQtyValByUom) / isNullOrNan(packageQtyForCal));
                    parent.find('.packaging_quantity').val(result);
                    $('.pkg-qty').text(result);
                    $('.pkg').text(pkgname);
                    checkStock(e);
                }else{
                // function changeQtyOnUom(uoms,currentQty,currentUomId,newUomId) {
                    let result=isNullOrNan(packageQtyForCal) * isNullOrNan(packageInputQty);
                    let qtyByCurrentUnit= changeQtyOnUom2(packagingUom,currentUomId,result,uoms).resultQty;
                    parent.find('.quantity_input').val(qDecimal(qtyByCurrentUnit));
                    $('.pkg-qty').text(packageInputQty);
                    $('.pkg').text(pkgname);
                    checkStock(e);
                }
            }
        }

        // for delete item
        $(document).on('click', '#delete-item', function() {
            let thisDom=$(this);
            $(this).closest('tr').remove();
            itemCal();
            totalSubtotalAmountCalculate();
            checkStock(thisDom);
            hideCalDisPrice(thisDom);
            packaging(thisDom,'/');
            totalDisPrice();

            checkStock(thisDom);
            totalSubtotalAmountCalculate();
            totalDisPrice();



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
                    let uomName = uoms.filter(uom => uom.id == current_uom_id)[0].short_name;
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

                let totalPriceAndOtherData = {total, discount, paid, balance, change, business_location, customer_name, customer_mobile,posRegisterId};

                let dataForSale = datasForSale('delivered',false,true);




                let total_sale_amount = isNullOrNan($(`#${infoPriceId} .sb-total-amount`).text());
                let paid_amount = isNullOrNan($('.print_paid').text());
                let currentReceiveAble=total_sale_amount-paid_amount;
                let currentReceivieAbleAmt=currentReceiveAble+isNullOrNan(receiveAbleAmount);
                balance_amount = total_sale_amount - paid_amount;
                if(creditLimit < currentReceivieAbleAmt && balance_amount != 0){
                    Swal.fire({
                        text: "Customer's Credit limit is reached.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    })
                }else{
                    $.ajax({
                        url: `/sell/create`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: dataForSale,
                        success: function(results){
                            let id=results.data;
                            if(results.status==200){
                                $.ajax({
                                    url: `/pos/${id}/payment-print-layout/${layoutId}`,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response){
                                        success('successfully sell and print!')
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

                                                // Trigger the print dialogre
                                                iframe.contentWindow.focus();
                                                setTimeout(() => {
                                                    iframe.contentWindow.print();
                                                }, 500);
                                    },
                                    error:function(e){
                                        error('Something Wrong On printing');
                                    },
                                })
                                $(`#${tableBodyId} tr`).remove();
                                totalSubtotalAmountCalculate();
                                totalDisPrice();
                                $('#payment_info .print_paid').text(pDecimal(0));
                                $('#payment_info .print_change').text(pDecimal(0));
                                $('#payment_info .print_balance').text(pDecimal(0));
                                $('input[name="pay_amount"]').val(pDecimal(0));
                                $('#payment_row_body').html('');
                                $('#payment_row_body').append(paymentRow);
                                $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                                $('.reservation_id').val('').trigger('change')
                                $('#paymentForm')[0].reset();
                                ajaxOnContactChange($(`#${contact_id}`).val());
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
            }
        })


        // Form Repeater payment amount add
        $(document).on('click', '.add-payment-row', function(){
            $('#payment_row_body').append(paymentRow);
            $('.paymentRepeaterAccount').select2();
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
            $('#payment_info .print_paid').text(pDecimal(isNullOrNan(pay_amount)));
            $('#payment_info .print_change').text(pDecimal(isNullOrNan(change)));
            $('#payment_info .print_balance').text(pDecimal(isNullOrNan(balance)));
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

            $('input[name="pay_amount"]').val(pDecimal(payable_amount));
            $('.print_paid ').text(pDecimal(payable_amount));
            $('.print_change').text(pDecimal(0));
            $('.print_balance').text(pDecimal(0));
            $(document).on('input', 'input[name="pay_amount"]', function() {
                pay_amount = 0;
                paidAmount=isNullOrNan($('#paidAmount').val()) ?? 0;
                $('#payment_row_body .payment_row').each(function() {
                    let parent = $(this).closest('.payment_row');
                    let each_amount = parent.find('input[name="pay_amount"]').val();
                    pay_amount += isNullOrNan(each_amount);
                })

                // let change = Math.abs(isNullOrNan(payable_amount) - pay_amount);
                pay_amount=pay_amount+ paidAmount;
                let change = isNullOrNan(payable_amount) < pay_amount ? pay_amount - isNullOrNan(payable_amount) : '0';
                let balance = isNullOrNan(payable_amount) > pay_amount ? isNullOrNan(payable_amount) - pay_amount : '0';
                $('#payment_info .print_paid').text(pDecimal(pay_amount) );
                $('#payment_info .print_change').text(pDecimal(change));
                $('#payment_info .print_balance').text(pDecimal(balance));
            })

            $('#payment_info .print_payable_amount').text(pDecimal(payable_amount));
        })

        // Sale With Credit
        $(document).on('click', '.sale_credit', function() {
            if(checkContact()){
                let dataForSale = datasForSale('delivered');
                let total_sale_amount = isNullOrNan($(`#${infoPriceId} .sb-total-amount`).text());
                let currentReceivieAbleAmt=total_sale_amount+isNullOrNan(receiveAbleAmount);
                if(creditLimit < currentReceivieAbleAmt){
                    Swal.fire({
                        text: "Customer's Credit limit is reached.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    })
                }else{
                    ajaxToStorePosData(dataForSale);
                }
            }
        })
        $(document).on('change','#services',function(){
            let val=$(this).val();
            console.log(val);
            if(val != 'dine_in'){
                $('.tableSelect').addClass('d-none');
            }else{
                $('.tableSelect').removeClass('d-none');

            }
        })
        // Sale With Order
        $(document).on('click', '.finalizeOrder', function() {
            if(posRegister.use_for_res==1){
                let table_id = $('select[name="table_id"]').val();
                let services=$('#services').val();
                if(services=='dine_in'){
                    if(table_id == '' || table_id==null){
                        warning('Select Table!');
                        return;
                    }
                }
            }

            if(checkContact()){
                let dataForSale = datasForSale('order',false,false,true);
                if(datasForSale('order').sale_details.length>0){
                    ajaxToStorePosData(dataForSale);
                    $('.tableSelect').removeClass('d-none');
                }else{
                    warning('need to add at least one item')

                $('.tableSelect').removeClass('d-none');
                }
            }
        })

        $(document).on('click', '.order_confirm_modal_btn', function() {
            let saleDetailOrders = datasForSale('order').sale_details;
            $('#services').val('dine_in').trigger('change');
            let tableName = $('#table_nav_id').children("option:selected").text();
            let tableId=$('#table_nav_id').val();
            $('#table-text').text(tableName);
            $('#tableForFinalize').val(tableId).trigger('change');

            if(saleDetailOrders.length>0){
                let orderComponent='';
                saleDetailOrders.forEach(sd => {
                    let product=productsOnSelectData.find((pos) => {
                        return pos.variation_id==sd.variation_id
                    });
                    let uoms=product.uom.unit_category.uom_by_category;
                    let currentUom=uoms.find((uom)=>uom.id==sd.uom_id);
                    let quantity=Number(sd.quantity);
                    orderComponent+=`
                        <div class="separator separator-dashed"></div>
                        <div class="d-flex justify-content-between px-5 py-3">
                            <div class="">
                                <h2 class=" fs-6 fw-bold">${product.product_name} <span class="text-gray-700">${product.variation_name ? '('+ product.variation_name +')' : ''}</span></h2>
                            </div>
                            <div class="">
                                <h2 class=" fs-6 fw-bold"> ${quantity.toFixed(0)} ${currentUom.short_name}</h2>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>
                    `
                });
                    // <div class="d-flex px-5 py-3">
                    //     <div class="">
                    //         <h2 class=" fs-6 fw-bold me-2">note:</h2>
                    //     </div>
                    //     <div class="">
                    //         <h2 class=" fs-6 fw-semibold">
                    //             <p>
                    //             နံနံပင်မထည့်ပါ။
                    //             </p>
                    //         </h2>
                    //     </div>
                    // </div>
                $('#orderDetailConfirm').html(
                    orderComponent
                )
            }else{
                $('#orderDetailConfirm').html(
                        `
                        <div class="d-flex justify-content-center px-5 py-3 ">
                            <div class="">
                                <h2 class=" fs-7 text-gray-500 fw-bold">There is no item for order !</h2>
                            </div>
                        </div>
                        `
                    )
            }

        })
        $(document).on('change','#tableForFinalize',function(){
            $('#table_nav_id').val($(this).val()).trigger('change');
            let tableName = $(this).children("option:selected").text();
            $('#table-text').text(tableName);
        })
        $(document).on('click', '.split_order_modal_btn', function() {
            let saleDetailOrders = datasForSale('order').sale_details;
            $('#services').val('dine_in').trigger('change');
            if(saleDetailOrders){
                isGetContact=false;
                if(saleDetailOrders.length>0){
                    let orderComponent='';
                    editSaleDetails.forEach((sd,index) => {
                        let product=productsOnSelectData.find((pos) => {
                            return pos.variation_id==sd.variation_id
                        });
                        let uoms=product.uom.unit_category.uom_by_category;
                        let currentUom=uoms.find((uom)=>uom.id==sd.uom_id);
                        let quantity=Number(sd.quantity);
                        orderComponent+=`
                            <div class="separator separator-dashed"></div>
                            <div class="d-flex justify-content-between px-5 py-3">
                                <div class="col-7">
                                    <div class="form-check form-check-custom">
                                        <input type="hidden" name="saleId" value="${saleId}" />
                                        <input type="checkbox" class="form-check-input me-3 border-gray-400" name="detailToSplit[${index}][id]" value="${sd.id}" id="${product.product_name}_${index}">
                                        <label class="fs-6 fw-semibold form-label mt-3 user-select-none" for="${product.product_name}_${index}">
                                            <span > ${product.product_name}<span class="text-gray-700">${product.variation_name ? '('+ product.variation_name +')' : ''}</span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group sale_dialer" >
                                        <button class="btn btn-sm btn-icon btn-outline btn-active-color-danger" type="button" data-kt-dialer-control="decrease">
                                            <i class="fa-solid fa-minus fs-2"></i>
                                        </button>
                                        <input type="text" class="form-control form-control-sm quantity form-control-sm text-center quantity"   placeholder="quantity"  name="detailToSplit[${index}][quantity]" value=" ${quantity.toFixed(0)}" data-kt-dialer-control="input"/>
                                        <button class="btn btn-sm btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
                                            <i class="fa-solid fa-plus fs-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `

                    });
                                    // <h2 class=" fs-6 fw-bold"> ${quantity.toFixed(0)} ${currentUom.short_name}</h2>
                        // <div class="d-flex px-5 py-3">
                        //     <div class="">
                        //         <h2 class=" fs-6 fw-bold me-2">note:</h2>
                        //     </div>
                        //     <div class="">
                        //         <h2 class=" fs-6 fw-semibold">
                        //             <p>
                        //             နံနံပင်မထည့်ပါ။
                        //             </p>
                        //         </h2>
                        //     </div>
                        // </div>
                    $('#orderListForSplit').html(
                        orderComponent
                    )

                    let name=`.sale_dialer`;
                    let dialerElements = document.querySelectorAll(name);
                    dialerElements.forEach(dialerElement => {
                        let max=$(dialerElement).find('.quantity').val();
                        console.log(max);
                        new KTDialer(dialerElement, {
                            min: 1,
                            max,
                            step: 1,
                        });
                    });
                }else{
                    $('#orderListForSplit').html(
                            `
                            <div class="d-flex justify-content-center px-5 py-3 ">
                                <div class="">
                                    <h2 class=" fs-7 text-gray-500 fw-bold">There is no item for order !</h2>
                                </div>
                            </div>
                            `
                        )
                }
            }else{
                alert('please save first');
            }

        })
        $(document).on('click','.split_order_modal_btn_from_create',function(){
            Swal.fire({
                text: "Please Save Order First.",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            })
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
                let dataForSale = datasForSale('delivered',false,true,true);

                let total_sale_amount = isNullOrNan($(`#${infoPriceId} .sb-total-amount`).text());
                let paid_amount = isNullOrNan($('.print_paid').text());
                let currentReceiveAble=total_sale_amount-paid_amount;
                let currentReceivieAbleAmt=currentReceiveAble+isNullOrNan(receiveAbleAmount);
                balance_amount = total_sale_amount - paid_amount;
                if(creditLimit < currentReceivieAbleAmt && balance_amount != 0){
                    Swal.fire({
                        text: "Customer's Credit limit is reached.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    })
                }else{
                    let qtyValidate = productsOnSelectData.find(function (pd) {
                        return pd.validate==false;
                    });
                    if(qtyValidate){
                        error('Products Are Out Of Stock');
                        return;
                    }
                    ajaxToStorePosData(dataForSale);
                }
            }
        })

        // Begin::Contact Add Modal Box
        $('form#add_contact_form').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            console.log($(this).attr('action'));
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



        // End

        // ============> CONTACT CHANGE PROCESS
        $(document).on('change', `#${contact_id}`, function() {
            let contact_id = $(this).val();
            ajaxOnContactChange(contact_id);
            let currentCustomer=customers.find(c=>c.id==contact_id) ?? '';
            let priceListId=currentCustomer.price_list_id;
            $('#selling_price_group').val(priceListId).trigger('change');
        })
        let ajaxOnContactChange=(contact_id)=>{
            if(contact_id){
                $.ajax({
                url: `/pos/pricelist-contact/${contact_id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                    success: function(results){
                        if(results.status === 200){

                            setReceivableAmount(results.receivable_amount);
                            creditLimit=results.credit_limit;
                            if(results.default_price_list){
                                customer_price_list = results.default_price_list.id;
                            }else{
                                customer_price_list = null;
                            }
                            // getPrice($(`#${tableBodyId}`));
                            // false ? getPriceByEachRow() : getPrice();
                        }
                    },
                    error: function(e){
                        console.log(e.responseJSON.error);
                    }
                });
            }
        }
        const ajaxToGetPriceList=(locationId)=>{
            $.ajax({
                url: `/pos/pricelist-location/${locationId}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(results){
                    if(results.status === 200){
                        price_lists_with_location = [];
                        price_lists_with_location = results;
                        // console.log(results)
                        let selectedElement = $(`#selling_price_group`);
                        // let eachSellingPrice = $('select[name="each_selling_price"]');

                        // Clear existing options
                        selectedElement.empty();
                        // eachSellingPrice.empty();

                        $.each(results.price_lists, function(index, item) {
                            var optionForSelectedElement = $("<option>")
                                .val(item.id)
                                .text(item.name);

                            selectedElement.append(optionForSelectedElement);
                        });

                        // Add default option
                        if(results.default_price_list){
                            selectedElement.val(results.default_price_list.id).trigger("change");
                        }else{
                            selectedElement.val(defaultPriceListId).trigger("change");
                        }
                    }
                },
                error: function(e){
                    console.log(e);
                }
            });
        }
        // ============> LOCATION CHANGE PROCESS
        $(document).on('change', `#business_location_id`, function() {
            let location_id = $(this).val();
            ajaxToGetPriceList(location_id);
        })
        let locationId=$('#business_location_id').val();
        if(locationId){
            ajaxToGetPriceList(locationId);
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

        // if edit mode
        if (editSaleDetails.length>0) {
            $(document).ready(()=>{
                $("#invoice_side_bar").is(':hidden') ?$('#pos_shopping_cart').click() :'';
            })
            setTimeout(() => {
                suggestionProductEvent();
                editSaleDetails.forEach(function(saleDetail,index){
                    let secIndex;
                    product= productsOnSelectData.find(function(pd,i) {
                        secIndex=i;
                        return saleDetail.product_variation.id== pd.variation_id;
                    });
                    // console.log(editSaleDetails);
                    let uoms=getCurrentAndRefUom(saleDetail.product.uom.unit_category.uom_by_category,saleDetail.uom_id);
                    let saleQty=0;
                    if(uoms.currentUom){
                        saleQty=isNullOrNan(getReferenceUomInfoByCurrentUomQty(saleDetail.quantity,uoms.currentUom,uoms.referenceUom)['qtyByReferenceUom']);
                    }
                    if(!product){
                        newProductData={
                            'product_id':saleDetail.product.id,
                            'product_type':saleDetail.product.product_type,
                            'product_name':saleDetail.product.name,
                            'cateogry_id':saleDetail.product.cateogry_id,
                            'variation_id':saleDetail.product_variation.id,
                            'category_id':saleDetail.product.category_id,
                            'defaultSellingPrices':saleDetail.product_variation.default_selling_price,
                            'defaultPurchasePrice':saleDetail.product_variation.default_purchase_price,
                            'variation_name':saleDetail.product_variation.variation_template_value ? saleDetail.product_variation.variation_template_value.name:'',
                            'sellingPrices':saleDetail.product_variation.uom_selling_price,
                            'stock_sum_current_quantity':saleDetail.stock_sum_current_quantity,
                            'aviable_qty':editSale.status=='delivered' ? isNullOrNan(saleDetail.stock_sum_current_quantity)+isNullOrNan(saleQty) :isNullOrNan(saleDetail.stock_sum_current_quantity) ,
                            'validate':true,
                            'uom':saleDetail.product.uom,
                            'uom_id':saleDetail.uom_id,
                            'stock':saleDetail.stock,
                            'additional_product':saleDetail.product_variation.additional_product,
                            'packaging':saleDetail.product_variation.packaging,
                            'sale_qty':saleDetail.quantity,
                        };
                        productsOnSelectData=[...productsOnSelectData,newProductData];
                    }else{
                        if(editSale.status=='delivered'){
                            productsOnSelectData[secIndex].stock_sum_current_quantity=isNullOrNan(productsOnSelectData[secIndex].stock_sum_current_quantity)+ saleQty;
                        }
                    }
                })

                // for increase and decrease SERVICE ITEM QUANTITY

                //     (()=>{
                //         $(document).on('click', '#increase', function() {
                //         let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
                //         let incVal = parent.find('input[name="quantity[]"]');
                //         let value = parseInt(incVal.val()) + 1;
                //         incVal.val(value);
                //         false ? getPriceByEachRow() : getPrice();
                //         calPrice($(this));
                //         totalSubtotalAmountCalculate();
                //         checkStock($(this));
                //         hideCalDisPrice($(this));
                //         totalDisPrice();
                //     })
                //     $(document).on('change', '.quantity_input', function() {
                //         calPrice($(this));
                //         totalSubtotalAmountCalculate();
                //         checkStock($(this));
                //         hideCalDisPrice($(this));
                //         totalDisPrice();
                //     })
                //     $(document).on('click', '#decrease', function() {
                //         let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
                //         let decVal = parent.find('input[name="quantity[]"]');
                //         let value = parseInt(decVal.val()) - 1;
                //         decVal.val(value >= 1 ? value : 1);
                //         false ? getPriceByEachRow() : getPrice();
                //         calPrice($(this));
                //         totalSubtotalAmountCalculate();
                //         checkStock($(this));
                //         hideCalDisPrice($(this));
                //         totalDisPrice();
                //     })
                // })();
            }, 1000);
        }
    });
</script>
