 $(document).ready(function () {

    var currentReportDataTable = $('#current_report_table_widget_5_table').DataTable();
    var currentReportDataTableBody = $('#current_report_table_widget_5_table tbody');

     var filterCard = $('.current-report-filter-card');
     var filterLocations = filterCard.find('.current_report_filter_locations');
     var filterCategory = filterCard.find('.current_report_filter_category');


     totalCurrentQuantity();


    $('.current-report-filter-card select').on('change', function() {
        var filterLocationsVal = filterLocations.val();
        var filterCategoryVal = filterCategory.val();

        console.log(filterLocationsVal)
        console.log(filterCategoryVal)

        currentReportDataTableBody.empty();
        filterCurrentBalance(filterLocationsVal, filterCategoryVal);
    });


     filterCurrentBalance( 0, 0);

    async function filterCurrentBalance(filterLocationsVal, filterCategoryVal) {
        var data = {
            filter_locations: filterLocationsVal,
            filter_category: filterCategoryVal,
        };
        try {
            $.ajax({
                url: '/dashboard/current-balance-filter',
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
                    }
                    else if (status === 419) {
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
                    currentReportDataTable.clear();
                    console.log(results);
                    results.forEach(function(item) {

                        var rowData = [
                            item.name+'<br>'+item.variation_value_name,
                            item.sku,
                            item.created_at,
                            item.expired_date,
                            item.ref_uom_price,
                            '<span class="text-dark fw-bold">'+item.current_qty+ (item.ref_uom_short_name ? '(' + item.ref_uom_short_name + ')' : '(' + item.ref_uom_name + ')')+'</span>',
                        ];


                        currentReportDataTable.row.add(rowData);


                    });


                    currentReportDataTable.draw();

                },
            });
        } catch (error) {
            console.error(error);
        }
    }

    async function totalCurrentQuantity(){
        try{
            $.ajax({
                url: '/dashboard/total-current-qty',
                type: 'POST',
                headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
                error: (e) => console.log(e.status),
                success: function (results) {
                    $('#total_current_qty_widget').html(results ?? 0);
                }

            });
        }catch(error){
            console.log(error);
        }
    }


});
