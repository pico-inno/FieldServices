    <div class="modal-dialog w-sm-600px">
        <div class="modal-content">
            <form action="" method="">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add Guest Information</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-10 mb-8">
                            <label class="form-label">Guest</label>
                            <div class="input-group flex-nowrap">
                                <select name="guest_id" class="form-select rounded-end-0 fs-7 guest-select-2" id="guest_id" data-control="select2" data-placeholder="Please select" data-allow-clear="true">
                                    <option></option>
                                    @php
                                    $contacts = \App\Models\Contact\Contact::all();
                                    @endphp
                                    @foreach($contacts as $contact)
                                    @if(empty($contact->company_name))
                                    <option value="{{$contact->id}}" {{ old('guest_id', $reservation->guest_id) == $contact->id ? 'selected' : ''}}>
                                        {{$contact->prefix}} {{$contact->first_name}} {{$contact->middle_name}} {{$contact->last_name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                <a type="button" class="input-group-text add_contact_button" id="add_contact_button" data-href="{{url('customers/quickadd')}}" data-form-type="{{ $formType }}">
                                    <i class="fa-solid fa-plus text-primary"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="form_type" value="{{$formType}}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        @include('App.contact_management.customers.quickAddContact')

    </div>
    <script>
        $('.guest-select-2').select2();

        $(document).on('click', '.add_contact_button', function() {
            var formType = $(this).data('form-type');

            console.log('formtype', formType);
            $('#add_contact_modal').load($(this).data('href'), function() {
                $('#add_contact_modal').find('#form_type_input').val(formType);

                $(this).modal('show');
                
            });        
        });
    </script>