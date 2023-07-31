"use strict";

var reservation = function () {
    $('.custom-select2').select2();

    function storeOriginalOptions(roomSelect) {
        var originalOptions = roomSelect.html();
        roomSelect.data('original-options', originalOptions);
    }

    $('#room_added_table select[name="room_id[]"]').each(function () {
        storeOriginalOptions($(this));
    });

    // Add event listener to handle dynamically added rows
    $(document).on('DOMNodeInserted', function (e) {
        if ($(e.target).is('tr') && $(e.target).parents('#room_added_table').length) {
            var newRow = $(e.target);
            var roomSelect = newRow.find('select[name="room_id[]"]');
            storeOriginalOptions(roomSelect);
        }
    });

    $(document).on('select2:select', '#room_added_table select[name="room_type_id[]"]', function () {
        var selectedRoomType = $(this).val();
        var rowIndex = $(this).closest('tr').index();
        var roomSelect = $('select[name="room_id[]"]').eq(rowIndex);
        var originalOptions = roomSelect.data('original-options');

        roomSelect.html(originalOptions);

        roomSelect.find('option').each(function () {
            var roomType = $(this).data('room-type-id');
            var roomStatus = $(this).data('room-status');

            if (roomType != selectedRoomType || roomStatus !== 'Available') {
                $(this).remove();
            }
        });

        roomSelect.val('');

        $('select[name="room_rate_id[]"]').eq(rowIndex).find('option').each(function () {
            var roomType = $(this).data('room-type-id');
        
            if (selectedRoomType === "" || roomType == selectedRoomType) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    });


    $(document).on('select2:select', '#room_added_table select[name="room_rate_id[]"]', function () {
        var currentRow = $(this).closest('tr');
        var rateAmountBefInput = currentRow.find('input[name="before_discount_amount[]"]');
        var rateAmountAfterInput = currentRow.find('input[name="after_discount_amount[]"]');
        var subTotalInput = currentRow.find('input[name="subtotal_amount[]"]');
        var selectedRoomRate = $(this).find('option:selected');
        var rateAmountBefDiscount = parseInt(selectedRoomRate.data('rate-amount'));
        var rateAmountAfterDiscount = parseInt(selectedRoomRate.data('rate-amount'));
        var subTotal = parseInt(selectedRoomRate.data('rate-amount'));
        var discountType = currentRow.find('select[name="discount_type[]"]').val();
        var discountAmount = currentRow.find('input[name="discount_amount[]"]').val();

        rateAmountBefInput.val(rateAmountBefDiscount).trigger('change');
        rateAmountAfterInput.val(rateAmountAfterDiscount).trigger('change');
        subTotalInput.val(subTotal).trigger('change');
        // subTotalInput.val(subTotal).trigger('change');

        if (discountType == 'fixed') {
            var amountIncDiscount = rateAmountBefDiscount - discountAmount;
            rateAmountAfterInput.val(amountIncDiscount).trigger('change');
            subTotalInput.val(amountIncDiscount).trigger('change');
        } else {
            var percentageDiscount = rateAmountBefDiscount * (discountAmount / 100);
            var amountIncDiscount = rateAmountBefDiscount - percentageDiscount;
            rateAmountAfterInput.val(amountIncDiscount).trigger('change');
            subTotalInput.val(amountIncDiscount).trigger('change');
        }
    });

    $(document).on('click', '#delete_room_row', function (event) {
        // console.log('delete row');
        if ($('#room_added_table tbody tr').length == 1 || $(this).hasClass('disable')) {
            $(this).css({
                'cursor': 'not-allowed',
                'opacity': 0.5
            });
            event.preventDefault();
            return false;
        }

        $(this).closest('.row').prev('.table-responsive').find('#room_added_table tbody tr:last').remove();

        var numOfDeletedRooms = $('#room_added_table tbody tr').length;
        // console.log(numOfDeletedRooms);

        $('.room-qty').val(numOfDeletedRooms);
    });

    $(document).on('click', '#delete_each_room_row', function (event) {
        // console.log('delete row');
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
        // console.log(numOfDeletedRooms);

        $('.room-qty').val(numOfDeletedRooms);
    });

    $('#check_in_date, #check_out_date').on('input change blur', function (e) {
        var check_in_date = $('#check_in_date').val();
        var check_out_date = $('#check_out_date').val();
        // console.log(checkin_date);
        // console.log(checkout_date);
        $('.room_check_in_date').val(check_in_date);
        $('.room_check_out_date').val(check_out_date);
    });

    $('.add_contact_button').click(function() {
        var formType = $(this).data('form-type');
        var reservationId = $(this).data('reservation-id');
        $('#reservation_id_input').val(reservationId);

        console.log(reservationId);
        console.log(formType);
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
            initializeDialer();
        }
    };
}();


// $(document).on('select2:select', '#room_added_table select[name="room_id[]"]', function() {      
//     var currentRow = $(this).closest('tr');
//     var maxOccupancyInput = currentRow.find("#max_occupancy");
//     var selectedRoom = $(this).find('option:selected');
//     var maxOccupancy = selectedRoom.data('max-occupancy');
//     // console.log(maxOccupancy);
//     maxOccupancyInput.val(maxOccupancy).trigger('change');
// });

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

function initializeDialer() {
    $('.input-group[data-kt-dialer="true"]').each(function () {
        var inputControl = $(this).find('input[data-kt-dialer-control="input"]');
        var decreaseControl = $(this).find('button[data-kt-dialer-control="decrease"]');
        var increaseControl = $(this).find('button[data-kt-dialer-control="increase"]');

        decreaseControl.on('click', function () {
            var currentValue = parseInt(inputControl.val());
            // console.log(currentValue);
            if (currentValue > inputControl.attr('min')) {
                inputControl.val(currentValue - 1);
                inputControl.trigger('input');
            }
        });

        increaseControl.on('click', function () {
            var currentValue = parseInt(inputControl.val());
            // console.log(currentValue);
            if (currentValue < inputControl.attr('max')) {
                inputControl.val(currentValue + 1);
                inputControl.trigger('input');
            }
        });
    });
}

// On document ready
KTUtil.onDOMContentLoaded(function () {
    reservation.init();
});