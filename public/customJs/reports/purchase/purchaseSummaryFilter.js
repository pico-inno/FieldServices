 $(document).ready(function () {
    // $('#stock_reports_table').DataTable();
    var dataTable = $('#purchase_reports_table').DataTable();

    var saleReportsTableBody = $('#purchase_reports_table tbody');
    var filterCard = $('.filter-card');
    var filterLocations = filterCard.find('.filter_locations');
    var filterCustomers = filterCard.find('.filter_customers');
    var filterStatus = filterCard.find('.filter_status');
    var filterDate = filterCard.find('.filter_date');

     // $('#search_input').on('keyup', function () {
     //     dataTable.search(this.value).draw();
     // });

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout( ()=>{
            var filterLocationsVal = filterLocations.val();
            var filterCustomersVal = filterCustomers.val();
             var filterStatusVal = filterStatus.val();
            var filterDateVal = filterDate.val();
            console.log(filterDateVal);
            saleReportsTableBody.empty();

            filterData(filterLocationsVal, filterCustomersVal, filterStatusVal, filterDateVal);
         }, 400 )
     });


    var filterDateVal = filterDate.val();
    filterData(0, 0, 0, filterDateVal);

    async function filterData(filterLocationsVal, filterCustomersVal, filterStatusVal, filterDateVal) {
    var data = {
    filter_locations: filterLocationsVal,
    filter_customers: filterCustomersVal,
    filter_status: filterStatusVal,
    filter_date: filterDateVal
};
    try {
        var tableLoadingMessage = $('.table-loading-message');

            $.ajax({
                url: '/reports/purchase/filter-list',
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                data: data,
                },
                beforeSend: function() {
                    // Show the loading message before AJAX request starts
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
                        var statusClass = getStatusClass(item.status);
                        var rowData = [
                            item.purchased_at ?? '',
                            item.purchase_voucher_no ?? '',
                            item.supplier.company_name ?? '',
                            item.purchase_amount ?? '',
                            item.business_location_id.name ?? '',
                            '<span class="badge badge-light-' + statusClass + '">' + item.status + '</span>',

                        ];
                        dataTable.row.add(rowData).draw();
                    });


                    dataTable.draw();

                },
                error: function (e) {
                    var status = e.status;
                    if (status === 405) {
                        warning('Method Not Allowed!');
                    }else if (status == 200) {
                       console.log('success');
                    }
                    else if (status === 419) {
                        error('Session Expired');
                    } else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                },

        });
} catch (error) {
    console.error(error);
}
}

     function getStatusClass(status) {
         return status === 'order'
             ? 'warning'
             : status === 'request' || status === 'pending'
                 ? 'primary'
                 : status === 'partial'
                     ? 'danger'
                     : status === 'deliver' || status == 'received'
                         ? 'success'
                         : 'info';
     }
});
