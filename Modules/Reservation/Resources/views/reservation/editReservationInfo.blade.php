@php
    $currentDate = date('d/m/Y, h:i A');
@endphp
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('updateReservationInfo', $reservation->id)}}" method="POST" id="editReservationInfoForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3 class="modal-title">Edit Reservation Info</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>
          
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label class="form-label">Guest</label>
                        <select name="guest_id" class="form-select fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @php
                            $contacts = \App\Models\Contact\Contact::where('type', 'Customer')->get();
                            @endphp
                            @foreach($contacts as $contact)
                            @if(empty($contact->company_name))
                            <option value="{{$contact->id}}" {{ old('guest_id', $reservation->guest_id) == $contact->id ? 'selected' : ''}}>
                                {{$contact->prefix}} {{$contact->first_name}} {{$contact->middle_name}} {{$contact->last_name}}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-select fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                            <option></option>
                            @foreach($contacts as $contact)
                            @if(!empty($contact->company_name))
                            <option value="{{$contact->id}}" {{ old('company_id', $reservation->company_id) == $contact->id ? 'selected' : ''}}>
                                {{$contact->company_name}}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label for="check_in_date" class="form-label">Checkin Date</label>
                        <input type="text" name="check_in_date" id="check_in_dates" value="{{ old('check_in_date', !empty($reservation->check_in_date) ? date('d/m/Y, h:i A', strtotime($reservation->check_in_date)) : $currentDate) }}" class="form-control fs-7" autocomplete="off" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label for="check_out_date" class="form-label">Checkout Date</label>
                        <input type="text" name="check_out_date" id="check_out_dates" value="{{ old('check_out_date', !empty($reservation->check_out_date) ? date('d/m/Y, h:i A', strtotime($reservation->check_out_date)) : $currentDate) }}" class="form-control fs-7" autocomplete="off" />
                    </div>
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label for="reservation_status" class="form-label">Reservation Status</label>
                        <select name="reservation_status" id="reservation_status" class="form-select fs-7 custom-select2" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                            <option></option>
                            <option value="Pending" {{$reservation->reservation_status == 'Pending' ? 'selected' : ''}}>Pending</option>
                            <option value="Cancelled" {{$reservation->reservation_status == 'Cancelled' ? 'selected' : ''}}>Cancelled</option>
                            <option value="Confirmed" {{$reservation->reservation_status == 'Confirmed' ? 'selected' : ''}}>Confirmed</option>
                            <option value="No_Show" {{$reservation->reservation_status == 'No_Show' ? 'selected' : ''}}>No Show</option>
                            <option value="Checkin" {{$reservation->reservation_status == 'Checkin' ? 'selected' : ''}}>Checkin</option>
                            <option value="Checkout" {{$reservation->reservation_status == 'Checkout' ? 'selected' : ''}}>Checkout</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-8">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea name="remark" id="remark" class="form-control fs-7" placeholder="Remark" style="resize: none;">{{ old('remark', $reservation->remark ?? null) }}</textarea>
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


 <script>
    $('.custom-select2').select2();

    tempusDominus.extend(tempusDominus.plugins.customDateFormat);

    var datepickerIds = ["check_in_dates", "check_out_dates"];

    datepickerIds.forEach(function (id) {
        new tempusDominus.TempusDominus(document.getElementById(id), {
            localization: {
                locale: "en",
                format: "dd/MM/yyyy, hh:mm T",
            }
        });
    });

    datepickerIds.forEach(function (id) {
        var currentDate = new Date();
        var datepicker = document.getElementById(id);
        datepicker.placeholder = currentDate.toLocaleString();

        var options = {
            autoClose: true
        };
    });
</script>
