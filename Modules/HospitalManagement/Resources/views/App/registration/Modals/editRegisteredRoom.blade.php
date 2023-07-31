
<div class="modal-dialog  modal-xl">
    <div class="modal-content" id="hospital_registration">
        <form action="{{route('registrationRoomInfoUpdate',$id)}}" method="POST" id="hospital_registration_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title"> Edit Registered Room </h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed fs-6 gy-4" id="room_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-600 fs-6">
                                <th class="min-w-200px">Room Type</th>
                                <th class="min-w-200px">Room Rate</th>
                                <th class="min-w-200px">Room</th>
                                <th class="min-w-200px">Date</th>
                                <th class="text-center "><i class="fa-solid fa-trash text-danger"></i></th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                                    @foreach ($roomDatas as $key=>$roomData)
                                        <input type="hidden" id="registration_type" value="IPD">
                                        <tr class=" input_group">
                                            <input type="hidden" name="roomRegistrationData[{{$key}}][room_sale_detail_id]" value="{{$roomData->id}}">
                                            <td class="fv-row">
                                                <select  name="roomRegistrationData[{{$key}}][room_type_id]" class="form-select form-select-sm room_type_select room_type " data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $room_types = \App\Models\RoomManagement\RoomType::all();
                                                    @endphp
                                                    @foreach($room_types as $room_type)
                                                        <option value="{{ $room_type->id}}" @selected($roomData->room_type_id==$room_type->id)>{{ $room_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <select name="roomRegistrationData[{{$key}}][room_rate_id]"  class="form-select form-select-sm  room_rate"  data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                        @php
                                                            $room_rates = \App\Models\RoomManagement\RoomRate::all();
                                                            $room_rates_by_type = \App\Models\RoomManagement\RoomRate::where('room_type_id',$roomData->room_type_id)->get();
                                                        @endphp
                                                        @foreach($room_rates_by_type as $room_rate)
                                                            <option value="{{ $room_rate->id}}" @selected($room_rate->id==$roomData->room_rate_id)>{{ $room_rate->rate_name }}</option>
                                                        @endforeach
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <select name="roomRegistrationData[{{$key}}][room_id]" class="form-select  fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $rooms = \App\Models\RoomManagement\Room::where('status',"Available")->select('id','room_type_id','status','name')->get();
                                                        $rooms_by_type = \App\Models\RoomManagement\Room::where('room_type_id',$roomData->room_type_id)->select('id','room_type_id','status','name')->get();
                                                    @endphp
                                                    @foreach($rooms_by_type as $room)
                                                        <option value="{{ $room->id}}"  @selected($room->id==$roomData->room_id)>{{ $room->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <div class="d-flex flex-column">
                                                    <input type="text" name="roomRegistrationData[{{$key}}][check_in_date]"  data-flat-date="true"  id="edit_check_in_date" class="form-control form-control-sm mb-3 edit_check_in_date" placeholder="Check In Date" autocomplete="off"  value="{{$roomData['check_in_date']}}" />
                                                    <input type="text" name="roomRegistrationData[{{$key}}][check_out_date]" data-flat-date="true"  id="edit_check_out_date" class="form-control form-control-sm" placeholder="Check Out Date" autocomplete="off"  value="{{$roomData['check_out_date']}}" />
                                                </div>
                                            </td>
                                            <td class="text-end fv-row">
                                                <button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                    <div class="row mb-8">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-light-primary me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-light-danger disable" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
                        {{-- <div class="payment mt-10">
                            <div class="row">
                                <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                    <span class="min-w-200px" for="">
                                            Total Amount:(=)Ks
                                    </span>
                                    <input type="text" class="form-control form-control-sm input_number max-w-100px" name="roomSale[total_amount]" id="total_amount" value="{{old('roomSale[total_amount]',$roomSaleData['total_amount'])}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        Discount Type
                                    </label>
                                    <select name="roomSale[discount_type]" id="total_discount_type" class="form-select form-select-sm" data-control="select2">
                                        <option value="fixed" @selected(old('total_discount_type',$roomSaleData['discount_type'])=="fixed")>Fixed</option>
                                        <option value="percentage" @selected(old('total_discount_type',$roomSaleData['discount_type'])=="percentage")>Percentage</option>
                                    </select>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        Discount Amount
                                    </label>
                                    <input type="text" class="form-control input_number form-control-sm" id="total_discount_amount"  name="roomSale[discount_amount]" value="{{$roomSaleData['discount_amount']}}">
                                </div>
                                <div class="fs-6 fw-semibold col-12 col-md-4 d-flex justify-content-between align-items-center">
                                    <span class="min-w-200px pe-2" for="">
                                            Discount :(=)Ks
                                    </span>
                                    <input type="text" class="form-control form-control-sm  input_number max-w-100px" id="discount" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                    <span class="min-w-200px pe-2" for="">
                                            Total Sale Amount:(=)Ks
                                    </span>
                                    <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[total_sale_amount]" id="balance_amount" value="{{$roomSaleData['total_sale_amount']}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                    <span class="min-w-200px pe-2" for="">
                                            Paid Amount:(=)Ks
                                    </span>
                                    <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[paid_amount]" id="balance_amount" value="{{old('balance_amount',$roomSaleData['paid_amount'])}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                    <span class="min-w-200px pe-2" for="">
                                            Balance Amount:(=)Ks
                                    </span>
                                    <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[balance_amount]" id="balance_amount" value="{{old('balance_amount',$roomSaleData['balance_amount'])}}">
                                </div>
                            </div>
                        </div> --}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-kt-hosptial-action="submit">Save</button>
            </div>
        </form>
    </div>
</div>


@include('App.Js.registrationJs')
<script>
    $('[data-control="select2"]').select2();
    var uniqueName= $('#room_table tbody tr').length ?? 0;
        $(document).on('click', '#add_room_row', function() {
            // console.log('click');
            var newRoomRow = `

                <tr class="new_row  input_group">
                    <td class="fv-row">
                        <select  name="roomRegistrationData[${uniqueName}][room_type_id]" class="form-select form-select-sm room_type_select room_type " data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                                $room_types = \App\Models\RoomManagement\RoomType::all();
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
                            <input type="text" name="roomRegistrationData[${uniqueName}][check_in_date]" id="edit_check_in_date" data-flat-date="true" class="form-control form-control-sm mb-3 edit_check_in_date" placeholder="Check In Date" autocomplete="off"  value="" />
                            <input type="text" name="roomRegistrationData[${uniqueName}][check_out_date]" id="edit_check_out_date" data-flat-date="true" class="form-control form-control-sm edit_check_out_date" placeholder="Check Out Date" autocomplete="off"  value="" />
                        </div>
                    </td>
                    <td class="text-end ">
                        <button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `;

            $('#room_table tbody').append(newRoomRow);

        __init();
            // var container = $('.table-responsive');
            // var scrollTo = $('#new_row');
            // container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());

            $('#room_table tbody select[data-control="select2"]').select2();

            if ($('#room_table tbody tr').length > 1) {
                $('#delete_room_row').removeClass('disable');
                $('#delete_room_row').css({
                    'cursor': 'pointer',
                    'opacity': 1
                });
            }
            $('[data-flat-date="true"]').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            var numOfRooms = $('#room_table tbody tr').length;
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
        __init();

</script>
<script src="{{asset('customJs/hospital/validation.js')}}"></script>
