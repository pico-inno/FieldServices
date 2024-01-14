<script>
    $(document).ready(function() {
        let products;
        let results=[];
        let throttleTimeout;
        let unique_name_id=1;
        let products_length=$('#rom_consume_table tbody tr').length-1;
        unique_name_id+=products_length;
        var optionProducts = @json($products);
        //for edit
        {{--$(document).ready(function(){--}}
        {{--    @if(isset($rom) && isset($rom->uom_id))--}}
        {{--    UomOption({{ $rom->uom_id }});--}}
        {{--    @endif--}}
        {{--});--}}
        //for edit

        //for create
        //old value load
        const selectedOption = $(this).find(':selected');
        const uom_id = selectedOption.data('product');

        // if (typeof uom_id !== 'undefined') {
        //     UomOption(uom_id);
        // }
        //old value load

        $(document).on('change', '.product_name', function() {

            const selectedOption = $(this).find(':selected');
            const uom_id = selectedOption.data('template_uom_id');
            const romVal = selectedOption.data('rom');

            $('#templateUom').val(uom_id);

            if (romVal === null) {
                $('#default_template').prop('checked', true);
                $('#default_template').on('click', function() {
                    return false;
                });
            } else {
                $('#default_template').prop('checked', false);
                $('#default_template').off('click');
            }
            // var selectedProduct = $('[name="product_id"]').children("option:selected");
            // var template_uom_id = selectedProduct.data('template_uom_id');
            // UomOption(uom_id);
        })
        //for create

        $('input').off('focus').on('focus', function() {
            $(this).select();
        });


        $('.quick-search-form input').on('input', function() {
            var query = $(this).val().trim();
            if (query.length >= 2) {
                $('.quick-search-results').removeClass('d-none');
                $('.quick-search-results').html(`<div class="quick-search-result result cursor-pointer p-2 ps-10 fw-senubold fs-5">
                <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
            </div>
            `);

                clearTimeout(throttleTimeout);
                throttleTimeout = setTimeout(function() {
                    $.ajax({
                        url: `/purchase/get/product`,

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

        var appendedUniqueId = [];
        function append_row(selected_product,unique_name_id) {

            let variation_id;
            if(selected_product.has_variation=='single'){
                variation_id=selected_product.product_variations[0].id;
            }else if(selected_product.has_variation=='sub_variable'){
                variation_id=selected_product.variation_id;
            }

            let newRow = `<tr class='cal-gp' data-unique_name_id="${unique_name_id}">
            <td class="d-none">
                <a href='#' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                <input type="hidden" class="input_number " value="${variation_id}" name="consume_details[${unique_name_id}][component_variation_id]">
            </td>
            <td class="d-none">
            </td>
            <td>
                <span  class="text-gray-600 text-hover-primary">${selected_product.name}</span>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name ? '('+selected_product.variation_name+')' : ''}</span>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm mb-1 purchase_quantity input_number" placeholder="Quantity" name="consume_details[${unique_name_id}][quantity]" value="1.00">
            </td>
            <td>
                <select  name="consume_details[${unique_name_id}][uom_id]" class="form-select form-select-sm unit_id" data-kt-repeater="uom_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select unit"   placeholder="select unit" required>
                    <option value="">Select UOM</option>
                </select>
            </td>
          <td id="applied_variation_column">
                <select  name="consume_details[${unique_name_id}][applied_variation_id]" class="form-select form-select-sm applied-variation" data-kt-repeater="applied_variation_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select Optoin">
                    <option value="">Select Option</option>
                </select>
            </td>

            <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow" ></i></th>
        </tr>`;

            // Append the new row to the table body
            $('#rom_consume_table tbody').prepend(newRow);
            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();
            $('#searchInput').val('');
            $('[data-kt-repeater="select2"]').select2({
                minimumResultsForSearch: Infinity
            });
            //
            let variantData=[];


            // var selectedOptionProduct = optionProducts.find(product => product.id == selectedProduct.val());
            // var productVariations = selectedOptionProduct.product_variations;
            // var checkVariations = selectedOptionProduct.has_variation;

            // if(checkVariations !== 'single'){
            //     try {
            //         productVariations.forEach(function(e){
            //
            //             let variation_template_value_name =e.variation_template_value.name;
            //             let variation_template_name =e.variation_template_value.variation_template.name;
            //             let fullName = variation_template_name+': '+ variation_template_value_name;
            //             variantData= [...variantData, {'id':e.id,'text':fullName}];
            //         })
            //
            //     } catch (e) {
            //         error('400 : Product need to define UOM');
            //         return;
            //     }
            // }


            $(`[data-kt-repeater="applied_variation_${unique_name_id}"]`).select2({
                minimumResultsForSearch: Infinity,
                data:variantData,
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
            optionSelected(selected_product.purchase_uom_id,$(`[name="consume_details[${unique_name_id}][uom_id]"]`));

            $('#searchInput').focus();

            $('input').off('focus').on('focus', function() {
                $(this).select();
            });

            appendedUniqueId.push(unique_name_id);
        }
// ==================================================== Events ================================
        function optionSelected(value, select) {
            select.val(value).trigger('change');
        }

        $('#productSelect').on('change', function() {
            handleSearchBox($(this));
            // $('.rom-table').remove();
        });

        handleSearchBox($('#productSelect'));

        function handleSearchBox(e){
            if (e.val()) {
                $('#searchInput').prop('disabled', false);
            } else {
                $('#searchInput').prop('disabled', true);
            }
        }

        // Assuming there is an input field with the name 'product_name' that triggers the change event
        // $('[name="product_id"]').change(function() {
        //     let variantData = [];
        //     var unique_name_id = 'your_value_here'; // Replace with the actual value of unique_name_id
        //
        //     var selectedProduct = $('[name="product_id"]').children("option:selected");
        //     var selectedOptionProduct = optionProducts.find(product => product.id == selectedProduct.val());
        //     var productVariations = selectedOptionProduct.product_variations;
        //     var checkVariations = selectedOptionProduct.has_variation;
        //
        //     if(checkVariations !== 'single'){
        //         try {
        //             productVariations.forEach(function(e){
        //                 let variation_template_value_name = e.variation_template_value.name;
        //                 let variation_template_name = e.variation_template_value.variation_template.name;
        //                 let fullName = variation_template_name + ': ' + variation_template_value_name;
        //                 variantData = [...variantData, {'id': e.id, 'text': fullName}];
        //             });
        //         } catch (e) {
        //             error('400 : Product needs to define UOM');
        //             return;
        //         }
        //     }
        //     $(`[data-kt-repeater="applied_variation_${appendedUniqueId}"]`).empty();
        //     $(`[data-kt-repeater="applied_variation_${appendedUniqueId}"]`).select2({
        //         minimumResultsForSearch: Infinity,
        //         data: variantData,
        //     });
        //
        // });


        $(document).on('click', '#rom_consume_table .deleteRow', function (e) {
            e.preventDefault();
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
                    console.log('Deleted row with ID ' + id + ', name: ' + name);
                    var rowCount = $('#rom_consume_table tbody tr').length;
                    console.log(rowCount);
                    if (rowCount == 2) {
                        $('.dataTables_empty').removeClass('d-none');
                    }
                    row.remove();
                }
            });
        });




        $(document).on('change','.uom_set',function () {
            let i=inputs($(this));
            let unit_id=i.unit_id;
            let uomset_id=$(this).val();
            unit_id.prop('disabled', false);
            let parent = $(this).closest('.cal-gp');
            $.ajax({
                url: `/purchase/${uomset_id}/units`,
                type: 'GET',
                success: function(e) {
                    parent.find('[data-kt-repeater="unit_select"]').empty();
                    parent.find('[data-kt-repeater="unit_select"]').select2({
                        data:e,
                        minimumResultsForSearch: Infinity
                    });

                }
            })
        })



        // function UomOption(uom_id){
        //     $.ajax({
        //         url: `/uom/get/${uom_id}`,
        //         type: 'GET',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(results){
        //             console.log(results);
        //             const purchaseUoM = $('#uom_unit')[0];
        //             purchaseUoM.innerHTML = '';
        //
        //             const defaultOption = document.createElement('option'); // Create default option
        //             defaultOption.value = '';
        //             defaultOption.text = 'Select an option';
        //             $(purchaseUoM).append(defaultOption);
        //
        //             for (let item of results) {
        //                 let option = document.createElement('option');
        //                 option.value = item.id;
        //                 option.text = item.name;
        //                 purchaseUoM.append(option);
        //
        //                 if (item.id === uom_id) {
        //                     option.selected = true;
        //                 }
        //             }
        //             $('#uom_unit').select2({ minimumResultsForSearch: Infinity });
        //         },
        //         error: function(e){
        //             console.log(e.responseJSON.error);
        //         }
        //     });
        // }



    });



</script>
