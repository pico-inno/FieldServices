
<ul>
    <li data-jstree='{ "icon" : "ki-solid ki-geolocation text-{{$mainColor}} fs-4","opened" : false  }' class="mt-3">
        {{$location['name']}}
        @php
            $subLocations = App\Models\settings\businessLocation::select('id', 'name')
                ->where('parent_location_id', $location['id'])
                ->get()
                ->toArray();
        @endphp
        @if ($subLocations)
            @foreach ($subLocations as $subLocation)
                <livewire:location.sub-location-component :location="$subLocation" :level="$level+1" />
            @endforeach

        @endif

        {{-- @if (isset($location) && is_array($location))
            <livewire:location.sub-location-component :location="$location" :level="$level+1"   />
        @endif --}}
    </li>
</ul>
