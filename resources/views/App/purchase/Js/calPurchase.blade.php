<script>
$(document).ready(function() {
    let products;
    let productsOnSelectData=[];
    var suppliers=@json($suppliers);
    let setting=@json($setting);
    let currency=@json($currency);
    let currentCurrency=currency;
    let currentCurrencySymbol=currency.symbol;
    let currencies=@json($currencies);
    let lotControl=setting.lot_control;
    let purchaseDetails=@json($purchase_detail?? []) ;
    if(purchaseDetails.length>0){
        purchaseDetails.forEach(function(purchase,index){
            setTimeout(() => {
                totalItem();
                net_purchase_total_amount_cal();
                total_purchase_amount_cal() ;
                total_balance_amount_cal();
                finalsubTotalCal($(`[name="purchase_details[${index}][per_item_expense]"]`));
            }, 500);
        })
    }
    $('#contact_id').on('change',function(){
        var selected_supplier =$(this).val();
        if(selected_supplier){
            let supplier=suppliers.filter(function(supplier){
                return supplier.id==selected_supplier;
            })
            supplier=supplier[0];

            $('.supplier_address').html(`${supplier.address_line_1? supplier.address_line_1+',' :''} ${supplier.address_line_2? supplier.address_line_2+',' :''}<br>
            ${supplier.city? supplier.city+',' :''}${supplier.state? supplier.state+',' :''}${supplier.country? supplier.country+',' :''}<br>
            ${ supplier.zip_code? supplier.zip_code+',' :''}`)
        }
    })
    $('#currency_id').change(function(e){
        let selectedCurrency=currencies.find(c=>c.id==$(this).val());
        currentCurrency=selectedCurrency;
        currentCurrencySymbol=selectedCurrency.symbol;
        $('.currencySymbol').text(currentCurrencySymbol);
    })
    $('input').off('focus').on('focus', function() {
    // Select the text in the input field
        $(this).select();
    });

    let unique_name_id=1;
    let products_length=$('#purchase_table tbody tr').length-1;
    unique_name_id+=products_length;


    let results=[];
    totalItem();
    numberOnly();
    // net_purchase_total_amount_cal();

     let throttleTimeout;
    $('.quick-search-form input').on('input', function() {
        var query = $(this).val().trim();
        if (query.length >= 2) {
            $('.quick-search-results').removeClass('d-none');
            $('.quick-search-results').html(`<div class="quick-search-result result cursor-pointer p-2 ps-10 fw-senubold fs-5">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
            </div>
            `);
            // results = products.filter(function(result) {
            //     let sku=result.sku?result.sku.toLowerCase().includes(query.toLowerCase()):false;console.log(sku);
            //     return result.name.toLowerCase().includes(query.toLowerCase()) || sku;
            // });
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
                append_row(t[0],unique_name_id);
                unique_name_id+=1;
            });
            return;
        }
        append_row(selected_product,unique_name_id);
        unique_name_id+=1;
    });

    function append_row(selected_product,unique_name_id) {
        let default_purchase_price,variation_id,variation;
        if(selected_product.has_variation=='single'){
            default_purchase_price=selected_product.product_variations[0].default_purchase_price;
            variation=selected_product.product_variations[0];
            variation_id=variation.id;
            lastPurchasePrice=selected_product.lastPurchasePrice;
        }else if(selected_product.has_variation=='sub_variable'){
            default_purchase_price=selected_product.default_purchase_price;
            variation=selected_product.product_variations;
            variation_id=selected_product.variation_id;
            lastPurchasePrice=selected_product.lastPurchasePrice;

        }
        let newProductData={
            'id':selected_product.id,
            'variation_id':variation.id,
            'product_variations':variation,
            'uoms':selected_product.uom.unit_category.uom_by_category
        };
        const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newProductData.id && p.variation_id === newProductData.product_variations.id);
        if(indexToReplace !== -1){
            productsOnSelectData[indexToReplace] = newProductData;
        }else{
            productsOnSelectData=[...productsOnSelectData,newProductData];
        }
        let packagingOption='';
        if(variation.packaging){
            variation.packaging.forEach((pk)=>{
                packagingOption+=`
                    <option value="${pk.id}" data-qty="${pk.quantity}" data-uomid="${pk.uom_id}">${pk.packaging_name}</option>
                `;
            })
        }
        let lot_serial_no_input=lotControl=="on" ? `
            <td>
                <input type="text" class="form-control form-control-sm " name="purchase_details[${unique_name_id}][lot_serial_no]" placeholder="Lot/serial">
            </td>`:
            '';
        let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <a href='#' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                <input type="hidden" class="input_number " value="${selected_product.id}" name="purchase_details[${unique_name_id}][product_id]">
            </td>
            <td class="d-none">
                <input type="hidden" class="input_number variation_id" value="${variation_id}" name="purchase_details[${unique_name_id}][variation_id]">
            </td>
            <td>
                <span  class="text-gray-600 text-hover-primary mb-1 ">${selected_product.name}</span><br>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name??''}</span>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm mb-1 purchase_quantity input_number" placeholder="Quantity" name="purchase_details[${unique_name_id}][quantity]" value="1.00">
            </td>
            <td>
                <select  name="purchase_details[${unique_name_id}][purchase_uom_id]" class="form-select form-select-sm unit_id" data-kt-repeater="uom_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select unit"   placeholder="select unit" required>
                    <option value="">Select UOM</option>
                </select>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity" name="purchase_details[${unique_name_id}][packaging_quantity]" value="1.00">
            </td>
            <td class="fv-row">
                <select name="purchase_details[${unique_name_id}][packaging_id]" class="form-select form-select-sm package_id"
                    data-kt-repeater="package_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                    data-placeholder="Select Package" placeholder="select Package" required>
                    <option value="">Select Package</option>
                    ${packagingOption}
                </select>
            </td>
            <td>
                <div class="input-group input-group-sm">

                    <input type="text" class="form-control form-control-sm sum uom_price  input_number" name="purchase_details[${unique_name_id}][uom_price]" id="numberonly"  value="${ default_purchase_price ?? 0}">
                    <span class="input-group-text currencySymbol">${currentCurrencySymbol}</span>
                </div>
            </td>
            <td class="${setting.enable_line_discount_for_purchase == 1 ? '' :'d-none'}">
                <select  name="purchase_details[${unique_name_id}][discount_type]" class="form-select form-select-sm discount_type" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Discount Type">
                    <option value="fixed">Fixed</option>
                    <option value="percentage">perc(%)</option>
                </select>
            </td>
            <td class="${setting.enable_line_discount_for_purchase == 1 ? '' :'d-none'}">
                <input type="text" class="form-control form-control-sm sum discount_amount per_item_discount input_number " name="purchase_details[${unique_name_id}][per_item_discount]" value="0">
                <div class='mt-3 d-none'>Discount : <span class="line_discount_txt">0</span><span class="input-group-text currencySymbol">${currentCurrencySymbol}</span></div>
                <input type="hidden" class="form-control form-control-sm sum line_discount"  value="0">
                <input type="hidden" class="subtotal_with_discount input_number" name="purchase_details[${unique_name_id}][subtotal_with_discount]"  >
            </td>
            <td>
                <input type="text" class="form-control form-control-sm sum per_item_expense input_number" name="purchase_details[${unique_name_id}][per_item_expense]" value="0">
            </td>
            <td>
                <div class="input-group input-group-sm">

                <input type="text" class="form-control form-control-sm sum subtotal_with_expense  input_number" name="purchase_details[${unique_name_id}][subtotal_with_expense]" value="0">
                    <span class="input-group-text currencySymbol">${currentCurrencySymbol}</span>
                </div>
            </td>

            <td class='d-none'>
               <span class="subtotal_with_tax p-3">0</span><span class="input-group-text currencySymbol">${currentCurrencySymbol}</span>
            </td>

            <input type="hidden" class="form-control form-control-sm sum  input_number subtotal_with_tax_input" name="purchase_details[${unique_name_id}][subtotal_with_tax]" value="0">
            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;

        // Append the new row to the table body
        $('#purchase_table tbody').prepend(newRow);
        $('.quick-search-results').addClass('d-none');
        $('.quick-search-results').empty();
        $('#searchInput').val('');
        $('[data-kt-repeater="select2"]').select2({
            minimumResultsForSearch: Infinity
        });

        let uomsData=[];
        try {
            let uomByCategory=selected_product['uom']['unit_category']['uom_by_category'];
            uomByCategory.forEach(function(e){
                    uomsData= [...uomsData,{'id':e.id,'text':e.name}]
                })
        } catch (e) {
            error('400 : Product need to define UOM');
            return;
        }
        $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
            minimumResultsForSearch: Infinity,
            data:uomsData,
        });
        $(`[data-kt-repeater=package_select_${unique_name_id}]`).select2({
            minimumResultsForSearch: Infinity
        });
        optionSelected(selected_product.purchase_uom_id,$(`[name="purchase_details[${unique_name_id}][purchase_uom_id]"]`));
        finalsubTotalCal(`[name="purchase_details[${unique_name_id}][purchase_uom_id]"]`);
        //  $('[data-kt-repeater="uom_select2"]').select2({
        //         // data:uom_set,
        //         minimumResultsForSearch: Infinity
        // });

        // Re-init flatpickr
        $('#purchase_table tbody').find('[data-kt-repeater="datepicker"]').flatpickr();

        // $('[data-kt-select="select2"]').select2();
         $('[data-kt-repeater="datepicker"]').flatpickr();

        // init_functions
        $('#searchInput').focus();
        numberOnly();
        totalItem();
        net_purchase_total_amount_cal();
        total_purchase_amount_cal() ;
        total_balance_amount_cal();
        $('input').off('focus').on('focus', function() {
        // Select the text in the input field
            $(this).select();
        });

        // getUomByUnit(selected_product.unit_id,selected_product.uomset_id,$(`[name="purchase_details[${unique_name_id}][uomset_id]"]`));
        // purchaseValidator.init();
    }
    $(document).on('change','.package_id',function(){
        packaging($(this));
        // let selectedOption = $(this).find(':selected');
        // let dataQty = selectedOption.data('qty');
        // let selectedOptionText = selectedOption.text();
        // let parent = $(this).closest('.cal-gp');
        // let qty=parent.find('.purchase_quantity').val();

    })
    $(document).on('change','.purchase_quantity,.unit_id',function(){
        packaging($(this),'/');
    })
    $(document).on('change','.package_qty',function(){
            packaging($(this),'*');
    })
    const packaging=(e,operator)=>{
        let parent = $(e).closest('.cal-gp');
        let unitQty=parent.find('.purchase_quantity').val();
        let selectedOption =parent.find('.package_id').find(':selected');
        let packageInputQty=parent.find('.package_qty').val();
        let packagingUom=selectedOption.data('uomid');
        let packageQtyForCal = selectedOption.data('qty');

        let unit_id=parent.find('.unit_id').val();
        let variation_id=parent.find('.variation_id').val();
        let product=productsOnSelectData.find((pod)=>pod.variation_id==variation_id);
        if(packageQtyForCal && packagingUom){
            if(operator=='/'){
                let unitQtyValByUom=changeQtyOnUom(product.uoms,unitQty,unit_id,packagingUom);
                parent.find('.package_qty').val(qDecimal(isNullOrNan(unitQtyValByUom) / isNullOrNan(packageQtyForCal)));
            }else{
                let result=isNullOrNan(packageQtyForCal) * isNullOrNan(packageInputQty);
                let qtyByCurrentUnit= changeQtyOnUom(product.uoms,result,packagingUom,unit_id);
                parent.find('.purchase_quantity').val(qDecimal(qtyByCurrentUnit));
            }
        }
    }
