        let products = @json($products);
        let results;



        totalItem();
        numberOnly();
        net_purchase_total_amount_cal();


        $('.quick-search-form input').on('input', function() {
            var query = $(this).val().trim();
            if (query.length >= 2) {
                results = products.filter(function(result) {
                    return result.name.toLowerCase().includes(query.toLowerCase()) || result.sku.toLowerCase().includes(query.toLowerCase());
                });
                var html = '';
                if (results.length > 0) {

                    results.forEach(function(result,key) {

                        html += `<div class="quick-search-result result cursor-pointer mt-1 mb-1 bg-hover-light p-2" data-id=${key} data-name=${result.name} style="z-index:100;">`;
                        html += `<h4 class="fs-6 ps-10 pt-3"> ${result.name} ${result.product_type==='variable'?'-('+result.product_variations.length+') select all' :result.sku??'' }`;
                        if(result.product_type=='sub_variable'){
                            html +=  `<span class="text-gray-700 fw-semibold ps-2 fs-5">(${result.variation_name??''})</span>`;
                        }
                        html+='</h4>'
                        html += '</div>';

                    });
                    if (results.length == 1) {
                        $('.quick-search-results').show();
                        $(`.result[data-name|='${results[0].name}']`).click();
                        $('.quick-search-results').hide();
                    } else {
                        $('.quick-search-results').show();
                    }
                } else {
                    html = '<p>No results found.</p>';
                }
                $('.quick-search-results').removeClass('d-none')
                $('.quick-search-results').html(html);
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

            let id = $(this).attr('data-id');
            let selected_product= results[id];
            if(selected_product.product_type==='variable')
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
            const lotes = filterProducts(selected_product);
            //Lot Select Option
            let lotSelectOptions = '';
            const prefix = selected_product.name.substring(0, 2).toUpperCase();
            lotes.forEach(function(option) {
                const numStr = (lotes.indexOf(option) + 1).toString().padStart(3, "0");
                const str = prefix + "-" + numStr;

                if (option == selected_product.lot_no) {
                    lotSelectOptions += `<option value="${option}" selected>${str}</option>`;
                } else {
                    lotSelectOptions += `<option value="${option}">${str}</option>`;
                }

            });
            //Lot Select Option


            let default_purchase_price, variation_id, stockout_detail_id, action;
            if (selected_product.action !== undefined) {

                        default_purchase_price=selected_product.purchase_price;
                        variation_id=selected_product.variation_id;
                        stockout_detail_id = selected_product.id;
                        action = 'edit'

            }else{
                    if(selected_product.product_type=='single'){
                    default_purchase_price=selected_product.product_variations[0].default_purchase_price;
                    variation_id=selected_product.product_variations[0].id;
                    }else if(selected_product.product_type=='sub_variable'){
                    default_purchase_price=selected_product.default_purchase_price;
                    variation_id=selected_product.variation_id;
                    }
            }


            let newRow = `<tr class='stockout-row'>
            <td>
                <a href='' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                ${action === 'edit' ? ` <input type="hidden" class="input_number" value="${selected_product.id}" name="stockout_details[${unique_name_id}][stockout_detail_id]">` : ` <input type="text" name="stockout_details[${unique_name_id}][current_stock_balance_id]" class="current_stock_balance_id" hidden="">`}
                ${action === 'edit' ? ` <input type="hidden" class="input_number " value="${selected_product.product_id}" name="stockout_details[${unique_name_id}][product_id]">` : `  <input type="hidden" class="input_number " value="${selected_product.id}" name="stockout_details[${unique_name_id}][product_id]">`}

                  <input type="hidden" class="input_number" value="${variation_id}" name="stockout_details[${unique_name_id}][variation_id]">
            </td>
            <td>
                <a href="#" class="text-gray-600 text-hover-primary mb-1 ">${selected_product.name}</a><br>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name??''}</span>
                     <p class="text-gray-500">Current Stocks: <span class="current_qty_text"></span></p>
                    <select  name="stockout_details[${unique_name_id}][lot_no]" class="form-select mt-3 lot_select2_${unique_name_id}" data-lot-select-${unique_name_id}="select2"   data-hide-search="false" data-placeholder="Select lot">
{{--                        data-kt-repeater="lot_select2"--}}
                    <option disabled selected>Select Lot</option>
                    ${lotSelectOptions}
                </select>
            </td>
            <td>
               <input type="text" hidden="" name="stockout_details[${unique_name_id}][uomset_id]" class="uom_set_id">
              <p class="form-control uom_set_text">unknow</p>
            </td>
            <td>
                <input type="text" class="form-control mb-1 stockout_quantity input_number" placeholder="Quantity" name="stockout_details[${unique_name_id}][quantity]" value="${selected_product.quantity}">
                <span class="text-danger-emphasis out-of-stock d-none fs-8 p-1">Out of Stock</span>
                <select  name="stockout_details[${unique_name_id}][unit_id]" class="form-select unit_id mt-3" data-kt-repeater="unit_select" data-hide-search="true" data-placeholder="Select unit" disabled  placeholder="select unit">
                    <option selected disabled value="default_unit">Select Unit</option>
                </select>
            </td>
            <td>
                <input type="text" readonly class="form-control sum single_purchase_price  input_number"  id="numberonly"  value="0">
                <input type="text" hidden class="form-control smallest_price  input_number"  id="numberonly"  value="0">
            </td>
            <td>

                    <input class="form-control exp_date" name="stockout_details[${unique_name_id}][expired_date]" placeholder="Pick a date" data-kt-repeater="datepicker" value="" />


                <td>
                <input type="text" name="stockout_details[${unique_name_id}][remark]" class="form-control" value="${action === 'edit' ? selected_product.remark : ''}">
            </td>
<td>
                <span class="purchase_items_total_price_text">0</span>
                <input type="text" hidden="" class="purchase_items_total_price form-control" id="numberonly" name="stockout_details[${unique_name_id}][purchase_price]" value="0">

            </td>
            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;

            // Append the new row to the table body
            $('#purchase_table tbody').append(newRow);
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');
            $('[data-kt-repeater="select2"]').select2();
            $('[data-kt-repeater="lot_select2"]').select2();
            $('[data-kt-repeater="unit_select"]').select2();


            // Re-init flatpickr
            $('#purchase_table tbody').find('[data-kt-repeater="datepicker"]').flatpickr();

            // $('[data-kt-select="select2"]').select2();
            $('[data-kt-repeater="datepicker"]').flatpickr();

            // init_functions
            numberOnly();
            totalItem();
            net_purchase_total_amount_cal();


        //Set data base on selected lot
        $(`.lot_select2_${unique_name_id}`).on('change', async function() {

            let selectedLot = $(this).val();
            let i = inputs($(this));

            const selected_current_result = current_stocks.find(item => item.lot_no === parseInt(selectedLot));
            selected_product.action === undefined ? i.current_stock_balance_id.val(selected_current_result.id) : '';
            i.current_qty_text.text(Math.floor(selected_current_result.current_quantity));
            i.single_purchase_price.val(selected_current_result.purchase_price);
            i.smallest_price.val(selected_current_result.purchase_price)
            i.exp_date.val(selected_product.action !== undefined ? selected_product.expired_date : selected_current_result.expired_date);
            i.stockout_quantity.val(selected_product.action !== undefined ? selected_product.quantity : 1);
            i.uom_set_id.val(selected_current_result.uomset_id);
            i.uom_set_text.text(uom_set.find(item => item.id === selected_current_result.uomset_id).text);


            let uomset_id = selected_current_result.uomset_id;
            let unit_id = i.unit_id;

            unit_id.prop('disabled', false);
            let parent = $(this).closest('.stockout-row');

            try {
                const response = await fetch(`/stock/${uomset_id}/units`);
                const e = await response.json();
                var selectEl = parent.find('[data-kt-repeater="unit_select"]');

                if (selected_product.action !== undefined) {
                    let selectedValue = selected_product.unit_id;
                    let options = '';
                    e.forEach(function(unit) {
                        let option = `<option value="${unit.id}"`;
                                              if (unit.id == selectedValue) {
                                              option += ' selected';
                                              }
                                              option += `>${unit.text}</option>`;
                        options += option;
                    });
                    selectEl.html(options);
                } else {
                    selectEl.empty();
                    selectEl.select2({
                    data: e
                    });
                }
            } catch (error) {
                console.log(error);
            }

            totalItem();
            purchase_items_total_price_cal($(this));
            net_purchase_total_amount_cal();
        });
        //Set data base on selected lot

        optionSelected(lotes[0],$(`[data-lot-select-${unique_name_id}="select2"]`));

        }



        //_________EVENT___________
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

                    var row = $(this).closest('tr');

                    var id = row.attr('data-id');

                    var name = row.find('td[data-id="' + id + '"]').text();

                    // Remove the row from the table
                    var rowCount = $('#purchase_table tbody tr').length;
                    // console.log(rowCount);
                    if (rowCount == 2) {
                        $('.dataTables_empty').removeClass('d-none');
                    }
                    row.remove();
                    totalItem();
                    net_purchase_total_amount_cal();

                }
            });
        });

        $(document).on('input','.stockout-row input',function () {
            checkCurrentStock($(this));
            totalItem();

            purchase_items_total_price_cal($(this));
            net_purchase_total_amount_cal();
        })

        $(document).on('change','.unit_id',function () {
            unit_price_cal($(this));
        });
        //_________EVENT___________



        function inputs(e) {
            let parent = $(e).closest('.stockout-row');
            let current_stock_balance_id = parent.find('.current_stock_balance_id');
            let unit_id=parent.find('.unit_id');
            let uom_set_id = parent.find('.uom_set_id');
            let uom_set_text = parent.find('.uom_set_text');
            let current_qty_text = parent.find('.current_qty_text');
            let stockout_quantity=parent.find('.stockout_quantity');
            let single_purchase_price=parent.find('.single_purchase_price');
            let smallest_price = parent.find('.smallest_price');
            let discount_type=parent.find('.discount_type');
            let discount_amount=parent.find('.discount_amount');
            let additional_expense=parent.find('.additional_expense');
            let purchase_items_total_price_text=parent.find('.purchase_items_total_price_text');
            let purchase_items_total_price=parent.find('.purchase_items_total_price');
            let exp_date=parent.find('.exp_date');
            let out_of_stock=parent.find('.out-of-stock');


            return {
                parent,
                current_stock_balance_id,
                unit_id,
                uom_set_id,
                uom_set_text,
                current_qty_text,
                stockout_quantity,
                single_purchase_price,
                smallest_price,
                discount_type,
                discount_amount,
                additional_expense,
                purchase_items_total_price_text,
                purchase_items_total_price,
                exp_date,
                out_of_stock,

            }
        }

        //Lot Filter
        function filterProducts(selectedData) {

            const filteredProducts = current_stocks.filter((product) => {

        if (selectedData.product_type === 'single') {
        return selectedData.action === undefined ? product.product_id === selectedData.id : product.product_id === selectedData.product_id;
        } else {
        return product.variation_id === selectedData.variation_id;
        }


        });

            return filteredProducts.map((product) => product.lot_no);
        }
        //Lot Filter

        // Selected to Option
        function optionSelected(value_to_select, select_instance) {
            var $select = $(select_instance);

            return $select.val(value_to_select).trigger('change');
        }
        // Selected to Option



        //Change Purchase Price Base on Unit
        async function unit_price_cal(e){
            const i=inputs(e);
            try{
                const smallestPrice = parseFloat(i.smallest_price.val()) || 0;
                const quantity = parseFloat(i.stockout_quantity.val()) || 0;

                const response = await fetch(`/stock/${i.unit_id.val()}/${i.uom_set_id.val()}/values`);
                const currentUnitQtyVal = await response.json();

                const defaultPrice = currentUnitQtyVal * smallestPrice;
                i.single_purchase_price.val(defaultPrice.toFixed(2)); // Default Price

                const totalPrice = defaultPrice * quantity; // Total Price
                InsertPurchaseItemTotalPrice(i, totalPrice);

                purchase_items_total_price_cal(e);
                net_purchase_total_amount_cal();
            } catch (error) {
                console.error(error);
            }
        }
        //Change Purchase Price Base on Unit

        //Total Price base on quantity
        function purchase_items_total_price_cal(e){
            const i=inputs(e);
            const quantity=isNullOrNan(i.stockout_quantity.val());
            const single_purchase_price=isNullOrNan(i.single_purchase_price.val());

            let result=single_purchase_price*quantity;
            InsertPurchaseItemTotalPrice(i,result);
        }
        //Total Price base on quantity


        //Data put function ot total price
        function InsertPurchaseItemTotalPrice(inputs,result){
            inputs.purchase_items_total_price_text.text(result);
            inputs.purchase_items_total_price.val(result);
            return result;
        }
        //Data put function ot total price



        // Total items
        function totalItem() {
            let total=0;
            $('.stockout_quantity').each(function() {
                var value = isNullOrNan($(this).val());
                total += value;

            });
            $('#total_item').text(total);
        }
        // Total items

        //Net Total Price
        function net_purchase_total_amount_cal() {
                let total=0;
                $('.purchase_items_total_price').each(function() {
                    var value = isNullOrNan($(this).val());
                    total += value;
                });

                $('.net_purchase_total_amount_text').text(total);
                $('.net_purchase_total_amount').val(total)
        }
        //Net Total Price


        //Value Check
        function isNullOrNan(val){
            let v=parseFloat(val);

            if(v=='' || v==null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }
        //Value Check


        //Character Filter
        function numberOnly() {
            $(".input_number").keypress(function(event) {
                var charCode = (event.which) ? event.which : event.keyCode;
                if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    event.preventDefault();
                }
            });
        }
        //Character Filter


        //Check Stock
        async function checkCurrentStock(e) {
            const i = inputs(e);

            try {
                const inputQuantity = parseFloat(i.stockout_quantity.val()) || 0;
                const currentQuantity = parseFloat(i.current_qty_text.text());
                const response = await fetch(`/stock/${i.unit_id.val()}/${i.uom_set_id.val()}/values`);
                const currentUnitQty = await response.json();
                const finalQty = currentUnitQty * inputQuantity;

                if (finalQty > currentQuantity) {
                    i.out_of_stock.removeClass('d-none');
                    $('.save_btn').prop('disabled', true);
                } else {
                    i.out_of_stock.addClass('d-none');
                    $('.save_btn').prop('disabled', false);
                }
            } catch (error) {
                console.error(error);
            }
        }
        //Check Stock
