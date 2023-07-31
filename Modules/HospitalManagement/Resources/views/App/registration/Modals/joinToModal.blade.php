    <div class="modal-dialog w-sm-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"> <i class="fa-solid fa-link text-primary pe-2"></i>Join Registration for ({{$data->registration_code}})</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <form  id="joinRegistrationForm" action="{{route('joinRegistraion',$id)}}">
                @csrf
                <div class="modal-body">
                        <div class="mb-10">
                            <label for="" class="form-label">Select Patient to join</label>
                            <select class="form-select form-select-solid joinSelect" name="idToJoin"  data-dropdown-parent="#joinTo" data-placeholder="Select an option" data-allow-clear="true">
                                <option disabled selected >Select Patient</option>
                                @foreach ($registeredPatients as $rp)
                                    <option value="{{$rp->id}}"  @selected($rp->id == $data->joint_registration_id??0)>{{$rp->patient['prefix']}} {{$rp->patient['first_name']  }} {{$rp->patient['middle_name']}} {{$rp->patient['last_name']}} ({{$rp->registration_code}})</option>
                                @endforeach
                            </select>
                            <input type="hidden" value="{{$id}}" name="childRegistration" class="form-control">
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitJoinForm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
