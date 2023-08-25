<div class="modal modal-lg fade" tabindex="-1" id="add_bed_type_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('bed-type.store')}}" method="POST" id="add_bed_type_form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add Bed Type</h3>

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