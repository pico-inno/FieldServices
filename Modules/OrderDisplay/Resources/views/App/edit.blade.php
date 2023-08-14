
<div class="modal-dialog  w-sm-500px">
    <div class="modal-content">
        <form action="{{route('odUpdate',$orderDisplay->id)}}" method="POST" id="add_exchange_rates">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Edit Order Display</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-12 col-md-6 mb-5">
                        <label for="name" class="required form-label">Name</label>
                        <input class="form-control form-control-sm " id="name" name="name" />
                    </div>
                    <div class="col-12 col-md-6 mb-5">
                        <label for="location" class="required form-label">Location</label>
                        <select name="location_id" id="location" class="form-select form-select-sm" data-control="select2">
                            @foreach ($locations as $l)
                                <option value="{{$l->id}}" @selected($l->id==$orderDisplay->location_id)>{{$l->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-5">
                        <label for="location" class="required form-label">POS Register Name</label>
                        <input class="form-control form-control-sm " id="pos_register_tagify"  value="{{$posRegisterText}}" name="pos_register_id" />
                    </div>
                    <div class="col-12 col-md-6 mb-5">
                        <label for="category" class="required form-label">Product Category</label>
                        <input class="form-control form-control-sm " id="category_tagify" value="{{$productCategoryText}}" name="category_id" />
                        {{-- <select name="category_id" id="category" class="form-select form-select-sm" data-control="select2">
                            @foreach ($categories as $c)
                                <option value="{{$c->id}}">{{$c->name}} ({{$c->short_code}})</option>
                            @endforeach
                        </select> --}}
                    </div>
                </div>
                <div class="row">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
(function tagify(){

let category=@json($categories).map((c)=>{
    return {'value':c.name,'id':c.id};
});
let posRegister=@json($posRegisters).map((c)=>{
    return {'value':c.name,'id':c.id};
});
var posInput = document.querySelector('#pos_register_tagify'),

// Init Tagify script on the above inputs
tagify = new Tagify(posInput, {
    whitelist:posRegister ,
    placeholder: "Type pos register name",
    enforceWhitelist: true
});

var categoryInput = document.querySelector('#category_tagify'),

// Init Tagify script on the above inputs
tagify = new Tagify(categoryInput, {
    whitelist: category,
    placeholder: "Type Product Category",
    enforceWhitelist: true
});

})();
</script>
