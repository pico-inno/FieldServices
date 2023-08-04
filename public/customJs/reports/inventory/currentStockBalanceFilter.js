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
    var filterLotVal = 1;


     var handleSearchDatatable = () => {

         const filterSearch = document.querySelector('[data-current-balance-report="search"]');
         console.log(filterSearch)
         filterSearch.addEventListener('keyup', function (e) {
             console.log('nice 1')
             dataTable.search(e.target.value).draw();
         });
     }

     handleSearchDatatable();
    $('#kt_daterangepicker_5, #flexCheckDefault, .filter-card select, .filter-card input').on('change', function() {
        var checkbox = $('#flexCheckDefault');
        filterLotVal = checkbox.prop('checked') ? 0 : 1;



        var filterLocationsVal = filterLocations.val();
        var filterProductVal = filterProduct.val();
        var filterCategoryVal = filterCategory.val();
        var filterBrandVal = filterBrand.val();
        var filterDateVal = filterDate.val();
        stockReportsTableBody.empty();
        filterData(filterLocationsVal, filterProductVal, filterCategoryVal, filterBrandVal, filterDateVal, filterLotVal);
    });


    var filterDateVal = filterDate.val();
    filterData( 0, 0, 0, 0, filterDateVal, filterLotVal);

    async function filterData( filterLocations, filterProductVal, filterCategoryVal, filterBrandVal, filterDate, filterLotVal) {
        var data = {
            filter_locations: filterLocations,
            filter_product: filterProductVal,
            filter_category: filterCategoryVal,
            filter_brand: filterBrandVal,
            filter_date: filterDate,
            filter_lot:filterLotVal,
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
                    } else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                },
                success: function (results) {
                    dataTable.clear();

                    results.forEach(function(item) {

                        var rowData = [
                            item.variation_sku ? item.variation_sku : item.sku,
                            item.name+'<br><span class="fs-7 text-muted">'+item.variation_template_name+' -    '+item.variation_value_name+'</span>',
                            item.lot_no,
                            item.location_name,
                            item.category_name ? item.category_name : '-',
                            item.brand_name ?  item.brand_name : '-',
                            Number(item.purchase_qty).toFixed(2)+' '+item.ref_uom_short_name ?? '',
                            Number(item.current_qty).toFixed(2)+' '+item.ref_uom_short_name ?? '',
                            item.ref_uom_name ?? '',
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
