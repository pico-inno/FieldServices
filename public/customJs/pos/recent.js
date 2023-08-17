document.getElementById('pos_sale_recent').addEventListener('shown.bs.modal', function() {
    let posId = posRegisterId;

    $.ajax({
        url: `/pos/sold/${posId}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(responses){
            let saleDatas = responses.saleDatas;
            setSoldInvoice(saleDatas);
        },
        error: function(e){
            console.log(e.responseJSON.error);
        }
    });

})

const setSoldInvoice = (saleDatas) => {
    let deliverBody = $(document).find('#deliever_table_body');
    let draftBody = $(document).find('#draft_table_body');
    let orderBody = $(document).find('#order_table_body');

    $.each(saleDatas, function(index, item) {
        var row = `
            <tr>
                <td>${item.sales_voucher_no}</td>
                <td>${item.contact_name}</td>
                <td>${item.total_sale_amount * 1}</td>
                <td class="d-flex flex-row">
                    <a href="/pos/sold/edit/${item.id}?pos_register_id=${posRegisterId}" class="me-5">
                        <i class="pos-recent-sale-edit fas fa-edit fs-4 text-warning cursor-pointer"></i>
                    </a>
                    <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                    <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                </td>
            </tr>
        `

        if(item.status === "delivered"){
            deliverBody.find('.not-exist-data').addClass('d-none');
            deliverBody.append(row);
        }

        if(item.status === "order"){
            orderBody.find('.not-exist-data').addClass('d-none');
            orderBody.append(row);
        }

        if(item.status === "draft"){
            draftBody.find('.not-exist-data').addClass('d-none');
            draftBody.append(row);
        }
    });
    
}