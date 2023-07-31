<div class="modal modal-lg fade" tabindex="-1" id="add_facility_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('facility.store')}}" method="POST" id="add_facility_form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add Facility</h3>

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
                            <label for="building_id" class="required form-label">Building</label>
                            <select name="building_id" id="building_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                <option></option>
                                @php
                                    $buildings = \App\Models\settings\Building::all();
                                @endphp
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}">{{ $building->name }}</option>
                                @endforeach
                            </select>    
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="Active" id="flexCheckChecked" checked />
                                <label class="form-check-label" for="flexCheckChecked">
                                    Is Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control form-control-sm fs-7" placeholder="Description" style="resize: none;"></textarea>
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