<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('building.update', $building->id)}}" method="POST" id="edit_building_form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Building</h3>

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
                        <input type="text" name="name" id="name" value="{{old('name',$building->name)}}" class="form-control form-control-sm fs-7" placeholder="Name" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm fs-7" placeholder="Description" style="resize: none;">{{ old('description',!empty($building->description) ? $building->description : null) }}</textarea>
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