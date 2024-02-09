<div>
    @if($status == 'paid')
    <span class='badge badge-success'>
       <i class="fa-regular fa-circle-check text-white me-2"></i> {{$status}}
     </span>
    @elseif ($status == 'partial')
    <span class='badge badge-dark'>
        <i class="fa-solid fa-circle-half-stroke text-white me-2"></i>   {{$status}}
    </span>
    @elseif ($status == 'due')
    <span class='badge badge-warning'>
        <i class="fa-solid fa-hourglass-start text-white me-2"></i>   {{$status}}
    </span>
    @else
    {{$status}}
    @endif
</div>
