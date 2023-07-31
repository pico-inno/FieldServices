<script>
    $(document).ready(function() {
        let services = @json($services);
        let uoms = @json($uoms ?? '');
        
        let checkServiceSale = @json($serviceSale->serviceSaleDetails ?? null); // for EDIT PAGE
        let serviceUsedProducts = @json($serviceUsedProducts ?? null); // for Edit Page
        let productIdAndName = @json($productIdAndName ?? null);
        let variationIdAndName = @json($variationIdAndName ?? null);

        let totalCal = () => {
            let totalDisType = $('select[name="parent_discount_type"]').val();
            let totalDisAmt = isNullOrNan($('input[name="total_discount_amount"]').val());
            let allServiceAmt = isNullOrNan($('#total-amount').text());
            let paidAmt = isNullOrNan($('input[name="paid_amount"]').val());
            // console.log(paidAmt)

            if(totalDisType == 'fixed'){
                total_price_after_discount = allServiceAmt - totalDisAmt;
            } 
            if(totalDisType=='percentage'){
                let percentage_amount = allServiceAmt * (totalDisAmt / 100);
                total_price_after_discount = allServiceAmt - percentage_amount;
            }

            let balance_amount = total_price_after_discount - paidAmt;
            
            $('.total_discount_amount_txt').text(totalDisAmt);
            $('.total_sale_amount_txt').text(total_price_after_discount);
            $('.paid_amount_txt').text(paidAmt);
            $('.balance_amount_txt').text(balance_amount);

            $('input[name="total_sale_amount"]').val(total_price_after_discount);
            $('input[name="balance_amount"]').val(balance_amount);
        }

        let showEmptyText = () => {
            if($('#table-body tr').length === 1){
                $('.dataTables_empty').removeClass('d-none');
            }else{
                $('.dataTables_empty').addClass('d-none');
            }
        }

        let isNullOrNan = (val) => {
            let v=parseFloat(val);

            if(v === '' || v === null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }

        let totalItemsAndPrice = () => {
            let itemRows = $('#table-body .item-row').length;
            $('#total-item').text(itemRows);

            let totalPrice = 0;
            let allPrice = $('.item-row #service_price');
            $('.item-row #service_price').each(function(){
                let price = parseFloat($(this).text().trim());
                totalPrice += price;
            })
            $('#total-amount').text(totalPrice);
            $('input[name="sale_amount"]').val(totalPrice);
            totalCal();
        } 

        let calPrice = (e) => {
            let parent = e.closest('tr');
            let sale_price_without_discount = isNullOrNan(parent.find('input[name="price[]"]').val());
            let discount_type = parent.find('select[name="child_dis_type[]"]').val();
            let discount_amount = isNullOrNan(parent.find('input[name="dis_amount[]"]').val());
            let quantity = isNullOrNan(parent.find('input[name="quantity[]"]').val());

            if(discount_type == 'fixed'){
                let price_without_dis = (quantity * sale_price_without_discount);
                price_after_discount = price_without_dis - discount_amount;
            }
            if(discount_type=='percentage'){
                let percentage_amount = sale_price_without_discount * (discount_amount / 100);
                price_after_discount = (sale_price_without_discount - percentage_amount) * quantity
            }

            parent.find('#service_price').text(price_after_discount);
            parent.find('input[name="sale_price[]"]').val(price_after_discount);
            totalItemsAndPrice();
        }
        
        let searchItem = (id, name, price) => {
            return `<div class="quick-search-result cursor-pointer mt-3 mb-3 bg-hover-light p-2" data-id="${id}" id="searchItem" style="z-index:100;">
                        <h4 class="fs-6 ps-10 pt-3 me-4 d-inline">
                            ${name}
                        </h4>
                        <span class="fs-6 ps-10 pt-3">
                            ${price * 1}
                        </span>
                    </div>`;
        }        

        showEmptyText();
        
        let filteredServices = [];

        // search service item
        $('#searchInput').on('input', function(event) {
            $('#search_container').empty();

            const searchText = $(this).val().trim().toLowerCase();
            
            if (searchText.length === 0) {
                $('.quick-search-results').addClass('d-none');
                return;
            }

            filteredServices = services.filter( service => {
                return service.name.toLowerCase().includes(searchText);
            })
            
            if(filteredServices.length > 0){
                $('.quick-search-results').removeClass('d-none');
                $(filteredServices).each(function(index, element) {
                    let checkServiceSaleDetail = checkServiceSale ? checkServiceSale.filter(item => item.service_id === element.id) : '';
                    if(checkServiceSaleDetail.length > 0){
                        // table body ထဲမှာ ရှိပြီးသားဆို search မှာ မပြ
                        return;
                    }
                    $('#search_container').append(searchItem(element.id, element.name, element.price));
                })
            }else {
                $('.quick-search-results').addClass('d-none');
            }
        })

        let newRow = ` 
            <tr class="item-row">

                <input type="hidden" name="service_detail_id[]" value="">
                <input type="hidden" name="service_id[]"  value=""/>
                <td id="service_name">
                    
                </td>
                <input type="hidden" name="uom_id[]"  value=""/>
                <td id="service_unit">
                </td>
                <td>
                    <div class="input-group w-md-150px service-quantity">
                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" id="decrease">
                            <i class="fa-solid fa-minus fs-2"></i>
                        </button>
                        <input type="text" name="quantity[]" class="form-control" placeholder="Amount" value="1"/>
                        
                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" id="increase">
                            <i class="fa-solid fa-plus fs-2"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control" name="price[]">
                </td>
                <td>
                    <select name="child_dis_type[]" id="child-dis-type" class="form-select mb-3" data-kt-select2="true" data-placeholder="Select status" data-hide-search="true">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                    <input type="text" class="form-control" value="0" name="dis_amount[]">
                </td>
                <input type="hidden" name="sale_price[]" val="" />
                <td id="service_price">
                </td>
                <td>
                    <i class="fa-solid fa-trash text-danger" type="button" id="delete-item"></i>
                </td>
            </tr>
        `;

        // when select search item / add new tr to tbody
        $('#search_container').on('click', '.quick-search-result', function(event) {
            let id = $(this).attr('data-id');
            // console.log(id)
            $(this).closest(`.quick-search-result`).remove();
            if(!$('#search_container #searchItem').length) {
                $('.quick-search-results').addClass('d-none');
            }

            let item = filteredServices.filter( item => {
                return item.id === parseInt(id);
            })
           
            let uom = uoms.filter( uom => {
                return uom.id === item[0].uom_id;
            })
            
            let cloneRow = $(newRow).clone();

            cloneRow.find('input[name="service_id[]"]').val(item[0].id)
            cloneRow.find('input[name="uom_id[]"]').val(uom[0].id)

            cloneRow.find('#service_name').text(item[0].name)
            cloneRow.find('#service_unit').text(uom[0].name)
            cloneRow.find('input[name="price[]"]').val(item[0].price * 1)
            cloneRow.find('#service_price').text(item[0].price * 1)
            $('#table-body').append(cloneRow)
            showEmptyText();

            totalItemsAndPrice();
            totalCal();

            // for service used products
            fetchServiceProducts(id);
        })        

        $(document).click(function(event) {
            if (!$(event.target).closest('.quick-search-result').length  && $('#search_container #searchItem').length) {
                $('.quick-search-results').addClass('d-none')
            }
        });

        // for increase and decrease SERVICE ITEM QUANTITY
        $(document).on('click', '#increase', function() {
            let incVal = $(this).closest('tr').find('input[name="quantity[]"]');
            let value = parseInt(incVal.val()) + 1;
            incVal.val(value);

            calPrice($(this));
            totalCal();
        })

        $(document).on('click', '#decrease', function() {
            let decVal = $(this).closest('tr').find('input[name="quantity[]"]');
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);

            calPrice($(this));
            totalCal();
        })

        $(document).on('input', 'input[name="quantity[]"]', function(){
            calPrice($(this));
            totalCal();
        })

        $(document).on('input', '[name="price[]"]', function(e){
            calPrice($(this));
            totalCal();
        })

        $(document).on('input', '[name="dis_amount[]"]', function(e){
            calPrice($(this));
            totalCal();
        })

        $(document).on('change', '#child-dis-type', function() {
            calPrice($(this));
            totalCal();
        })

        // for delete item
        $(document).on('click', '#delete-item', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to remove it!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f1416c',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.value){
                    let delete_row_id = $(this).closest('tr').find('input[name="service_id[]"]').val();
                    $(this).closest('tr').remove();
                    totalItemsAndPrice();
                    totalCal();
                    showEmptyText();
                    deleteServiceProductRow(delete_row_id);
                }
            });
        })

        //  =====> TOTAL DISCOUNT TYPE AND PRICE <=======

        $(document).on('change', '#parent-discount-type', () => {
            totalCal();
        })

        $(document).on('input', '[name="total_discount_amount"]', () => {
            totalCal();            
        })

        $(document).on('input', 'input[name="paid_amount"]', () => {
            totalCal();
        })

        // FOR EDIT PAGE
        if(checkServiceSale !== null){ 
            // console.log(checkServiceSale)
            
            $(checkServiceSale).each(function(index, value){
                let service_name = services.filter( item => item.id === value.service_id)[0] ?. name;
                let uom_name = uoms.filter( item => item.id === value.uom_id)[0] ?. name;

                let cloneRow = $(newRow).clone();
                cloneRow.find('input[name="service_id[]"]').val(value.service_id);
                cloneRow.find('input[name="uom_id[]"]').val(value.uom_id);
                cloneRow.find('input[name="service_detail_id[]"]').val(value.id);

                cloneRow.find('#service_name').text(service_name);
                cloneRow.find('#service_unit').text(uom_name);
                cloneRow.find('input[name="price[]"]').val(value.sale_price_without_discount * 1);
                cloneRow.find('#service_price').text(value.sale_price * 1);
                cloneRow.find('select[name="child_dis_type[]"]').val(value.service_detail_discount_type);
                cloneRow.find('input[name="dis_amount[]"]').val(value.discount_amount * 1);
                cloneRow.find('input[name="quantity[]"]').val(value.quantity * 1);
                $('#table-body').append(cloneRow)
            })
            showEmptyText();

            totalItemsAndPrice();
            totalCal();
        }


        //  =====> SERVICE USED PRODUCTS <=======
        let showEmptyTextService = () => {
            if($('#product-table-body tr').length === 1){
                $('.product_dataTables_empty').removeClass('d-none');
            }else{
                $('.product_dataTables_empty').addClass('d-none');
            }
        }
        showEmptyTextService();

        let fetchServiceProducts = (id) => {
            $.get(`/service-sale/service-product/${id}`, function(data) {
                $(data.variousDatas).each(function (index, element){
                    let uom_name = uoms.find(uom => uom.id === element.uom_id) ?. name;
                    setTableRow(element.service_name, element.product_name, uom_name, element.variation_value, element.quantity,
                                element.service_id, element.uom_id, element.product_variation_id, element.product_id);
                })
            }).fail(function(xhr, status, error) {
                console.log('Error:', error);
            });
        }

        let setTableRow = (service_name, product_name, uom_name, variation_name, quantity, ser_id, uom_id, vari_id, pro_id, old_id = null, old_ssd_id = null) => {
            let service_product_row = () => {
                return `
                    <tr class="delete_${ser_id}">
                        <input type="hidden" name="old_service_used_product_id[]" value="${old_id}" />
                        <input type="hidden" name="old_service_sale_detail_id[]" value="${old_ssd_id}" />

                        <input type="hidden" name="service_used_id[]" value="${ser_id}" />
                        <input type="hidden" name="service_used_unit_id[]" value="${uom_id}" />
                        <input type="hidden" name="service_used_vari_id[]" value="${vari_id}" />
                        <input type="hidden" name="service_used_product_id[]" value="${pro_id}" />
                        <input type="hidden" name="service_used_quantity[]" value="${quantity}" />

                        <td id="service_used_name">${service_name}</td>
                        <td id="service_product_name">${product_name}</td>
                        <td id="service_unit_name">${uom_name}</td>
                        <td id="service_vari_name">${variation_name}</td>
                        <td id="service_quantity">${quantity}</td>
                    </tr>
                `;
            }

            $('#product-table-body').append(service_product_row);
            showEmptyTextService();
        }
        
        let deleteServiceProductRow = (id) => {
            let for_del_class = `delete_${id}`;
            $('#product-table-body').find(`.${for_del_class}`).remove();
            showEmptyTextService();
        }

        // BEGIN:: FOR EDIT PAGE
        if(serviceUsedProducts !== null){
            $(serviceUsedProducts).each(function (index, element){
                // console.log(element)
                let uom_name = uoms.find(uom => uom.id === element.uom_id) ?. name;
                setTableRow(element.service_name, element.product_name, uom_name, element.variation_value, element.quantity,
                            element.service_id, element.uom_id, element.product_variation_id, element.product_id);
            })
            
        }
        // END:: FOR EDIT PAGE

    })
</script>

