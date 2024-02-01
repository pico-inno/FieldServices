 $(document).ready(function () {

    // $('#stock_reports_table').DataTable();
    var dataTable = $('#alert_expire_reports_table').DataTable();
    var saleReportsTableBody = $('#alert_expire_reports_table tbody');
    var filterCard = $('.filter-card');
     var filterLocations = filterCard.find('.filter_locations');
     var filterProduct = filterCard.find('.filter_product');
     var filterCategory = filterCard.find('.filter_category');
     var filterBrand = filterCard.find('.filter_brand');
    var filterExpire = filterCard.find('.filter_expire_range');
     var selectedIds = [];

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

                            '<div class="form-check form-check-sm form-check-custom me-3">' +
                            '<input class="form-check-input record-checkbox" type="checkbox" data-kt-check="true" value="' + item.csb_id + '">' +
                            '</div>',
                            item.name ,
                            item.sku ?? '',
                            item.location_name ?? '-',
                            item.expired_date ?'<span class="text-dark">'+item.expired_date+'</span>' : '-',
                            item.current_qty,

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
                    }else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                },

        });
        } catch (error) {
            console.error(error);
        }
    }


     $('#select-all-checkbox').on('change', function () {
         var isChecked = $(this).prop('checked');
         $('.record-checkbox').prop('checked', isChecked);
         updateSelectedIds();
     });

     $('.record-checkbox').on('change', function () {
         updateSelectedIds();
     });

     function updateSelectedIds() {
         var selectedIds = [];
         $('.record-checkbox:checked').each(function () {
             selectedIds.push($(this).val());
         });
         console.log('Selected IDs:', selectedIds);
     }

     $(document).on('change', '.record-checkbox', function () {
         updateToolbarVisibility();
     });


     $('#select-all-checkbox').on('change', function () {
         var isChecked = $(this).prop('checked');
         $('.record-checkbox').prop('checked', isChecked);
         updateToolbarVisibility();
     });


     function updateToolbarVisibility() {
         var selectedCount = $('.record-checkbox:checked').length;

         if (selectedCount > 0) {
             $('[data-kt-customer-table-toolbar="base"]').addClass('d-none');
             $('[data-kt-purchase-table-toolbar="selected"]').removeClass('d-none');
             $('[data-kt-purchase-table-select="selected_count"]').text(selectedCount);
         } else {
             $('[data-kt-customer-table-toolbar="base"]').removeClass('d-none');
             $('[data-kt-purchase-table-toolbar="selected"]').addClass('d-none');
         }
     }

     $('[data-kt-purchase-table-select="delete_selected"]').on('click', function () {

         if (filterExpire.val() != 'expired'){
             Swal.fire({
                 text: "Unexpired items cannot be deleted from this section",
                 icon: "error",
                 buttonsStyling: false,
                 confirmButtonText: "Ok, got it!",
                 customClass: {
                     confirmButton: "btn fw-bold btn-primary",
                 }
             });
             return;
         }
         var selectedIds = [];
         $('.record-checkbox:checked').each(function () {
             selectedIds.push($(this).val());
         });


         if (selectedIds.length === 0) {
             alert('Please select records to delete.');
             return;
         }

         Swal.fire({
             text: "Do you want to remove expired items from current stock balance?",
             icon: "warning",
             showCancelButton: true,
             buttonsStyling: false,
             confirmButtonText: "Yes, delete!",
             cancelButtonText: "No, cancel",
             customClass: {
                 confirmButton: "btn fw-bold btn-danger",
                 cancelButton: "btn fw-bold btn-active-light-primary"
             }
         }).then(function (result) {
             if (result.value) {
                 $.ajax({
                     url: self.removeExpireItemApi,
                     type: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     data: { ids: selectedIds },
                     success: function (response) {
                         if (response.message == 'success'){
                             console.log('Records deleted successfully:', response);

                             Swal.fire({
                                 text: "Selected expired items was successfully deleted.",
                                 icon: "success",
                                 buttonsStyling: false,
                                 confirmButtonText: "Ok, got it!",
                                 customClass: {
                                     confirmButton: "btn fw-bold btn-primary",
                                 }
                             }).then(function (){
                                 document.location.reload();

                             });
                             updateToolbarVisibility();
                         }
                     },
                     error: function (error) {
                         console.error('Error deleting records:', error);
                     }
                 });
             } else if (result.dismiss === 'cancel') {
                 Swal.fire({
                     text: "Selected expired items was not deleted.",
                     icon: "error",
                     buttonsStyling: false,
                     confirmButtonText: "Ok, got it!",
                     customClass: {
                         confirmButton: "btn fw-bold btn-primary",
                     }
                 });
             }
         });

     });


 });
