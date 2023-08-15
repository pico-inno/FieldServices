// Parent Category
$('#filter_category .modal-footer .save-parent-category').on('click', function() {
    var selectedValue = $('#filter_category input[name="filter_parent_category_id"]:checked').val();

    $('#all_product_list .each_product').each(function() {
        var categoryID = $(this).find('input[name="category_id"]').val();

        if (categoryID != selectedValue) {
            $(this).addClass('d-none');
        }else {
            $(this).removeClass('d-none');
        }
    });
    $('#filter_category').modal('hide');
});

// Sub Category
$('#filter_sub_category .modal-footer .save-sub-category').on('click', function() {
    var selectedValue = $('#filter_sub_category input[name="filter_child_category_id"]:checked').val();

    $('#all_product_list .each_product').each(function() {
        var subCategoryID = $(this).find('input[name="sub_category_id"]').val();

        if (subCategoryID != selectedValue) {
            $(this).addClass('d-none');
        }else {
            $(this).removeClass('d-none');
        }
    });

    $('#filter_sub_category').modal('hide');
});

// Brand
$('#filter_brand .modal-footer .save-brand').on('click', function() {
    var selectedValue = $('#filter_brand input[name="filter_brand_id"]:checked').val();

    $('#all_product_list .each_product').each(function() {
        var brandID = $(this).find('input[name="brand_id"]').val();

        if (brandID != selectedValue) {
            $(this).addClass('d-none');
        }else {
            $(this).removeClass('d-none');
        }
    });

    $('#filter_brand').modal('hide');
});

// Manufacturer
$('#filter_manufacturer .modal-footer .save-manufacturer').on('click', function() {
    var selectedValue = $('#filter_manufacturer input[name="filter_manufacturer_id"]:checked').val();
    // console.log(selectedValue)
    $('#all_product_list .each_product').each(function() {
        var manufacturerID = $(this).find('input[name="manufacturer_id"]').val();

        if (manufacturerID != selectedValue) {
            $(this).addClass('d-none');
        }else {
            $(this).removeClass('d-none');
        }
    });

    $('#filter_manufacturer').modal('hide');
});

// Generic
$('#filter_generic .modal-footer .save-generic').on('click', function() {
    var selectedValue = $('#filter_generic input[name="filter_generic_id"]:checked').val();

    $('#all_product_list .each_product').each(function() {
        var genericID = $(this).find('input[name="generic_id"]').val();

        if (genericID != selectedValue) {
            $(this).addClass('d-none');
        }else {
            $(this).removeClass('d-none');
        }
    });

    $('#filter_generic').modal('hide');
});

// Clear All Filter
$(document).on('click', '.clear_all_filter', function() {
    $('#all_product_list .each_product').each(function() {
        if($(this).hasClass('d-none')){
            $(this).removeClass('d-none');
        }
    });

    $(this).closest('.modal').modal('hide');
})
