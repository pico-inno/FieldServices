 $(document).ready(function () {

     console.log('work');
    // $('#stock_reports_table').DataTable();
    var dataTable = $('#alert_expire_reports_table').DataTable();

    var saleReportsTableBody = $('#alert_expire_reports_table tbody');
    var filterCard = $('.filter-card');
     var filterLocations = filterCard.find('.filter_locations');
     var filterProduct = filterCard.find('.filter_product');
     var filterCategory = filterCard.find('.filter_category');
     var filterBrand = filterCard.find('.filter_brand');
    var filterExpire = filterCard.find('.filter_expire_range');

     $('#search_input').on('keyup', function () {
         dataTable.search(this.value).draw();
     });

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout( ()=>{
             var filterLocationsVal = filterLocations.val();
             var filterProductVal = filterProduct.val();
             var filterCategoryVal = filterCategory.val();
             var filterBrandVal = filterBrand.val();
             var filterExpireVal = filterExpire.val();

            saleReportsTableBody.empty();

            filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterExpireVal);
         }, 400 )
     });



    filterData(0, 0, 0, 0, 0);

    async function filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterExpireVal) {
        var data = {
            filter_locations: filterLocationsVal,
            filter_product: filterProductVal,
            filter_category: filterCategoryVal,
            filter_brand: filterBrandVal,
            filter_expire: filterExpireVal,
        };
    try {
        var tableLoadingMessage = $('.table-loading-message');

            $.ajax({
                url: '/reports/alert-expire/filter-list',
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
                            item.expired_date ?'<span class="text-dark">'+item.expired_date+'</span>' : '-',
                            // '<span class="text-danger">' +parseFloat(item.current_qty).toFixed(2)+ (item.ref_uom_short_name ? '(' + item.ref_uom_short_name + ')' : '(' + item.ref_uom_name + ')') + '</span>',


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
                    }else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                },

        });
} catch (error) {
    console.error(error);
}
}

});

  // $("#alert_expire_reports_table").DataTable({
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
