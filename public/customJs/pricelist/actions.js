const addBtnRemove = () => {
    $('#price_list_body tr').each(function() {
        let current_row = $(this).closest('tr');
        let current_value = current_row.find('select[name="apply_type[]"]').val();

        if(current_value === 'All'){
            $(this).closest('#price_list_body').find('tr').not(current_row).remove();
            $('#add_price_list_row').addClass('d-none');
        }else{
            $('#add_price_list_row').removeClass('d-none');
        }
    })
}

const disableDeleteButton = () => {
    let trCount = $('#price_list_body tr').length;
    if(trCount == 1){
        $(document).find('.delete_each_row').prop("disabled", true);
        return true;
    }else{
        $(document).find('.delete_each_row').prop("disabled", false);
        return false;
    }
}



const priceListRow =
`
<tr class="price_list_row">
    <input type="hidden" name="price_list_detail_id[]" value="">
    <td>
        <div class="fv-row">
            <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7 apply_type" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                <option></option>
                <option value="All">All</option>
                <option value="Category">Category</option>
                <option value="Product">Product</option>
                <option value="Variation">Variations</option>
            </select>
        </div>
    </td>
    <td>
        <div class="fv-row">
            <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-placeholder="Please select">

            </select>
        </div>
    </td>
    <td>
        <div class="fv-row">
            <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="">
        </div>
    </td>
    <td>
        <div class="fv-row">
            <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                <option></option>
                <option value="fixed">Fix</option>
                <option value="percentage" selected>Percentage</option>
            </select>
        </div>
    </td>
    <td>
        <div class="fv-row">
            <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]" value="">
        </div>
    </td>
    <td>
        <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
    </td>
    <td>
        <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" placeholder="Select date" autocomplete="off" />
    </td>
    <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i class="fa-solid fa-trash"></i></button></td>
</tr>
`;
