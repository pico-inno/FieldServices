 $(document).ready(function () {
    // $('#transfer_details_reports_table').DataTable();
    var dataTable = $('#transfer_details_reports_table').DataTable();
    var stockReportsTableBody = $('#transfer_details_reports_table tbody');
    var filterCard = $('.filter-card');

     var filterLocationsFrom = filterCard.find('.filter_locations_from');
     var filterLocationsTo = filterCard.find('.filter_locations_to');
     var filterProduct = filterCard.find('.filter_product');
    var filterCategory = filterCard.find('.filter_category');
    var filterBrand = filterCard.find('.filter_brand');
    var filterDate = filterCard.find('.filter_date');

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         var filterLocationsFromVal = filterLocationsFrom.val();
         var filterLocationsToVal = filterLocationsTo.val();
         var filterProductVal = filterProduct.val();
         var filterCategoryVal = filterCategory.val();
         var filterBrandVal = filterBrand.val();
         var filterDateVal = filterDate.val();
         stockReportsTableBody.empty();
         filterData(filterLocationsFromVal, filterLocationsToVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal);
     });


    var filterDateVal = filterDate.val();
    filterData(0, 0, 0, 0, 0, filterDateVal);

    async function filterData(filterLocationsFromVal, filterLocationsToVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDate) {
    var data = {
    filter_locations_from: filterLocationsFromVal,
    filter_locations_to: filterLocationsToVal,
    filter_product: filterProductVal,
    filter_category: filterCategoryVal,
    filter_brand: filterBrandVal,
    filter_date: filterDate
};
    try {
    $.ajax({
    url: '/reports/transfer-details/filter-list',
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
}else {
    error('Something Went Wrong! Error Status: ' + status);
}
},
    success: function (results) {
    console.log(results);

    dataTable.clear();

    results.forEach(function(item) {

    var rowData = [
        item.transfered_at,
    item.variation_sku ? item.variation_sku : item.sku,
    item.name,
    item.variation_template_name+' -    '+item.variation_value_name,
    item.category_name ? item.category_name : '',
    item.brand_name ?? '',
    item.uom_name,
    item.transfer_quantity+' '+item.uom_short_name ?? '',
    item.remark,
    // item.samllest_stock_qty+' '+item.smallest_unit_name,
    // item.smallest_purchase_price,
    // item.purchase_price,
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

//  $("#transfer_details_reports_table").DataTable({
//      "footerCallback": function(row, data, start, end, display) {
//          var api = this.api(),
//              data;

//          // Remove the formatting to get integer data for summation
//          var intVal = function(i) {
//              return typeof i === "string" ?
//                  i.replace(/[\$,]/g, "") * 1 :
//                  typeof i === "number" ?
//                      i : 0;
//          };

//          // Total over this page
//          var priceTotal = api
//              .column(6, {
//                  page: "current"
//              })
//              .data()
//              .reduce(function(a, b) {
//                  return intVal(a) + intVal(b);
//              }, 0);


//          var priceByUnitTotal = api
//              .column(5, {
//                  page: "current"
//              })
//              .data()
//              .reduce(function(a, b) {
//                  return intVal(a) + intVal(b);
//              }, 0);


//         //  Update footer
//          $(api.column(6).footer()).html(
//              priceTotal +" Ks"
//          );
//          $(api.column(5).footer()).html(
//              priceByUnitTotal +" Ks"
//          );
//      }
//  });
