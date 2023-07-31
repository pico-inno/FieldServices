<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('bed.update', $bed->id)}}" method="POST" id="edit_bed_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Bed</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-12">
                        <label for="room_id" class="required form-label">Room</label>
                        <select name="room_id" id="room_id" class="form-select form-select-sm fs-7 bed-select2" data-control="select2" data-placeholder="Please select" required>
                           <option></option>
                            @php
                                $rooms = \Modules\RoomManagement\Entities\Room::all();
                            @endphp

                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', $bed->room_id) == $room->id ? 'selected' : ''}}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <label for="bed_type_id" class="required form-label">Bed Type</label>
                        <select name="bed_type_id" id="bed_type_id" class="form-select form-select-sm fs-7 bed-select2" data-control="select2" data-placeholder="Please select" required>
                            <option></option>
                            @php
                                $bed_types = \Modules\RoomManagement\Entities\BedType::all();
                            @endphp

                            @foreach($bed_types as $bed_type)
                                <option value="{{ $bed_type->id }}" {{ old('bed_type_id', $bed->bed_type_id) == $bed_type->id ? 'selected' : ''}}>
                                    {{ $bed_type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="flexCheckCheckedEdit" class="form-check-input" value="{{ $bed->is_active }}" {{ old('is_active', $bed->is_active) == 'Active' ? 'checked' : ''}} />
                            <label class="form-check-label" for="flexCheckCheckedEdit">
                                Is Active
                            </label>
                        </div>
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

<script type="text/javascript">
    $('.bed-select2').select2();
</script>