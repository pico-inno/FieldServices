
@if (count($locations) > 0)

    @foreach ($locations as $l)

        <ul>
            <li data-jstree='{ "icon" : "ki-solid ki-geolocation text-{{$mainColor}} fs-4","opened" : true  }' class="mt-3">
                {{$l['name']}}
                <x-subLocationTreeComponent :location="$l" :level="$level+1"   />
            </li>
        </ul>


    @endforeach
@endif
