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
$(document).on('change', 'select[name="apply_type[]"]', function () {
    let current_row = $(this).closest('tr');
    let applied_type = current_row.find('select[name="apply_type[]"]').val();
    let selectedElement = current_row.find('select[name="apply_value[]"]');
    // Clear existing options
    selectedElement.empty();
    addBtnRemove();
    current_row.find('select[name="apply_value[]"]').select2({}).empty();
    getApplyValue(current_row, applied_type);
});

function getApplyValue(row, applied_type) {
    if (applied_type == 'All') {
        let option = document.createElement('option');
        option.value = '0';
        option.text = 'All Products';
        row.find('select[name="apply_value[]"]').append(option);
        return;
    }
    row.find('select[name="apply_value[]"]').select2({
        ajax: {
            url: `/price-list-detail/search?applied_type=${applied_type}`,
            delay: 250,
            processResults: function (results, params) {
                params.page = params.page || 1;
                resultsForSelect = [];
                let data = results.data;
                data.map(function (d) {
                    resultsForSelect.push({
                        id: d.id,
                        text: `${d.name ?? ''} ${d.uniqCode ? ' ( ' + d.uniqCode + ' ) ' : ''}`
                    });
                })
                return {
                    results: resultsForSelect,
                    pagination: {
                        more: (params.page * 20) < results.total
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for an item',
        minimumInputLength: 0,
    });
}
// change cal type
$(document).on('change', 'select[name="cal_type[]"]', function (event) {
    let current_row = $(this).closest('tr');
    let cal_type = current_row.find('select[name="cal_type[]"]').val();
})
