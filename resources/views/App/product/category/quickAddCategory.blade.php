<div class="modal fade" tabindex="-1" id="kt_modal_category">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Brand</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
               <div class="row">
               <div class="col-md-6">
                <div class="mb-10 fv-row">
                                <label class="required form-label">{{ __('product/category.category_name') }}</label>
                                <input type="text" name="category_name" class="form-control form-control-sm mb-2" placeholder="Category name" value="" />
                                @error('category_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="text-muted fs-7 mt-2">A category name is required and recommended to be unique.</div>
                            </div>
                </div>
                <div class="col-md-6">
                <div class="mb-10 fv-row">
                                <label class="form-label">{{ __('product/category.category_code') }}</label>
                                <input type="text" name="category_code" class="form-control form-control-sm mb-2" placeholder="Category code" value="" />
                                <div class="text-muted fs-7">Category code is same as <b>HSN code</b></div>
                            </div>
                </div>
                <div>
                                <label class="form-label mb-5">{{ __('product/category.select_parent_category') }}</label>
                                
                                <select class="form-select mb-2 form-select-sm" name="parent_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                    <option  selected="selected">Select</option>
                                    @foreach ($categories as $category)
                                        <option  value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
               </div>
                            
                            
                            <div>
                                <label class="form-label">{{ __('product/category.description') }}</label>
                                <textarea name="category_desc" id="" cols="10" rows="5" class="form-control"></textarea>
                                
                                <div class="text-muted fs-7">Set a description to the category for better visibility.</div>
                                
                            </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary quick-add-category">Save changes</button>
                </div>
        </div>
    </div>
</div>