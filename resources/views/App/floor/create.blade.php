<div class="modal modal-lg fade" tabindex="-1" id="add_floor_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('floor.store')}}" method="POST" id="add_floor_form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add Floor</h3>

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
                            <input type="text" name="name" id="name" class="form-control form-control-sm fs-7" placeholder="Name" required />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-12">
                            <label for="business_location_id" class="required form-label">Business Location</label>
                            <select name="business_location_id" id="business_location_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                <option value="" selected disabled>Please select</option>
                                @php
                                    $business_locations = \App\Models\settings\businessLocation::all();
                                @endphp
                                @foreach($business_locations as $business_location)
                                    <option value="{{ $business_location->id }}">{{ $business_location->name }}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="building_id" class="required form-label">Building</label>
                            <select name="building_id" id="building_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                <option value="" selected disabled>Please select</option>
                                @php
                                    $buildings = \App\Models\settings\Building::all();
                                @endphp
                                @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>