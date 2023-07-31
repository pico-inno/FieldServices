<div class="modal fade" tabindex="-1" id="subscribe_models">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h3 class="modal-title">Subscribe</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                   <div class="row">
                        <div class="mb-10 mt-3 col-12 col-md-12">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Subscription Interval:
                            </label>
                            <div class="input-group flex-nowrap ">
                                <div class=" col-6">
                                    <input type="text" class="form-control rounded-end-0" placeholder="subscription_interval:">
                                </div>
                                <select class="form-select fw-bold rounded-start-0 border-gray-500 col-6" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option>Please Select</option>
                                    <option value="Administrator">Months</option>
                                    <option value="Analyst">Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-10">
                            <label for="repetitions" class="form-label">
                                No. of Repetitions:
                            </label>
                            <input type="text" class="form-control">
                            <span class="mt-3 text-muted">
                                If blank invoice will be generated infinite times
                            </span>
                        </div>
                   </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


