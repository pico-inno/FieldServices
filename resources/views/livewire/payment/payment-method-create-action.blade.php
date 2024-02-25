<div>
    <button class="btn btn-sm btn-primary fs-9 fs-sm-7" data-bs-toggle="modal" data-bs-target="#kt_modal_1" wire:ignore>
        Create Payment Methods
    </button>

    <div class=" modal fade" tabindex="-1" id="kt_modal_1"  wire:ignore.self>
        <div class="modal-dialog w-md-600px" wire:ignore.self>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Crearte Payment Method </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    {{-- <form  wire.submit=''> --}}
                        <div class="form mb-5">
                            <label for="" class="form-label required">Name</label>
                            <input type="text" placeholder="Name" wire:model.live="name" class="form-control">
                            <div class="text-success" wire:target="name" wire:loading>Validating...</div>
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
                                <select name="paymentAccount" id="paymentAccount" class="form-select form-select-sm" data-kt-select2='true' data-allow-clear="true" data-placeholder="Select Payment Account" placeholder="Select Payment Account">
                                    <option value=""></option>
                                    @foreach ($paymentAccounts as $paymentAccount)
                                        <option value="{{$paymentAccount->id}}" >{{$paymentAccount->name}}</option>
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
                    {{-- </form> --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " disabled wire:loading wire:target='create'>Saving...</button>
                    <button type="button" class="btn btn-primary " wire:click='create' wire:loading.remove>Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        $('#paymentAccount').select2().on('select2:select', function (e) {
            @this.set('paymentAccountId', $('#paymentAccount').select2("val"));
        }).on('select2:unselect', function (e) {
            @this.set('paymentAccountId', '');
        });


</script>

@script
<script>


    const element = document.getElementById('kt_modal_1');
    const modal = new bootstrap.Modal(element);

    $wire.on('pm-created-success', () => {
        $('#paymentAccount').val([]).trigger('change');
        @this.set('paymentAccountId', '');
        modal.hide();
        Swal.fire({
            text:'Successfully Created',
            icon:'success'
        })
    });
    $wire.on('pm-created-fail', (event) => {
        let message=event.message;
        Swal.fire({
            title:'Error',
            text:message,
            icon:'error'
        })
    });
</script>
@endscript
