
<div class="modal-dialog  ">
    <div class="modal-content">
        <form action="{{route('restaurant.tableStore')}}" method="POST" id="">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Create Table</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-md-12 mb-5">
                            <input class="form-control form-control border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 fs-4" name="table_no" placeholder="Enter Table No"
                                id="table_no"
                                value=""/>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="seats" class=" form-label">Seats</label>
                        <input type="number" name="seats" id="seats" class="form-control form-control-sm" value="" placeholder="">
                    </div>
                    <div class="col-6 mb-5">
                        <label for="description" class=" form-label">Description</label>
                        <textarea name="description" id="description" cols="10" rows="3" class="form-control form-control-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
