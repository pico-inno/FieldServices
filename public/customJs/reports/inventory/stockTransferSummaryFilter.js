 $(document).ready(function () {
     var dataTable = $('#stock_transfer_reports_table').DataTable();
     var stocktransferTableBody = $('#stock_transfer_reports_table tbody');
     var filterCard = $('.filter-card');
     var filterLocationsFrom = filterCard.find('.filter_locations_from');
     var filterLocationsTo = filterCard.find('.filter_locations_to');
     var filterStockTransferperson = filterCard.find('.filter_transferperosn');
     var filterStockReceiveperson = filterCard.find('.filter_receiveperosn');
     var filterStatus = filterCard.find('.filter_status');
     var filterDate = filterCard.find('.filter_date');


     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout(()=>{
            var filterLocationsFromVal = filterLocationsFrom.val();
            var filterLocationsToVal = filterLocationsTo.val();
            var filterStockTransferpersonVal = filterStockTransferperson.val();
            var filterStockReceivepersonVal = filterStockReceiveperson.val();
            var filterStatusVal = filterStatus.val();
            var filterDateVal = filterDate.val();
   
            stocktransferTableBody.empty();
            filterData(filterLocationsFromVal, filterLocationsToVal, filterStockTransferpersonVal, filterStockReceivepersonVal, filterStatusVal, filterDateVal);
         }, 400)
     });

     var filterDateVal = filterDate.val();
     filterData(0, 0, 0, 0, 0, filterDateVal);


    async function filterData(filterLocationsFromVal, filterLocationsToVal, filterStockTransferpersonVal, filterStockReceivepersonVal, filterStatusVal, filterDateVal) {
    var data = {
        filter_locations_from: filterLocationsFromVal,
        filter_locations_to: filterLocationsToVal,
        filter_stocktransferperson: filterStockTransferpersonVal,
        filter_stockreceiveperson: filterStockReceivepersonVal,
        filter_status: filterStatusVal,
        filter_date: filterDateVal
};
    try {
    $.ajax({
    url: '/reports/transfer-report/filter-list',
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
} else {
    error('Something Went Wrong! Error Status: ' + status);
}
},
    success: function (results) {
    console.log(results);

    dataTable.clear();

    results.forEach(function(item) {
        var statusClass = getStatusClass(item.status);
    var rowData = [
    item.transfered_at,
    item.transfer_voucher_no,
    item.business_location_from.name,
    item.business_location_to.name,
    // item.transfer_expense,
    '<span class="badge badge-light-' + statusClass + '">' + item.status + '</span>',
    item.created_by.username,
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
