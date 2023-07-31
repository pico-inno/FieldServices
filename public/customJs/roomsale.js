"use strict";

var roomsale = function () {
    $('#transactionType').on('change', function() {
        if ($(this).val() == "reservation") {
            $('.registration-div').css({
                'display' : 'none'
            });
            $('.reservation-div').css({
                'display' : 'block'
            });
        } else {
            $('.reservation-div').css({
                'display' : 'none'
            });

            $('.registration-div').css({
                'display' : 'block'
            });
        }
    });

    $('.reservation-div,.registration-div').on('change', function() {
        $('.check-in-out-date-div').css({
            'display' : 'block'
        });
    });

    $('.custom-select2').select2();

    // $(document).on('select2:select', '#room_added_table select[name="room_type_id[]"]', function () {
    //     var selectedRoomType = $(this).val(); // Get the selected room type

    //     var rowIndex = $(this).closest('tr').index();

    //     $('select[name="room_id[]"]').eq(rowIndex).find('option').each(function () {
    //         var roomType = $(this).data('room-type-id');

    //         if (selectedRoomType === "" || roomType == selectedRoomType) {
    //             $(this).prop('disabled', false); // Enable the option if it matches the selected room type
    //         } else {
    //             $(this).prop('disabled', true); // Disable the option if it doesn't match the selected room type
    //         }
    //     });

    //     $('select[name="room_rate_id[]"]').eq(rowIndex).find('option').each(function () {
    //         var roomType = $(this).data('room-type-id');

    //         if (selectedRoomType === "" || roomType == selectedRoomType) {
    //             $(this).prop('disabled', false);
    //         } else {
    //             $(this).prop('disabled', true);
    //         }
    //     });
    // });

    // $(document).on('select2:select', '#room_added_table select[name="room_rate_id[]"]', function () {
    //     var currentRow = $(this).closest('tr');
    //     var room_fees = currentRow.find('input[name="room_fees[]"]');
    //     var selectedRoomRate = $(this).find('option:selected');
    //     var rateAmountBefDiscount = parseInt(selectedRoomRate.data('rate-amount'));
    //     room_fees.val(rateAmountBefDiscount).trigger('change');

    //     calSubTotalAndDisc(currentRow);
    //     update_sale_amount();

    // });

     $(document).on('change', '#sale_amount,#total_discount_amount,#total_discount_type', function () {
        total_sale_amount();
        balance_amount();
    });

    $(document).on('input click', '#room_added_table select[name="discount_type[]"], #room_added_table input[name="per_item_discount[]"], #room_added_table .btn[data-kt-dialer-control="increase"], #room_added_table .btn[data-kt-dialer-control="decrease"]', function () {
        var parentRow = $(this).closest('tr');
        calSubTotalAndDisc(parentRow);

    });

    $(document).on('click', '#delete_room_row', function (event) {
        if ($('#room_added_table tbody tr').length == 1 || $(this).hasClass('disable')) {
            $(this).css({
                'cursor': 'not-allowed',
                'opacity': 0.5
            });
            event.preventDefault();
            return false;
        }

        $(this).closest('.row').prev('br').prev('.table-responsive').find('#room_added_table tbody tr:last').remove();

        var numOfDeletedRooms = $('#room_added_table tbody tr').length;

        $('.room-qty').val(numOfDeletedRooms);

        update_sale_amount();
    });

    $(document).on('click', '#delete_each_room_row', function (event) {
        if ($('#room_added_table tbody tr').length == 1 || $(this).hasClass('disable')) {
            $(this).css({
                'cursor': 'not-allowed',
                'opacity': 0.5
            });
            event.preventDefault();
            return false;
        }

        $(this).closest('tr').remove();

        var numOfDeletedRooms = $('#room_added_table tbody tr').length;

        $('.room-qty').val(numOfDeletedRooms);

        update_sale_amount();
    });

    $(document).on('change', '#total_paid_amount', function () {
        balance_amount();
    });

    $('#check_in_date, #check_out_date').on('input change blur', function (e) {
        var check_in_date = $('#check_in_date').val();
        var check_out_date = $('#check_out_date').val();
        $('.room_check_in_date').val(check_in_date);
        $('.room_check_out_date').val(check_out_date);
    });

    $("#room_added_table").DataTable({
        "order": false,
        "paging": false,
        "info": false,
        "dom": "<'table-responsive'tr>"
    });

    // Public methods
    return {
        init: function () {
            update_sale_amount();
            total_sale_amount();
            balance_amount();
            initializeDialer();
        }
    };
}();
    function calSubTotalAndDisc(parentRow) {
        var totalRoomQty = isNullOrNan(parentRow.find('input[name="qty[]"]').val());
        var discountType = parentRow.find('select[name="discount_type[]"]').val();
        var perItemDiscount = isNullOrNan(parentRow.find('input[name="per_item_discount[]"]').val());
        var total_line_discount_amount = parentRow.find('input[name="total_line_discount_amount[]"]');
        var room_fees = isNullOrNan(parentRow.find('input[name="room_fees[]"]').val());
        var subtotal = parentRow.find('input[name="subtotal[]"]');

        var totalRoomQtyAmount = totalRoomQty * room_fees;
        subtotal.val(totalRoomQtyAmount);
        if (discountType == 'fixed') {
            total_line_discount_amount.val(perItemDiscount*totalRoomQty);
        } else {
            var percentageDiscountAmt = room_fees * (perItemDiscount / 100);
            total_line_discount_amount.val(percentageDiscountAmt*totalRoomQty);
        }
        update_sale_amount();
        update_total_line_discount();
        total_sale_amount();
        balance_amount();
    }
