<script src="{{ asset('customJs/debounce.js') }}"></script>
<script>
    $(document).ready(function(){ 
        let uoms = @json($uoms);
        let serviceProducts = @json($serviceProducts ?? null); // For EDIT PAGE
        let oldProducts = @json($products ?? null);
        let oldVariations = @json($variations ?? null); 
        
        let products = null;
        let variations = null;
        let newItem = (productId = null, variationId = null, name, back_text) => {
            return `
                <div id="searchItem" data-productId="${productId}" data-variationId="${variationId}" class="quick-search-result cursor-pointer mt-3 mb-3 bg-hover-light p-2" id="searchItem" style="z-index:100;">
                    <h4 class="fs-6 ps-10 pt-3 me-4 d-inline">
                        ${name} <span class="text-muted">${back_text}</span>
                    </h4>
                </div>
            `
        }

        let newRow = `
            <tr class="item-row">
                <input type="hidden" name="service_product_id[]" />

                <input type="hidden" name="product_id[]" />
                <input type="hidden" name="variation_id[]" />
                <input type="hidden" name="product_uom_id[]" />
                <td id="product_name">
                    
                </td>
                <td id="product_unit">

                </td>
                <td id="product_variation">
                
                </td>
                <td>
                    <div class="input-group w-md-150px service-quantity">
                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" id="decrease">
                            <i class="fa-solid fa-minus fs-2"></i>
                        </button>
                        <input type="text" name="product_quantity[]" class="form-control" placeholder="Amount" value="1"/>
                        
                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" id="increase">
                            <i class="fa-solid fa-plus fs-2"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <i class="fa-solid fa-trash text-danger" type="button" id="delete-item"></i>
                </td>
            </tr>
        `;

        let showEmptyText = () => {
            if($('#table-body tr').length === 1){
                $('.dataTables_empty').removeClass('d-none');
            }else{
                $('.dataTables_empty').addClass('d-none');
            }
        }
        showEmptyText();

        let cloneRow = (product_id, name, unit, variation,quantity, vari_id = null, unit_id = null, service_productId = null) => {
            showEmptyText();
            let cloneRow = $(newRow).clone();
            cloneRow.find('input[name="service_product_id[]"]').val(service_productId)

            cloneRow.find('input[name="product_id[]"]').val(product_id);
            cloneRow.find('input[name="variation_id[]"]').val(vari_id);
            cloneRow.find('input[name="product_uom_id[]"]').val(unit_id);

            cloneRow.find('#product_name').text(name);
            cloneRow.find('#product_unit').text(unit);
            cloneRow.find('#product_variation').text(variation);
            cloneRow.find('input[name="product_quantity[]"]').val(quantity);
            $('#table-body').append(cloneRow);
        }

        let clearContainer = () => {
            $('#search_container').empty();
            $('#search_container').addClass('d-none');
        }

        $(document).on('input', '#search-product', function() {
            if($(this).val() === ''){
                clearContainer();
            }
        })
        
        $(document).on('input', '#search-product', debounce(function() {
            let val = $(this).val();
            
            $.get(`/service/products/?search=${val}`, function(data) {
                products = data.search_products;
                variations = data.variation_template;

                let eachVariation = (element) => {
                    for(let i = 0; i < variations.length; i++){
                        element.product_variations.map(item => {
                            if(item.variation_template_value_id === variations[i].id){
                                let back_text = ` - ${variations[i].name}`;
                                $('#search_container').append(newItem(element.id ,variations[i].id, element.name, back_text))
                            }
                        })
                    }
                }

                $(products).each(function(parentIndex, element) {
                    $('.quick-search-results').removeClass('d-none');

                    let check_variation = element.product_variations[0].variation_template_value_id !== null;
                    
                    let back_text = check_variation ? `- (${element.product_variations.length}) Select All` : ' - Select';                    

                    $('#search_container').append(newItem(element.id, null, element.name, back_text));
                    if(check_variation){
                        eachVariation(element);
                    }
                })
            }).fail(function(xhr, status, error) {
                console.log('Error:', error);
            });

        }))

        $(document).click(function(event) {
            if (!$(event.target).closest('.quick-search-result').length  && $('#search_container #searchItem').length) {
                clearContainer();
            }
        }); 

        // when select search item / add new tr to tbody
        $('#search_container').on('click', '.quick-search-result', function(event) {
            let productId = $(this).attr('data-productId');
            let variationId = $(this).attr('data-variationId');
            $(this).closest(`.quick-search-result`).remove();

            if(!$('#search_container #searchItem').length) {
                clearContainer();
            }
            
            // for Select All products
            if(productId !== 'null' && variationId === 'null'){         
                let filterProduct = products.filter( product => {
                    return product.id === parseInt(productId);
                })
                
                let product_name = filterProduct[0].name;
                let raw_uom_name = uoms.filter( uom => {
                    if(uom.id === filterProduct[0].purchase_uom_id){
                        return uom;
                    }else{
                        return;
                    }
                })
                
                let uom_name = raw_uom_name.length !== 0 ? raw_uom_name[0].name : 'undefined';
                let uom_id = raw_uom_name.length !== 0 ? raw_uom_name[0].id : null;

                $(filterProduct[0].product_variations).each(function(index, element){
                    let raw_vari_name = variations.filter( vari => {
                        if(vari.id === element.variation_template_value_id){
                            return vari;
                        }else {
                            return
                        }
                    })
                    let vari_name = raw_vari_name.length !== 0 ? raw_vari_name[0].name : 'undefined';
                    cloneRow(productId, product_name, uom_name, vari_name, 1, element.id, uom_id);
                })
            }

            // for Each Variation
            if(variationId !== 'null' && productId !== 'null'){
                let filterProduct = products.filter( product => {
                    return product.id === parseInt(productId);
                })
                let product_name = filterProduct[0].name;
                let raw_uom_name = uoms.filter( uom => {
                    if(uom.id === filterProduct[0].purchase_uom_id){
                        return uom;
                    }else{
                        return;
                    }
                })
                let uom_name = raw_uom_name.length !== 0 ? raw_uom_name[0].name : 'undefined';
                let uom_id = raw_uom_name.length !== 0 ? raw_uom_name[0].id : null;

                let raw_vari_name = variations.filter( vari => {
                    if(vari.id === parseInt(variationId)){
                        return vari;
                    }else {
                        return
                    }
                })
                let vari_name = raw_vari_name.length !== 0 ? raw_vari_name[0].name : 'undefined';
                let vari_id = raw_vari_name.length !== 0 ? raw_vari_name[0].id : null;  

                cloneRow(productId, product_name, uom_name, vari_name, 1, vari_id, uom_id);
            }
            showEmptyText();
        })

        // for increase and decrease SERVICE ITEM QUANTITY
        $(document).on('click', '#increase', function() {
            let incVal = $(this).closest('tr').find('input[name="product_quantity[]"]');
            let value = parseInt(incVal.val()) + 1;
            incVal.val(value);
        })

        $(document).on('click', '#decrease', function() {
            let decVal = $(this).closest('tr').find('input[name="product_quantity[]"]');
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);
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
                    $(this).closest('tr').remove();
                    showEmptyText();
                }
            });
        })

        // FOR EDIT PAGE
        if(serviceProducts !== null){
            $(serviceProducts).each(function (index, item){
                let product_name = oldProducts.find(product => product.id === item.product_id) ?. name;
                let uom_name = uoms.find(uom => uom.id === item.uom_id) ?. name;
                let variation_name = oldVariations.find( vari => vari.id === item.variation_id) ?. name;
                
                cloneRow(item.product_id, product_name, uom_name, variation_name,item.quantity, item.variation_id, item.uom_id, item.id);
            })
            showEmptyText();
        }
    })
</script>