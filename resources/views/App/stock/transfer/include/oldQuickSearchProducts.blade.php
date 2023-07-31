
<script>
    $(document).ready(function () {
        var products;
        let unique_name_id=1;
        let products_length=$('#sale_table tbody tr').length-1;
        unique_name_id+=products_length;
        totalItem();
        net_purchase_total_amount_cal();

        let dia=document.querySelectorAll('.dialer_obj')

        dia.forEach(e => {
            // getCurrentQtyOnUnit($(e));
            let diaO = new KTDialer(e, {
                min: 0,
                step: 1,
                decimals: 2
            });

            diaO.on('kt.dialer.change',function(ev) {
                let unit=$('.unit_input').val();

                checkStockSaleQty($(ev.inputElement));
                calSalePrice($(ev.inputElement));
                sale_amount_cal();
            })
        });


        var waitTyping; //Timer variable for waiting typing

        $('.quick-search-form input').on('input', function() {
            clearTimeout(waitTyping);
            waitTyping = setTimeout(function() {
            var query = $(this).val().trim();
            let business_location_id = $('#business_location_id').val();
            let data = {
                business_location_id,
                query
            }
            if (query.length >= 3) {
                $('.quick-search-results').removeClass('d-none');
                $('.quick-search-results').html(`
                <div class="quick-search-result result cursor-pointer">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
                </div>
                `);

                $.ajax({
                    url: `/stock/get-product`,
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
                        products=results;
                        var html = '';
                        if (results.length > 0) {
                            results.forEach(function(result,key) {
                                let total_current_stock_qty=Number(result.total_current_stock_qty);
                                let css_class=result.total_current_stock_qty<=0?" text-gray-600 order-3":'';

                                html += `<div class="quick-search-result result  mt-1 mb-1 bg-hover-light p-2 ${css_class}" data-id=${key} data-name="${result.name}" style="z-index:300;">`;
                                html += `<h4 class="fs-6  pt-3 ${css_class}">
                                            ${result.name} ${result.product_type==='variable'?'-('+result.product_variations.length+') select all' :result.sku??'' }`;
                                if(result.product_type=='sub_variable'){
                                    html +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">(${result.variation_name??''})</span>`;
                                }
                                html+='</h4>'
                                if(result.total_current_stock_qty>0){
                                    html += `<p>${total_current_stock_qty.toFixed()} ${result.smallest_unit}(s/es)</p>`;
                                }else{
                                    html += '<p>Out of Stocks</p>';
                                }
                                html += '</div>';
                                html += '<div class="separator mb-1"></div>';
                            });
                            if (results.length == 1) {
                                $('.quick-search-results').show();
                                if(results[0].total_current_stock_qty>0){
                                    setTimeout(() => {
                                        $(`.result[data-name|='${results[0].name}']`).click();
                                        $('.quick-search-results').hide();
                                    },100);
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
            }else {
                $('.quick-search-results').addClass('d-none');
                $('.quick-search-results').empty();
            }
            }.bind(this), 600);
        });

        $('#autocomplete').on('click', '.result', function() {
            let id = $(this).attr('data-id');
            let selected_product= products[id];
            if(selected_product.total_current_stock_qty==0 || selected_product.total_current_stock_qty==null){
                return;
            }

            $('.dataTables_empty').addClass('d-none');
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




        $(document).on('change','.lot_no',function (e) {
            let parent = $(this).closest('.sale_row');
            let current_stock_qty_txt=parent.find('.current_stock_qty_txt');
            let single_purchase_price=parent.find('.single_purchase_price');
            let transaction_detail_id=parent.find('.transaction_detail_id');
            let current_stock_id=parent.find('.current_stock_id');
            let smallest_price=parent.find('.smallest_price');
            let uom_set=parent.find('.uom_set');
            let uom_set_id=parent.find('.uom_set_id');
            let unit_input=parent.find('.unit_input');
            let smallest_unit_txt=parent.find('.smallest_unit');
            let lotNo=parent.find('.lot_no').val();
            let expired_date=parent.find('.expired_date');
            $.ajax({
                url: `/stock/get-stock`,
                type: 'GET',
                data: {
                    lotNo
                },
                success: function(stock){
                    let uom_set_data=stock.uom_set;
                    let units=uom_set_data.units;
                    current_stock_qty_txt.text(parseFloat(stock.current_quantity).toFixed(2));
                    single_purchase_price.val(parseFloat(stock.purchase_price).toFixed(2));
                    smallest_price.val(parseFloat(stock.purchase_price).toFixed(2));
                    uom_set.val(uom_set_data.uomset_name);
                    uom_set_id.val(uom_set_data.id);
                    transaction_detail_id.val(stock.transaction_detail_id);
                    current_stock_id.val(stock.id);
                    smallest_unit_txt.text(stock.smallest_unit.name);
                    expired_date.val(stock.expired_date);
                    let unit_data_for_select2=units.map(function (data) {
                        return {id:data.id,text:data.name}
                    })
                    unit_input.empty();
                    unit_input.select2({data:unit_data_for_select2});
                    optionSelected(stock.smallest_unit_id,unit_input);
                    totalItem();
                }
            });

        })

        $(document).on('input','.quantity',function(e) {
            unit_price_cal($(this));
            checkStock($(this));
            totalItem();
            net_purchase_total_amount_cal($(this));
        })



        // Attach click event listener to all delete buttons in the table
        $(document).on('click', '#sale_table .deleteRow', function (e) {
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
                    totalItem();

                    rowCount = $('#sale_table tbody tr').length;
                    $('.total_item').text(rowCount-1);
                }
            });
        });




        $(document).on('change','.unit_input',function(){
            let parent=$(this).closest('.sale_row');
            let unit_selcted_txt=parent.find('.unit_input option:selected').text();
            let smallest_unit_txt=parent.find('.smallest_unit_txt');
            smallest_unit_txt.text(unit_selcted_txt);

            net_purchase_total_amount_cal($(this));
            unit_price_cal($(this));
            checkStock($(this));
        })





        //append table row for product to sell
        function append_row(selected_product,unique_name_id) {
            let default_purchase_price,variation_id;

            let lotOption='';
            // if the item is out of stock reutrn do nothing;
            if(selected_product.total_current_stock_qty==0){
                return;
            }
            selected_product.lot_nos.forEach((lot_no,key) => {
                lotOption+=` <option value="${lot_no}" >Lot ${key+1}</option>`
            })

            var newRow = `
                <tr class="sale_row">
                    <td>
                        <div class="my-5">
                            <span>${selected_product.name}</span>
                            <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name??''}</span>
                            <br>
                            <p class="text-gray-500">Current Stocks: <span class="current_stock_qty_txt">${parseFloat(selected_product.total_current_stock_qty).toFixed(2)}</span></p>

                            <select name="transfer_details[${unique_name_id}][lot_no]" id="" class="form-select mt-3 lot_no" data-lot-select-${unique_name_id}="select2"  data-hide-search="true">
                                ${lotOption}
                            </select>
                        </div>
                    </td>
                    <td class="d-none">
                        <div>
                            <input type='hidden'  class="current_stock_id"  name="transfer_details[${unique_name_id}][current_stock_id]"  />
                             <input type='hidden'  class="transaction_detail_id"  name="transfer_details[${unique_name_id}][transaction_detail_id]"  />

                            <input type='hidden' value="${selected_product.id}"  name="transfer_details[${unique_name_id}][product_id]"  />
                            <input type='hidden' value="${selected_product.product_variations.id}"  name="transfer_details[${unique_name_id}][variation_id]"  />
                            <input type="text" value="0" class="uom_set_id" name="transfer_details[${unique_name_id}][uomset_id]" />
                        </div>
                    </td>

                    <td>

<!--                            <button class="btn btn-icon btn-outline btn-active-color-danger" type="button" data-kt-dialer-control="decrease">-->
<!--                                <i class="fa-solid fa-minus fs-2"></i>-->
<!--                            </button>-->
                            <input type="text" class="form-control quantity"  placeholder="quantity" name="transfer_details[${unique_name_id}][quantity]" value="1" data-kt-dialer-control="input"/>

<!--                            <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">-->
<!--                                <i class="fa-solid fa-plus fs-2"></i>-->
<!--                            </button>-->
<!--                        </div>-->
                        <span class="text-danger-emphasis stock_error d-none fs-7 p-2">Out of Stock</span>
                        <select name="transfer_details[${unique_name_id}][unit_id]" id="" class="form-select mt-3 unit_input" data-kt-repeater="select2"  data-hide-search="true"  data-placeholder="Select Unit" required>

                        </select>
                    </td>

                    <td>
                     <input type="text" readonly class="form-control sum single_purchase_price  input_number"  id="numberonly"  value="">
                    <input type="text" hidden class="form-control smallest_price  input_number"  id="numberonly"  value="0">
                    </td>
                    <td>
                    <input type="text" hidden="" class="expired_date" name="transfer_details[${unique_name_id}][expired_date]"  value="${selected_product.expired_date}">
                    <input type="text" name="transfer_details[${unique_name_id}][remark]" class="form-control">
                    </td>
                    <td>
                     <span class="purchase_items_total_price_text">0</span>
                      <input type="text" hidden="" class="purchase_items_total_price form-control" id="numberonly" name="transfer_details[${unique_name_id}][purchase_price]" value="0">
                    </td>
                    <th><i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i></th>
                </tr>
            `;
            // default_purchase_price
            // new row append
            $('#sale_table tbody').append(newRow);
            $('.dataTables_empty').addClass('d-none');
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');

            let rowCount = $('#sale_table tbody tr').length;

            $('.total_item').text(rowCount-1);
            // searching disable in select 2
            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity});
            $(`[data-lot-select-${unique_name_id}="select2"]`).select2({

                minimumResultsForSearch: Infinity
            })

            optionSelected(selected_product.lot_nos[0],$(`[data-lot-select-${unique_name_id}="select2"]`));

            setTimeout(() => {
                totalItem();
            }, 100);
        }




        // Selected to Option
        function optionSelected(value_to_select, select_instance) {
            var $select = $(select_instance);

            return $select.val(value_to_select).trigger('change');
        }
        // Selected to Option

        async function unit_price_cal(childInput){

            try{
                const parent=childInput.closest('.sale_row');
                const unit_id=parent.find('.unit_input').val();
                const single_purchase_price=parent.find('.single_purchase_price');
                const purchase_items_total_price_text=parent.find('.purchase_items_total_price_text');
                const purchase_items_total_price=parent.find('.purchase_items_total_price');
                const uom_set_id=parent.find('.uom_set_id').val();
                const smallestPrice = parseFloat(parent.find('.smallest_price').val()) || 0;
                const quantity = parseFloat(parent.find('.quantity').val()) || 0;

                const response = await fetch(`/stock/${unit_id}/${uom_set_id}/values`);
                const currentUnitQtyVal = await response.json();

                const defaultPrice = currentUnitQtyVal * smallestPrice;
                single_purchase_price.val(defaultPrice.toFixed(2)); // Default Price

                const totalPrice = defaultPrice * quantity; // Total Price
                purchase_items_total_price_text.text(totalPrice.toFixed(2));
                purchase_items_total_price.val(totalPrice.toFixed(2));


                net_purchase_total_amount_cal($(this));

            } catch (error) {
                console.error(error);
            }
        }

        function isNullOrNan(val){
            let v=parseFloat(val);

            if(v=='' || v==null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }

        //Check Stock
        async function checkStock(e) {
            let parent=e.closest('.sale_row');

            try{
                let inputQuantity=isNullOrNan(parent.find('.quantity').val());
                let currentQuantity=parseFloat(parent.find('.current_stock_qty_txt').text());
                const response = await fetch(`/stock/${parent.find('.unit_input').val()}/${parent.find('.uom_set_id').val()}/values`);
                const currentUnitQty = await response.json();
                const finalQty = currentUnitQty * inputQuantity;

                console.log(currentUnitQty);
                console.log(finalQty);
                let stock_error=parent.find('.stock_error');
                if(finalQty > currentQuantity){
                    stock_error.removeClass('d-none');
                    $('.save_btn').prop('disabled',true)
                }else{
                    stock_error.addClass('d-none');
                    $('.save_btn').prop('disabled',false);
                }
            } catch (error) {
                console.error(error);
            }
        }
        //Check Stock

        // Total items
        function totalItem() {
            let total=0;
            $('.quantity').each(function() {
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
                console.log(total);
            });

            $('.net_purchase_total_amount_text').text(total);
            $('.net_purchase_total_amount').val(total)
        }
        //Net Total Price

    });
</script>
