<div class="modal-dialog w-700px modal-lg" id="printArea">
    <div class="modal-content">
        <div>
            {{-- <div class="modal-header">
                <h1>Business Location</h1>
            </div> --}}

            <div class="modal-body">
                <div class="mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <h3 class="text-gray-800 fs-2">{{businessLocationName($bl)}}</h3>
                            </div>
                            <div class="  mt-2">
                                <h4 class="text-gray-600 fs-6 fw-semibold"><span class="text-gray-600">location type </span> : {{$bl->locationType->name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-20">
                    <div class="col-12 text-start">
                        <div class="">
                            <h3 class="text-gray-600 fs-7 fw-semibold"><span class="text-gray-600">Location Code </span> :
                               <span class="text-gray-700">{{$bl->location_code}}</span></h3>
                        </div>
                        <div class="">
                            <h3 class="text-gray-600 fs-7 fw-semibold"><span class="text-gray-600">Inventor flow </span> : <span
                                    class="text-uppercase text-gray-700">{{$bl->inventory_flow}}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-15">
                    <div class="col-6 ">
                        <address class="fs-7 fw-semibold text-gray-700">
                            {{arr($address,'address',',')}}<br>
                            {{arr($address,'city',',')}}{{arr($address,'state',',')}}{{arr($address,'country',',')}}<br>
                            {{arr($address,'zip_postal_code','')}}
                        </address>
                    </div>
                    <div class="col-6  text-end">
                        <div class="fs-7 fw-semibold text-gray-700">
                            {{arr($address,'email')}}<br>
                            {{arr($address,'mobile')}}<br>
                            {{arr($address,'alternate_contact_number')}}<br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

