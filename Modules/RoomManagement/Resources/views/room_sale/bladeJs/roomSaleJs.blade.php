<script>
    let room_rates=@json($room_rates ?? []) ;
    let rooms=@json($rooms ?? []) ;
    let typeFromRequest=@json(request('type'));
    let idFromRequest=@json(request('id'));
    if(typeFromRequest){
        $('#transactionType').val(typeFromRequest).trigger('change');
        setTimeout(() => {

        $('.transaction_id').val(idFromRequest).trigger('change');
        }, 200);
    }
    let rates;
    __init();

    var uniqueIndex= $('#room_added_table tbody tr').length ?? 0;
    $(document).on('click', '#add_room_row', function() {
        appendRow();
        initializeDialer();

        if ($('#room_added_table tbody tr').length > 1) {
            $('#delete_room_row').removeClass('disable');
            $('#delete_room_row').css({
                'cursor': 'pointer',
                'opacity': 1
            });
        }

        var numOfRooms = $('#room_added_table tbody tr').length;

        $('.room-qty').val(numOfRooms);
        uniqueIndex++;
        __init();
    });

    function getReservationDetails(selectElement) {
        let reservationId = selectElement.value;
        if (reservationId) {
            let selectedReservation = {!! $reservations !!}.find(reservation => reservation.id == reservationId);
            if (selectedReservation) {
                $('#guest_id').empty();

                var fullName = '';
                var companyName = '';
                if(selectedReservation.contact) {
                    if (selectedReservation.contact.prefix) {
                        fullName += selectedReservation.contact.prefix;
                    }
                    if (selectedReservation.contact.first_name) {
                        fullName += ' ' + selectedReservation.contact.first_name;
                    }
                    if (selectedReservation.contact.middle_name) {
                        fullName += ' ' + selectedReservation.contact.middle_name;
                    }
                    if (selectedReservation.contact.last_name) {
                        fullName += ' ' + selectedReservation.contact.last_name;
                    }
                }

                if(selectedReservation.company) {
                    companyName += selectedReservation.company.company_name;
                }

                let option = $('<option>');

                if (fullName) {
                    option.val(selectedReservation.guest_id).text(fullName).prop('selected', true);
                } else if (companyName) {
                    option.val(selectedReservation.company_id).text(companyName).prop('selected', true);
                }

                // let option = $('<option>').val(selectedReservation.guest_id).text(fullName).prop('selected', true);

                $('#guest_id').append(option).trigger('change');
                $('#check_in_date').val(formatDate(selectedReservation.check_in_date));
                $('#check_out_date').val(formatDate(selectedReservation.check_out_date));


                // append rows that are registered in resveration
                let roomResveration=selectedReservation.room_reservations;
                if(roomResveration.length>0){
                    $('#room_added_table tbody tr').remove();
                    roomResveration.forEach(function(r,index){
                        appendRow(index);
                        __init();
                        $(`[data_${index}="roomType"]`).val(r.room_type.id).trigger('change');
                        setTimeout(() => {
                            $(`[data_${index}="roomId"]`).val(r.room.id).trigger('change');
                            $(`[data_${index}="roomRate"]`).val(r.room_rate.id).trigger('change');
                            $(`[data_${index}="check_in_date"]`).val(formatDate(r.room_check_in_date)).trigger('change');
                            $(`[data_${index}="check_out_date"]`).val(formatDate(r.room_check_out_date)).trigger('change');
                        }, 100);
                    })
                }

            }
        }
    }

    function getRegistrationDetails(selectElement) {
        let registrationId = selectElement.value;
        if (registrationId) {
            var selectedRegistration = {!! $registrations !!}.find(registration => registration.id == registrationId);
            if (selectedRegistration) {
                $('#patient_id').empty();

                var fullName = '';
                if (selectedRegistration.patient.prefix) {
                    fullName += selectedRegistration.patient.prefix;
                }
                if (selectedRegistration.patient.first_name) {
                    fullName += ' ' + selectedRegistration.patient.first_name;
                }
                if (selectedRegistration.patient.middle_name) {
                    fullName += ' ' + selectedRegistration.patient.middle_name;
                }
                if (selectedRegistration.patient.last_name) {
                    fullName += ' ' + selectedRegistration.patient.last_name;
                }

                var option = $('<option>').val(selectedRegistration.patient_id).text(fullName).prop('selected', true);

                $('#patient_id').append(option).trigger('change');
                $('#check_in_date').val(formatDate(selectedRegistration.ipd_check_in_date));
                $('#check_out_date').val(formatDate(selectedRegistration.check_out_date));



                // append rows that are registered in resveration
                let roomRegistrations=selectedRegistration.hospital_room_registrations;
                if(roomRegistrations.length>0){
                    $('#room_added_table tbody tr').remove();
                    roomRegistrations.forEach(function(r,index){
                        appendRow(index);
                        __init();
                        $(`[data_${index}="roomType"]`).val(r.room_type.id).trigger('change');
                        setTimeout(() => {
                            $(`[data_${index}="roomId"]`).val(r.room.id).trigger('change');
                            $(`[data_${index}="roomRate"]`).val(r.rate.id).trigger('change');
                            $(`[data_${index}="check_in_date"]`).val(formatDate(r.check_in_date)).trigger('change');
                            $(`[data_${index}="check_out_date"]`).val(formatDate(r.check_out_date)).trigger('change');
                        }, 100);
                    })
                }

            }
        }
    }



    function __init() {
        $('.room_type').on('change',function () {
            let parent = $(this).closest('.room_sales');
            let room_select=parent.find('.room_select');
            let room_rate=parent.find('.room_rate');

            let room_type_id=$(this).val();
            let room=rooms.filter(function(room){
                return room.room_type_id==room_type_id;
            });

            rates=room_rates.filter(function(rate){
                return rate.room_type_id==room_type_id;
            });

            // for  room
            let data= room.map(function(r) {
                return {id:r.id,text:r.name};
            })

            let rate_data= rates.map(function(rate) {
                return {id:rate.id,text:rate.rate_name};
            })
            room_select.empty();

            data=[{id:"",text:"select room"},...data]
            room_select.select2({
                data
            })
            let rs=document.getElementsByClassName('room_select');
            rs.forEach(function(e){
                room_select.find('option[value="' + $(e).val() + '"]').prop('disabled', true);
            })


            // for room rate
            room_rate.empty();
            room_rate.select2({
                data:rate_data
            })
            let roomFees=parent.find('[name="room_fees[]"]');
            if(rates[0]){
                roomFees.val(rates[0].rate_amount ?? 0);
            }else{
                roomFees.val(0);
            }

            calSubTotalAndDisc(parent);



        })


        $('.room_rate').on('change',function(){
            let parent = $(this).closest('.room_sales');
            let roomFees=parent.find('[name="room_fees[]"]');
            let room_rate_id=$(this).val();
            let room_type_id=parent.find('.room_type').val();
            let rates=room_rates.filter(function(rate){
                return rate.room_type_id==room_type_id;
            });
            let e= rates.filter(function(v){
                return v.id==room_rate_id;
             })
            roomFees.val(e[0].rate_amount ?? 0);
            calSubTotalAndDisc(parent);

        })
    }

    function appendRow(uniqueId='') {
        var newRoomRow = `
                <tr id="new_row" class="room_sales">
                    <td>
                        <select name="room_type_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_type" data_${uniqueId}="roomType" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                                $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                            @endphp
                            @foreach($room_types as $room_type)
                                <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="room_rate_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_rate" data_${uniqueId}="roomRate" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                                $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                            @endphp
                            @foreach($room_rates as $room_rate)
                                <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}">{{ $room_rate->rate_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="room_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_select" data_${uniqueId}="roomId" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                                $rooms = \Modules\RoomManagement\Entities\Room::all();
                            @endphp

                        </select>
                    </td>
                    <td>
                        <input type="text" name="check_in_date[]" class="room_check_in_date form-control form-control-sm rounded-0 fs-7" data_${uniqueId}="check_in_date" placeholder="Select date" autocomplete="off" />
                    </td>
                    <td>
                        <input type="text" name="check_out_date[]" class="room_check_out_date form-control form-control-sm rounded-0 fs-7" data_${uniqueId}="check_out_date"  placeholder="Select date" autocomplete="off" />
                    </td>
                    <td>
                        <!--begin::Dialer-->
                        <div class="input-group w-md-150px w-sm-300px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="50000" data-kt-dialer-step="1">

                            <!--begin::Decrease control-->
                            <button class="btn btn-icon btn-outline btn-active-color-primary btn-sm" type="button" data-kt-dialer-control="decrease">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <!--end::Decrease control-->

                            <!--begin::Input control-->
                            <input type="number" name="qty[]" class="form-control form-control-sm rounded-0 fs-7" value="1" min="1" max="5000" data-kt-dialer-control="input" placeholder="Amount" readonly />
                            <!--end::Input control-->

                            <!--begin::Increase control-->
                            <button class="btn btn-icon btn-outline btn-active-color-primary btn-sm" type="button" data-kt-dialer-control="increase">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            <!--end::Increase control-->
                        </div>
                        <!--end::Dialer-->
                    </td>
                    <td>
                        <select name="uoms[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            <option class="day">Day</option>
                        </select>
                        <input type="hidden" name="currencies[]">
                    </td>
                    <td>
                        <input type="number" name="room_fees[]" id="rate_amout_bef_discount" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" readonly />
                    </td>
                    <td>
                        <input type="number" name="subtotal[]" id="subtotal" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" readonly />
                    </td>
                    <td>
                        <select name="discount_type[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                            {{-- <option></option> --}}
                            <option value="fixed">Fixed</option>
                            <option value="percentage" selected>Percentage</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="per_item_discount[]" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" />
                        <input type="hidden" name="total_line_discount_amount[]" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" />
                    </td>
                    <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
            `;

        $('#room_added_table tbody').append(newRoomRow);

        // var container = $('.table-responsive');
        // var scrollTo = $('#new_row');
        // container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());

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


        $('#room_added_table tbody select[data-control="select2"]').select2();

    }
    function formatDate(dateString) {
        const date = new Date(dateString);

        const options = {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };

        return date.toLocaleString('en-GB', options);
    }
</script>
