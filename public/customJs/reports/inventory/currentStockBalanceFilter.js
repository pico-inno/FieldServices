 $(document).ready(function () {
    // $('#current_stock_balance_reports_table').DataTable();
    var dataTable = $('#current_stock_balance_reports_table').DataTable();
    var stockReportsTableBody = $('#current_stock_balance_reports_table tbody');
    var filterCard = $('.filter-card');

    var filterLocations = filterCard.find('.filter_locations');
    var filterProduct = filterCard.find('.filter_product');
    var filterCategory = filterCard.find('.filter_category');
    var filterBrand = filterCard.find('.filter_brand');
    var filterDate = filterCard.find('.filter_date');
    var filterView = filterCard.find('.filter_view');


     var handleSearchDatatable = () => {

         const filterSearch = document.querySelector('[data-current-balance-report="search"]');
         console.log(filterSearch)
         filterSearch.addEventListener('keyup', function (e) {
             console.log('nice 1')
             dataTable.search(e.target.value).draw();
         });
     }

     handleSearchDatatable();
    $('#kt_daterangepicker_5, .filter-card select, .filter-card input').on('change', function() {

        var filterLocationsVal = filterLocations.val();
        var filterProductVal = filterProduct.val();
        var filterCategoryVal = filterCategory.val();
        var filterBrandVal = filterBrand.val();
        var filterDateVal = filterDate.val();
        var filterViewVal = filterView.val();
        stockReportsTableBody.empty();
        filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal, filterViewVal);
    });


    var filterDateVal = filterDate.val();
    filterData( 0, 0, 0, 0, filterDateVal, 0);

    async function filterData( filterLocations, filterProductVal, filterCategoryVal, filterBrandVal, filterDate, filterViewVal) {
        var data = {
            filter_locations: filterLocations,
            filter_product: filterProductVal,
            filter_category: filterCategoryVal,
            filter_brand: filterBrandVal,
            filter_date: filterDate,
            filter_view: filterViewVal,
        };
        try {
            $.ajax({
                url: '/reports/current-stock-balance/filter-list',
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

                        var rowData = [
                            item.variation_sku ? item.variation_sku : item.sku,
                            item.name+'<br><span class="fs-7 text-muted">'+item.variation_template_name+' -    '+item.variation_value_name+'</span>',
                            item.batch_no,
                            item.lot_no,
                            item.location_name,
                            item.category_name ? item.category_name : '-',
                            item.brand_name ?  item.brand_name : '-',
                            Number(item.purchase_qty).toFixed(2)+' '+item.ref_uom_short_name ?? '',
                            Number(item.current_qty).toFixed(2)+' '+item.ref_uom_short_name ?? '',
                            item.ref_uom_name ?? '',
                        ];
                        // if (filterViewVal == 3) {
                        //     rowData.splice(3, 0, item.lot_no);
                        // }

                        dataTable.row.add(rowData);


                    });


                    dataTable.draw();

                },
            });
        } catch (error) {
            console.error(error);
        }
    }


function enableLotSerialColumn(showLotSerial){
    var tableHeader = document.getElementById("current_stock_balance_reports_table").getElementsByTagName("thead")[0];
    var lotSerialTh = tableHeader.querySelector("th:nth-child(4)"); 

    if (showLotSerial) {
       
        lotSerialTh.style.display = "table-cell";
    } else {
        
        lotSerialTh.style.display = "none";
    }

}

});
