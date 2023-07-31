
<script>

        var uniqueName= $('#room_table tbody tr').length ?? 0;
        $(document).on('click', '#add_room_row', function() {
            // console.log('click');
            var numOfRooms = $('#room_table tbody tr').length;
            var newRoomRow = `
                <tr class="new_row  input_group">
                    <td class="fv-row">
                        <select  name="roomRegistrationData[${uniqueName}][room_type_id]" class="form-select form-select-sm room_type_select room_type " data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                                $room_types = Modules\RoomManagement\Entities\RoomType::all();
                            @endphp
                            @foreach($room_types as $room_type)
                            <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="fv-row">
                        <select name="roomRegistrationData[${uniqueName}][room_rate_id]"  class="form-select form-select-sm  room_rate"  data-control="select2" data-placeholder="Please select">
                            <option></option>
                        </select>
                    </td>
                    <td class="fv-row">
                        <select name="roomRegistrationData[${uniqueName}][room_id]" class="form-select  fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select">
                            <option></option>
                        </select>
                    </td>
                    <td   class="fv-row">
                        <div class="d-flex flex-column  class="fv-row"">
                            <input type="text" name="roomRegistrationData[${uniqueName}][check_in_date]" id="edit_check_in_date" class="form-control form-control-sm mb-3 edit_check_in_date" placeholder="Check In Date" autocomplete="off"  value="" />
                            <input type="text" name="roomRegistrationData[${uniqueName}][check_out_date]" id="edit_check_out_date" class="form-control form-control-sm edit_check_out_date" placeholder="Check Out Date" autocomplete="off"  value="" />
                        </div>
                    </td>
                    <td class="text-end ">
                        <button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `;

            $('#room_table tbody').append(newRoomRow);
            __init();
            $('#room_table tbody select[data-control="select2"]').select2();

            // var datepickerIds = [ "edit_check_in_date","edit_check_out_date"];
            $('.edit_check_in_date,.edit_check_out_date').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
            })
            // datepickerIds.forEach(function(id) {
            //     $(`.${id}`).flatpickr({
            //         enableTime: true,
            //         dateFormat: "Y-m-d H:i",
            //     });
            // });

            if ($('#room_table tbody tr').length > 1) {
                $('#delete_room_row').removeClass('disable');
                $('#delete_room_row').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }
            // console.log(numOfRooms);

            $('.room-qty').val(numOfRooms);
            uniqueName++;
        });

        $(document).on('click', '#delete_room_row', function() {
            // console.log('delete row');
            if ($('#room_table tbody tr').length == 1 || $(this).hasClass('disable')) {
                $(this).css({
                    'cursor': 'not-allowed',
                    'opacity': 0.5
                });
                event.preventDefault();
                return false;
            }

            let parent =$(this).closest('.row').prev('.table-responsive').find('#room_table tbody tr:last .room_select').closest('.input_group');
            let room_select=parent.find('.room_select');
            $('.room_select').find('option[value="' + room_select.val() + '"]').prop('disabled', false);

            $(this).closest('.row').prev('.table-responsive').find('#room_table tbody tr:last').remove();
            var numOfDeletedRooms = $('#room_table tbody tr').length;
            // console.log(numOfDeletedRooms);
            $('.room-qty').val(numOfDeletedRooms);
        });

        $(document).on('click', '#delete_each_room_row', function() {
            let rowLength=$('#room_table tbody tr').length;
            if (rowLength == 1 || $(this).hasClass('disable')) {
                $(this).css({
                    'cursor': 'not-allowed',
                    'opacity': 0.5
                });
                event.preventDefault();
                return false;
            }
            let parent = $(this).closest('.input_group');
            let room_select=parent.find('.room_select');
            $('.room_select').find('option[value="' + room_select.val() + '"]').prop('disabled', false);

            $(this).closest('tr').remove();

            var numOfDeletedRooms = $('#room_table tbody tr').length;
            // console.log(numOfDeletedRooms);
            $('.room-qty').val(numOfDeletedRooms);




        });
</script>
