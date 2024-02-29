<div>
    <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle actionRow"
            type="button" id="saleItemDropDown" data-bs-toggle="dropdown" aria-expanded="false">
            Actions
        </button>
        <div class="z-3 actionRow">
            <ul class="dropdown-menu z-10 p-5 actionRow" aria-labelledby="saleItemDropDown" role="menu">
                <button class="dropdown-item p-2 " type="button" >
                    View
                </button>
                <button class="dropdown-item p-2 " type="button"  data-bs-toggle="modal" data-bs-target="#kt_edit_modal_{{$id}}">
                    Edit
                </button>
                <button class="dropdown-item p-2 " type="button" wire:key="pmad-{{ $id }}"
                id="delete_confirm_modal_{{$id}}" data-id="{{$id}}"
                {{-- wire:click="delete({{ $id }})" --}}
                {{-- wire:click="confirmDelete({{ $id }})" --}}
                >
                    Delete
                </button>
            </ul>
        </div>
        <div class=" modal fade" tabindex="-1" id="kt_edit_modal_{{$id}}"  wire:ignore.self  wire:key="pmm-{{ $id }}">
            <div class="modal-dialog w-md-600px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Payment Method </h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="form mb-5">
                            <label for="" class="form-label required">Name</label>
                            <input type="text" placeholder="Name" wire:model.live="name" class="form-control">
                            <div class="">
                                <div class="text-danger">
                                    @error('name')
                                            {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="form mb-5">
                            <label for="" class="form-label required">Payment Account</label>
                            <div class="select" wire:ignore>
                                <select name="paymentAccount" id="paymentAccount_{{$id}}" class="form-select form-select-sm" data-kt-select2='true' data-allow-clear="true" data-placeholder="Select Payment Account" placeholder="Select Payment Account">
                                    <option value=""></option>
                                    @foreach ($paymentAccounts as $paymentAccount)
                                        <option value="{{$paymentAccount->id}}" @selected($paymentAccountId==$paymentAccount->id)>{{$paymentAccount->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="">
                                <div class="text-danger">
                                    @error('paymentAccountId')
                                            {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <label for="" class="form-label">Note</label>
                            <div class="select" wire:ignore>
                                <textarea wire:model.live="note" class="form-control " id="" cols="30" rows="5"></textarea>
                            </div>

                            <div class="">
                                <div class="text-danger">
                                    @error('note')
                                            {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary " disabled wire:loading wire:target='update'>Updating...</button>
                        <button type="button" class="btn btn-primary " wire:click='update' wire:loading.remove>Update changes</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<script wire:ignore>

        $(`#paymentAccount_{{$id}}`).select2().on('select2:select', function (e) {
            @this.set('paymentAccountId', $(`#paymentAccount_{{$id}}`).select2("val"));
        }).on('select2:unselect', function (e) {
            @this.set('paymentAccountId', '');
        });


</script>

@script
<script>

$wire.on('swal-confirm', function (data) {
        Swal.fire({
            title:'Are You Sure To Delete',
            icon: "question",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sure,Delete",
            cancelButtonText: 'Nope, cancel it',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('delete',{'id': data[0].itemId});
            }
        });
    });

    $('#delete_confirm_modal_{{$id}}').on('click',function(){
        Swal.fire({
            title:'Are You Sure To Delete',
            icon: "question",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sure,Delete",
            cancelButtonText: 'Nope, cancel it',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // alert($(this).data('id'));
                $wire.dispatch('delete',{'id':$(this).data('id')});
                // Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    })

    let element = document.getElementById(`kt_edit_modal_{{$id}}`);
    let modal = new bootstrap.Modal(element);

    $wire.on('pm-updated-success', () => {
        modal.hide();
        Swal.fire({
            text:'Successfully Updated',
            icon:'success'
        })
    });
    $wire.on('pm-updated-fail', (event) => {
        let message=event.message;
        Swal.fire({
            title:'Error',
            text:message,
            icon:'error'
        })
    });
</script>
@endscript
