<style>
    .percentage.hide, .price-list.hide{
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
                            <select name="price_calculation_type" id="price_calculation_type" class="form-select form-select-sm fs-7">
                                <option value="percentage" selected>Percentage</option>
                                <option value="price_list">Price List</option>
                            </select>    
                        </div>
                    </div>
                    <div class="row percentage mb-6">
                        <div class="col-12">
                            <label for="" class="form-label">Calculation Percentage(%)</label>
                            <input type="text" name="amount" class="form-control form-control-sm fs-7" placeholder="Calculation Percentage(%)" />
                        </div>
                    </div>
                    <div class="row price-list hide">
                        <div class="col-12">
                            <label for="price_list_id" class="form-label">Price List</label>
                            @php
                                $price_lists = \App\Models\Product\PriceLists::all();
                            @endphp                            
                            <select name="price_list_id" id="price_list_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" data-dropdown-parent="#add_customer_group_modal" data-allow-clear="true">
                                <option></option>
                                @foreach($price_lists as $price_list)
                                <option value="{{ $price_list->id }}">{{ $price_list->name }}</option>
                                @endforeach
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
    const priceList = document.querySelector('.price-list');

    priceCalculation.addEventListener('change', () => {
        if(priceCalculation.value == 'price_list'){
            priceList.classList.remove('hide');
            percentage.classList.add('hide');
        }else {
            priceList.classList.add('hide');
            percentage.classList.remove('hide');
        }
    })
</script>

    