tempusDominus.extend(tempusDominus.plugins.customDateFormat);
var checkInInputs = document.getElementsByClassName("room_check_in_date");
var checkOutInputs = document.getElementsByClassName("room_check_out_date");

new tempusDominus.TempusDominus(checkInInputs[checkInInputs.length - 1], {
    localization: {
        locale: "en",
        format: "dd/MM/yyyy, hh:mm T",
    }
});

new tempusDominus.TempusDominus(checkOutInputs[checkOutInputs.length - 1], {
    localization: {
        locale: "en",
        format: "dd/MM/yyyy, hh:mm T",
    }
});

var datepickerIds = ["check_in_date", "check_out_date"];

datepickerIds.forEach(function (id) {
    new tempusDominus.TempusDominus(document.getElementById(id), {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy, hh:mm T",
        }
    });
});

datepickerIds.forEach(function (id) {
    var currentDate = new Date();
    var datepicker = document.getElementById(id);
    datepicker.placeholder = currentDate.toLocaleString();

    var options = {
        autoClose: true
    };
});

function update_sale_amount() {

    var sale_amount = 0;

    $('#room_added_table tbody')
        .find('tr')
        .each(function () {
            var rate_amount_after_discount = parseFloat($(this).find('input[name="subtotal[]"]').val());
            // var subtotal = parseFloat($(this).find('input[name="amount_inc_tax[]"]').val());

            // total_amount += isNaN(subtotal) ? 0 : subtotal;
            sale_amount += isNaN(rate_amount_after_discount) ? 0 : rate_amount_after_discount;

        });
    // $('#total_amount').text(total_amount);
    $('#sale_amount').val(sale_amount);
        total_sale_amount();
        balance_amount();
}
function update_total_line_discount() {

    var total_amount = 0;

    $('#room_added_table tbody')
        .find('tr')
        .each(function () {
            var total_line_discount_amount = parseFloat($(this).find('input[name="total_line_discount_amount[]"]').val());
            // var subtotal = parseFloat($(this).find('input[name="amount_inc_tax[]"]').val());

            // total_amount += isNaN(subtotal) ? 0 : subtotal;
            total_amount += isNaN(total_line_discount_amount) ? 0 : total_line_discount_amount;

        });
    // $('#total_amount').text(total_amount);
        $('#total_item_discount').val(total_amount);
        total_sale_amount();
        balance_amount();
}
function total_sale_amount() {
    let total_item_discount = isNullOrNan($('#total_item_discount').val());
    let sale_amount=isNullOrNan($('#sale_amount').val());
    $('#total_sale_amount').val(sale_amount-total_item_discount)
}
// function total_sale_amount() {
//     let result;
//     let discount_type = $('#total_discount_type').val(); console.log(discount_type);
//     let discount_amount = parseFloat($('#total_discount_amount').val() ?? 0);
//     // console.log(discount_amount);
//     // let sale_amount=$('#sale_amount').val();
//     // let total_amount = $('#total_amount').text();
//     // console.log(total_amount);
//     // if (discount_type == 'fixed') {
//     //     result = sale_amount - discount_amount;
//     //     $('#discount').val(discount_amount);

//     // } else if (discount_type == 'percentage') {
//     //     let percentage_amount = sale_amount * (discount_amount / 100);
//     //     $('#discount').val(percentage_amount);
//     //     result = (sale_amount - percentage_amount);
//     // }
//     $('#sale_amount').val(result)
// }

function balance_amount() {
    let total_sale_amount = isNullOrNan($('#total_sale_amount').val());
    let total_paid_amount = isNullOrNan($('#total_paid_amount').val());
    let result = total_sale_amount - total_paid_amount;
    $('#total_balance_amount').val(result)
}

function initializeDialer() {
    $('.input-group[data-kt-dialer="true"]').each(function () {
        var inputControl = $(this).find('input[data-kt-dialer-control="input"]');
        var decreaseControl = $(this).find('button[data-kt-dialer-control="decrease"]');
        var increaseControl = $(this).find('button[data-kt-dialer-control="increase"]');

        decreaseControl.on('click', function () {
            var currentValue = parseInt(inputControl.val());
            if (currentValue > inputControl.attr('min')) {
                inputControl.val(currentValue - 1);
                inputControl.trigger('input');
            }
        });

        increaseControl.on('click', function () {
            var currentValue = parseInt(inputControl.val());
            if (currentValue < inputControl.attr('max')) {
                inputControl.val(currentValue + 1);
                inputControl.trigger('input');
            }
        });
    });
}
function isNullOrNan(val){
    let v=parseFloat(val);

    if(v=='' || v==null || isNaN(v)){
        return 0;
    }else{
        return v;
    }
}

// On document ready
KTUtil.onDOMContentLoaded(function () {
    roomsale.init();
});
