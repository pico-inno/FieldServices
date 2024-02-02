<script>
    $(document).ready(function () {
        var unique_name_id=1;
        var quickSearchResults = $('.quick-search-results');
        var products;
        var productsOnSelectData=[];
        var setting=@json($setting);
        var lotControl=setting.lot_control;
        var business_location_id = $('[name="from_location"]').val();
        let products_length=$('#transfer_table tbody tr').length-1;



        var mainLocation = null;
        var mainLocation = {{ optional($stockTransfer)->from_location }};

        unique_name_id+=products_length;

        var fromLocationSelect = $('select[name="from_location"]');

        fromLocationSelect.on('change', function() {
             mainLocation = $(this).val();
        });


        //Begin: edit row data load
        let editTransferDetails=@json($stock_transfer_details ?? []);
        let editTransfer=@json($stockTransfer ?? []);
        if (editTransferDetails.length>0) {
            editTransferDetails.forEach(function(detail,index){

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


            editTransferDetails.forEach(function(detail,index){
                let initPackage = $(`[name="transfer_details[${index}][packaging_id]"]`);
                let uom=$(`[name="transfer_details[${index}][uom_id]"]`);
                uom.select2();
                initPackage.select2();
                let uom_select=uom.val();

                changeQtyOnUom(uom,uom_select);
            })


        }
        //End: edit row data load


        //Begin: quick search
        $('.quick-search-form input').on('input', function() {
            var query = $(this).val().trim(); //input search query
            let business_location_id = $('#business_location_id').val();
            let psku_kw=$('#psku_kw').is(':checked');
            let vsku_kw=$('#vsku_kw').is(':checked');
            let pgbc_kw=$('#pgbc_kw').is(':checked');
            let data = {
                business_location_id,
                query, //text from search bar,
                psku_kw,
                vsku_kw,
                pgbc_kw
            }

            console.log(data);

            if (query.length >= 2) {
                quickSearchResults.removeClass('d-none');
                quickSearchResults.html(`
                    <div class="quick-search-result result cursor-pointer">
                    <span><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</span>
                    </div>
                `);

                setTimeout(function() {
                    $.ajax({
                        url: "{{ route('transfer.product.quicksearch') }}",
                        type: 'GET',
                        delay: 150,
                        data: {data},
                        error:function(e){
                            console.log(' Something Went Wrong! Error Status: '+ e.status )
                        },
                        success: function(results){
                            products=results;
                            var html = '';
                            if (results.length > 0) {
                                let sku;
                                let addedSku=[];

                                results.forEach(function(result,key) {

                                    let checkSku = addedSku.find((s) => s === result.sku);

                                    if(sku && result.sku==sku && !checkSku){
                                        html += `<div class="quick-search-result result cursor-pointer mt-1 mb-1 bg-hover-light p-2" style="order:-1;" data-id="selectAll" data-productid='${result.id}' style="z-index:100;">`;
                                        html += `<h4 class="fs-6 ps-10 pt-3">${result.name}-(selectAll)</h4>`;
                                        html += '</div>';
                                        addedSku=[...addedSku,result.sku];
                                        quickSearchResults.html(html);
                                    }else{
                                        sku=result.sku;
                                    }

                                    if (result.has_variation === 'variable' && results.length === 2) {
                                        return;
                                    }

                                    let total_current_stock_qty = Number(result.stock_sum_current_quantity);
                                    let css_class = isNullOrNan(result.stock_sum_current_quantity) <=0 && result.product_type === "storable" ? "order-3" : '';

                                    html += `<div class="quick-search-result result ps-10  mt-1 mb-1 bg-hover-light p-2 ${css_class} " data-id=${key} data-name="${result.name}" style="z-index:300;">`;
                                    html += `<h4 class="fs-6  pt-3 ${css_class} "> ${result.name}`;

                                    if (result.has_variation === 'variable') {
                                        html +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">( ${result.variation_name??''})</span>`;
                                    }

                                    html+='</h4>'
                                    html+=`<span class=" pt-3 text-gray-600 fw-bold fs-8"> ${result.has_variation === 'variable' ? 'SKU : '+result.variation_sku : 'SKU : '+result.sku} </span>`

                                    if (result.product_type === "storable") {
                                        if (result.stock_sum_current_quantity > 0) {
                                            html += `<p>${total_current_stock_qty.toFixed(2)} ${result.uom.name}(s/es)</p>`;
                                        }else{
                                            html += '<p class="text-danger">Out of Stocks</p>';
                                        }
                                    }
                                    html += '</div>';
                                });

                                if (results.length === 1 || (results[0].has_variation === 'variable' && results.length === 2)) {
                                    quickSearchResults.show();
                                    if(results[0].stock_sum_current_quantity > 0 || results[0].product_type !== "storable"){
                                        setTimeout(() => {
                                            $(`.result[data-name|='${results[0].name}']`).click();
                                            quickSearchResults.hide();
                                        }, 100);
                                    }
                                } else {
                                    quickSearchResults.show();
                                }
                            } else{
                                html = '<p>No results found.</p>';
                                quickSearchResults.show();
                            }

                            quickSearchResults.removeClass('d-none')
                            quickSearchResults.html(html);

                            $(document).click(function(event) {
                                if (!$(event.target).closest('.quick-search-results').length) {
                                    quickSearchResults.addClass('d-none')
                                }
                            });


                        },
                    });
                },300)

            }else {
                quickSearchResults.addClass('d-none');
                quickSearchResults.empty();
            }
        });
        //End: quick search


        //Begin: quick search results to click and autocomplete
        $('#autocomplete').on('click', '.result', function() {
            const quickSearchResults = $(this);
            quickSearchResults.addClass('d-none');

            const id = $(this).attr('data-id');
            let selected_product;

            if (id !== "selectAll") {
                selected_product = products[id];
                const isStorable = selected_product.product_type === "storable";
                const stockQuantity = selected_product.stock_sum_current_quantity;

                if ((stockQuantity === 0 || stockQuantity === null) && isStorable) {
                    return;
                }
            }

            if (id === "selectAll") {
                const productid = $(this).data('productid');
                const filteredProducts = products.filter(p => p.id === productid);

                filteredProducts.forEach(p => {
                    append_row(p, unique_name_id);
                    unique_name_id += 1;
                });

                return;
            }

            append_row(selected_product, unique_name_id);
            unique_name_id += 1;
            $('#searchInput').focus();
        });
        //End: quick search results to click and autocomplete


        //Begin: append row to transfer table
        function append_row(selected_product, unique_name_id) {
            console.log(selected_product);
            let isStorable = selected_product.product_type === "storable";
            if (selected_product.total_current_stock_qty === 0 && isStorable) {
                warning('Products are out of stock');
                return;
            }

            let uomByCategory = selected_product['uom']['unit_category']['uom_by_category'];
            let uomsData = createUomsData(uomByCategory);
            let packagingOption = createPackagingOptions(selected_product.product_variations, uomByCategory);

            let newRow = createNewRow(selected_product, unique_name_id, uomsData, packagingOption);

            let modalTemplate = lotModelTemplate(selected_product, unique_name_id);



            $('#transfer_table tbody').append(newRow);
            $('body').append(modalTemplate);
            $('.dataTables_empty').addClass('d-none');
            quickSearchResults.addClass('d-none');
            quickSearchResults.empty();
            $('#searchInput').val('');


            checkAndStoreSelectedProduct(selected_product);

            initializeSelect2(unique_name_id, uomsData);

            $(`[name="transfer_details[${unique_name_id}][uom_id]"]`).val(selected_product.uom_id).trigger('change');

            enableDeleteRowButton();
        }
        //End: append row to transfer table


        function createUomsData(uomByCategory) {
            let uomsData = [];
            uomByCategory.forEach(function (e) {
                uomsData = [...uomsData, { 'id': e.id, 'text': e.name }];
            });
            return uomsData;
        }

        function createPackagingOptions(variation, uomByCategory) {
            let packagingOption = '';
            if (variation.packaging) {
                variation.packaging.forEach((pk) => {
                    let package_uom_name = uomByCategory.find((item) => item.id === pk.uom_id).short_name;
                    packagingOption += `
                <option value="${pk.id}" data-qty="${pk.quantity}" data-uomid="${pk.uom_id}">${pk.packaging_name} (${Number(pk.quantity).toFixed(2)} ${package_uom_name})</option>
            `;
                });
            }
            return packagingOption;
        }

        function createNewRow(selected_product, unique_name_id, uomsData, packagingOption) {
            let qtyInputField = '';

            if (lotControl === 'on') {
                qtyInputField = `
                 <td>

                        <input type="text" class="form-control form-control-sm transfer_qty quantity form-control-sm"   aria-hidden="true" data-bs-toggle="modal" data-bs-target="#batch_lot_selection_modal_${unique_name_id}" value="0.00" readonly/>
                        <input type="hidden" class="form-control form-control-sm transfer_qty quantity form-control-sm quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="transfer_details[${unique_name_id}][quantity]" value="1" data-kt-dialer-control="input"/>

                </td>`;
            } else {
                qtyInputField = `
                          <td>
                            <div class="input-group transfer_dialer_${unique_name_id}" >
                                <input type="text" class="form-control form-control-sm transfer_qty quantity form-control-sm quantity-${selected_product.product_variations.id}"   placeholder="quantity" name="transfer_details[${unique_name_id}][quantity]" value="1" data-kt-dialer-control="input"/>
                            </div>
                         </td>`;
            }


            var newRow = `
                <tr class="transfer_row" data-row-id="${unique_name_id}">
                     <td class="d-none">
                        <input type='hidden' value="${selected_product.id}" class="product_id"  name="transfer_details[${unique_name_id}][product_id]"  />
                        <input type='hidden' value="${selected_product.product_variations.id}" class="variation_id" name="transfer_details[${unique_name_id}][variation_id]"  />
                        <input type='hidden' value="${selected_product.product_variations.id}" name="transfer_details[${unique_name_id}][currency_id]"/>

                        @if ($setting->lot_control=='on')
                            <input type='hidden' value="0" class="uom_set_id"  />
                        @else
                        <input type='hidden' value="${selected_product.stock[0].id}" class="uom_set_id"  />
                        @endif
                    </td>
                    <td>
                         <div class="my-5 mt-2">
                            <span>${selected_product.name}</span>
                            <span class="text-primary fw-semibold fs-5">${selected_product.variation_name?'-'+selected_product.variation_name:''}</span>
                        </div>
                    </td>
                    <td>
                        <span class="current_stock_qty_txt">${parseFloat(selected_product.stock_sum_current_quantity).toFixed(2)}</span> <span class='smallest_unit_txt'>${selected_product.smallest_unit}</span>
                        <p class="text-danger-emphasis  stock_alert_${selected_product.product_variations.id} d-none fs-7 p-2">* Out of Stock</p>
                    </td>
                  ${qtyInputField}
                    <td>
                        <select name="transfer_details[${unique_name_id}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>
                        </select>
                        <input type="hidden" name="transfer_details[${unique_name_id}][ref_uom_id]" value="${selected_product.stock[0].ref_uom_id}">
                    </td>
                    <td class="fv-row">
                        <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity" name="transfer_details[${unique_name_id}][packaging_quantity]" value="1.00">
                    </td>
                    <td class="fv-row">
                        <select name="transfer_details[${unique_name_id}][packaging_id]" class="form-select form-select-sm package_id"
                            data-kt-repeater="package_select_${unique_name_id}" data-kt-repeater="select2" data-hide-search="true"
                            data-placeholder="Select Package" placeholder="select Package" >
                            <option value="">Select Package</option>
                            ${packagingOption}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="transfer_details[${unique_name_id}][remark]">
                        <input type="hidden" class="modal-data-input" name="transfer_details[${unique_name_id}][lot_sertial_details]" value="">
                    </td>
                    <th>

                        <i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i>
                    </th>
                </tr>
                `;
            return newRow;
        }

        function lotModelTemplate(item ,unique_name_id){
        return `
                                <div class="modal fade batchLotModal" data-item="${item}" id="batch_lot_selection_modal_${unique_name_id}"  tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog mw-800px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"> </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                            </div>
                                            <div class="modal-body">



                                                <table class="table table-rounded table-striped border gy-7 gs-7" id="lotSerialTable_${unique_name_id}">
                                <thead>
                                    <tr>
                                        <th>Lot Serial</th>
                                        <th class="min-w-40px">Quantity</th>
                                        <th>UOM</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="lot_serial_body">

                                <tr class="lot_serial_row">
                                    <td>
                                        <select class="form-select form-select-sm lot_selection"
                                            name="lot_serial_no[]"
                                            data-kt-repeater="select2" data-hide-search="true"
                                            data-placeholder="Select Batch" >
                                            <option value="">Select Lot</option>

                                          ${generateLotOption(item)}
                                        </select>
                                        <input type="hidden" id="itemData" value='${JSON.stringify(item)}'>
                                    </td>
                                    <td>
                                        <input type="number" name="lot_serial_qty[]" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                    ${item.uom.name}
                                    </td>
                                   <td class="text-center">

                                              <button class="btn btn-sm  btn-light-primary btn-add-row" type="button">
                                                            <i class="ki-duotone ki-plus fs-5"></i>

                                                        </button>
                                        </td>
                                </tr>

                                </tbody>
                            </table>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                          <button type="button" class="btn btn-primary modal-btn-save-changes">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;


        }

        $(document).on('click', '.btn-add-row', function() {
            const modalContainer = $(this).closest('.batchLotModal');


            const firstRow = $(this).closest('.lot_serial_body').find('.lot_serial_row:first');
            // const itemObject = firstRow.find('#itemData').val();

            let itemObject = firstRow.find('.item_data').val();



            // console.log(aa);
            const itemData = JSON.parse(itemObject);

            console.log(itemData, 'itemdata')

            const newRow = ` <tr class="lot_serial_row">
                                    <td>
                                        <select class="form-select form-select-sm lot_selection"
                                            name="lot_serial_no[]"
                                            data-kt-repeater="select2" data-hide-search="true"
                                            data-placeholder="Select Batch" >
                                            <option value="">Select Lot</option>
                                               ${generateLotOption(itemData)}
                                        </select>
                                        <input type="hidden" id="itemData" value='${itemObject}'>
                                    </td>
                                    <td>
                                        <input type="number" name="lot_serial_qty[]" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        ${itemData.uom?.name ?? itemData.product?.uom?.name ?? ''}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm  btn-light-danger btn-remove-row">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>

                                        </button>
                                    </td>
                                </tr>`;

            $(this).closest('.lot_serial_body').append(newRow);
            $('.lot_selection').select2();
            eachModalcalculateTotalQuantity(modalId);
        });

        $(document).on('input', '[name="lot_serial_qty[]"]', function() {

            const modalContainer = $(this).closest('.batchLotModal');
            const modalId = modalContainer.attr('id');

            const selectElement = $(this).closest('tr').find('.lot_selection');
            const selectedOptionQty = parseFloat(selectElement.find(':selected').data('lotqty')) || 0;

            const inputQty = parseFloat($(this).val()) || 0;

            if (inputQty > selectedOptionQty) {
                $(this).addClass('danger-input');
                $(this).val(selectedOptionQty);
            } else {
                $(this).removeClass('danger-input');
            }

            eachModalcalculateTotalQuantity(modalId);
        });

        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.lot_serial_row').remove();
            const modalContainer = $(this).closest('.batchLotModal');
            const modalId = modalContainer.attr('id');

            setTimeout(function() {
                eachModalcalculateTotalQuantity(modalId);
            }, 0);

        });


        $(document).on('click', '.modal-btn-save-changes', function() {

            const modalId = $(this).closest('.modal').attr('id');


            const unique_name_id = modalId.substring("batch_lot_selection_modal_".length);

            const modalData = {};
            let totalQuantity = 0;


            $('#lotSerialTable_' + unique_name_id + ' tbody tr.lot_serial_row').each(function(index) {
                const rowInputs = $(this).find('input[name^="lot_serial_detail_id"], input[name^="lot_serial_qty"], input[name^="before_edit_lot_serial_qty"], select[name^="lot_serial_no"]');
                const rowData = {};

                rowInputs.each(function() {
                    const inputName = $(this).attr('name').replace('[]', '');

                    if (inputName === 'lot_serial_qty') {
                        const quantity = parseFloat($(this).val() || 0);
                        totalQuantity += quantity;
                    }

                    rowData[inputName] = $(this).val();
                });

                modalData[index + 1] = rowData;
            });

            const parentRow = $('#transfer_table tbody').find(`[data-row-id="${unique_name_id}"]`);
            const hiddenInput = parentRow.find('.modal-data-input'); // Find the hidden input element

            const jsonString = `{${Object.keys(modalData).map(key => `"${key}":${JSON.stringify(modalData[key])}`).join(',')}}`;
            hiddenInput.val(jsonString);

            parentRow.find('.transfer_qty').val(totalQuantity);

            checkQty(parentRow);


            // $('#' + modalId).modal('hide');
            $('#' + modalId).find('[data-bs-dismiss="modal"]').trigger('click');

        });

        function eachModalcalculateTotalQuantity(modalId) {
            let totalQuantity = 0;
            const uniqueModal = $('#' + modalId);

            const modalNumberInputs = uniqueModal.find('[name="lot_serial_qty[]"]');
            const $addButton = uniqueModal.find('.btn-add-row');


            const demandQuantity = parseInt(uniqueModal.find('.demand_qty_in_lot').val()) || 0;

            modalNumberInputs.each(function() {
                const value = parseInt($(this).val()) || 0;
                totalQuantity += value;
            });

            if (totalQuantity >= demandQuantity) {
                $addButton.prop('disabled', true);
            } else {
                $addButton.prop('disabled', false);
            }
        }


        function generateLotOption(item) {
            let lotOption = '';


            console.log()
            if (item.current_stock) {
                const uniqueLots = {};

                item.current_stock.forEach((csb) => {
                    const lotNo = csb.lot_serial_no;
                    const lotLocation = csb.business_location_id;
                    const currentQuantity = parseFloat(csb.current_quantity) || 0;

                    if (currentQuantity > 0 && lotLocation == mainLocation) {
                        // const currentUomQty = changeQtyOnUom(item.uom_data.uom_by_category, currentQuantity, csb.ref_uom_id, item.uom.id);
                        if (!uniqueLots[lotNo]) {
                            uniqueLots[lotNo] = currentQuantity;
                        } else {
                            uniqueLots[lotNo] += currentQuantity;
                        }
                    }
                });

                for (const lotNo in uniqueLots) {
                    lotOption += `
                <option value="${lotNo}" data-lotqty="${uniqueLots[lotNo]}">
                    ${lotNo} - Qty: ${uniqueLots[lotNo]} ${item.uom?.name ?? item.product?.uom?.name ?? ''}
                </option>
            `;
                }
            }

            return lotOption;
        }

        function initializeSelect2(unique_name_id, uomsData) {
            $('[data-kt-repeater="select2"]').select2({ minimumResultsForSearch: Infinity });
            $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).select2({
                minimumResultsForSearch: Infinity,
                data: uomsData,
            });
            $(`[data-kt-repeater=package_select_${unique_name_id}]`).select2({
                minimumResultsForSearch: Infinity
            });
        }

        function enableDeleteRowButton() {
            if ($('#transfer_table tbody tr').length > 1) {
                $('.deleteRow').removeClass('disable').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }
        }

        function inputs(e) {
            let parent = $(e).closest('.transfer_row');
            let quantity = parent.find('.quantity');
            let package_id = parent.find('.package_id');
            let package_qty = parent.find('.package_qty');
            let before_edit_quantity = parent.find('.before_edit_quantity');
            let smallest_unit_txt = parent.find('.smallest_unit_txt');
            let current_stock_qty_txt = parent.find('.current_stock_qty_txt');
            let uom_select = parent.find('.uom_select');
            let variation_id = parent.find('.variation_id');
            let product_id = parent.find('product_id');


            return {
                parent,
                product_id,
                variation_id,
                uom_select,
                quantity,
                package_id,
                package_qty,
                before_edit_quantity,
                smallest_unit_txt,
                current_stock_qty_txt,
            }
        }



        $('#business_location_id').on('change', function () {
            $('#transfer_table tbody tr:not(.dataTables_empty)').remove();
        });


        $(document).on('change','.package_id',function(){
            packaging($(this));
            checkStock($(this));
        });

        $(document).on('input','.quantity',function(){
            packaging($(this),'/');
            checkQty($(this));
            checkStock($(this));
        });

        $(document).on('change','.uom_select',function(){
            let parent=$(this).closest('.transfer_row');
            let unit_selcted_txt=parent.find('.unit_input option:selected').text();
            let smallest_unit_txt=parent.find('.smallest_unit_txt');
            smallest_unit_txt.text(unit_selcted_txt);

            changeQtyOnUom($(this),$(this).val());
            checkStock($(this));
            packaging($(this),'/');

        });

        $(document).on('input','.package_qty',function(){
            packaging($(this),'*');
            checkStock($(this));
            checkQty($(this));
        });


        function checkAndStoreSelectedProduct(newSelectedProduct) {
            let newProductData={
                'product_id':newSelectedProduct.id,
                'variation_id':newSelectedProduct.product_variations.id,
                'defaultSellingPrices':newSelectedProduct.product_variations.default_selling_price,
                'sellingPrices':newSelectedProduct.product_variations.uom_selling_price,
                'total_current_stock_qty':newSelectedProduct.stock_sum_current_quantity,
                'aviable_qty':newSelectedProduct.stock_sum_current_quantity,
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
        }

        function packaging(e, operator) {
            const i = inputs(e);
            const unitQty = Number(i.quantity.val());
            const packageInputQty = Number(i.package_qty.val());
            const currentUomId = Number(i.uom_select.val());
            const variation_id = Number(i.variation_id.val());

            const selectedOption = i.package_id.find(':selected');
            const packagingUom = selectedOption.data('uomid');
            const packageQtyForCal = selectedOption.data('qty');

            const product = productsOnSelectData.find(pod => pod.variation_id === variation_id);
            const uoms = product.uom.unit_category.uom_by_category;

            if (packageQtyForCal && packagingUom) {
                if (operator === '/') {
                    const unitQtyValByUom = changeQtyOnUom2(currentUomId, packagingUom, unitQty, uoms).resultQty;
                    i.package_qty.val(qDecimal(isNullOrNan(unitQtyValByUom) / isNullOrNan(packageQtyForCal)));
                } else {
                    const result = isNullOrNan(packageQtyForCal) * isNullOrNan(packageInputQty);
                    const qtyByCurrentUnit = changeQtyOnUom2(packagingUom, currentUomId, result, uoms).resultQty;
                    i.quantity.val(qDecimal(qtyByCurrentUnit));
                }
            }
        }

        function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='') {

            let newUomInfo = uoms.find((uomItem) => uomItem.id === newUomId);
            let currentUomInfo = uoms.find((uomItem) => uomItem.id === currentUomId);
            let refUomInfo = uoms.find((uomItem) => uomItem.unit_type === "reference");

            let currentRefQty = isNullOrNan(getReferenceUomInfoByCurrentUomQty(currentQty,currentUomInfo,refUomInfo).qtyByReferenceUom);
            let currentUomType = currentUomInfo.unit_type;
            let newUomType = newUomInfo.unit_type;
            let newUomValue=newUomInfo.value;
            let currentUomValue=currentUomInfo.value;
            let resultQty;
            let resultPrice;

            if ( newUomType === 'bigger') {
                resultQty = currentRefQty / newUomInfo.value;
            } else if (newUomType === 'smaller') {
                resultQty = currentRefQty * newUomInfo.value;
            } else {
                resultQty = currentRefQty;
            }


            resultPrice='';
            if(currentUomPrice !== ''){
                if(currentUomId === newUomId){
                    resultPrice = currentUomPrice ;
                    return {resultQty,resultPrice};
                }
                if (currentUomType === 'reference' && newUomType === 'smaller') {
                    resultPrice = currentUomPrice /(newUomInfo.value * currentUomInfo.value);
                }else if (currentUomType === 'reference' && newUomType === 'bigger') {
                    resultPrice = currentUomPrice * newUomValue;
                }else if (currentUomType === 'bigger' && newUomType === 'reference') {
                    resultPrice = currentUomPrice / currentUomValue;
                }else if (currentUomType === 'bigger' && newUomType === 'bigger') {
                    resultPrice = currentUomPrice *( newUomInfo / currentUomValue);
                }else if (currentUomType === 'smaller' && newUomType === 'bigger') {
                    resultPrice = currentUomPrice * (currentUomValue * newUomValue) ;
                }else if (currentUomType === 'smaller' && newUomType === 'smaller') {
                    resultPrice = currentUomPrice / newUomValue;
                }else if (currentUomType === 'smaller' && newUomType === 'reference') {
                    resultPrice = currentUomPrice * currentUomValue ;
                }else{
                    resultPrice = currentUomPrice  ;
                }
            }
            return {resultQty,resultPrice};

        }

        function checkQty(e) {
            const i = inputs(e);
            var quantity = Number(i.quantity.val());
            var before_edit_quantity = Number(i.before_edit_quantity.val() ?? 0);
            var current_stock_qty_txt = Number(i.current_stock_qty_txt.text());

            setTimeout(function() {
                var isQtyInvalid =  quantity > current_stock_qty_txt + before_edit_quantity;

                var isQtyNull = quantity === 0 || quantity == null;
                i.quantity.toggleClass('text-danger', isQtyInvalid || isQtyNull);
                i.current_stock_qty_txt.toggleClass('text-danger', isQtyInvalid);
                i.smallest_unit_txt.toggleClass('text-danger', isQtyInvalid);
                $('.update_btn').toggleClass('disabled', isQtyInvalid || isQtyNull);
            }, 700)
        }

        function checkStock(e){
            let parent = e.closest('.transfer_row');
            let variationId=parent.find('.variation_id').val();
            let index;

            let product = productsOnSelectData.find(function(pd,i) {
                index=i;
                return  variationId == pd.variation_id;
            });

            const uoms=product.uom.unit_category.uom_by_category;

            const referenceUom =uoms.filter(function ($u) {
                return $u.unit_type == "reference";
            })[0];

            let result=0;
            $(`.quantity-${variationId}`).each(function(){

                let quantity=Number(parent.find('.quantity').val());
                let uom_id=Number(parent.find('.uom_select').val());
                const currentUom =uoms.filter(function ($u) {
                    return $u.id ==uom_id;
                })[0];
                let refQty=getReferenceUomInfoByCurrentUomQty(quantity,currentUom,referenceUom)['qtyByReferenceUom'];

                result+=isNullOrNan(refQty)

            })

            if(result > productsOnSelectData[index].total_current_stock_qty){

                productsOnSelectData[index].validate=false;
                parent.find('.current_stock_qty_txt').addClass('text-danger');
                parent.find('.smallest_unit_txt').addClass('text-danger');
                parent.find('.quantity').addClass('text-danger');
                $('.save_btn').addClass('disabled');
            }else{

                productsOnSelectData[index].validate=true;
                parent.find('.current_stock_qty_txt').removeClass('text-danger');
                parent.find('.smallest_unit_txt').removeClass('text-danger');
                parent.find('.quantity').removeClass('text-danger');
                $('.save_btn').removeClass('disabled');
            }

        }

        function changeQtyOnUom(e, newUomId) {
            try {
                const parent = e.closest('.transfer_row');
                const productId = Number(parent.find('.product_id').val());
                const variationId = Number(parent.find('.variation_id').val());

                const product = productsOnSelectData.find(pd => pd.product_id === productId && pd.variation_id === variationId);

                const { total_current_stock_qty: qty, uom } = product;
                const { unit_category: { uom_by_category: uoms } } = uom;

                const newUomInfo = uoms.find(nu => nu.id === Number(newUomId));
                const { unit_type: newUomType } = newUomInfo;

                const referenceUom = uoms.find($u => $u.unit_type === "reference");
                const { unit_type: refUomType } = referenceUom;

                const { qtyByReferenceUom } = getReferenceUomInfoByCurrentUomQty(qty, referenceUom, referenceUom);

                let result = 0;
                if (refUomType === 'reference' && newUomType === 'bigger') {
                    result = qtyByReferenceUom / newUomInfo.value;
                } else if (refUomType === 'reference' && newUomType === 'smaller') {
                    result = qtyByReferenceUom * newUomInfo.value;
                } else {
                    result = qtyByReferenceUom;
                }

                const rounded_amount = newUomInfo.rounded_amount ?? 2;
                const roundedResult = Math.floor(isNullOrNan(result), rounded_amount);

                parent.find('.current_stock_qty_txt').text(roundedResult);
                parent.find('.smallest_unit_txt').text(newUomInfo.name);

            } catch (error) {
                console.error(error);
            }
        }

        function getReferenceUomInfoByCurrentUomQty(qty, currentUom, referenceUom) {
            const currentUomType = currentUom.unit_type;
            const currentUomValue = currentUom.value;
            const referenceUomId = referenceUom.id;
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

        function getCurrentAndRefUom(uoms,currentUomId){
            const currentUom =uoms.filter(function ($u) {
                return $u.id ==currentUomId;
            })[0];
            const referenceUom =uoms.filter(function ($u) {
                return $u.unit_type == "reference";
            })[0];
            return {currentUom,referenceUom};
        }

        function isNullOrNan(val) {
            const v = parseFloat(val);
            return isNaN(v) ? 0 : v;
        }

        // Attach click event listener to all delete buttons in the table
        $(document).on('click', '#transfer_table .deleteRow', function (e) {
            const $row = $(this).closest('tr');
            const rowCount = $('#transfer_table tbody tr').length - 1;

            if (rowCount === 1) {
                $(this).css({
                    'cursor': 'not-allowed',
                    'opacity': 0.5
                });
                e.preventDefault();
                return false;
            }



            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to remove it!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f1416c',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    const id = $row.attr('data-id');
                    $row.remove();

                    if (rowCount === 2) {
                        $('.dataTables_empty').removeClass('d-none');
                    }

                    checkStock($(this));
                    $('.total_item').text(rowCount - 1);
                }
            });
        });

    });
</script>
