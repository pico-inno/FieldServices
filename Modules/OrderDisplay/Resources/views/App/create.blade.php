
    <div class="modal-dialog  ">
        <div class="modal-content">
            <form action="{{route('odStore')}}" method="POST" id="add_exchange_rates">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Create Order Display</h3>

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
                            <select name="location_id" id="location" class="form-select form-select-sm" data-control="select2" data-kt-select2="true">
                                @foreach ($locations as $l)
                                    <option value="{{$l->id}}">{{$l->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-5">
                            <label for="pos_register_tagify" class="required form-label">POS Register Name</label>
                            <input class="form-control form-control-sm " id="pos_register_tagify" name="pos_register_id" />
                        </div>
                        <div class="col-12 col-md-6 mb-5">
                            <label for="category_tagify" class="required form-label">Product Category</label>
                            <input class="form-control form-control-sm " id="category_tagify"   name="category_id" />
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
    $('[data-control="select2"]').select2();

    let category=@json($categories).map((c)=>{
        return {'value':c.name,'id':c.id};
    });
    category=[{
        'value':'All','id':0
    },...category]
    let posRegister=@json($posRegisters).map((c)=>{
        return {'value':c.name,'id':c.id};
    });

    posRegister=[{
        'value':'All','id':0
    },...posRegister]
    var posInput = document.querySelector('#pos_register_tagify'),

    // Init Tagify script on the above inputs
    posTagify = new Tagify(posInput, {
        whitelist:posRegister ,
        placeholder: "Type pos register name",
        enforceWhitelist: true
    });

    var categoryInput = document.querySelector('#category_tagify'),

    // Init Tagify script on the above inputs
    tagifyForCategory = new Tagify(categoryInput, {
        whitelist: category,
        placeholder: "Type Product Category",
        enforceWhitelist: true
    });
    $('#category_tagify').change(function(c){
        let val=$(this).val();
        if(val){
            let jsonValue=JSON.parse(val);
            let checkAll=jsonValue.find(j=>j.id==0);
            if(checkAll && checkAll.id==0){
                $(this).prop('disabled', true);
                $(this).val('all,test');
                tagifyForCategory.removeAllTags();
                tagifyForCategory.addTags(['All']);
            }
        }else{
            $(this).prop('disabled', false);
        }

    })
    $('#pos_register_tagify').change(function(c){
        let val=$(this).val();
        if(val){
            let jsonValue=JSON.parse(val);
            let checkAll=jsonValue.find(j=>j.id==0);
            if(checkAll && checkAll.id==0){
                $(this).prop('disabled', true);
                $(this).val('all,test');
                posTagify.removeAllTags();
                posTagify.addTags(['All']);
            }else{
                $(this).prop('disabled', false);
            }
        }

    })
})();
</script>
