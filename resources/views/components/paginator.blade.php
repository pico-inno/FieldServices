
<div class="row justify-content-center  justify-content-md-between">
    <style>
        .pagination{
            justify-content: center !important;
        }
        @media(min-width:780px){
            .pagination{
                justify-content: end !important;
            }
        }
    </style>
    <div class="col-md-6 col-12 mb-3 ">
        <div class="w-auto">
            <select name="" id="" wire:model.change="perPage" class="form-select form-select-sm w-auto m-auto m-md-0">
                @foreach ($aviablePerPages as $page)
                <option value="{{$page}}">{{$page}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-12 mb-3">
        {{$slot}}
    </div>
</div>
