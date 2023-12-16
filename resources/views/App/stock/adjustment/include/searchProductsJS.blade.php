<script>
   var uniqueId = 1;
   var tableLength = $('#adjustment_table tbody tr').length-1;
   var businessLocation = $('#business_location_id');
   var searchInput = $('.quick-search-form input');
   var quickSearchResult = $('.quick-search-results');
   var searchResultProducts;


   //Begin: Search Product
   searchInput.on('input', function (){
       const query = $(this).val().trim();
       const business_location_id = businessLocation.val();
       const psku_kw = $('#psku_kw').is(':checked');
       const vsku_kw = $('#vsku_kw').is(':checked');
       const pgbc_kw = $('#pgbc_kw').is(':checked');
       const data = {
           business_location_id,
           query,
           psku_kw,
           vsku_kw,
           pgbc_kw
       };

       if(!isNullOrNan(businessLocation.val())){
           quickSearchResult.removeClass('d-none');
           htmlElement = `<p class="text-danger fw-bold">Need Location!</p><span class="text-gray-700">Which Location's product want to adjust?</span>`;
           quickSearchResult.html(htmlElement);

       }else{
           if (query.length >= 2) {

               quickSearchResult.removeClass('d-none');
               htmlElement = `<p class="m-3"><span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading</p>`;
               quickSearchResult.html(htmlElement);


               setTimeout(function() {
                   $.ajax({
                       url: '/adjustment/get/product/v3',
                       type: 'GET',
                       delay: 150,
                       data: {data},
                       error: (e) => console.log('Something Went Wrong! Error Status: '+e.status),
                       success: function (results){
                           searchResultProducts = results;
                           var htmlElement = '';
                           if (results.length > 0) {
                               let sku;
                               let addedSku = [];

                               results.forEach(function (result, key){
                                   let checkSku = addedSku.includes(result.sku);
                                   if(sku && result.sku === sku && !checkSku){
                                       htmlElement += `<div class="quick-search-result rounded-3 result mt-1 mb-1 bg-hover-light p-2" style="order:-1;" data-select-type="selectAll" data-productid='${result.id}' data-name="${result.name}" style="z-index:100;">`;
                                       htmlElement += `<h4 class="fs-6 ps-10 pt-3">${result.name} (Select All)</h4>`;
                                       htmlElement += '</div>';

                                       addedSku.push(result.sku);
                                       quickSearchResult.html(htmlElement);
                                   }else{
                                       sku = result.sku;
                                   }

                                   if(result.has_variation === 'variable' && results.length === 1){
                                       return;
                                   }


                                   let cssClass= result.stock.length === 0 && result.product_type === "storable" ? "text-gray-600 order-3" : '';

                                   htmlElement += `<div class="quick-search-result rounded-3 result ps-10  mt-1 mb-1 bg-hover-light p-2 ${cssClass}" data-select-type=${key} data-name="${result.name}" style="z-index:300;">`;
                                   htmlElement += `<h4 class="fs-6  pt-3 ${cssClass}">${result.name} `;
                                   if(result.has_variation === 'variable'){
                                       htmlElement +=   `<span class="text-gray-700 fw-semibold fs-5 ms-2">(${result.variation_name??''})</span>`;
                                   }
                                   htmlElement+='</h4>'
                                   htmlElement+=`<span class=" pt-3 text-gray-600 fw-bold fs-8">${result.has_variation === 'variable'?'SKU : '+result.variation_sku :'SKU : '+result.sku} </span>`
                                   htmlElement += '</div>';

                               });

                               if (results.length === 1 || (results[0].has_variation === 'variable' && results.length === 1)) {
                                   quickSearchResult.show();
                                   if(results[0].stock_sum_current_quantity > 0 || results[0].product_type !== "storable"){
                                       setTimeout(() => {
                                           $(`.result[data-name|='${results[0].name}']`).click();
                                           quickSearchResult.hide();
                                       }, 100);
                                   }
                               } else {
                                   $('.quick-search-results').show();
                               }

                           }else{
                               htmlElement = `<p class="m-3 text-danger">"${query}" product not found!</p>`;
                               quickSearchResult.show();
                           }

                           quickSearchResult.removeClass('d-none');
                           quickSearchResult.html(htmlElement);
                           $(document).click(function(event) {
                               if (!$(event.target).closest('.quick-search-results').length) {
                                   quickSearchResult.addClass('d-none')
                               }
                           });
                       }
                   })
               },300)
           }else {
               quickSearchResult.addClass('d-none');
               quickSearchResult.empty();
           }
       }



   })
   //End: Search Product

   $('#autocomplete').on('click', '.result', function() {

       quickSearchResult.addClass('d-none')
       let selectType = $(this).attr('data-select-type');
       let selected_product;
       if(selectType !== "selectAll"){
           selected_product= products[id];
           let isStorable=selected_product.product_type=="storable";
           if((selected_product.stock.length == 0 || selected_product.stock_sum_current_quantity==null) && isStorable){
               return;
           }
       }
       if(selectType === "selectAll")
       {
           let productId= $(this).data('productid');
           let selectedAllProducts = searchResultProducts.filter(searchResultProduct=>{
               return searchResultProduct.id === productId;
           });

           selectedAllProducts.forEach(productData => {
               append_row(productData ,uniqueId);
               uniqueId += 1;
           });
           return;
       }
       append_row(selected_product, uniqueId);
       uniqueId += 1;
       $('#searchInput').focus();

   });
</script>
