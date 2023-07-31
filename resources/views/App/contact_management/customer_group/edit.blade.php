<style>
    .percentage-edit.hide,
    .selling-price-group-edit.hide {
        display: none;
    }
</style>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('customer-group.update', $customer_group->id)}}" method="POST" id="edit_customer_group_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Customer Group</h3>

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
                        <input type="text" name="name" id="name" value="{{old('name',$customer_group->name)}}" class="form-control form-control-sm fs-7" placeholder="Customer Group Name" />
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label">Price Calculation Type</label>
                        <select name="price_calculation_type" class="form-select form-select-sm fs-7" aria-label="Select example" id="price-calculation-edit">
                            <option value="percentage" {{ $customer_group->price_calculation_type == "percentage"  ? 'selected' : '' }}>Percentage</option>
                            <option value="selling_price_group" {{ $customer_group->price_calculation_type == "selling_price_group"  ? 'selected' : '' }}>Selling Price Group</option>
                        </select>
                    </div>
                </div>
                <div class="row percentage-edit mb-6">
                    <div class="col-12">
                        <label for="" class="form-label">Calculation Percentage(%)</label>
                        <input type="number" class="form-control form-control-sm fs-7" placeholder="Calculation Percentage(%)" />
                    </div>
                </div>
                <div class="row selling-price-group-edit hide">
                    <div class="col-12">
                        <label for="selling_price_group_id" class="form-label">Selling Price Group</label>
                        <select id="selling_price_group_id" class="form-select form-select-sm fs-7" aria-label="Select example">
                            <option value="10pcs">10pcs price</option>
                            <option value="50pcs">50pcs price</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        const priceCalculationEdit = $('#price-calculation-edit');
        const percentageEdit = $('.percentage-edit');
        const sellingPriceGroupEdit = $('.selling-price-group-edit');

        priceCalculationEdit.on('change', function() {
            if ($(this).val() == 'selling_price_group') {
                sellingPriceGroupEdit.removeClass('hide');
                percentageEdit.addClass('hide');
            } else {
                sellingPriceGroupEdit.addClass('hide');
                percentageEdit.removeClass('hide');
            }
        });
    });
</script>