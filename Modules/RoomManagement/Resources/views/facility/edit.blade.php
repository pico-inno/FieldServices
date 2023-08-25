<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('facility.update', $facility->id)}}" method="POST" id="edit_facility_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Facility</h3>

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
                        <input type="text" name="name" id="name" value="{{ old('name', $facility->name) }}" class="form-control form-control-sm fs-7" placeholder="Name" required />
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <label for="building_id" class="required form-label">Building</label>
                        <select name="building_id" id="building_id" class="form-select form-select-sm fs-7 facility-select2" data-control="select2" data-placeholder="Please select" required>
                            <option></option>
                            @php
                                $buildings = \App\Models\settings\Building::all();
                            @endphp

                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ old('building_id', $facility->building_id) == $building->id ? 'selected' : ''}}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="flexCheckCheckedEdit" value="{{ $facility->is_active }}" class="form-check-input" {{ old('is_active', $facility->is_active) == 'Active' ? 'checked' : ''}} />
                            <label class="form-check-label" for="flexCheckCheckedEdit">
                                Is Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm fs-7" placeholder="Description" style="resize: none;">{{ old('description', !empty($facility->description) ? $facility->description : null) }}</textarea>
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
    $('.facility-select2').select2();
</script>