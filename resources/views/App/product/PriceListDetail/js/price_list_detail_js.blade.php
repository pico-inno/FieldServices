<script src="{{ asset('customJs/debounce.js') }}"></script> 
<script>
    $(document).ready(function() {
        $(".select_date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

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

        const priceListRow = () => {
            return `
            <tr class="price_list_row">
                <input type="hidden" name="price_list_detail_id[]" value="">
                <td>
                    <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-hide-search="true" data-placeholder="Please select">
                        <option></option>
                        <option value="All">All</option>
                        <option value="Category">Category</option>
                        <option value="Product">Product</option>
                        <option value="Variation">Variations</option>
                    </select>
                </td>
                <td>
                    <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-placeholder="Please select">
                        
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]" value="0">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm rounded-0" name="price[]" value="00">
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
        }

        // ============> EVENT LISTENER

        // add new row
        $(document).on('click', '#add_price_list_row', function() {
            addBtnRemove();
            $('#price_list_body').append(priceListRow());

            $('[data-control="select2"]').select2({ 
                minimumResultsForSearch: Infinity 
            });
            $(".select_date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d"
            });
        })

        // delete row
        $(document).on('click', '.delete_each_row', function() {
            let current_row = $(this).closest('tr'); 
            current_row.remove();
        })

        // Event listener for input changes
        $(document).on('change', 'select[name="apply_type[]"]', function (event) {    
            let current_row = $(this).closest('tr');        
            let applied_type = current_row.find('select[name="apply_type[]"]').val();

            let selectedElement = current_row.find('select[name="apply_value[]"]');
            // Clear existing options
            selectedElement.empty();
            addBtnRemove();

            if(applied_type === 'All'){
                let option = document.createElement('option');
                option.value = '0';
                option.text = 'All Products';
                current_row.find('select[name="apply_value[]"]').append(option);

                return;
            }        
            
            $.ajax({
                url: `/price-list-detail/search`,
                type: 'GET',
                data: {
                    applied_type
                },
                success: function(results){
                    const defaultOption = document.createElement('option'); // Create default option
                    defaultOption.value = '';
                    defaultOption.text = 'Select an option';
                    $(selectedElement).append(defaultOption);

                    for(let item of results) {
                        let option = document.createElement('option');
                        option.value = item.id;
                        if(applied_type === 'Variation'){
                            option.text = item.product_variation_name;
                        }else{
                            option.text = item.name;
                        }
                        selectedElement.append(option);
                    }
                    $('[data-control="select2"]').select2();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        });
    });
</script>