// ==================================================== Events ================================

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
    $(document).on('click', '#purchase_table .deleteRow', function (e) {
            e.preventDefault();
                // let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
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
                        console.log('Deleted row with ID ' + id + ', name: ' + name);

                        // Remove the row from the table
                        var rowCount = $('#purchase_table tbody tr').length;
                        console.log(rowCount);
                        if (rowCount == 2) {
                            $('.dataTables_empty').removeClass('d-none');
                        }
                        row.remove();
                        totalItem();
                        net_purchase_total_amount_cal();
                        total_purchase_amount_cal() ;
                        total_balance_amount_cal();
                    }
                });
    });

    $(document).on('input','.cal-gp input,.per_item_discount',function () {
        totalItem();
        net_purchase_total_amount_cal();
        finalsubTotalCal($(this));
        total_purchase_amount_cal() ;
        total_balance_amount_cal();
    })

    $(document).on('input','#payment input',function () {
        total_purchase_amount_cal();
        total_balance_amount_cal();
    })

    $(document).on('change','#total_discount_type',function () {
        total_purchase_amount_cal();
        total_balance_amount_cal();
    })

    $(document).on('change','.discount_type,#extra_discount_type',function(){
        net_purchase_total_amount_cal();
        finalsubTotalCal($(this));
        total_purchase_amount_cal() ;
        total_balance_amount_cal();
    })

    $(document).on('change','.uom_set',function () {
        let i=inputs($(this));
        console.log(i);
        let unit_id=i.unit_id;
        console.log(unit_id);
        let uomset_id=$(this).val();
        unit_id.prop('disabled', false);
        let parent = $(this).closest('.cal-gp');
        $.ajax({
            url: `/purchase/${uomset_id}/units`,
            type: 'GET',
            success: function(e) {
                console.log(e);
                // console.log(uom_set);
                parent.find('[data-kt-repeater="unit_select"]').empty();
                parent.find('[data-kt-repeater="unit_select"]').select2({
                    data:e,
                    minimumResultsForSearch: Infinity
                });

            }
        })
    })









    function inputs(e) {
        let parent = $(e).closest('.cal-gp');
        let unit_id=parent.find('.unit_id')
        let purchase_quantity=parent.find('.purchase_quantity');
        let uom_price=parent.find('.uom_price');
        let subtotal_with_discount=parent.find('.subtotal_with_discount');
        let subtotal_with_expense=parent.find('.subtotal_with_expense');
        let purchase_price_inc_tax=parent.find('.purchase_price_inc_tax');
        let subtotal_with_tax=parent.find('.subtotal_with_tax');
        let subtotal_with_tax_input=parent.find('.subtotal_with_tax_input');
        let discount_type=parent.find('.discount_type');
        let line_discount=parent.find('.line_discount');
        let line_discount_txt=parent.find('.line_discount_txt');
        let per_item_discount=parent.find('.per_item_discount');
        let per_item_expense=parent.find('.per_item_expense');
        let exp_date=parent.find('.exp_date');

        return {
            parent,
            unit_id,
            purchase_quantity,
            line_discount,
            uom_price,
            subtotal_with_discount,
            subtotal_with_expense,
            discount_type,
            per_item_discount,
            per_item_expense,
            purchase_price_inc_tax,
            subtotal_with_tax,
            subtotal_with_tax_input,
            exp_date,
            line_discount_txt,
        }
    }


    function finalsubTotalCal(e){
        const i=inputs(e);
        const quantity=isNullOrNan(i.purchase_quantity.val());
        const uom_price=isNullOrNan(i.uom_price.val());
        const discount_type=i.discount_type.val();
        const per_item_discount=isNullOrNan(i.per_item_discount.val());
        const per_item_expense=isNullOrNan(i.per_item_expense.val());
        const exp_date=i.exp_date.val();
        let result,price_after_discount,percentAmount;
        if(discount_type == 'fixed'){
            let subtotal_amount=(quantity*uom_price);
            percentAmount=per_item_discount;
            let total_discount=per_item_discount * quantity
            price_after_discount=subtotal_amount - total_discount;

        }else if(discount_type=='percentage'){
            percentAmount=uom_price* (per_item_discount/100);
            price_after_discount=(uom_price - percentAmount)*quantity
        }
        i.line_discount.val(pDecimal(percentAmount));
        i.line_discount_txt.text(fpDecimal(percentAmount,currentCurrency));
        i.subtotal_with_discount.val(pDecimal(price_after_discount));

        result=pDecimal(price_after_discount+(per_item_expense*quantity));

        i.subtotal_with_tax.text(result);
        i.subtotal_with_tax_input.val(result)
        InsertPurchaseItemTotalPrice(i,result);
    }


    function InsertPurchaseItemTotalPrice(inputs,result){
        inputs.purchase_price_inc_tax.val(result);
        inputs.subtotal_with_expense.val(result);
        return result;
    }



    function totalItem() {
        let total=0;
        $('.purchase_quantity').each(function() {
            var value = isNullOrNan($(this).val());
            total += value;

        });
        $('#total_item').text(total);
    }

    function net_purchase_total_amount_cal() {
        setTimeout(() => {
            let total=0;
            $('.subtotal_with_tax_input').each(function() {
                var value = isNullOrNan($(this).val());
                total += value;
            });
            // console.log('pt',$('.purchase_price_inc_tax'));
            $('.net_purchase_total_amount_text').text(fpDecimal(total,currentCurrency));
            $('.net_purchase_total_amount').val(pDecimal(total));
            totalLineDiscount()
        }, 100);
    }
    function totalLineDiscount(){
        setTimeout(() => {
            let total=0;
            // let total_item=isNullOrNan($('#total_item').text(),1);
            $('.line_discount').each(function() {
                var value = isNullOrNan($(this).val());
                let parent = $(this).closest('.cal-gp');
                let quantity=isNullOrNan(parent.find('.purchase_quantity').val(),1);
                console.log(quantity);

                total += value*quantity;
            });
            // console.log('pt',$('.purchase_price_inc_tax'));
            // $('.total_line_discount').text(total );
            $('.total_line_discount_input ').val(pDecimal(total));
        }, 100);
    }



    function isNullOrNan(val,d=0){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return d;
        }else{
            return v;
        }
    }

    function total_purchase_amount_cal() {

        setTimeout(() => {


            let extra_discount_type=$('#extra_discount_type').val();
            let extra_discount_amount=isNullOrNan($('#extra_discount_amount').val());
            let total_line_discount_val=isNullOrNan($('.total_line_discount_input').val());
            let price_after_discount;


            let purchaseExpense=pDecimal(isNullOrNan($('#purchase_expense').val()));
            $('#purchase_expense_txt').text(fpDecimal(purchaseExpense,currentCurrency) );

            let net_purchase_total_amount=isNullOrNan($('.net_purchase_total_amount').val());


            if(extra_discount_type == 'fixed'){
                $('.extra_discount_txt').text(fpDecimal(extra_discount_amount,currentCurrency));
                $('.extra_discount').val(pDecimal(extra_discount_amount));
                $('#total_discount_amount_txt').text(fpDecimal(total_line_discount_val+extra_discount_amount,currentCurrency));
                $('#total_discount_amount').val(pDecimal(total_line_discount_val+extra_discount_amount));
                price_after_discount=pDecimal(net_purchase_total_amount - extra_discount_amount);


            }else if(extra_discount_type=='percentage'){
                percentage_amount=pDecimal(net_purchase_total_amount* (extra_discount_amount/100));
                $('.extra_discount_txt').text(percentage_amount);
                $('.extra_discount').val(percentage_amount);
                $('#total_discount_amount_txt').text(fpDecimal(total_line_discount_val+percentage_amount,currentCurrency));
                $('#total_discount_amount').val(pDecimal(total_line_discount_val+percentage_amount));
                price_after_discount=pDecimal(net_purchase_total_amount - percentage_amount);

            }
            // console.log(price_after_discount,purchaseExpense);
            result=isNullOrNan(price_after_discount)+isNullOrNan(purchaseExpense);
            $('#total_purchase_amount').val(pDecimal(result));
            $('#total_purchase_amount_txt').text(fpDecimal(result,currentCurrency));


        }, 100);

    }

    function total_balance_amount_cal() {
        setTimeout(() => {
        let total_purchase_amount=isNullOrNan($('#total_purchase_amount').val());

        let paid_amount=isNullOrNan($('#paid_amount').val());
        $('#paid_amount_txt').text(paid_amount);

        let result=pDecimal(total_purchase_amount-paid_amount);

        $('#balance_amount_txt').text(fpDecimal(result,currentCurrency));
        $('#balance_amount').val(result);
        }, 700)
    }
    function numberOnly() {
        $(".input_number").keypress(function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            }
        });
    }

function changeQtyOnUom(uoms,currentQty,currentUomId,newUomId) {
    let newUomInfo = uoms.find((uomItem) => uomItem.id == newUomId);
    let currentUomInfo = uoms.find((uomItem) => uomItem.id == currentUomId);
    console.log(newUomInfo,currentUomInfo,newUomId,currentUomId);
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
    return resultQty;

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

});


</script>
