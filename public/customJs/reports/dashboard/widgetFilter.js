$('#sale_order_click, #purchase_order_click').click(()=>totalSaleAndPurchaseOrder());
totalContact();
totalSaleAndPurchaseOrder();
async function totalContact(){
    try{
        $.ajax({
            url: '/dashboard/total-contacts-widget',
            type: 'POST',
            headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
            error: (e) => console.log(e.status),
            success: function (results) {
                $('#total_this_month_customer_widget').html('+'+results.totalCustomers ?? 0);
                $('#total_this_month_supplier_widget').html('+'+results.totalSuppliers ?? 0);
            }

        });
    }catch(error){
        console.log(error);
    }
}

async function totalSaleAndPurchaseOrder(){
    try{
        $.ajax({
            url: '/dashboard/total-sale-purchase-order-widget',
            type: 'POST',
            headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
            error: (e) => console.log(e.status),
            success: function (results) {
                $('#total_sale_order_widget').html(results.totalSaleOrder ?? 0);
                $('#total_purcahse_order_widget').html(results.totalPurchaseOrder ?? 0);
            }

        });
    }catch(error){
        console.log(error);
    }
}
