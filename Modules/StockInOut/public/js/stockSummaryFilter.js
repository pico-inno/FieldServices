 $(document).ready(function () {
    // $('#stock_reports_table').DataTable();
    var dataTable = $('#stock_reports_table').DataTable();
    var stockReportsTableBody = $('#stock_reports_table tbody');
    var filterCard = $('.filter-card');
    var filterType = $('.filter_type');
    var filterLocations = filterCard.find('.filter_locations');
    var filterStocksperosn = filterCard.find('.filter_person');
    // var filterStatus = filterCard.find('.filter_status');
    var filterDate = filterCard.find('.filter_date');

     $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {
         setTimeout( ()=>{
            var filterTypeVal = filterType.val();
            var filterLocationsVal = filterLocations.val();
            var filterStocksperosnVal = filterStocksperosn.val();
            //  var filterStatusVal = filterStatus.val();
            var filterDateVal = filterDate.val();
            stockReportsTableBody.empty();

            filterData(filterTypeVal, filterLocationsVal, filterStocksperosnVal, filterDateVal);
         }, 400 )
     });


    var filterDateVal = filterDate.val();
    filterData(1, 0, 0, filterDateVal);

    async function filterData(filterTypeVal, filterLocations, filterStocksperosnVal, filterDate) {
    var data = {
    filter_type: filterTypeVal,
    filter_locations: filterLocations,
    filter_stocksperson: filterStocksperosnVal,
    // filter_status: filterStatus,
    filter_date: filterDate
};
    try {
    $.ajax({
    url: '/reports/stock-report/filter-list',
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
        // var statusClass = getStatusClass(item.status);
    var rowData = [
    item.stockin_date ? item.stockin_date : item.stockout_date,
    item.stockin_voucher_no ? item.stockin_voucher_no : item.stockout_voucher_no,
    item.business_location.name,
    item.stockin_person && item.stockin_person.username ? item.stockin_person.username : item.stockout_person.username,
    // '<span class="badge badge-light-' + statusClass + '">' + item.status + '</span>',
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

    //  function getStatusClass(status) {
    //      return status === 'pending'
    //          ? 'warning'
    //          : status === 'received'
    //              ? 'success'
    //              : status === 'issued'
    //                  ? 'danger'
    //                  : status === 'confirmed'
    //                      ? 'primary'
    //                      : 'info';
    //  }
});
