
// let tableBodyId = $("#invoice_side_bar").is(':hidden') ? 'invoice_with_modal' : 'invoice_with_sidebar';
// let infoPriceId = $("#invoice_side_bar").is(':hidden') ? 'info_price_with_modal' : 'info_price_with_sidebar';
// let contact_id = $("#invoice_side_bar").is(':hidden') ? 'pos_customer' : 'sb_pos_customer';
// let contact_edit_btn_id = $("#invoice_side_bar").is(':hidden') ? 'contact_edit_btn_modal' : 'contact_edit_btn';
// let contact_phone_btn_id = $("#invoice_side_bar").is(':hidden') ? 'contact_edit_phone_btn_modal' : 'contact_edit_phone_btn';
// let paymentRow = () => {
//     return `
//         <div class="payment_row">
//             <div class="mb-3">
//                 <div class="form-group row">
//                     <div class="col-md-12 col-sm-5 col-12">
//                         <label class="form-label">Amount:</label>
//                         <input type="text" class="form-control form-control-sm mb-2 mb-md-0" name="pay_amount" placeholder="" value=""/>
//                     </div>
//                     <div class="col-md-3 col-sm-5 col-5 d-none">
//                         <label class="form-label">Payment Method:</label>
//                         <select class="form-select mb-2 form-select-sm" name="payment_method" data-control="select2" data-hide-search="true" data-placeholder="Select category">
//                             <option></option>
//                             <option value="1">Cash</option>
//                             <option value="2">Card</option>
//                         </select>
//                     </div>
//                     <div class="col-md-4 col-sm-2 col-2 d-none">
//                         <button class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove_payment_row">
//                             <i class="fas fa-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
//                             Delete
//                         </button>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     `
// };
// let ajaxToStorePosData = (dataForSale) => {
//     $.ajax({
//         url: `/sell/create`,
//         type: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: dataForSale,
//         success: function(results){
//             if(results.status==200){
//                 Swal.fire({
//                     text: "Successfully Sold! Thanks you.",
//                     icon: "success",
//                     buttonsStyling: false,
//                     confirmButtonText: "Ok, got it!",
//                     customClass: {
//                         confirmButton: "btn fw-bold btn-primary",
//                     }
//                 }).then(function () {
//                     //sth

//                     $(`#${tableBodyId} tr`).remove();
//                     totalSubtotalAmountCalculate();
//                     totalDisPrice();
//                     $('#payment_info .print_paid').text(0);
//                     $('#payment_info .print_change').text(0);
//                     $('#payment_info .print_balance').text(0);
//                     $('input[name="pay_amount"]').val(0);
//                 });
//             }
//         },
//         error:function(e){
//             status=e.status;
//             if(status==405){
//                 warning('Method Not Allow!');
//             }else if(status==419){
//                 error('Session Expired')
//             }else{
//                 error(' Something Went Wrong! Error Status: '+status )
//             };
//         },

//     })
// }

// let datasForSale = (status) => {
//     let business_location_id = $('select[name="business_location_id"]').val();
//     let contact_id = $("#invoice_side_bar").is(':hidden') ? $('#pos_customer').val() : $('#sb_pos_customer').val();
//     let pos_register_id = posRegisterId;
//     let sale_amount = $(`#${infoPriceId} .sb-total`).text();
//     let total_item_discount = $(`#${infoPriceId} .sb-discount`).text();
//     let extra_discount_type = null;
//     let extra_discount_amount = null;
//     let total_sale_amount = $(`#${infoPriceId} .sb-total-amount`).text();
//     let paid_amount = $('.print_paid').text();
//     let balance_amount = total_sale_amount - paid_amount;
//     let currency_id = null;

//     let sales ={
//             'business_location_id': business_location_id,
//             'contact_id': contact_id,
//             'status': status,
//             'pos_register_id': pos_register_id,
//             'sale_amount': sale_amount,
//             'total_item_discount': total_item_discount,
//             'extra_discount_type': extra_discount_type,
//             'extra_discount_amount': extra_discount_amount,
//             'total_sale_amount': total_sale_amount,
//             'paid_amount': paid_amount,
//             'balance_amount': balance_amount,
//             'currency_id': currency_id
//         }


