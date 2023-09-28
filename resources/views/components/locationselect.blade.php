<select name="business_location_id" class="form-select form-select-sm fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
    <option></option>
    @foreach ($locations as $l)
        <option value="{{$l->id}}"  @selected(Auth::user()->default_location_id==$l->id)>{{businessLocationName($l)}}</option>
    @endforeach
</select>
