<div>
    <select name="{{$name ?? ''}}" class="form-select rounded-0 form-select-sm  border-top-0  border-right-0 border-left-0 {{$class ?? '' }}" id="{{$id ?? '' }}"
        data-control="select2" data-placeholder="{{$placeholder ?? ''}}" placeholder="{{$placeholder ?? '' }}" {!! $attr ?? '' !!}>
        {{$slot}}
    </select>
</div>
