<div class="card-toolbar" id="cardToolBar">

    <button type="button" class="btn btn-primary btn-sm" id="exportModalBtn">
        Export Daily Report
    </button>

    <div class="modal fade show" tabindex="-1" id="exportModal" wire:ignore>
        <div class="modal-dialog   w-md-500px" id="exportModalSEc"  id="dateRangeParent">
            <form  wire:submit='export'>
                @csrf
                <div class="modal-content" ">
                    <div class="modal-header">
                        <h3 class="modal-title">Export Daily Report</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="mb-0" >
                            <label class="form-label">Pick A</label>
                            <input class="form-control" placeholder="Pick a date" id="kt_datepicker_1"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Export</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade show " tabindex="-1" wire:loading wire:target='export'>
        <div class="modal-dialog w-md-500px w-100">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="modal-title text-center">
                        Exporting......
                    </h3>
                    <div class="progress mt-5">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script >

        $(document).ready(function(){

            let exportModal = new bootstrap.Modal($('#exportModal'));
                $('#exportModalBtn').click(function(){
                    exportModal.show();
                });
                $("#kt_datepicker_1").flatpickr({
                    defaultDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        @this.set('dateFilter', dateStr);
                    },
                });

        })

    </script>
</div>
