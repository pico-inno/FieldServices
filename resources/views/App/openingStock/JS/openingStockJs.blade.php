<script>
$(document).ready(function() {
    let prodcuts;
    let productsOnSelectData=[];
    let osdDetails=@json($openingStockDetails?? []) ;
    if(osdDetails.length>0){
        osdDetails.forEach(function(osd,index){
            let newProductData={
                'id':osd.product.id,
                'variation_id':osd.product_variation.id,
                'product_variations':osd.product_variation,
                'uoms':osd.product.uom.unit_category.uom_by_category
            };
            const indexToReplace = productsOnSelectData.findIndex(p => p.product_id === newProductData.id && p.variation_id === newProductData.product_variations.id);
            if(indexToReplace !== -1){
                productsOnSelectData[indexToReplace] = newProductData;
            }else{
                productsOnSelectData=[...productsOnSelectData,newProductData];
            }
        })
    }
    const itemCal=()=>{
        let total=0;
        $('.quantity').each(function() {
            var value = isNullOrNan($(this).val());
            total += value;

        });
        $('.item_count').text(total );
    }
    const totalOpeningAmountCal=()=>{
        let total=0;
        $('.subtotal').each(function() {
            var value = isNullOrNan($(this).val());
            total += value;
        });
        $('.total_opening_amount').val(total);
    }

    $('#contact_id').on('change',function(){
        var selected_supplier =$(this).val();
        let supplier=suppliers.filter(function(supplier){
            return supplier.id==selected_supplier;
        })
        supplier=supplier[0];
        $('.supplier_address').html(`${supplier.address_line_1? supplier.address_line_1+',' :''} ${supplier.address_line_2? supplier.address_line_2+',' :''}<br>
           ${supplier.city? supplier.city+',' :''}${supplier.state? supplier.state+',' :''}${supplier.country? supplier.country+',' :''}<br>
           ${ supplier.zip_code? supplier.zip_code+',' :''}`)
    })

    let unique_name_id=1;
    let products_length=$('#openingStockTable tbody tr').length-1;
    unique_name_id+=products_length;


    let results=[];
    numberOnly();
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
                    type: 'GET',
                    delay:150,
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
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
                            error(' Something Went Wrong! Error Status: '+status )
                        };
                    },success:function(e){
                        results=e;
                        products=e;
                        var html = '';
                        // products=results;
                        if (results.length > 0) {
                            console.log(results);
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
        let default_purchase_price,variation_id;
        if(selected_product.has_variation=='single'){
            default_purchase_price=selected_product.product_variations[0].default_purchase_price;
            variation=selected_product.product_variations[0];
            variation_id=variation.id;
        }else if(selected_product.has_variation=='sub_variable'){
            default_purchase_price=selected_product.default_purchase_price;
            variation=selected_product.product_variations;
            variation_id=variation.variation_id;
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
        let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <a href='' class='text-gray-800 text-hover-primary mb-1'>${unique_name_id}</a>
                <input type="hidden" class="input_number product_id" value="${selected_product.id}" name="opening_stock_details[${unique_name_id}][product_id]">
            </td>
            <td class="d-none">
                <input type="hidden" class="input_number variation_id" value="${variation_id}" name="opening_stock_details[${unique_name_id}][variation_id]">
            </td>
            <td>
                <a href="#" class="text-gray-600 text-hover-primary mb-1 ">${selected_product.name}</a><br>
                <span class="text-gray-500 fw-semibold fs-5">${selected_product.variation_name??''}</span>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm  quantity input_number" placeholder="Quantity" name="opening_stock_details[${unique_name_id}][quantity]" value="1.00">

            </td>
            <td class="fv-row">
                <select name="opening_stock_details[${unique_name_id}][uom_id]" class="form-select form-select-sm unit_id " data-kt-repeater="uom_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                    data-placeholder="Select unit" placeholder="select unit" required>
                    <option value="">Select UOM</option>
                </select>
            </td>
            <td class="fv-row">
                <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                    name="opening_stock_details[${unique_name_id}][packaging_quantity]" value="1.00">
            </td>
            <td class="fv-row">
                <select name="opening_stock_details[${unique_name_id}][packaging_id]" class="form-select form-select-sm package_id"
                    data-kt-repeater="package_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                    data-placeholder="Select Package" placeholder="select Package" required>
                    <option value="">Select Package</option>
                    ${packagingOption}
                </select>
            </td>
            <td>
                <input type="text" class="form-control sum uom_price  form-control-sm input_number" name="opening_stock_details[${unique_name_id}][uom_price]" id="numberonly"  value="${default_purchase_price ?? 0}">
            </td>
            <td>
                <input type="text" class="form-control sum subtotal  form-control-sm input_number text-dark" name="opening_stock_details[${unique_name_id}][subtotal]" id="numberonly"  value="${default_purchase_price ?? 0}" >
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text" data-td-target="#kt_datepicker_1"  data-kt-repeater="datepicker">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input class="form-control form-control-sm" name="opening_stock_details[${unique_name_id}][expired_date]" class="exp_date" placeholder="Pick a date"  data-allow-clear="true" data-kt-repeater="datepicker" value="" />
                </div>
            </td>
            <td>
                <textarea name="opening_stock_details[${unique_name_id}][remark]" class="form-control form-control-sm" data-kt-autosize="true"  id="" ></textarea>
            </td>
            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;

        // Append the new row to the table body
        $('#openingStockTable tbody').prepend(newRow);
        $('.quick-search-results').addClass('d-none');
        $('.quick-search-results').empty();
        $('#searchInput').val('');
        $('[data-kt-repeater="select2"]').select2();
        $('[data-kt-repeater="unit_select"]').select2();
        itemCal();
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
        }).val(selected_product.purchase_uom_id)
        .trigger('change');
        $(`[data-kt-repeater=package_select_${unique_name_id}]`).select2({
            minimumResultsForSearch: Infinity
        });
        subtotalCal(`[data-kt-repeater="uom_select_${unique_name_id}"]`);
        //  $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
        //         data:uom_set
        // });

        // Re-init flatpickr
        $('#openingStockTable tbody').find('[data-kt-repeater="datepicker"]').flatpickr();

        // $('[data-kt-select="select2"]').select2();
         $('[data-kt-repeater="datepicker"]').flatpickr();

        // init_functions
        numberOnly();

        // user update validation
        var openingStockValidator = function () {
            // Shared variables

        const element = document.getElementById("openingStock_container");
        const form = element.querySelector("#openingStock_form");
        const table = $('#openingStockTable tbody tr').length-1;
        let uom_validate=`opening_stock_details[${unique_name_id}][uomset_id]`;
        let unit_validate=`opening_stock_details[${unique_name_id}][unit_id]`;
        var initAddOpeningStock = () => {

                var validationFields = {

                    };
                validationFields[uom_validate] = {
                    validators: {
                        notEmpty: { message: "Uom Set is required" },
                    },
                };
                validationFields[unit_validate] = {
                    validators: {
                        notEmpty: { message: "Unit field is required" },
                    },
                };
                var validator = FormValidation.formValidation(
                        form,
                        {
                            fields: validationFields,

                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                                })
                            },

                        }
                );
                // Submit button handler
                const submitButton = element.querySelector('[data-kt-opening-stock-action="submit"]');
                submitButton.addEventListener('click', function (e) {
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == 'Valid') {
                                e.currentTarget=true;
                                return true;
                            } else {
                                e.preventDefault();
                                // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        });
                    }
                });
        }

        // Select all handler

        return {
            // Public functions
            init: function () {
                initAddOpeningStock();
            }
        };
    }();
    $(document).on('change','.package_id',function(){
        packaging($(this));
    })
    $(document).on('change','.unit_id',function(){
        packaging($(this),'/');
    })
    $(document).on('input','.quantity',function(){
        packaging($(this),'/');
    })
    $(document).on('input','.package_qty',function(){
            packaging($(this),'*');
    })
    const packaging=(e,operator)=>{
        let parent = $(e).closest('.cal-gp');
        let unitQty=parent.find('.quantity').val();
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
                parent.find('.quantity').val(qDecimal(qtyByCurrentUnit));
            }
        }
    }
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        openingStockValidator.init();
    });







    }
