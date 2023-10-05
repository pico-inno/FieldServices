<div>
    <div class="row mb-5 justify-content-between">
        <div class="col-12 col-lg-6 d-flex mb-5 justify-content-between">
            <div class="">
                <label class="fs-6 fw-semibold form-label mt-3" for="{{$firstFor}}">
                    <span class="required">{{$firstLabel}}</span>
                </label>
            </div>
            <div class="col-6">
                {{$firstInput}}
                {{-- <input type="text" class="form-control form-control form-control form-control-sm border-left-0 border-right-0 border-top-0 border-bottom-1 rounded-0" name="name" id="business_name" value="{{$settingData['name']}}" /> --}}
            </div>
        </div>
        @if (isset($secInput))
            <div class="col-12 col-lg-6 d-flex mb-5 justify-content-between">
                <div class="">
                    <label class="fs-6 fw-semibold form-label mt-3" for="{{$secFor}}">
                        <span class="required">{{$secLabel}}</span>
                    </label>
                </div>
                <div class="col-7">
                    {{$secInput}}
                </div>
            </div>
        @endif

    </div>
</div>
