// add new row
$(document).on('click', '#add_price_list_row', function() {
    addBtnRemove();
    $('#price_list_body').append(priceListRow);

    $('[data-control="select2"]').select2({ 
        minimumResultsForSearch: Infinity 
    });
    $(".select_date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    disableDeleteButton();
})

// delete row
$(document).on('click', '.delete_each_row', function() {
    let isDelete = disableDeleteButton();
    if(isDelete) return;
    
    let current_row = $(this).closest('tr'); 
    current_row.remove();
})

// change aply type
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

// change cal type
$(document).on('change', 'select[name="cal_type[]"]', function (event) {
    let current_row = $(this).closest('tr');
    let cal_type = current_row.find('select[name="cal_type[]"]').val();
})