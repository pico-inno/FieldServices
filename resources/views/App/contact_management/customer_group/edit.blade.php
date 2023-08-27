<style>
    .percentage-edit.hide,
    .price-list-edit.hide {
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
                            <option value="price_list" {{ $customer_group->price_calculation_type == "price_list"  ? 'selected' : '' }}>Price List</option>
                        </select>
                    </div>
                </div>
                <div class="row percentage-edit @if(isset($customer_group->price_list_id)) hide @endif mb-6">
                    <div class="col-12">
                        <label for="" class="form-label">Calculation Percentage(%)</label>
                        <input type="text" name="amount" value="{{old('amount',$customer_group->amount)}}" class="form-control form-control-sm fs-7" placeholder="Calculation Percentage(%)" />
                    </div>
                </div>
                <div class="row price-list-edit @if(isset($customer_group->amount)) hide @endif">
                    <div class="col-12">
                        <label for="price_list_id" class="form-label">Price List</label>
                        @php
                            $price_lists = \App\Models\Product\PriceLists::all();
                        @endphp 
                        <select name="price_list_id" id="price_list_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" data-dropdown-parent="#edit_customer_group_modal" data-allow-clear="true">
                            <option></option>    
                            @foreach($price_lists as $price_list)
                            <option value="{{ $price_list->id }}" {{ old('price_list_id', $customer_group->price_list_id) == $price_list->id ? 'selected' : ''}}>
                                {{ $price_list->name }}
                            </option>
                            @endforeach
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

<script type="text/javascript">
    $(document).ready(function() {
        const priceCalculationEdit = $('#price-calculation-edit');
        const percentageEdit = $('.percentage-edit');
        const priceListEdit = $('.price-list-edit');

        priceCalculationEdit.on('change', function() {
            if ($(this).val() == 'price_list') {
                priceListEdit.removeClass('hide');
                percentageEdit.addClass('hide');
            } else {
                priceListEdit.addClass('hide');
                percentageEdit.removeClass('hide');
            }
        });
    });
</script>
