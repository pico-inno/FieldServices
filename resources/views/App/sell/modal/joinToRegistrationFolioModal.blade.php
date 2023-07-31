    <div class="modal-dialog w-sm-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-6"><i class="fa-solid fa-paperclip text-primary pe-2"></i> {{$SaleVoucher->sales_voucher_no}} To
                    @if ($postedRegistration)
                        Edit
                    @endif
                    Post Registration's Folio Invoice</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <form  id="postFolioToFolioInvoice" action="{{route('addToRegistrationFolio')}}" method="POST">
                @csrf
                <div class="modal-body">
                        <div class="mb-10">
                            <label for="" class="form-label">Select Registration to Post</label>
                            <select class="form-select" name="registration_id"  data-dropdown-parent="#postFolioToFolioInvoice" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                                <option disabled selected >Select registration</option>
                                @foreach ($registrations as $registration)
                                @php
                                    $patient=$registration->patient;
                                    $patientName=$patient['prefix']??'' .$patient['first_name']??'' .$patient['middle_name']??''.$patient['last_name']??''
                                @endphp

                                    <option value="{{$registration->id}}"
                                       @if ($postedRegistration)
                                            @selected($postedRegistration->id == $registration->id)
                                       @endif

                                        >{{$patientName}} ({{$registration->registration_code}})</option>
                                @endforeach
                            </select>
                            <input type="hidden" value="{{$SaleVoucher->id}}" name="sale_id" class="form-control">
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitJoinForm">
                        @if ($postedRegistration)
                        Edit Posting
                        @else
                            Save Posting
                        @endif
                </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('[data-control="select2"]').select2();
    </script>
