<div class="modal modal-lg fade" tabindex="-1" id="selectPaymentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('building.store')}}" method="POST" id="add_building_form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title"> Payment </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="kt_docs_repeater_basic">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div data-repeater-list="kt_docs_repeater_basic">
                                <div data-repeater-item>
                                    <div class="form-group row">
                                        <div class="row mb-6">
                                            <div class="col-md-3 col-12">
                                                <label for="" class="form-label">Amout </label>
                                                <input type="text" class="form-control form-control-sm" value="0">
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <label for="" class="form-label"> Payment Method </label>
                                                <select class="form-select form-select-sm" data-control="select2" data-dropdown-parent="#selectPaymentModal" data-placeholder="Select Patient" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="daw mya">Cash</option>
                                                    <option value="ts">Bank Transfer</option>
                                                    <option value="ts">Card</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Payment Note</label>
                                                <textarea name="" id="" cols="30" rows="2" class="form-control textarea"></textarea>
                                            </div>
                                             <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8" style="display: none;">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Form group-->

                        <!--begin::Form group-->
                        <div class="form-group mt-5">
                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add
                            </a>
                        </div>
                        <!--end::Form group-->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

</script>
