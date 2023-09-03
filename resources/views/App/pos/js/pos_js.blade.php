<script src="{{ asset('customJs/debounce.js') }}"></script>
{{-- <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script> --}}
<script src="{{ asset('customJs/pos/calc_pos.js') }}"></script>
{{-- <script src="{{ asset('customJs/pos/sale.js') }}"></script> --}}
<script src="{{ asset('customJs/pos/recent.js') }}"></script>
<script src="{{ asset('customJs/pos/filter_products.js') }}"></script>

<script>
    var price_lists_with_location = [];
    var uoms = @json($uoms ?? null);
    var posRegisterId=@json($posRegisterId);
    var sessionId=@json(request('sessionId'));
    var posRegister=@json($posRegister);
    var editSale=@json($sale ?? []);
    var saleId=editSale ? editSale.id :'';
    var route=null;
    var getProductVariations;
    var creditLimit=0;
    var receiveAbleAmount=0;
    @if(isset($sale))
        route="{{route('update_sale',$sale->id)}}"
    @endif
    let productsOnSelectData=[];
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
                            <div class=" {{isUsePaymnetAcc() ? 'col-md-5 col-sm-5 col-12 ' : 'col-12'}}">
                                <label class="form-label fw-bold">Amount:</label>
                                <input type="text" class="form-control form-control-sm mb-2 mb-md-0" name="pay_amount" placeholder="" value=""/>
                            </div>
                            @if (isUsePaymnetAcc())
                                <div class="col-md-5 col-sm-5 col-5 ">
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
            checkAndStoreSelectedProduct(product);
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
            // console.log(product)
            // getProducts(1, );
            let stockBalanceText=product.product_type =="storable" ? `<span class="fs-7 fw-semibold text-gray-600 stock_quantity_unit stock_quantity_unit_${product.product_variations.id}">${product.uom.value * 1}</span> - <span class="fs-7 fw-semibold text-gray-600 stock_quantity_name stock_quantity_${product.product_variations.id}">${product.uom.name}</span>` : ''
            return `
                <tr class="fs-9 mb-3 invoiceRow invoice_row_${product.product_variations.id} cursor-pointer">
                    <input type="hidden" name="product_id" value="${product.id}" />
                    <input type="hidden" name="lot_no" value="0" />
                    <input type="hidden" name="variation_id" value="${product.product_variations.id}" />
                    <input type="hidden" name="each_selling_price" value="" />
                    <input type="hidden" name="discount_type" value="" />
                    <input type="hidden" name="per_item_discount" value="" />
                    <input type="hidden" name="subtotal_with_discount" value="" />
                    <input type="hidden" name="item_detail_note" value="" />
                    <input type="hidden" name="cost_price" value="${product.stock[0] ?product.stock[0].ref_uom_price : 0}" />
                    <input type="hidden" name="_default_sell_price" value="${product.product_variations.default_selling_price * 1}" />

                    <td class=" text-break text-start fw-bold fs-6 text-gray-700 "><span class="product-name">${product.name}</span>
                        <br>
                        <span class="fs-7 fw-semibold text-gray-600 product-sku">SKU : ${product.sku}</span>
                        <br>
                        ${variation_value_and_name !== undefined ? `<span class="fs-7 fw-semibold text-gray-600 variation_value_and_name">${variation_value_and_name}</span><br>` : ''}
                        ${stockBalanceText}
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

                                <input type="text" class=" form-control form-control-sm border-0 text-center  fw-bold text-gray-800 quantity_input" name="quantity[]"  value="1" />

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
            let perItemDis = $element.closest('tr').find('input[name="per_item_discount"]').val();
            let disType = $element.closest('tr').find('input[name="discount_type"]').val();
            let total_price = default_price * quantity;
            let perItemDiscounts=isNullOrNan(perItemDis) * isNullOrNan(quantity);
            if(disType === "fixed"){
                $element.closest('tr').find('input[name="subtotal_with_discount"]').val(total_price - perItemDiscounts);
            }
            if(disType === "percentage"){
                let dis_amount_with_price = default_price * (perItemDiscounts / 100);
                $element.closest('tr').find('input[name="subtotal_with_discount"]').val(total_price - dis_amount_with_price);
            }
            $element.closest('tr').find('.subtotal_price').text(total_price);
        }

        let totalSubtotalAmountCalculate = () => {
            let itemCount = $(`#${tableBodyId} tr`).length;
            let totalSum = 0;
            $(`#${tableBodyId} .subtotal_price`).each(function() {
                let value = isNullOrNan($(this).text());
                totalSum += value;
            });

            $(`#${infoPriceId} .sb-item-quantity`).text(itemCount);
            $(`#${infoPriceId} .sb-total`).text(totalSum);
        }

        let getPrice = () => {
            // console.log(productsOnSelectData)
            let contact_pricelist_id = customer_price_list;
            let all_pricelist_id = $('#selling_price_group option:selected').val();

            let pricelist_id = contact_pricelist_id ? contact_pricelist_id : all_pricelist_id;
            $(`#${tableBodyId} tr`).each(function() {
                let parent_row = $(this).closest('tr');
                let product_variation_id = parent_row.find('input[name="variation_id"]').val();
                let uom_id = parent_row.find('.invoice_unit_select option:selected').val();

                parent_row.find('input[name="each_selling_price"]').val(pricelist_id);

                let quantity = 0;
                $(`#${tableBodyId} tr`).each(function() {
                    let each_uom_id = $(this).closest('tr').find('.invoice_unit_select option:selected').val();
                    let variation_id = $(this).closest('tr').find('input[name="variation_id"]').val();

                    if(variation_id == product_variation_id && each_uom_id == uom_id){
                        let qty = $(this).closest('tr').find('input[name="quantity[]"]').val();
                        quantity += qtyByReferenceUom(each_uom_id, qty);
                    }
                });
                let datas = { pricelist_id, product_variation_id, quantity, uom_id };
                // getProducts2(datas);
                let price_info = getProducts(datas);
                console.log(price_info);
                let result_pricelist_id, price;
                if(price_info == undefined){
                    let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
                    result_pricelist_id = pricelist_id;
                    price = default_sell_price * 1;
                }else{
                    let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
                    result_pricelist_id = price_info.price_id;
                    price = isNaN(price_info.price) ? default_sell_price * 1 : price_info.price;
                }

                if(price === undefined){
                    let cost_price = parent_row.find('input[name="cost_price"]').val();
                    let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
                    if(all_pricelist_id == 'default_selling_price'){
                        parent_row.find('input[name="selling_price[]"]').val(default_sell_price * 1);
                        calPrice(parent_row);
                    }else {
                        parent_row.find('input[name="selling_price[]"]').val(cost_price * 1);
                        calPrice(parent_row);
                    }
                }

                if(price !== undefined && !isNaN(price)){
                    parent_row.find('input[name="each_selling_price"]').val(result_pricelist_id);
                    $(`#${tableBodyId} tr`).each(()=>{
                        let each_uom_id = $(this).closest('tr').find('.invoice_unit_select option:selected').val();
                        let variation_id = $(this).closest('tr').find('input[name="variation_id"]').val();
                        if(price==0){
                           price=isNullOrNan($(this).closest('tr').find('input[name="selling_price[]"]').val());
                        }
                        if(variation_id == product_variation_id && each_uom_id == uom_id){
                            $(this).closest('tr').find('input[name="selling_price[]"]').val(price * 1);
                        }
                        calPrice($(this));
                    });
                }
            })
        }

        let getPriceByEachRow = () => {
            let contact_pricelist_id = customer_price_list;
            let all_pricelist_id = $('#selling_price_group option:selected').val();
            let pricelist_id = contact_pricelist_id ? contact_pricelist_id : all_pricelist_id;

            $(`#${tableBodyId} tr`).each(function() {
                let parent_row = $(this).closest('tr');
                let product_variation_id = parent_row.find('input[name="variation_id"]').val();
                let uom_id = parent_row.find('.invoice_unit_select option:selected').val();
                let raw_quantity = parent_row.find('input[name="quantity[]"]').val();
                let quantity = qtyByReferenceUom(uom_id, raw_quantity);

                parent_row.find('input[name="each_selling_price"]').val(pricelist_id);

                let datas = { pricelist_id, product_variation_id, quantity, uom_id };
                let price_info = getProducts(datas);
                let result_pricelist_id, price;
                if(price_info == undefined){
                    let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
                    result_pricelist_id = pricelist_id;
                    price = default_sell_price * 1;
                }else{
                    result_pricelist_id = price_info.price_id;
                    price = price_info.price;
                }

                if(price === undefined){
                    let cost_price = parent_row.find('input[name="cost_price"]').val();
                    let default_sell_price = parent_row.find('input[name="_default_sell_price"]').val();
                    if(all_pricelist_id == 'default_selling_price'){
                        parent_row.find('input[name="selling_price[]"]').val(default_sell_price * 1);
                        calPrice(parent_row);
                    }else {
                        parent_row.find('input[name="selling_price[]"]').val(cost_price * 1);
                        calPrice(parent_row);
                    }
                }

                if(price !== undefined && !isNaN(price)){
                    parent_row.find('input[name="each_selling_price"]').val(result_pricelist_id);
                    parent_row.find('input[name="selling_price[]"]').val(price * 1);
                    calPrice(parent_row);
                }
            })
        }

        let changeQtyOnUom = (e,newUomId) => {
            let parent = $(`#${tableBodyId}`).find(e).closest(`.invoiceRow`);
            let productId = parent.find('input[name="product_id"]').val();
            let variationId = parent.find('input[name="variation_id"]').val();

            let product = productsOnSelectData.filter( product => product.variation_id == variationId )[0];
            let quantity = isNullOrNan(product.total_current_stock_qty);

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

            parent.find('.stock_quantity_unit').text(result);
            parent.find('.stock_quantity_name').text(newUomInfo.name);

            false ? getPriceByEachRow() : getPrice();
        }

        let checkAndStoreSelectedProduct = (newSelectedProduct) => {
            let newProductData={
                'product_id':newSelectedProduct.id,
                'product_type':newSelectedProduct.product_type,
                'product_name':newSelectedProduct.name,
                'variation_name':newSelectedProduct.variation_name,
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
                const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newSelectedProduct.id && p.variation_id === newSelectedProduct.product_variations.id);
                if(indexToReplace !== -1){
                    productsOnSelectData[indexToReplace] = newProductData;
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

        let checkStock = (e) => {
            let tr_parent = $(e).closest('.invoiceRow');
            let parentTBody = tr_parent.parent().attr('id');

            let variationId = tr_parent.find('input[name="variation_id"]').val();
            let index;
            // console.log(productsOnSelectData,'--');
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
            if(product.product_type == 'storable'){
                if(result > productsOnSelectData[index].total_current_stock_qty){
                    productsOnSelectData[index].validate=false;
                    $(`.stock_alert_${variationId}`).removeClass('d-none');
                }else{
                    productsOnSelectData[index].validate=true;
                    $(`.stock_alert_${variationId}`).addClass('d-none');
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
                    console.log(result,isNullOrNan(subtotal) , isNullOrNan(subtotal_with_discount));
                    totalDisPrice += result;
                }
                subTotalPrice += isNullOrNan(subtotal);
            })

            let total_amount = subTotalPrice - totalDisPrice;
            $(`#${infoPriceId} .sb-discount`).text(totalDisPrice);
            $(`#${infoPriceId} .sb-total-amount`).text(total_amount);
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
                                $('#payment_info .print_paid').text(0);
                                $('#payment_info .print_change').text(0);
                                $('#payment_info .print_balance').text(0);
                                $('input[name="pay_amount"]').val(0);
                                $('#payment_row_body').html('');
                                $('#payment_row_body').append(paymentRow);
                                $('.reservation_id').val('').trigger('change')
                                $('#paymentForm')[0].reset();
                                ajaxOnContactChange($(`#${contact_id}`).val());
                            });
                        }

                    }
                },
                error:function(e){
                    console.log(e);
                    status=e.status;
                    if(status==405){
                        warning('Method Not Allow!');
                    }else if(status==419){
                        error('Session Expired')
                    }else if(status==422){
                        error(e.message)
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
                let raw_sale_details = {
                    sale_detail_id,
                    item_detail_note,
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

        let setReceivableAmount = (amount) => {
            let price = isNullOrNan(amount);
            receiveAbleAmount=amount;
            $(document).find('.receivable-amount').text(price);
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
                        <div class="p-1 col-lg-2 col-md-2 col-2 min-w-125px cursor-pointer each_product">
                            <div class="card">
                                <input type="hidden" name="category_id" value="${item.category_id}">
                                <input type="hidden" name="sub_category_id" value="${item.sub_category_id}">
                                <input type="hidden" name="brand_id" value="${item.brand_id}">
                                <input type="hidden" name="manufacturer_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="generic_id" value="${item.manufacturer_id}">
                                <input type="hidden" name="product_id" value="${item.id}">
                                <input type="hidden" name="product_variation_id" value="${item.product_variation_id}">
                                <div class="card-body text-center p-3">
                                    ${item.image ? `<img src="/storage/product-image/${item.image}" class="rounded-3 mb-4 w-80px h-80px w-xxl-100px h-xxl-100px" alt="" />` :
                                    `<img src="{{asset('assets/media/svg/files/blank-image.svg')}}" class="rounded-3 mb-4 w-80px h-80px w-xxl-100px h-xxl-100px" alt="" />`}
                                    <div class="mb-2">
                                        <div class="text-center">
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
                    if(results.length > 0){
                        $(results).each(function(index, element){
                            // console.log(element)
                            let css_class = element.total_current_stock_qty !== 0 ? " " : " text-gray-500 order-3 not-use";

                            let product_countOrSku = element.has_variation === 'variable' ? element.product_variations.length : element.sku;
                            // let stock_qty = element.total_current_stock_qty !== 0 ? element.total_current_stock_qty * 1 + ' ' + element.smallest_unit : 'Out of Stocks';
                            let vari_name_or_selectAll = element.has_variation === 'sub_variable' ? element.variation_name : 'select all';
                            let unit = element.uom.name;

                            $('#search_container').append(searchNewRow(index, element.id, element.has_variation, element.variation_id, element.name, product_countOrSku, vari_name_or_selectAll, unit, css_class));
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

            if(selected_product.has_variation === 'variable'){
                let variation = selected_product.product_variations;
                variation.forEach(variation => {
                    let filteredId = products.filter( p => p.variation_id === variation.id);
                    let newInvoiceSidebar = $(invoiceSidebar(filteredId[0]));
                    $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                    changeQtyOnUom(newInvoiceSidebar, filteredId[0].uom.id);
                });
                totalSubtotalAmountCalculate();
                totalDisPrice();
                return;
            }
            let newInvoiceSidebar = $(invoiceSidebar(selected_product));
            $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
            $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
            changeQtyOnUom(newInvoiceSidebar, selected_product.uom.id);
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
                    if(results.length>0 && results[0].product_type=="storable"){
                        if(results[0].total_current_stock_qty === 0 || results[0].total_current_stock_qty === ''){
                            error('Out of stock');
                            return;
                        }
                    }

                    if(results[0].has_variation === "single"){
                        let product = results[0];
                        let newInvoiceSidebar = $(invoiceSidebar(product));
                        $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                        $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                        changeQtyOnUom(newInvoiceSidebar, product.uom.id);
                        totalSubtotalAmountCalculate();
                        totalDisPrice();
                        return;
                    }
                    let product = results[1];
                    let newInvoiceSidebar = $(invoiceSidebar(product));
                    $(`#${tableBodyId}`).prepend(newInvoiceSidebar);
                    $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
                    changeQtyOnUom(newInvoiceSidebar, product.uom.id);
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
            let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
            let selected_uom_id = $(this).val();
            let product_id = parent.find('input[name="product_id"]').val();
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
            false ? getPriceByEachRow() : getPrice();
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
        })
        $(document).on('change', '.quantity_input', function() {
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
        })
        $(document).on('click', '#decrease', function() {
            let parent = $(`#${tableBodyId}`).find($(this)).closest('tr');
            let decVal = parent.find('input[name="quantity[]"]');
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);
            false ? getPriceByEachRow() : getPrice();
            calPrice($(this));
            totalSubtotalAmountCalculate();
            checkStock($(this));
            hideCalDisPrice($(this));
            totalDisPrice();
        })

        // change price list
        $(document).on('change', '#selling_price_group', function() {
            $(`#${tableBodyId} tr`).each(function() {
                let parent = $(this).closest('tr');
                false ? getPriceByEachRow() : getPrice();
                calPrice(parent);
                totalSubtotalAmountCalculate();
                totalDisPrice();
            })
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
            // let filtered_product = productsOnSelectData.filter( item => item.product_id == product_id && item.variation_id == variation_id);


            $('#invoice_row_discount').find('input[name="discount_amount"]').val(per_item_dis!='' ?per_item_dis: 0);
            $('#invoice_row_discount').find('input[name="subtotal_with_discount"]').val(subtotal_with_dis !='' ?subtotal_with_dis: price);
            $('#invoice_row_discount').find('.modal-title').text(`${product_name} - ${product_sku}`);
            $('#invoice_row_discount').find('select[name="each_selling_price"]').val(each_selling_price).trigger('change');
            $('#invoice_row_discount').find('input[name="modal_price_without_dis"]').val(price);
            $('#invoice_row_discount').find('#item_detail_note_input').val(item_detail_note);

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
        $('#invoice_row_discount').on('hidden.bs.modal', function(event) {
            // let selling_price_group = $(this).find('select[name="each_selling_price"]').val();
            let uom_price = $(this).find('input[name="modal_price_without_dis"]').val();
            let item_detail_note = $(this).find('#item_detail_note_input').val();
            let dis_type = $(this).find('select[name="invoice_row_discount_type"]').val();
            let dis_amount = $(this).find('input[name="discount_amount"]').val();
            let subtotal_with_dis = $(this).find('input[name="subtotal_with_discount"]').val();

            current_tr.find('input[name="selling_price[]"]').val(uom_price);
            // current_tr.find('input[name="each_selling_price"]').val(selling_price_group);
            current_tr.find('input[name="discount_type"]').val(dis_type);
            current_tr.find('input[name="per_item_discount"]').val(dis_amount);
            current_tr.find('input[name="subtotal_with_discount"]').val(subtotal_with_dis);
            current_tr.find('input[name="item_detail_note"]').val(item_detail_note);
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

                                        if(response.type=='network'){
                                            success('successfully sell and print!')
                                        }else{
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
                                        }
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
            $('#payment_info .print_paid').text(isNullOrNan(pay_amount));
            $('#payment_info .print_change').text(isNullOrNan(change));
            $('#payment_info .print_balance').text(isNullOrNan(balance));
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
                $('#payment_info .print_paid').text(pay_amount );
                $('#payment_info .print_change').text(change);
                $('#payment_info .print_balance').text(balance);
            })

            $('#payment_info .print_payable_amount').text(payable_amount);
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
                    ajaxToStorePosData(dataForSale);
                }
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



        // End

        // ============> CONTACT CHANGE PROCESS
        $(document).on('change', `#${contact_id}`, function() {
            let contact_id = $(this).val();
            ajaxOnContactChange(contact_id);
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
                            false ? getPriceByEachRow() : getPrice();
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
                    // console.log(results)
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

                        var defaultOption = $("<option>")
                                .val('default_selling_price')
                                .text('defalut selling price');
                        selectedElement.append(defaultOption);

                        // Add default option
                        if(results.default_price_list){
                            selectedElement.val(results.default_price_list.id).trigger("change");
                        }else{
                            selectedElement.val('default_selling_price').trigger("change");
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
            setTimeout(() => {
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
                            'variation_id':saleDetail.product_variation.id,
                            'category_id':saleDetail.product.category_id,
                            'defaultSellingPrices':saleDetail.product_variation.default_selling_price,
                            'variation_name':saleDetail.product_variation.variation_template_value ? saleDetail.product_variation.variation_template_value.name:'',
                            'sellingPrices':saleDetail.product_variation.uom_selling_price,
                            'total_current_stock_qty':saleDetail.stock_sum_current_quantity,
                            'aviable_qty':editSale.status=='delivered' ? isNullOrNan(saleDetail.stock_sum_current_quantity)+isNullOrNan(saleQty) :isNullOrNan(saleDetail.stock_sum_current_quantity) ,
                            'validate':true,
                            'uom':saleDetail.product.uom,
                            'uom_id':saleDetail.uom_id,
                            'stock':saleDetail.stock,
                        };
                        console.log(newProductData);
                        productsOnSelectData=[...productsOnSelectData,newProductData];
                    }else{
                        if(editSale.status=='delivered'){
                            productsOnSelectData[secIndex].total_current_stock_qty=isNullOrNan(productsOnSelectData[secIndex].total_current_stock_qty)+ saleQty;
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