//     let sale_details = [];
//     $(`#${tableBodyId} .invoiceRow`).each(function() {
//         let parent = $(this).closest('tr');
//         let product_id = parent.find('input[name="product_id"]').val();
//         let variation_id = parent.find('input[name="variation_id"]').val();
//         let uom_id = parent.find('.invoice_unit_select').val();
//         let quantity = parent.find('input[name="quantity[]"]').val();
//         let price_list_id = parent.find('input[name="each_selling_price"]').val();
//         let uom_price = parent.find('input[name="selling_price[]"]').val();
//         let subtotal = parent.find('.subtotal_price').text();
//         let discount_type = parent.find('input[name="discount_type"]').val();
//         let per_item_discount = parent.find('input[name="per_item_discount"]').val();
//         let subtotal_with_discount = parent.find('input[name="subtotal_with_discount"]').val();

//         let raw_sale_details = {
//             'product_id': product_id,
//             'variation_id': variation_id,
//             'uom_id': uom_id,
//             'quantity': quantity,
//             'price_list_id': price_list_id,
//             'uom_price': uom_price,
//             'subtotal': subtotal,
//             'discount_type': discount_type,
//             'per_item_discount': per_item_discount,
//             'subtotal_with_discount': subtotal_with_discount,
//             'tax_amount': null,
//             'subtotal_with_tax': null,
//             'currency_id': null,
//             'delivered_quantity': null
//         };

//         sale_details.push(raw_sale_details);
//     })
//     let type = 'pos';
//     let data = {...sales,
//          'sale_details':sale_details,
//          type}
//     return data;
// }

// // For Payment Print
// $('#payment_print').on('click', function(){
//     if(checkContact()){
//         let invoice_row_data = [];
//         $(`#${tableBodyId} .invoiceRow`).each(function() {
//             let current_row = $(this).closest('tr');
//             let current_uom_id = current_row.find('.invoice_unit_select').val();
//             let referenceUom = toGetReferenceUOMRelate(current_uom_id);
//             let product_name = current_row.find('.product-name').text();
//             let variation = current_row.find('.variation_value_and_name').text();
//             let quantity = current_row.find('input[name="quantity[]"]').val();
//             let uomName = uoms.filter(uom => uom.id == current_uom_id)[0].name;
//             let price = current_row.find('input[name="selling_price[]"]').val();
//             let subtotal = current_row.find('.subtotal_price').text();

//             let rowData = { referenceUom, product_name, variation, quantity, uomName, price, subtotal };

//             invoice_row_data.push(rowData);
//         });

//         let total = $(`#${infoPriceId} .sb-total`).text();
//         let discount = $(`#${infoPriceId} .sb-discount`).text();
//         let paid = $('.print_paid').text();
//         let balance = $('.print_balance').text();
//         let change = $('.print_change').text();

//         let business_location = $('select[name="business_location_id"] option:selected').text();
//         let customer_name = $(`#${contact_id} option:selected`).text();
//         let customer_id = $(`#${contact_id} option:selected`).val();
//         let filtered_customer = customers.filter( customer => customer.id === parseInt(customer_id))[0];
//         let customer_mobile = filtered_customer.mobile ? filtered_customer.mobile : '';

//         let totalPriceAndOtherData = {total, discount, paid, balance, change, business_location, customer_name, customer_mobile};

//         let dataForSale = datasForSale('delivered');
//         $.ajax({
//             url: `/sell/create`,
//             type: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             data: dataForSale,
//             success: function(results){
//                 if(results.status==200){
//                     let invoice_no = results.data;
//                     $(`#${tableBodyId} tr`).remove();
//                     totalSubtotalAmountCalculate();
//                     totalDisPrice();
//                     $('#payment_info .print_paid').text(0);
//                     $('#payment_info .print_change').text(0);
//                     $('#payment_info .print_balance').text(0);
//                     $('input[name="pay_amount"]').val(0);

