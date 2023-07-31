
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="{{route('registration_info_update',$registrationInfo->id)}}" method="POST" id="add_building_form">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h3 class="modal-title">Edit Registration Info</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row mb-6">
                         <div class="col-md-4 col-12">
                            <label for="" class="form-label">Patient Type</label>
                            <select name="registration_type" class="form-select form-select-sm" data-control="select2" data-dropdown-parent="#editInfo" data-placeholder="Select an option" data-allow-clear="true">
                                <option></option>
                                <option value="IPD" @selected($registrationInfo->registration_type=='IPD')>IPD</option>
                                <option value="OPD" @selected($registrationInfo->registration_type=='OPD')>OPD</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="" class="form-label">IPD Check In date</label>
                            <input type="text" name="ipd_check_in_date" id="ipd_check_in_date" class="form-control form-control-sm" data-flat-date="true" autocomplete="off"  value="{{$registrationInfo->ipd_check_in_date}}" />
                        </div>
                        {{-- <div class="col-md-4 col-12">
                            <label for="" class="form-label">OPD Check In date</label>
                            <input type="text" name="opd_check_in_date" id="ipd_check_in_date" class="form-control form-control-sm" autocomplete="off"  value="{{$registrationInfo->opd_check_in_date}}" />
                        </div> --}}
                        <div class="col-md-4 col-12">
                            <label for="" class="form-label">Check Out date</label>
                            <input type="text" name="opd_check_in_date" id="check_out_date" class="form-control form-control-sm" data-flat-date="true" autocomplete="off"  value="{{$registrationInfo->check_out_date}}" />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-md-4 col-12">
                            <label for="" class="form-label"> Patient Name </label>
                            <select class="form-select form-select-sm" name="patient_id" data-control="select2" data-dropdown-parent="#editInfo" data-placeholder="Select Patient" data-allow-clear="true">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}" @selected($registrationInfo->patient_id==$contact->id)>{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="" class="form-label">Company Name </label>
                            <select class="form-select form-select-sm" name="company_id" data-control="select2" data-dropdown-parent="#editInfo" data-placeholder="Select Compay" data-allow-clear="true">
                                <option></option>
                                @foreach ($Businesscontacts as $Businesscontact)
                                    <option value="{{ $Businesscontact->id }}" @selected($registrationInfo->company_id==$Businesscontact->id)>{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="" class="form-label">Registration Status</label>
                            <select name="registration_status" id="registration_status"  class="form-select form-select-sm" data-control="select2" data-dropdown-parent="#editInfo" data-placeholder="Select an option" data-allow-clear="true">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending" @selected($registrationInfo->registration_status=='pending')>Pending</option>
                                <option value="Cancelled" @selected($registrationInfo->registration_status=='cancelled')>Cancelled</option>
                                <option value="Confirmed" @selected($registrationInfo->registration_status=='confirmed')>Confirmed</option>
                                <option value="Checkin" @selected($registrationInfo->registration_status=='checkin')>Checkin</option>
                                <option value="Checkout" @selected($registrationInfo->registration_status=='checkout')>Checkout</option>
                            </select>
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
    <script>
        $('[data-flat-date="true"]').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
