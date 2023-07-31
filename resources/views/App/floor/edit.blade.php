<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('floor.update', $floor->id)}}" method="POST" id="edit_floor_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Floor</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-12">
                        <label for="name" class="required form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name', $floor->name)}}" class="form-control form-control-sm fs-7" placeholder="Name" required />
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <label for="business_location_id" class="required form-label">Business Location</label>
                        <select name="business_location_id" id="business_location_id" class="form-select form-select-sm fs-7 floor-select2" data-control="select2" data-placeholder="Please select" required>
                            @php
                                $business_locations = \App\Models\settings\businessLocation::all();
                            @endphp

                            @foreach($business_locations as $business_location)
                                <option value="{{ $business_location->id }}" {{ old('business_location_id', $floor->business_location_id) == $business_location->id ? 'selected' : ''}}>
                                    {{ $business_location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="building_id" class="required form-label">Building</label>
                        <select name="building_id" id="building_id" class="form-select form-select-sm fs-7 floor-select2" data-control="select2" data-placeholder="Please select" required>
                            @php
                                $buildings = \App\Models\settings\Building::all();
                            @endphp

                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ old('building_id', $floor->building_id) == $building->id ? 'selected' : ''}}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
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
    $('.floor-select2').select2();
</script>