@php
$currentDate = date('d/m/Y, h:i A');
$room_reservations = \Modules\Reservation\Entities\RoomReservation::where('reservation_id', $reservation->id)->get();
@endphp
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('updateReservedRoom', $reservation->id)}}" method="POST" id="editReservedRoomForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Reserved Rooms</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <div class="table-responsive mb-4">
                    <table class="table table-row-dashed fs-6 gy-4" id="room_added_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-600">
                                <th>Room Type</th>
                                <th>Room Rate</th>
                                <th>Room</th>
                                <th class="min-w-150px">Checkin Date</th>
                                <th class="min-w-150px">Checkout Date</th>
                                <th><i class="fa-solid fa-trash text-danger"></i></th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @foreach($reservation->room_reservations as $room_reservation)
                            <tr>
                                <td>
                                    <select name="room_type_id[]" class="form-select rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select">
                                        <option></option>
                                        @php
                                        $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                        @endphp
                                        @foreach($room_types as $room_type)
                                        <option value="{{ $room_type->id}}" {{ old('$room_reservation->room_type_id ', $room_reservation->room_type_id ) == $room_type->id ? 'selected' : ''}}>
                                            {{ $room_type->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="room_rate_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                        <option></option>
                                        @php
                                        $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                                        @endphp
                                        @foreach($room_rates as $room_rate)
                                        <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}" {{ old('$room_reservation->room_rate_id ', $room_reservation->room_rate_id ) == $room_rate->id ? 'selected' : ''}}>
                                            {{ $room_rate->rate_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                        <option></option>
                                        @php
                                        $rooms = \Modules\RoomManagement\Entities\Room::all();
                                        @endphp
                                        @foreach($rooms as $room)
                                        <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}" {{ old('$room_reservation->room_id ', $room_reservation->room_id ) == $room->id ? 'selected' : ''}}>
                                            {{ $room->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="room_check_in_date[]" value="{{ old('room_check_in_date', !empty($room_reservation->room_check_in_date) ? date('d/m/Y, h:i A', strtotime($room_reservation->room_check_in_date)) : $currentDate) }}" class="room_check_in_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td>
                                    <input type="text" name="room_check_out_date[]" value="{{ old('room_check_out_date', !empty($room_reservation->room_check_out_date) ? date('d/m/Y, h:i A', strtotime($room_reservation->room_check_out_date)) : $currentDate) }}" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                            </tr>
                            @endforeach 
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <div class="row mb-8">
                    <div class="d-flex">
                        <button type="button" class="btn btn-light-primary btn-sm me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-light-danger btn-sm disable delete_room_row" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="{{asset('customJs/reservation.js')}}"></script>
<script>
    var reservationData = {!! json_encode($reservation) !!};
    var roomReservations = {!! json_encode($room_reservations) !!};

    setInitialValues();

    function setInitialValues() {
        roomReservations.forEach(function(roomReservation, index) {
            var roomType = roomReservation.room_type_id;
            var roomRate = roomReservation.room_rate_id;
            var room = roomReservation.room_id;

            // Set the selected room type
            $('select[name="room_type_id[]"]').eq(index).val(roomType).trigger('change');

            // Filter room rate options based on the selected room type
            filterRoomRates(index, roomType);

            // Set the selected room rate
            $('select[name="room_rate_id[]"]').eq(index).val(roomRate).trigger('change');

            // Filter room options based on the selected room type
            filterRooms(index, roomType);

            // Set the selected room
            // $('select[name="room_id[]"]').eq(index).val(room).trigger('change');
            var roomSelect = $('select[name="room_id[]"]').find('option:selected').data('max-occupancy');
            // console.log(roomSelect);
            // Set the max occupancy value
            var maxOccupancy = $('select[name="room_id[]"]').eq(index).find('option:selected').data('max-occupancy');
            $('.max_occupancy').eq(index).val(maxOccupancy).trigger('change');

        });
        $('.room-qty').val(roomReservations.length);
    }

    function filterRoomRates(rowIndex, selectedRoomType) {
        $('select[name="room_rate_id[]"]').eq(rowIndex).find('option').each(function() {
            var roomType = $(this).data('room-type-id');

            if (selectedRoomType === "" || roomType == selectedRoomType) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }

    function filterRooms(rowIndex, selectedRoomType) {
        $('select[name="room_id[]"]').eq(rowIndex).find('option').each(function() {
            var roomType = $(this).data('room-type-id');

            if (selectedRoomType === "" || roomType == selectedRoomType) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }
    var firstCheckInInput = checkInInputs[0];
    var firstCheckOutInput = checkOutInputs[0];

    var firstRowCheckInPicker = new tempusDominus.TempusDominus(firstCheckInInput, {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy, hh:mm T",
        },
    });

    var firstRowCheckOutPicker = new tempusDominus.TempusDominus(firstCheckOutInput, {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy, hh:mm T",
        },
    });
    $(document).on('click', '#add_room_row', function() {
        var newRoomRow = `
            <tr id="new_row">
                <td>
                    <select name="room_type_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
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
                    <select name="room_rate_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
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
                    <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                        <option></option>
                        @php
                            $rooms = \Modules\RoomManagement\Entities\Room::all();
                        @endphp
                        @foreach($rooms as $room)
                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}">{{ $room->name }}</option>
                        @endforeach       
                    </select>
                </td>
                <td>
                    <input type="text" name="room_check_in_date[]" class="room_check_in_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                </td>
                <td>
                    <input type="text" name="room_check_out_date[]" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                </td>
                <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `;

        $('#room_added_table tbody').append(newRoomRow);

        // Initialize the date pickers for the new row
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

        if ($('#room_added_table tbody tr').length > 1) {
            $('#delete_room_row').removeClass('disable');
            $('#delete_room_row').css({
                'cursor': 'pointer',
                'opacity': 1
            });
        }
    });
</script>
