<div class="modal fade" tabindex="-1" id="service_sale_view"> 
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> 
            <form>
                <div class="modal-header">
                    <h3 class="-title">sale Details (Reference No )</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row mb-10">
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-4">
                                Supplier:
                            </h3>
                            <address class="mt-3 fs-5">
                                <p id="customer-name"></p>
                                <p id="mobile"></p>
                            </address>
                        </div>
                        <div class="col-sm-4">
                            <h3 class="text-primary-emphasis fs-4">
                                Bussiness: 
                            </h3>
                            <address class="mt-3 fs-5">
                                <p id="business_name"></p>
                                <div>
                                    <span id="city"></span>
                                    <span id="state"></span>
                                </div>
                                <div>
                                    <span id="country"></span>
                                    <span id="zip_code"></span>
                                </div>
                            </address>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-group">
                                
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    Date : <span class="text-gray-600" id="sold_at"></span>
                                </h3>
                                <h3 class="text-primary-emphasis fw-semibold fs-5">
                                    sale Status : <span class="text-gray-600" id="sale_status"></span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-10">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-primary">
                                <!--begin::Table row-->
                                <tr class="bg-primary fw-bold fs-6 text-white text-center text-uppercase fs-7">
                                    <th class="min-w-30 text-center ps-5">#</th>
                                    <th class="min-w-100px">Service Name</th>
                                    <th class="min-w-100px">Quantity</th>
                                    <th class="min-w-100px">Unit</th>
                                    <th class="min-w-100px">sale Price <br> (Before Discount)</th>
                                    <th class="min-w-100px">Discount Type</th>
                                    <th class="min-w-100px">Discount Amount</th>
                                    <th class="min-w-100px">Total sale Price</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-800" id="service-sale-detail">
                                
                            </tbody>
                            <!--end::Table body-->
                        </table>

                    </div>
                    <div class="row mt-5 justify-content-end">
                        
                        <div class="col-md-6 col-sm-12 col-xs-12  me-10">
                            <div class="table-responsive mt-10">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                    <!--begin::Table body-->
                                   <tbody class="fw-semibold text-gray-800 table-bordere">
                                        <tr>
                                            <td>
                                                Net Total Amount:
                                            </td>
                                            <td class="text-end">
                                            </td>
                                            <td class="text-end" id="net-total-amount">
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Discount amount:
                                            </td>
                                            <td class="text-end">
                                                (-)
                                            </td>
                                            <td class="text-end" id="total-discount-amount">
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Discount type:
                                            </td>
                                            <td class="text-end">
                                                (-)
                                            </td>
                                            <td class="text-end" id="total-discount-type">
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                sale Total:
                                            </td>
                                            <td class="text-end">
                                                (=)
                                            </td>
                                            <td class="text-end" id="net-total-price">
                                            
                                            </td>
                                        </tr>

                                    </tbody>
                                    <!--end::Table body-->
                                </table>

                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" id="print">Print</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let tableRow = `
        <tr>
            <td class="text-center" id="num"></td>
            <td class="text-center" id="service_name"></td>
            <td class="text-center" id="quantity"></td>
            <td class="text-center" id="unit"></td>
            <td class="text-center" id="sale_price"></td>
            <td class="text-center" id="discount_type"></td>
            <td class="text-center" id="discount_amount"></td>
            <td class="text-center" id="total_price"></td>
        </tr>
        `;
    function executeScript(response){
        
        $('#customer-name').text(response.customer_name)
        $('#mobile').text('Mobile: ' + response.customer_phone[0].mobile)
        $('#city').text(response.business[0].city ? response.business[0].city + ' , ' : '')
        $('#state').text(response.business[0].state ? response.business[0].state: '')
        $('#country').text(response.business[0].country ? response.business[0].country + ' , ': '')
        $('#zip_code').text(response.business[0].zip_code ? response.business[0].zip_code : '')
        $('#sold_at').text(response.date);
        $('#sale_status').text(response.serviceSale.service_status)

        $(response.service_sale_details).each(function(index, value){
            let service_name = response.services.filter( item => item.id === value.service_id)[0] ?. name;
            let unit_name = response.units.filter( item => item.id === value.unit_id)[0] ?. name;

            let cloneRow = $(tableRow).clone();
            cloneRow.find('#num').text(index+1)
            cloneRow.find('#service_name').text(service_name)
            cloneRow.find('#quantity').text(value.quantity * 1)
            cloneRow.find('#unit').text(unit_name)
            cloneRow.find('#sale_price').text(value.sale_price_without_discount * 1)
            cloneRow.find('#discount_type').text(value.service_detail_discount_type)
            cloneRow.find('#discount_amount').text(value.discount_amount * 1)            
            cloneRow.find('#total_price').text(value.sale_price * 1)

            $('#service-sale-detail').append(cloneRow)
        })

        $('#net-total-amount').text(response.serviceSale.sale_amount * 1)
        $('#total-discount-amount').text(response.serviceSale.discount_amount * 1)
        $('#total-discount-type').text(response.serviceSale.service_discount_type)
        $('#net-total-price').text(response.serviceSale.total_sale_amount * 1)

        $('#service_sale_view').on('hidden.bs.modal', function (e) {
            $('#service-sale-detail').empty();
        });
    }
</script>
