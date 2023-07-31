
<script>
    var productsOnSelectData=[];
    $(document).ready(function () {
        var products;
        let unique_name_id=1;
        let products_length=$('#adjustment_table tbody tr').length-1;
        let productQty=[];
        let setting=@json($setting);
        {{--let currency=@json($currency);--}}
        let lotControl=setting.lot_control;

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
                let transferQty=isNullOrNan(getReferenceUomInfoByCurrentUomQty(detail.quantity,uoms.currentUom,uoms.referenceUom)['qtyByReferenceUom']);
                if(!product){
                    newProductData={
                        'product_id':detail.product_id,
                        'variation_id':detail.variation_id,
                        'total_current_stock_qty':detail.stock_sum_current_quantity,
                        'validate':true,
                        'uom':detail.product.uom,
                        'uom_id':detail.uom_id,
                        'stock':detail.stock,
                    };
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }else{
                    if(detail.status=='delivered'){
                        productsOnSelectData[secIndex].total_current_stock_qty=isNullOrNan(productsOnSelectData[secIndex].total_current_stock_qty)+ transferQty;
                    }
                }
            })
            //Edit Session

            editAdjustmentDetails.forEach(function(detail,index){
                let uom=$(`[name="adjustment_details[${index}][uom_id]"]`);
                uom.select2();
                let uom_select=uom.val();


                changeQtyOnUom(uom,uom_select);
            })


        }



        unique_name_id+=products_length;
        $('#business_location_id').on('change',function(){
            $('#adjustment_table').find('tbody').empty();

        })







        // for quick search
        let throttleTimeout;
        $('.quick-search-form input').on('input', function() {
            var query = $(this).val().trim();
            let business_location_id = $('#business_location_id').val();
            let data = {
                business_location_id,
                query //text from search bar
            }
            if (query.length >= 3) {
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
                                // console.log(e);
                                error(' Something Went Wrong! Error Status: '+status )
                            };
                        },
                        success: function(results){
                            // console.log(results);
                            products=results;
                            var html = '';
                            if (results.length > 0) {
                                results.forEach(function(result,key) {
                                    let total_current_stock_qty=Number(result.total_current_stock_qty);
                                    let css_class=result.total_current_stock_qty<=0?" text-gray-600 order-3":'';

                                    html += `<div class="quick-search-result result  mt-1 mb-1 bg-hover-light p-2 ${css_class} " data-id=${key} data-name="${result.name}" style="z-index:300;">`;
                                    html += `<h4 class="fs-6  pt-3 ${css_class}">
                                                ${result.name} ${result.product_type==='variable'?'-('+result.product_variations.length+') select all' :result.sku??'' }`;
                                    if(result.product_type=='sub_variable'){
                                        html +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">(${result.variation_name??''})</span>`;
                                    }
                                    html+='</h4>'
                                    if(result.total_current_stock_qty>0){
                                        html += `<p>${total_current_stock_qty.toFixed()} ${result.uom.name}(s/es)</p>`;
                                    }else{
                                        html += '<p>Out of Stocks</p>';
                                    }
                                    html += '</div>';
                                });
                                if (results.length == 1) {
                                    $('.quick-search-results').show();
                                    if(results[0].total_current_stock_qty>0){
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

        $('#autocomplete').on('click', '.result', function() {
            let id = $(this).attr('data-id');
            let selected_product= products[id];
            console.log(selected_product)
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

        //append table row for product to sell
        function append_row(selected_product,unique_name_id) {
            console.log(selected_product,'sp');

            let default_purchase_price,variation_id;
            // let uomSetOption=""
            let uomIds=[];
            // if the item is out of stock reutrn do nothing;
            if(selected_product.total_current_stock_qty==0){
                return;
            }
            let uomByCategory=selected_product['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })

            // var lotSelector;
            // if(lotControl=="on"){
            //     let stock=selected_product.stock[0];
            //     let lotOption='';
            //     selected_product.stock.forEach((s,key) => {
            //         lotOption+=` <option value="${s.id}" >Lot-${s.lot_serial_no ?? '-'}</option>`
            //     });
            //     lotSelector=`
            //         <td>
            //             <select name="adjustment_details[${unique_name_id}][stock_id_by_lot_serial_no]" id="" class="form-select form-select-sm mt-3 lot_no" data-lot-select-${unique_name_id}="select2"  data-hide-search="true">
            //                 ${lotOption}
            //             </select>
            //         </td>
            //         `
            // }else{
            //     let value=selected_product.lot_serial_nos[0];
            //
            //
            //     lotSelector=`
            //                         <input type="hidden" name="adjustment_details[${unique_name_id}][stock_id_by_lot_serial_no]" class="lot_no" value="${value.id}">
            //                     `
            // }
            var newRow = `
                <tr class="adjustment_row">
                    <td>
                        <div class="my-5 mt-2">
                            <span>${selected_product.name}</span>
                            <span class="text-primary fw-semibold fs-5">${selected_product.variation_name?'-'+selected_product.variation_name:''}</span>
                        </div>
                    </td>
                    <td>
                         <span class="current_stock_qty_txt">${parseFloat(selected_product.total_current_stock_qty).toFixed(2)}</span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>
                          <input type="hidden" class="balance_qty" value="${selected_product.total_current_stock_qty}"  name="adjustment_details[${unique_name_id}][balance_quantity]"  />
                     </td>
                    <td class="d-none">
                        <div>
                            <input type='hidden' value="${selected_product.id}" class="product_id"  name="adjustment_details[${unique_name_id}][product_id]"  />
                            <input type='hidden' value="${selected_product.product_variations.id}" class="variation_id" name="adjustment_details[${unique_name_id}][variation_id]"  />

                            @if ($setting->lot_control=='on')
                            <input type='hidden' value="0" class="uom_set_id"  />
                            @else
                            <input type='hidden' value="${selected_product.stock[0].id}" class="uom_set_id"  />
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="input-group transfer_dialer_${unique_name_id}" >
                            <input type="text" class="form-control form-control-sm gnd_quantity form-control-sm gnd_quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="adjustment_details[${unique_name_id}][gnd_quantity]" value="" data-kt-dialer-control="input"/>
                        </div>
                    </td>
                    <td>
                    <span class="adj_quantity_text">- </span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>
                    <input class="adj_quantity" type="hidden" name="adjustment_details[${unique_name_id}][adj_quantity]">
                    </td>
                    <td>
                        <select name="adjustment_details[${unique_name_id}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>
                        </select>
                        <input type="hidden" name="adjustment_details[${unique_name_id}][ref_uom_id]" value="${selected_product.stock[0].ref_uom_id}">
                    </td>

                    <th><i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i></th>
                </tr>
            `;

            // new row append
            $('#adjustment_table tbody').prepend(newRow);
            $('.dataTables_empty').addClass('d-none');
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');
            checkAndStoreSelectedProduct(selected_product);
            let rowCount = $('#adjustment_table tbody tr').length;

            $('.total_item').text(rowCount-1);

            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity});

            $(`[data-lot-select-${unique_name_id}="select2"]`).select2({

                minimumResultsForSearch: Infinity
            })
            // Dialer container element
            let name=`.transfer_dialer_${unique_name_id}`;
            let dialerElement = document.querySelector(name);

            // Create dialer object and initialize a new instance
            let dialerObject = new KTDialer(dialerElement);

            dialerObject.on('kt.dialer.change',function(e) {
                // checkStock($(e.inputElement));
            })
            $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
                minimumResultsForSearch: Infinity,
                data:uomsData,
            });
            optionSelected(selected_product.uom_id,$(`[name="adjustment_details[${unique_name_id}][uom_id]"]`));



            if ($('#adjustment_table tbody tr').length > 1) {
                $('.deleteRow').removeClass('disable');
                $('.deleteRow').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }
            $('.price_group').select2({minimumResultsForSearch: Infinity});

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
                let fileterProduct=productsOnSelectData.filter(function(p){
                    console.log(p.product_id==newSelectedProduct.id && p.variation_id==newSelectedProduct.product_variations.id,'-----------');
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
            console.log(productsOnSelectData);
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


        $(document).on('input','.adjustment_row input',function () {
            changeQtyOnUom($(this));
            calDifferenceQty($(this));
        })


        function calDifferenceQty(e) {
            const i = inputs(e);
            var gnd_quantity = Number(i.gnd_quantity.val());
            var current_stock_qty_txt = Number(i.current_stock_qty_txt.text());


            setTimeout(function() {
                var difference_qty =  gnd_quantity - current_stock_qty_txt;
                console.log(difference_qty);
                i.adj_quantity_text.text(difference_qty);
                i.adj_quantity.val(difference_qty);
            }, 800)


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
                if(lotControl=='on'){
                    currentRefQty =getReferenceUomInfoByCurrentUomQty(qty,currentUom,referenceUom)['qtyByReferenceUom'];
                }else{
                    currentRefQty =getReferenceUomInfoByCurrentUomQty(qty,referenceUom,referenceUom)['qtyByReferenceUom'];
                }


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

                calDifferenceQty(e);


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
        //
        // function checkStock(e){
        //     let parent = e.closest('.adjustment_row');
        //     let variationId=parent.find('.variation_id').val();
        //     let index;
        //     let product = productsOnSelectData.find(function(pd,i) {
        //         index=i;
        //         return  variationId == pd.variation_id;
        //     });
        //
        //     const uoms=product.uom.unit_category.uom_by_category;
        //
        //     const referenceUom =uoms.filter(function ($u) {
        //         return $u.unit_type == "reference";
        //     })[0];
        //     // let refQty=getReferenceUomInfoByCurrentUomQty(quantity,uom_select,referenceUom)['qtyByReferenceUom'];
        //     let result=0;
        //     $(`.gnd_quantity-${variationId}`).each(function(){
        //         let parent =  $(this).closest('.adjustment_row');
        //         let gnd_quantity=Number(parent.find('.gnd_quantity').val());
        //         let uom_id=Number(parent.find('.uom_select').val());
        //         const currentUom =uoms.filter(function ($u) {
        //             return $u.id ==uom_id;
        //         })[0];
        //         let refQty=getReferenceUomInfoByCurrentUomQty(gnd_quantity,currentUom,referenceUom)['qtyByReferenceUom'];
        //         let transferQty=getReferenceUomInfoByCurrentUomQty(gnd_quantity,currentUom,referenceUom)['qtyByReferenceUom'];
        //         result+=isNullOrNan(refQty)
        //
        //     })
        //     if(result > productsOnSelectData[index].total_current_stock_qty){
        //         productsOnSelectData[index].validate=false;
        //         parent.find('.current_stock_qty_txt').addClass('text-danger');
        //         parent.find('.smallest_unit_txt').addClass('text-danger');
        //         parent.find('.gnd_quantity').addClass('text-danger');
        //     }else{
        //         productsOnSelectData[index].validate=true;
        //         parent.find('.current_stock_qty_txt').removeClass('text-danger');
        //         parent.find('.smallest_unit_txt').removeClass('text-danger');
        //         parent.find('.gnd_quantity').removeClass('text-danger');
        //     }
        //     console.log(productsOnSelectData[0].validate);
        // }

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
            let parent = $(this).closest('.adjustment_row');
            let uom_select=parent.find('.uom_select').val();
            changeQtyOnUom($(this),uom_select);
        })


        $(document).on('change','.uom_select',function(e){
            console.log($(this).val());
            changeQtyOnUom($(this),$(this).val());
            // checkStock($(this));
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
                    var rowCount = $('#adjustment_table tbody tr').length;
                    if (rowCount == 2) {
                        $('.dataTables_empty').removeClass('d-none');
                    }
                    row.remove();
                    // checkStock($(this));
                    rowCount = $('#adjustment_table tbody tr').length;
                    $('.total_item').text(rowCount-1);
                }
            });
        });


        $(document).on('change','.unit_input',function(){
            let parent=$(this).closest('.adjustment_row');
            let unit_selcted_txt=parent.find('.unit_input option:selected').text();
            let smallest_unit_txt=parent.find('.smallest_unit_txt');
            smallest_unit_txt.text(unit_selcted_txt);

            // sale_amount_cal();

            // checkStock($(this));

        })
        $(document).on('change','.uom_set',function () {
            // getLotByUom($(this));

        })


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






    });
</script>
