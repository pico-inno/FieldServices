<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title fs-6">
                <i class="fa-solid fa-paperclip text-primary pe-2"></i> {{$saleVoucher->sales_voucher_no}} To
                @if ($postedReservation)
                Edit
                @endif
                Post Reservation's Folio Invoice
            </h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </div>
            <!--end::Close-->
        </div>
        <form id="postToReservationFolio" action="{{route('addToReservationFolio')}}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-10">
                    <label for="" class="form-label">Select Reservation to Post</label>
                    <select class="form-select" name="reservation_id" data-dropdown-parent="#postToReservationFolio" data-control="select2" data-placeholder="Select reservation" data-allow-clear="true">
                        <option></option>
                        @foreach ($reservations as $reservation)
                        @php
                        $guest = $reservation->contact;
                        $guestName = ($guest['prefix'] ?? '') . ' ' . ($guest['first_name'] ?? '') . ' ' . ($guest['middle_name'] ?? '') . ' ' . ($guest['last_name'] ?? '');
                        @endphp

                        <option value="{{$reservation->id}}" @if ($postedReservation) @selected($postedReservation->id == $reservation->id)
                            @endif

                        >{{$guestName}} ({{$reservation->reservation_code}})</option>
                        @endforeach
                    </select>
                    <input type="hidden" value="{{$saleVoucher->id}}" name="sale_id" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submitJoinForm">
                    @if ($postedReservation)
                    Edit Posting
                    @else
                    Save Posting
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $('[data-control="select2"]').select2();
</script>