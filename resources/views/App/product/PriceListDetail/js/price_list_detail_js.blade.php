<script src="{{ asset('customJs/debounce.js') }}"></script>
<script src="{{ asset('customJs/pricelist/actions.js') }}"></script>
<script src="{{ asset('customJs/pricelist/event-handlers.js') }}"></script>
<script src="{{ asset('customJs/pricelist/validation.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".select_date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

        disableDeleteButton();
    });

    // const priceListRowFromExcel=(data) =>
    // `
    // <tr class="price_list_row">
    //     <input type="hidden" name="price_list_detail_id[]" value="">
    //     <td>
    //         <div class="fv-row">
    //             <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7 apply_type"
    //                 data-control="select2" data-hide-search="true" data-placeholder="Please select">
    //                 <option></option>
    //                 <option value="All" ${data['apply_type'=='all'? 'selected':'']}>All</option>
    //                 <option value="Category" ${data['apply_type'=='category'? 'selected':'']}>Category</option>
    //                 <option value="Product" ${data['apply_type'=='all'? 'selected':'']}>Product</option>
    //                 <option value="Variation">Variations</option>
    //             </select>
    //         </div>
    //     </td>
    //     <td>
    //         <div class="fv-row">
    //             <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
    //                 data-placeholder="Please select">

    //             </select>
    //         </div>
    //     </td>
    //     <td>
    //         <div class="fv-row">
    //             <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="">
    //         </div>
    //     </td>
    //     <td>
    //         <div class="fv-row">
    //             <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
    //                 data-hide-search="true" data-placeholder="Please select">
    //                 <option></option>
    //                 <option value="fixed">Fix</option>
    //                 <option value="percentage" selected>Percentage</option>
    //             </select>
    //         </div>
    //     </td>
    //     <td>
    //         <div class="fv-row">
    //             <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]" value="">
    //         </div>
    //     </td>
    //     <td>
    //         <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
    //             placeholder="Select date" autocomplete="off" />
    //     </td>
    //     <td>
    //         <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date"
    //             placeholder="Select date" autocomplete="off" />
    //     </td>
    //     <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i
    //                 class="fa-solid fa-trash"></i></button></td>
    // </tr>
    // `;
    // if(priceLists){
    //     let arr=['Category','Product'];
    //     priceLists.forEach((p,i) => {
    //     let dom= $('#price_list_body').append(priceListRowFromExcel(p));
    //     });
    // }
</script>