//                     let data = { invoice_row_data, totalPriceAndOtherData , invoice_no };
//                     $.ajax({
//                         url: '/pos/payment-print-layout',
//                         headers: {
//                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                         },
//                         data: data,
//                         success: function(response){
//                             var iframe = $('<iframe>', {
//                                 'height': '0px',
//                                 'width': '0px',
//                                 'frameborder': '0',
//                                 'css': {
//                                     'display': 'none'
//                                 }
//                             }).appendTo('body')[0];
//                             // Write the invoice HTML and styles to the iframe document
//                             var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
//                             iframeDoc.open();
//                             iframeDoc.write(response.html);
//                             iframeDoc.close();

//                             // Trigger the print dialog
//                             iframe.contentWindow.focus();
//                             setTimeout(() => {
//                                 iframe.contentWindow.print();
//                             }, 500);
//                         }
//                     })
//                 }
//             },
//             error:function(e){

//             },
//         })
//     }
// })

// // Form Repeater payment amount add
// $(document).on('click', '.add-payment-row', function(){
//     $('#payment_row_body').append(paymentRow);
//     $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
// })

// // Form Repeater payment amount remove
// $(document).on('click', '.remove_payment_row', function(){
//     $(this).closest('.payment_row').remove();

//     let payable_amount = $(`#${infoPriceId} .sb-total-amount`).text();

//     let pay_amount = 0;
//     $('#payment_row_body .payment_row').each(function() {
//         let parent = $(this).closest('.payment_row');
//         let each_amount = parent.find('input[name="pay_amount"]').val();
//         pay_amount += isNullOrNan(each_amount);
//     })

//     let change = Math.abs(isNullOrNan(payable_amount) - pay_amount);
//     $('#payment_info .print_paid').text(pay_amount);
//     $('#payment_info .print_change').text(change);
//     $('#payment_info .print_balance').text(balance);
// })

// // when opening payment info modal box
// let isPaymentRowAppended = false;
// $('#payment_info').on('shown.bs.modal', function() {
//     if(!isPaymentRowAppended){
//         $('#payment_row_body').append(paymentRow);
//         $('[data-control="select2"]').select2({ minimumResultsForSearch: Infinity });
//         isPaymentRowAppended = true;
//     }

//     let payable_amount = $(`#${infoPriceId} .sb-total-amount`).text();
//     let pay_amount = 0;
//     $(document).on('input', 'input[name="pay_amount"]', function() {
//         pay_amount = 0;
//         $('#payment_row_body .payment_row').each(function() {
//             let parent = $(this).closest('.payment_row');
//             let each_amount = parent.find('input[name="pay_amount"]').val();
//             pay_amount += isNullOrNan(each_amount);
//         })

//         // let change = Math.abs(isNullOrNan(payable_amount) - pay_amount);
//         let change = isNullOrNan(payable_amount) < pay_amount ? pay_amount - isNullOrNan(payable_amount) : '0';
//         let balance = isNullOrNan(payable_amount) > pay_amount ? isNullOrNan(payable_amount) - pay_amount : '0';
//         $('#payment_info .print_paid').text(pay_amount);
//         $('#payment_info .print_change').text(change);
//         $('#payment_info .print_balance').text(balance);
//     })

//     $('#payment_info .print_payable_amount').text(payable_amount);
// })

// // Sale With Credit
// $(document).on('click', '.sale_credit', function() {
//     if(checkContact()){
//         let dataForSale = datasForSale('delivered');
//         ajaxToStorePosData(dataForSale);
//     }
// })

// // Sale With Order
// $(document).on('click', '.sale_order', function() {
//     if(checkContact()){
//         let dataForSale = datasForSale('order');
//         ajaxToStorePosData(dataForSale);
//     }
// })

// // Sale With Draft
// $(document).on('click', '.sale_draft', function() {
//     if(checkContact()){
//         let dataForSale = datasForSale('draft');
//         ajaxToStorePosData(dataForSale);
//     }
// })

// // Sale With Payment
// $(document).on('click', '.payment_save_btn', function() {
//     if(checkContact()){
//         let dataForSale = datasForSale('delivered');
//         ajaxToStorePosData(dataForSale);
//     }
// })
