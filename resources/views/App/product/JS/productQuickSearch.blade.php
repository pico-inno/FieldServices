<script>
    $(document).ready(function() {
        let products;
        let results=[];
        let throttleTimeout;
        let unique_name_id=1;
        let products_length=$('#additional_product_table tbody tr').length-1;
        unique_name_id+=products_length;


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

        function append_row(selected_product,unique_name_id) {

            let variation_id;
            if(selected_product.has_variation=='single'){
                variation_id=selected_product.product_variations[0].id;
            }else if(selected_product.has_variation=='sub_variable'){
                variation_id=selected_product.variation_id;
            }

            let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <a href='#' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                <input type="hidden" class="input_number " value="${selected_product.id}" name="additional_product_details[${unique_name_id}][product_id]">
            </td>
            <td class="d-none">
                <input type="hidden" class="input_number" value="${variation_id}" name="additional_product_details[${unique_name_id}][variation_id]">
            </td>
            <td>
                <span  class="text-gray-600 text-hover-primary">${selected_product.name}</span>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name ? '('+selected_product.variation_name+')' : ''}</span>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm mb-1 purchase_quantity input_number" placeholder="Quantity" name="additional_product_details[${unique_name_id}][quantity]" value="1.00">
            </td>
            <td>
                <select  name="additional_product_details[${unique_name_id}][uom_id]" class="form-select form-select-sm unit_id" data-kt-repeater="uom_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select unit"   placeholder="select unit" required>
                    <option value="">Select UOM</option>
                </select>
            </td>

            <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow" ></i></th>
        </tr>`;

            // Append the new row to the table body
            $('#additional_product_table tbody').prepend(newRow);
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
            optionSelected(selected_product.purchase_uom_id,$(`[name="additional_product_details[${unique_name_id}][uom_id]"]`));

            $('#searchInput').focus();

            $('input').off('focus').on('focus', function() {
                $(this).select();
            });
        }
// ==================================================== Events ================================
        function optionSelected(value, select) {
            select.val(value).trigger('change');
        }


        $(document).on('click', '#additional_product_table .deleteRow', function (e) {
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
                    var rowCount = $('#additional_product_table tbody tr').length;
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


    });


</script>
