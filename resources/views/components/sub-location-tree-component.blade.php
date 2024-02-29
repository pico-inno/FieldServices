
    @foreach ($locations as $location)
    

        <ul>
            <li data-jstree='{ "icon" : "ki-solid ki-geolocation text-{{$mainColor}} fs-4","opened" : true  }' class="mt-3">
                {{$location['name']}}

                @if (isset($location) && is_array($location))
                <x-sub-location-tree-component :location="$location" :level="$level+1"   />
                @endif
            </li>
        </ul>

    @endforeach
