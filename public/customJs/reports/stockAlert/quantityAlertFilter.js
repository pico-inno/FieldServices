 $(document).ready(function () {

     console.log('work');
    // $('#stock_reports_table').DataTable();
    var dataTable = $('#alert_quantity_reports_table').DataTable();

    var saleReportsTableBody = $('#alert_quantity_reports_table tbody');
    var filterCard = $('.filter-card');
     var filterLocations = filterCard.find('.filter_locations');
     var filterProduct = filterCard.find('.filter_product');
     var filterCategory = filterCard.find('.filter_category');
     var filterBrand = filterCard.find('.filter_brand');
    var filterDate = filterCard.find('.filter_date');

     $('#search_input').on('keyup', function () {
         dataTable.search(this.value).draw();
     });

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout( ()=>{
             var filterLocationsVal = filterLocations.val();
             var filterProductVal = filterProduct.val();
             var filterCategoryVal = filterCategory.val();
             var filterBrandVal = filterBrand.val();
             var filterDateVal = filterDate.val();

            saleReportsTableBody.empty();

            filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal);
         }, 400 )
     });


    var filterDateVal = filterDate.val();
    filterData(0, 0, 0, 0, filterDateVal);

    async function filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal) {
        var data = {
            filter_locations: filterLocationsVal,
            filter_product: filterProductVal,
            filter_category: filterCategoryVal,
            filter_brand: filterBrandVal,
            filter_date: filterDateVal,
        };
    try {
        var tableLoadingMessage = $('.table-loading-message');

            $.ajax({
                url: '/reports/alert-quantity/filter-list',
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                data: data,
                },
                beforeSend: function() {
                    tableLoadingMessage.removeClass('d-none');
                },
                complete: function() {
                    // Hide the loading message after AJAX request completes (regardless of success or failure)
                    tableLoadingMessage.addClass('d-none');
                },
                success: function (results) {
                    console.log(results);

                    dataTable.clear();

                    results.forEach(function(item) {

                        var rowData = [

                            item.name ,
                            item.sku ?? '',
                            item.location_name ?? '-',
                            '<span class="text-danger">' +parseFloat(item.current_qty).toFixed(2)+ (item.ref_uom_short_name ? '(' + item.ref_uom_short_name + ')' : '(' + item.ref_uom_name + ')') + ' remains</span>',


                            // item.sale_data.supplier.company_name ?? '-',
                            // item.category_name ?? '',
                            // item.brand_name ?? '',
                            // parseFloat(item.quantity).toFixed(2) + (item.uom_short_name ? ' (' + item.uom_short_name + ')' : ''),
                            // parseFloat(item.uom_price).toFixed(2) ,
                            // (item.uom_price * item.quantity).toFixed(2),
                        ];
                        dataTable.row.add(rowData).draw();
                    });


                    dataTable.draw();

                },
                error: function (e) {
                    var status = e.status;
                    if (status === 405) {
                        warning('Method Not Allowed!');
                    }else if (status === 200) {
                        console.log('success');
                    }else{
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                },

        });
} catch (error) {
    console.error(error);
}
}

});

  // $("#alert_quantity_reports_table").DataTable({
  //     "footerCallback": function(row, data, start, end, display) {
  //         var api = this.api(),
  //             data;
  //
  //         // Remove the formatting to get integer data for summation
  //         var intVal = function(i) {
  //             return typeof i === "string" ?
  //                 i.replace(/[\$,]/g, "") * 1 :
  //                 typeof i === "number" ?
  //                     i : 0;
  //         };
  //
  //         // Total over all pages
  //         // total = api
  //         //     .column( 8 )
  //         //     .data()
  //         //     .reduce( function (a, b) {
  //         //         return intVal(a) + intVal(b);
  //         //     }, 0 );
  //
  //         // Total over this page
  //         pageTotal = api
  //             .column( 8, { page: 'current'} )
  //             .data()
  //             .reduce( function (a, b) {
  //                 return intVal(a) + intVal(b);
  //             }, 0 );
  //
  //         // Update footer
  //         $( api.column( 7 ).footer() ).html(
  //             'Ks '+pageTotal
  //         );
  //
  //         // qtyTotal = api
  //         //     .column( 6, { page: 'current'} )
  //         //     .data()
  //         //     .reduce( function (a, b) {
  //         //         return intVal(a) + intVal(b);
  //         //     }, 0 );
  //         //
  //         //
  //         // $( api.column( 6 ).footer() ).html(
  //         //     qtyTotal
  //         // );
  //
  //
  //     }
  // });
