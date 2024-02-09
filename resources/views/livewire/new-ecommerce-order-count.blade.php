<div wire:poll>
    @if ($count >0)
    <span class="position-absolute top-0 start-100 translate-middle  badge  badge-danger">
        {{$count <= 99 ? $count : '99+'}}
        </span>
    @endif
</div>
