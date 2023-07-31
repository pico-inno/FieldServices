<script>
    $(document).ready(function() {
        let uom_set=@json($uoms);

        let unique_name_id=1;
        let products_length=$('#purchase_table tbody tr').length-1;
        unique_name_id+=products_length;

        let results;
        totalItem();
        numberOnly();
        net_purchase_total_amount_cal();

        $('.quick-search-form input').on('input', function() {
            var query = $(this).val().trim();

            if (query.length >= 1) {

                $('.quick-search-results').removeClass('d-none');
                $('.quick-search-results').html(`<div class="quick-search-result result cursor-pointer p-2 ps-10 fw-senubold fs-5">
                    <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
                </div>`);

                // results = products.filter(function(result) {
                //     return result.name.toLowerCase().includes(query.toLowerCase()) || result.sku.toLowerCase().includes(query.toLowerCase());
                // });

                if (query.length >= 3) {

                    $.ajax({
                        url: `/stockin/search/product`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            data: query
                        },
                        error: function (e) {
                            status = e.status;
                            if (status == 405) {
                                warning('Method Not Allow!');
                            } else if (status == 419) {
                                error('Session Expired')
                            } else {
                                error(' Something Went Wrong! Error Status: ' + status)
                            }
                            ;
                        }, success: function (e) {
                            results = e;
                            products = e;
                            var html = '';
                            // products=results;
                            if (results.length > 0) {
                                console.log(results);
                                results.forEach(function (result, key) {
                                    html += `<div class="quick-search-result result cursor-pointer mt-1 mb-1 bg-hover-light p-2" data-id=${key} data-name="${result.name}" style="z-index:100;">`;
                                    html += `<h4 class="fs-6 ps-10 pt-3">
                                ${result.name} ${result.product_type === 'variable' ? '-(' + result.product_variations.length + ') select all' : ''}`;
                                    if (result.product_type == 'sub_variable') {
                                        html += `<span class="text-gray-700 fw-semibold p-1 fs-5">(${result.variation_name ?? ''})</span>`;
                                    }

                                    html += '</h4>'
                                    html += `<span class="ps-10 pt-3 text-gray-700">${result.sku ? 'SKU : ' + result.sku : ''} </span>`

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
                                html = '<p class="ps-10 pt-5 pb-2 fs-6 m-0 fw-semibold text-gray-800">No results found.</p>';
                            }
                            $('.quick-search-results').removeClass('d-none')
                            $('.quick-search-results').html(html);

                        }
                    });


                }

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
            let default_purchase_price,variation_id;
            if(selected_product.product_type=='single'){
                default_purchase_price=selected_product.product_variations[0].default_purchase_price;
                variation_id=selected_product.product_variations[0].id;
            }else if(selected_product.product_type=='sub_variable'){
                default_purchase_price=selected_product.default_purchase_price;
                variation_id=selected_product.variation_id;

            }

            let uomByCategory=selected_product['uom']['unit_category']['uom_by_category'];
            let uomsData=[];
            uomByCategory.forEach(function(e){
                uomsData= [...uomsData,{'id':e.id,'text':e.name}]
            })

            let newRow = `<tr class='cal-gp'>
            <td>
                <a href='' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                <input type="hidden" class="input_number " value="${selected_product.id}" name="stockin_details[${unique_name_id}][product_id]">
                <input type="hidden" class="input_number" value="${variation_id}" name="stockin_details[${unique_name_id}][variation_id]">
            </td>
            <td>
                <a href="#" class="text-gray-600 text-hover-primary mb-1 ">${selected_product.name}</a><br>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name??''}</span>

            </td>
            <td>
                <input type="text" class="form-control form-control-sm mb-3 purchase_quantity input_number" placeholder="Quantity" name="stockin_details[${unique_name_id}][quantity]" value="1">
                <select  name="stockin_details[${unique_name_id}][uom_id]" class="form-select form-select-sm unit_id mt-3" data-kt-repeater="uom_select" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select unit"   placeholder="select unit" required>
                    <option value="">Select UOM</option>
                </select>

            </td>
            <td>
                <input type="text" class="form-control form-control-sm sum purchase_price_without_discount  input_number" id="numberonly"  value="${default_purchase_price ?? 0}">
                <input type="text" hidden="" class="form-control sum smallest_price input_number"  id="numberonly"  value="${default_purchase_price ?? 0}">
            </td>
            <td>
                <input class="form-control form-control-sm" name="stockin_details[${unique_name_id}][expired_date]" class="exp_date" placeholder="Pick a date" data-kt-repeater="datepicker" value="" />
            <td>
                <input type="text" name="stockin_details[${unique_name_id}][remark]" class="form-control form-control-sm">
            </td>
            <td>
                <span class="purchase_items_total_price_text">${default_purchase_price}</span>
                <input type="hidden" name="stockin_details[${unique_name_id}][purchase_price]" class="input_number purchase_items_total_price" value="${default_purchase_price}">
            </td>
            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;

            // Append the new row to the table body
            $('#purchase_table tbody').append(newRow);
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');
            $('[data-kt-repeater="select2"]').select2();
            $('[data-kt-repeater="unit_select"]').select2();

            $('[data-kt-repeater="uom_select"]').select2({
                minimumResultsForSearch: Infinity,
                data:uomsData,
            });
            $('[data-kt-repeater="uom_select"]').val(selected_product.purchase_uom_id).trigger('change');

            // Re-init flatpickr
            $('#purchase_table tbody').find('[data-kt-repeater="datepicker"]').flatpickr();

            $('[data-kt-repeater="datepicker"]').flatpickr();

            // init_functions
            numberOnly();
            totalItem();
            net_purchase_total_amount_cal();
        }


// ==================================================== Events ================================

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
                }
            });
        });



        $(document).on('input','.cal-gp input',function () {
            totalItem();
            net_purchase_total_amount_cal();
            purchaseItemsTotalPriceCal($(this));
        })


        $(document).on('change','.unit_id',function () {
            unitPriceCal($(this));
            purchaseItemsTotalPriceCal($(this));
            net_purchase_total_amount_cal();
        });

        $(document).on('change','.uom_set',function () {
            let i=inputs($(this));
            let unit_id=i.unit_id;
            let uomset_id=$(this).val();
            unit_id.prop('disabled', false);
            let parent = $(this).closest('.cal-gp');
            $.ajax({
                url: `/stock/${uomset_id}/units`,
                type: 'GET',
                success: function(e) {
                    // console.log(uom_set);
                    parent.find('[data-kt-repeater="unit_select"]').empty();
                    parent.find('[data-kt-repeater="unit_select"]').select2({
                        data:e
                    });

                }
            })
            net_purchase_total_amount_cal();
            purchaseItemsTotalPriceCal($(this));
        })



        function inputs(e) {
            let parent = $(e).closest('.cal-gp');
            let unit_id=parent.find('.unit_id');
            let uom_set = parent.find('.uom_set');
            let purchase_quantity=parent.find('.purchase_quantity');
            let purchase_price_without_discount=parent.find('.purchase_price_without_discount');
            let purchase_items_total_price_text=parent.find('.purchase_items_total_price_text');
            let purchase_items_total_price=parent.find('.purchase_items_total_price');
            let exp_date=parent.find('.exp_date');
            let smallest_price = parent.find('.smallest_price');

            return {
                parent,
                unit_id,
                uom_set,
                purchase_quantity,
                purchase_price_without_discount,
                purchase_items_total_price_text,
                purchase_items_total_price,
                exp_date,
                smallest_price
            }
        }


        function fetchUnitValue(unitId, uomId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `/stock/${unitId}/${uomId}/values`,
                    type: 'GET',
                    success: resolve,
                    error: reject
                });
            });
        }

        function calculatePurchasePriceWithoutDiscount(currentUnitValue, smallestPrice) {
            return currentUnitValue * smallestPrice;
        }

        //Cal Default Purchase Price
        function unitPriceCal(e) {
            const i = inputs(e);
            const smallestPrice = isNullOrNan(i.smallest_price.val());
            const unitId = i.unit_id.val();
            const uomId = i.uom_set.val();

            fetchUnitValue(unitId, uomId)
                .then(currentUnitValue => {
                    const result = calculatePurchasePriceWithoutDiscount(currentUnitValue, smallestPrice);
                    i.purchase_price_without_discount.val(result);
                })
                .catch(error => {
                    console.log(error);
                });
        }

        //Cal Total Purchase Price each item
        async function purchaseItemsTotalPriceCal(e) {
            const i = inputs(e);
            const smallestPrice = isNullOrNan(i.smallest_price.val());
            const quantity = isNullOrNan(i.purchase_quantity.val());
            const unitId = i.unit_id.val();
            const uomId = i.uom_set.val();

            try {
                const currentUnitValue = await fetchUnitValue(unitId, uomId);
                const purchasePriceWithoutDiscount = calculatePurchasePriceWithoutDiscount(currentUnitValue, smallestPrice);
                const result = purchasePriceWithoutDiscount * quantity;

                i.purchase_items_total_price_text.text(result);
                i.purchase_items_total_price.val(result);

                net_purchase_total_amount_cal();
            } catch (error) {
                console.log(error);
            }
        }





    });


    function totalItem() {
        let total=0;
        $('.purchase_quantity').each(function() {
            var value = isNullOrNan($(this).val());
            total += value;

        });
        $('#total_item').text(total);
    }

    function net_purchase_total_amount_cal() {
        let total = 0;
        $('.purchase_items_total_price').each(function() {
            var value = isNullOrNan($(this).val());
            total += value;
        });

        $('.net_purchase_total_amount_text').text(total);
        $('.net_purchase_total_amount').val(total);
    }



    function isNullOrNan(val){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return 0;
        }else{
            return v;
        }
    }


    function numberOnly() {
        $(".input_number").keypress(function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            }
        });
    }
</script>
