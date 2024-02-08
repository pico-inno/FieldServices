 $(document).ready(function () {
    // $('#stock_details_reports_table').DataTable();
    var dataTable = $('#stock_details_reports_table').DataTable();
    var stockReportsTableBody = $('#stock_details_reports_table tbody');
    var filterCard = $('.filter-card');
    var filterType = $('.filter_type');
    var filterLocations = filterCard.find('.filter_locations');
    var filterProduct = filterCard.find('.filter_product');
    var filterCategory = filterCard.find('.filter_category');
    var filterBrand = filterCard.find('.filter_brand');
    var filterDate = filterCard.find('.filter_date');


     $('#search_input').on('keyup', function () {
         dataTable.search(this.value).draw();
     });

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         var filterTypeVal = filterType.val();
         var filterLocationsVal = filterLocations.val();
         var filterProductVal = filterProduct.val();
         var filterCategoryVal = filterCategory.val();
         var filterBrandVal = filterBrand.val();
         var filterDateVal = filterDate.val();
         stockReportsTableBody.empty();
         filterData(filterTypeVal, filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal);
     });


    var filterDateVal = filterDate.val();
    filterData(1, 0, 0, 0, 0, filterDateVal);

    async function filterData(filterTypeVal, filterLocations, filterProductVal, filterCategoryVal, filterBrandVal, filterDate) {
    var data = {
    filter_type: filterTypeVal,
    filter_locations: filterLocations,
    filter_product: filterProductVal,
    filter_category: filterCategoryVal,
    filter_brand: filterBrandVal,
    filter_date: filterDate
};
    try {
    $.ajax({
    url: '/reports/stock-details/filter-list',
    type: 'POST',
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
    data: {
    data: data,
},
    error: function (e) {
    var status = e.status;
    if (status === 405) {
    warning('Method Not Allowed!');
    } else if (status === 419) {
        error('Session Expired');
    }
    else if (status == 200) {
            console.log('success');
    }
    else {
        error('Something Went Wrong! Error Status: ' + status);
    }
},
    success: function (results) {


    dataTable.clear();

    results.forEach(function(item) {
        let price = Number(item.default_selling_price.replace(/,/g, ''));
        let qty = Number(item.stock_qty.replace(/,/g, '')); // Remove commas from the string



        var rowData = [
    item.variation_sku ? item.variation_sku : item.sku,
    item.name,
    item.variation_template_name+' -    '+item.variation_value_name,
    item.category_name ? item.category_name : '',
    item.brand_name ?? '',
    item.uom_name,
            qty+' '+item.uom_short_name ?? '',
    // item.samllest_stock_qty+' '+item.smallest_unit_name,
        // item.smallest_purchase_price,
            price,
    Number(price * qty).toFixed(2),
    ];


    dataTable.row.add(rowData);


});


    dataTable.draw();

},
});
} catch (error) {
    console.error(error);
}
}




 });

 $("#stock_details_reports_table").DataTable({
     "footerCallback": function(row, data, start, end, display) {
         var api = this.api(),
             data;

         // Remove the formatting to get integer data for summation
         var intVal = function(i) {
             return typeof i === "string" ?
                 i.replace(/[\$,]/g, "") * 1 :
                 typeof i === "number" ?
                     i : 0;
         };

         // Total over this page
         var priceTotal = api
             .column(8, {
                 page: "current"
             })
             .data()
             .reduce(function(a, b) {
                 return intVal(a) + intVal(b);
             }, 0);

         var priceByUnitTotal = api
             .column(7, {
                 page: "current"
             })
             .data()
             .reduce(function(a, b) {
                 return intVal(a) + intVal(b);
             }, 0);

         // Update footer
         $(api.column(8).footer()).html(
              priceTotal +" Ks"
         );
         $(api.column(7).footer()).html(
             priceByUnitTotal +" Ks"
         );
     }
 });
