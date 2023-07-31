
$(document).on('click', '#edit', function(e){
    e.preventDefault();
    loadingOn();
    $('#modal').load($(this).data('href'), function() {
        // $(this).remove();
        $(this).modal('show');
        loadingOff();
        expenseUtilities();

        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
    });
});
$(document).on('click', '#viewPayment', function(e){
    e.preventDefault();
    loadingOn();
    $('#modal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
        $('[data-control="select2-acc"]').select2();
        $('[data-datepicker="datepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });
        handleDeletePayment($(this));
    });
});

$(document).on('click', '.edit_payment', function(e){
    e.preventDefault();
    loadingOn();
    $('#paymentEditModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
        $('[data-control="select2-acc"]').select2();
        $('[data-datepicker="datepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });
    });
});

$(document).on('change','#currency_id',function(e){

    $.ajax({
        url: `/payment-account/get/${$(this).val()}`,
        type: 'get',
        error:function(e){
           let status=e.status;
            if(status==405){
                warning('Method Not Allow!');
            }else if(status==419){
                error('Session Expired')
            }else{
                console.log(e);
                console.error(' Something Went Wrong! Error Status: '+status )
            };
        },
        success: function(response) {
            let accounts=response.accounts;
            let data=accounts.map((account)=>{
            return {
                'id':account.id,
                'text':`${account.name} (${account.account_number})`
            }
            })
            $('#payment_account_id').empty();
            $('#payment_account_id').select2({
                data
            });
            console.log(data);
        }
    })
})
var handleDeletePayment = (modalDom) => {
    // Select all delete buttons
    $(document).off('click', '[data-table="delete_payment"]').on('click','[data-table="delete_payment"]',function (e) {
        e.preventDefault();
        // Select parent row
        const parent = $(this).closest('.transactionList');
        // Get expense name
        const expenseName = parent.find('.voucher_no').text();
        let id=$(this).attr('data-id');
        // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
        Swal.fire({
            text: "Are you sure to remove  " + expenseName + " from report?",
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

                parent.remove();
                    $.ajax({
                        url: `/payment-transactions/remove/${id}/sale/`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        error:function(e){
                            warning(e.message)
                        },
                        success: function(s) {
                            try {
                                datatable.ajax.reload();
                            } catch (error) {
                                console.log(error);
                            }
                            $('#total_expense_amount').text(s.total_expense_amount);
                            $('#balance_amount').text(s.balance_amount);
                            Swal.fire({
                                text: "You have deleted " + expenseName + "!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                try {
                                    datatable.ajax.reload();
                                } catch (error) {

                                }
                                modalDom.modal('hide');
                                success(s.success);
                            });
                        }
                    })
            }
        });
    })
    // Delete button on click
}
$(document).on('click', '#paymentCreate', function(e){
    e.preventDefault();
    loadingOn();
    $('#modal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
        $('[data-control="select2-acc"]').select2();
        $('[data-datepicker="datepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });
    });
});
