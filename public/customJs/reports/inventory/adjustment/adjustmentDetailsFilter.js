$(document).ready(function () {
    var dataTable = $('#stock_adjustment_details_reports_table').DataTable();
    var stockAdjustmentTableBody = $('#stock_adjustment_details_reports_table tbody');
    var filterCard = $('.filter-card');
    var filterLocations = filterCard.find('.filter_locations');
    var filterProduct = filterCard.find('.filter_product')
    var filterAdjType = filterCard.find('.filter_adjustment_type');
    var filterDate = filterCard.find('.filter_date');


    $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
        setTimeout(()=>{
            var filterLocationsVal = filterLocations.val();
            var filterProductVal = filterProduct.val();
            var filterAdjTypeVal = filterAdjType.val();
            var filterDateVal = filterDate.val();

            stockAdjustmentTableBody.empty();
            filterData(filterLocationsVal, filterProductVal, filterAdjTypeVal, filterDateVal);
        }, 400)
    });

    var filterDateVal = filterDate.val();
    filterData(0, 0,'all', filterDateVal);


    async function filterData(filterLocationsVal, filterProductVal, filterAdjTypeVal, filterDateVal) {
        var data = {
            filter_locations: filterLocationsVal,
            filter_product: filterProductVal,
            filter_adj_type: filterAdjTypeVal,
            filter_date: filterDateVal
        };
        try {
            $.ajax({
                url: '/reports/adjustment-details/filter-list',
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
                    console.log(results);

                    dataTable.clear();

                    results.forEach(function(item) {
                        var statusClass = getStatusClass(item.adjustment_type);

                        console.log(item.variation_template_name,'111111')
                        var rowData = [
                            item.sku,
                            item.name,
                            item.variation_template_name ?? '-',
                            '<span class="badge badge-light-' + statusClass + '">' + item.adjustment_type + '</span>',
                            item.balance_quantity + ' ('+item.uom_short_name+')',
                            item.gnd_quantity + ' ('+item.uom_short_name+')',
                            '<span style="color: ' + (item.adjustment_type === 'increase' ? 'green' : 'red') + ';">' + item.adj_quantity + '('+item.uom_short_name+')</span>',
                            item.uom_name,
                            '<span style="color: ' + (item.adjustment_type === 'increase' ? 'green' : 'red') + ';">' + item.uom_price + '('+item.currency_name+')</span>',
                            '<span style="color: ' + (item.adjustment_type === 'increase' ? 'green' : 'red') + ';">' + item.subtotal + '('+item.currency_name+')</span>',
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

    function getStatusClass(status) {
        return status === 'decrease'
            ? 'danger'
            : status === 'increase'
                ? 'success'
                : 'info';
    }
});