// ==================================================== Events ================================

    $(document).on('click', '#openingStockTable .deleteRow', function (e) {
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

                        // Remove the row from the table
                        var rowCount = $('#openingStockTable tbody tr').length;
                        console.log(rowCount);
                        if (rowCount == 2) {
                            $('.dataTables_empty').removeClass('d-none');
                        }
                        row.remove();
                        // totalItem();
                        itemCal();
                        totalOpeningAmountCal();
                        // net_purchase_total_amount_cal();
                        // total_purchase_amount_cal() ;
                        // total_balance_amount_cal();
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
    $(document).on('input','.quantity,.uom_price,.subtotal',function(){
        subtotalCal($(this));
        itemCal();
        totalOpeningAmountCal();
    })


    const subtotalCal=(e)=>{
        let parent = $(e).closest('.cal-gp');
        let uom_price=parent.find('.uom_price').val();
        let quantity=parent.find('.quantity').val();
        let subtotal=parent.find('.subtotal');
        subtotal.val(uom_price * quantity);
        totalOpeningAmountCal();
    }

    function inputs(e) {
        let parent = $(e).closest('.cal-gp');
        let unit_id=parent.find('.unit_id')
        let exp_date=parent.find('.exp_date');

        return {
            parent,
            unit_id,
            exp_date,
        }
    }

    function numberOnly() {
    $(".input_number").keypress(function(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
        }
    });


    const autosize_elements = document.querySelectorAll('[data-kt-autosize="true"]');
    autosize(autosize_elements);
}

    function isNullOrNan(val,d=0){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return d;
        }else{
            return v;
        }
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
