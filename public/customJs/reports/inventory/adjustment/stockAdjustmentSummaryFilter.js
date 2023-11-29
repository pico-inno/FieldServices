 $(document).ready(function () {
     var dataTable = $('#stock_adjustment_reports_table').DataTable();
     var stockAdjustmentTableBody = $('#stock_adjustment_reports_table tbody');
     var filterCard = $('.filter-card');
     var filterLocations = filterCard.find('.filter_locations');
     var filterStatus = filterCard.find('.filter_status');
     var filterDate = filterCard.find('.filter_date');


     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout(()=>{
            var filterLocationsVal = filterLocations.val();
            var filterStatusVal = filterStatus.val();
            var filterDateVal = filterDate.val();

            stockAdjustmentTableBody.empty();
            filterData(filterLocationsVal, filterStatusVal, filterDateVal);
         }, 400)
     });

     var filterDateVal = filterDate.val();
     filterData(0, 'all', filterDateVal);


    async function filterData(filterLocationsVal, filterStatusVal, filterDateVal) {
    var data = {
        filter_locations: filterLocationsVal,
        filter_status: filterStatusVal,
        filter_date: filterDateVal
};
    try {
    $.ajax({
    url: '/reports/adjustment-report/filter-list',
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
        var statusClass = getStatusClass(item.status);

    var rowData = [
    item.adjustment_date,
    item.adjustment_voucher_no,
    item.business_location.name,
    Number(item.increase_subtotal).toFixed(2),
    Number(item.decrease_subtotal).toFixed(2),
    '<span class="badge badge-light-' + statusClass + '">' + item.status + '</span>',
    item.created_person.username,
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
         return status === 'pending'
             ? 'warning'
             : status === 'completed'
                 ? 'success'
                    : 'info';
     }
});
