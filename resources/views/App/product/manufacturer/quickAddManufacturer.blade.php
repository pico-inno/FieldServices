<div class="modal fade" tabindex="-1" id="kt_modal_manufacturer">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- <form action="{{ route('manufacturer.create') }}" method="POST">
                @csrf --}}
                <div class="modal-header">
                    <h3 class="modal-title">Add Manufacturer</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="required form-label">Manufacturer Name</label>
                        <input type="text" name="manufacturer_name" class="form-control" placeholder="Name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary quick-add-manufacturer">Save changes</button>
                </div>
            {{-- </form> --}}
        </div>
    </div>
</div>