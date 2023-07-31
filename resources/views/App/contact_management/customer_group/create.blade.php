<style>
    .percentage.hide, .selling-price-group.hide{
        display: none;
    }
</style>

<div class="modal modal-lg fade" tabindex="-1" id="add_customer_group_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('customer-group.store')}}" method="POST" id="add_customer_group_form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add Customer Group</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row mb-6">
                        <div class="col-12">
                            <label for="name" class="required form-label">Customer Group Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm fs-7" placeholder="Customer Group Name" required />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-12">
                            <label for="price_calculation_type" class="form-label">Price Calculation Type</label>
                            <select name="price_calculation_type" id="price_calculation_type" class="form-select form-select-sm fs-7" aria-label="Select example">
                                <option value="percentage" selected>Percentage</option>
                                <option value="selling_price_group">Selling Price Group</option>
                            </select>    
                        </div>
                    </div>
                    <div class="row percentage mb-6">
                        <div class="col-12">
                            <label for="" class="form-label">Calculation Percentage(%)</label>
                            <input type="number" class="form-control form-control-sm fs-7" placeholder="Calculation Percentage(%)" />
                        </div>
                    </div>
                    <div class="row selling-price-group hide">
                        <div class="col-12">
                            <label for="selling_price_group_id" class="form-label">Selling Price Group</label>
                            <select id="selling_price_group_id" class="form-select form-select-sm fs-7" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="10pcs">10pcs price</option>
                                <option value="50pcs">50pcs price</option>
                            </select>    
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    const priceCalculation = document.getElementById('price_calculation_type');
    const percentage = document.querySelector('.percentage');
    const sellingPriceGroup = document.querySelector('.selling-price-group');

    priceCalculation.addEventListener('change', () => {
        if(priceCalculation.value == 'selling_price_group'){
            sellingPriceGroup.classList.remove('hide');
            percentage.classList.add('hide');
        }else {
            sellingPriceGroup.classList.add('hide');
            percentage.classList.remove('hide');
        }
    })
</script>

    