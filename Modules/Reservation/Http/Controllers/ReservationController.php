<?php

namespace Modules\Reservation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact\Contact;
use App\Models\sale\sales;
use Modules\Reservation\Entities\FolioInvoice;
use Modules\Reservation\Entities\FolioInvoiceDetail;
use Modules\Reservation\Entities\Reservation;
use Modules\Reservation\Entities\RoomReservation;
use Modules\RoomManagement\Entities\Room;
use Modules\RoomManagement\Entities\RoomType;
use Modules\RoomManagement\Entities\RoomSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index()
    {
        if (request()->ajax()) {
            $reservations = Reservation::with('room_reservations')->get();

            return DataTables::of($reservations)
                ->addColumn('guest', function ($reservation) {
                    $contact = Contact::find($reservation->guest_id);
                    $guestName = '';

                    if ($contact) {
                        if ($contact->prefix) {
                            $guestName = $contact->prefix;
                        }

                        if ($contact->first_name) {
                            $guestName .= ' ' . $contact->first_name;
                        }

                        if ($contact->middle_name) {
                            $guestName .= ' ' . $contact->middle_name;
                        }

                        if ($contact->last_name) {
                            $guestName .= ' ' . $contact->last_name;
                        }
                    }

                    $companyContact = Contact::find($reservation->company_id);
                    $companyName = '';

                    if ($companyContact) {
                        $companyName = $companyContact->company_name;
                    }

                    return $guestName ?: $companyName;
                })
                ->addColumn('room_type', function ($reservation) {
                    $roomTypeNames = RoomReservation::whereIn('id', $reservation->room_reservations->pluck('id'))
                        ->pluck('room_type_id')
                        ->map(function ($roomTypeId) {
                            $roomType = RoomType::find($roomTypeId);
                            return $roomType ? $roomType->name : '';
                        })
                        ->toArray();

                    return implode(', ', $roomTypeNames);
                })
                ->addColumn('booking_confirmed_by', function ($reservation) {
                    $confirmed_user = BusinessUser::find($reservation->booking_confirmed_by);
                    if ($confirmed_user) {
                        $confirmed_by = $confirmed_user->username;
                        return $confirmed_by;
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    return  '
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                                <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <a href="' . route('reservation.show', $row->id) . '" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                </li>
                                <li>
                                    <a href="' . route('reservation.edit', $row->id) . '" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                </li>
                                <li>
                                    <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                        <i class="fa-solid fa-trash me-3"></i> Delete
                                    </button>
                                </li>
                                <li>
                                    <a href="' . route('room-sale.create', ['type' => 'reservation', 'id' => $row->id]) . '" class="dropdown-item p-2"><i class="fa-solid fa-door-closed me-3"></i> Room Sale</a>
                                </li>
                            </ul>
                        </div>
                        ';
                })
                ->rawColumns(['room_type', 'guest', 'booking_confirmed_by', 'action'])
                ->make(true);
        }
        return view('reservation::reservation.index');
    }

    public function show($id)
    {
        $reservation = Reservation::where('id', $id)->with('room_reservations', 'room_sales', 'contact', 'company')->first();

        $folioInvoice = FolioInvoice::where('reservation_id', $reservation->id)->select('id')->first();
        $currentReservation = Reservation::where('id', $id)->with('room_reservations', 'room_sales', 'contact', 'company')->first();

        if ($currentReservation->joint_reservation_id !== null) {
            $parentReservation = Reservation::where('id', $currentReservation->joint_reservation_id)
                ->with('room_reservations', 'room_sales', 'contact', 'company')
                ->first();

            $reservations = Reservation::where('joint_reservation_id', $currentReservation->joint_reservation_id)
                ->orWhere('id', $currentReservation->joint_reservation_id)
                ->with('room_reservations', 'room_sales', 'contact', 'company')
                ->get();
        } else {
            $parentReservation = $currentReservation;
            $reservations = Reservation::where('joint_reservation_id', $currentReservation->id)
                ->orWhere('id', $currentReservation->id)
                ->with('room_reservations', 'room_sales', 'contact', 'company')
                ->get();
        }

        $folioInvoice = FolioInvoice::where('reservation_id', $currentReservation->id)->select('id')->first();
        if ($folioInvoice) {
            $folioInvoiceDetail = FolioInvoiceDetail::where('folio_invoice_id', $folioInvoice->id)->get();
        }

        return view('reservation::reservation.show')->with(compact('currentReservation', 'folioInvoiceDetail', 'reservations', 'parentReservation'));
    }

    public function getReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->with(
                'room_reservations',
                'room_reservations.room',
                'room_reservations.room_type',
                'room_reservations.room_rate',
                'room_sales',
                'room_sales.room_sale_details',
                'room_sales.room_sale_details.room',
                'room_sales.room_sale_details.room_type',
                'room_sales.room_sale_details.room_rate',
                'contact',
                'company'
            )
            ->first();

        return response()->json(['reservation' => $reservation]);
    }

    public function getMultipleReservations($ids)
    {
        $reservationIds = explode(',', $ids);

        $reservations = Reservation::whereIn('id', $reservationIds)
            ->with(
                'room_reservations',
                'room_reservations.room',
                'room_reservations.room_type',
                'room_reservations.room_rate',
                'room_sales',
                'room_sales.room_sale_details',
                'room_sales.room_sale_details.room',
                'room_sales.room_sale_details.room_type',
                'room_sales.room_sale_details.room_rate',
                'contact',
                'company'
            )
            ->get();

        $folioInvoices = FolioInvoice::whereIn('reservation_id', $reservationIds)->get();
        $sales = [];

        foreach ($folioInvoices as $folioInvoice) {
            $folioInvoiceDetail = FolioInvoiceDetail::where('folio_invoice_id', $folioInvoice->id)->get();

            if ($folioInvoiceDetail) {
                foreach ($folioInvoiceDetail as $detail) {
                    if ($detail->transaction_type == 'sale') {
                        $sale_id = $detail->transaction_id;
                        $sale = Sales::where('id', $sale_id)->with('sale_details', 'sale_details.product', 'sale_details.uom')->first();

                        if ($sale) {
                            $sales[] = $sale;
                        }
                    }
                }
            }
        }

        return response()->json(['reservations' => $reservations, 'sales' => $sales]);
    }

    public function create(Request $request)
    {
        $contacts = Contact::where('type', 'Customer')->get();

        $reservations = Reservation::whereNull('joint_reservation_id')->with('contact', 'company')->get();

        $formType = 'create';

        $checkInDate = Carbon::parse($request->input('check_in_date'));
        $checkOutDate = Carbon::parse($request->input('check_out_date'));

        // $availableRooms = Room::where('status', 'Available')
        //     ->whereDoesntHave('room_reservations', function ($query) use ($checkInDate, $checkOutDate) {
        //         $query->where('room_check_in_date', '<=', $checkOutDate)
        //             ->where('room_check_out_date', '>=', $checkInDate);
        //     })
        //     ->get();


        return view('reservation::reservation.create')->with(compact('contacts', 'reservations', 'formType'));
    }

    public function store(Request $request)
    {
        try {
            $reservation_data = $request->only(['guest_id', 'company_id', 'reservation_status', 'remark']);

            $joint_reservation_id = $request->input('joint_reservation_id');

            $is_group_reservation = $request->has('group_reservation');

            $check_in_date = $request->input('check_in_date');
            $check_out_date = $request->input('check_out_date');

            $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
            $reservation_data['check_in_date'] = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;

            $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
            $reservation_data['check_out_date'] = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

            $reservation_data['joint_reservation_id'] = !empty($joint_reservation_id) ? $joint_reservation_id : null;

            $reservation_data['booking_confirmed_by'] = Auth::user()->id;
            $reservation_data['created_by'] = Auth::user()->id;

            $room_type_ids = $request->input('room_type_id');
            $room_rate_ids = $request->input('room_rate_id');
            $room_ids = $request->input('room_id');
            $check_in_dates = $request->input('room_check_in_date');
            $check_out_dates = $request->input('room_check_out_date');

            $reservation_status = $request->input('reservation_status');

            DB::beginTransaction();

            if ($is_group_reservation) {

                if (!empty($joint_reservation_id)) {
                    // Find the parent reservation
                    $parent_reservation = Reservation::findOrFail($joint_reservation_id);

                    // Get the number of existing joint reservations
                    $child_count = $parent_reservation->joint_reservations()->count();

                    preg_match('/(\d+)$/', $parent_reservation->reservation_code, $matches);
                    $parent_numeric_code = $matches[1];

                    foreach ($room_type_ids as $index => $room_type_id) {
                        $reservation_data['reservation_code'] = 'REC-' . str_pad($parent_numeric_code, 6, '0', STR_PAD_LEFT) . '-' . str_pad($child_count + $index + 2, 1, '0', STR_PAD_LEFT);
                        $reservation_data['joint_reservation_id'] = $parent_reservation->id;

                        $reservation = Reservation::create($reservation_data);
                        $this->folioInvoiceCreate($reservation);

                        // Create the room reservation for each room in the group reservation
                        $room_type_id = isset($room_type_ids[$index]) ? $room_type_ids[$index] : null;
                        $room_rate_id = isset($room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
                        $room_id = isset($room_ids[$index]) ? $room_ids[$index] : null;
                        $check_in_date = $check_in_dates[$index];
                        $check_out_date = $check_out_dates[$index];

                        $check_in_date = isset($check_in_dates[$index]) ? $check_in_dates[$index] : null;
                        $check_out_date = isset($check_out_dates[$index]) ? $check_out_dates[$index] : null;

                        $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
                        $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
                        $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
                        $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

                        $reservation->room_reservations()->create([
                            'room_type_id' => $room_type_id,
                            'room_id' => $room_id,
                            'room_rate_id' => $room_rate_id,
                            'guest_id' => isset($reservation->guest_id) ? $reservation->guest_id : $reservation->company_id,
                            'room_check_in_date' => $formatted_check_in_date,
                            'room_check_out_date' => $formatted_check_out_date,
                            'remark' => $reservation->remark,
                            'created_by' => $reservation->created_by
                        ]);
                    }
                } else {
                    return redirect()->back()->with('error', 'Please select a parent reservation for the group reservation.');
                }
            } else {
                if (empty($joint_reservation_id)) {
                    $reservation = Reservation::whereNull('joint_reservation_id')->get()->count();
                    $reservation_data['reservation_code'] = 'REC-' . str_pad($reservation + 1, 6, '0', STR_PAD_LEFT);
                }

                $reservation = Reservation::create($reservation_data);

                foreach ($room_type_ids as $index => $room_type_id) {
                    $room_type_id = isset($room_type_ids[$index]) ? $room_type_ids[$index] : null;
                    $room_rate_id = isset($room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
                    $room_id = isset($room_ids[$index]) ? $room_ids[$index] : null;

                    $check_in_date = isset($check_in_dates[$index]) ? $check_in_dates[$index] : null;
                    // dd($check_in_date);
                    $check_out_date = isset($check_out_dates[$index]) ? $check_out_dates[$index] : null;

                    $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
                    // dd($check_in_date_format);
                    $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
                    $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
                    $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

                    $reservation->room_reservations()->create([
                        'room_type_id' => $room_type_id,
                        'room_id' => $room_id,
                        'room_rate_id' => $room_rate_id,
                        'guest_id' => isset($reservation->guest_id) ? $reservation->guest_id : $reservation->company_id,
                        'room_check_in_date' => $formatted_check_in_date,
                        'room_check_out_date' => $formatted_check_out_date,
                        'remark' => $reservation->remark,
                        'created_by' => $reservation->created_by
                    ]);
                }

                $this->folioInvoiceCreate($reservation);
            }

            // if ($reservation_status == 'Confirmed') {
            //     foreach ($room_ids as $room_id) {
            //         $room = Room::findOrFail($room_id);
            //         $room->status = 'Out_of_service';
            //         $room->save();
            //     }
            // }

            DB::commit();
            return redirect('/reservation')->with('success', 'Reservation Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while creating the reservation');
        }
    }

    private function folioInvoiceCreate($reservation)
    {
        $folioInvoiceCount = FolioInvoice::count();
        $folioInvoiceCode = 'FI-' . str_pad($folioInvoiceCount + 1, 6, '0', STR_PAD_LEFT);

        $folioInvoiceData = [
            'folio_invoice_code' => $folioInvoiceCode,
            'billing_contact_id' => isset($reservation->guest_id) ? $reservation->guest_id : $reservation->company_id,
            'reservation_id' => $reservation->id,
            'confirm_at' => now(),
            'confirm_by' => Auth::user()->id,
            'created_by' => Auth::user()->id
        ];
        $folioInvoice = FolioInvoice::create($folioInvoiceData);

        $folioInvoice->folio_invoice_details()->create([
            'created_by' => Auth::user()->id
        ]);
    }

    public function edit($id)
    {
        $reservation = Reservation::with('room_reservations')->find($id);
        $formType = 'edit';

        return view('reservation::reservation.edit')->with(compact('reservation', 'formType'));
    }

    public function update(Request $request, $id)
    {
        try {
            $reservation = Reservation::find($id);

            $joint_reservation_id = $request->input('joint_reservation_id');

            // $is_group_reservation = $request->has('group_reservation');

            $reservation->joint_reservation_id = !empty($joint_reservation_id) ? $joint_reservation_id : null;
            $reservation->guest_id = $request['guest_id'];
            $reservation->company_id = $request['company_id'];
            $check_in_date = $request['check_in_date'];
            $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
            $reservation->check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
            $check_out_date = $request['check_out_date'];
            $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
            $reservation->check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;
            $reservation->reservation_status = $request['reservation_status'];
            $reservation->booking_confirmed_by = Auth::user()->id;
            $reservation->remark = $request['remark'];
            $reservation->updated_by = Auth::user()->id;

            DB::beginTransaction();

            $reservation->update();

            $room_type_ids = $request->input('room_type_id');
            $room_rate_ids = $request->input('room_rate_id');
            $room_ids = $request->input('room_id');
            $check_in_dates = $request->input('room_check_in_date');
            $check_out_dates = $request->input('room_check_out_date');

            $this->updateRoomReservations($reservation, $room_type_ids, $room_rate_ids, $room_ids, $check_in_dates, $check_out_dates);

            $folioInvoice = FolioInvoice::where('reservation_id', $reservation->id)->first();

            if ($folioInvoice) {
                $folioInvoice->billing_contact_id = isset($reservation->guest_id) ? $reservation->guest_id : $reservation->company_id;
                $folioInvoice->updated_by = Auth::user()->id;
                $folioInvoice->save();
            }

            // if ($reservation->reservation_status == 'Checkout') {
            //     foreach ($room_ids as $room_id) {
            //         $room = Room::findOrFail($room_id);
            //         $room->status = 'Available';
            //         $room->save();
            //     }
            // }

            DB::commit();

            return redirect('/reservation')->with('success', 'Reservation Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating the reservation');
        }
    }

    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $reservation = Reservation::find($id);

                $reservation->is_delete = true;
                $reservation->deleted_by = Auth::user()->id;
                $reservation->save();

                RoomReservation::where('reservation_id', $id)
                    ->update([
                        'is_delete' => true,
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => now(),
                    ]);

                FolioInvoice::where('reservation_id', $id)
                    ->update([
                        'is_delete' => true,
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => now(),
                    ]);

                $reservation->delete();

                return response()->json(['success' => true, 'msg' => 'Reservation Deleted Successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Reservation not found']);
            }
        }
    }

    public function editReservationInfo($id)
    {
        $reservation = Reservation::find($id);
        return view('reservation::reservation.editReservationInfo')->with(compact('reservation'));
    }

    public function updateReservationInfo(Request $request, $id)
    {
        try {
            $reservation = Reservation::find($id);

            $reservation->guest_id = $request->guest_id;
            $reservation->company_id = $request->company_id;
            $check_in_date = $request->check_in_date;
            $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
            $reservation->check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
            $check_out_date = $request->check_out_date;
            $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
            $reservation->check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;
            $reservation->reservation_status = $request->reservation_status;
            $reservation->remark = $request->remark;

            $reservation->update();

            return back()->with('success', 'Reservation Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function editReservedRoom($id)
    {
        $reservation = Reservation::with('room_reservations')->find($id);

        return view('reservation::reservation.editReservedRoom')->with(compact('reservation'));
    }

    public function updateReservedRoom(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        $room_type_ids = $request->input('room_type_id');
        $room_rate_ids = $request->input('room_rate_id');
        $room_ids = $request->input('room_id');
        $check_in_dates = $request->input('room_check_in_date');
        $check_out_dates = $request->input('room_check_out_date');

        $this->updateRoomReservations($reservation, $room_type_ids, $room_rate_ids, $room_ids, $check_in_dates, $check_out_dates);

        return back()->with(['success' => 'Rooms Updated Successfully', 'updated' => 'roomTab']);
    }

    private function updateRoomReservations($reservation, $room_type_ids, $room_rate_ids, $room_ids, $check_in_dates, $check_out_dates)
    {
        $room_reservations = RoomReservation::where('reservation_id', $reservation->id)->get();

        foreach ($room_reservations as $index => $room_reservation) {
            $room_type_id = isset($room_type_ids[$index]) ? $room_type_ids[$index] : null;
            $room_rate_id = isset($room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
            $room_id = isset($room_ids[$index]) ? $room_ids[$index] : null;
            $check_in_date = isset($check_in_dates[$index]) ? $check_in_dates[$index] : null;
            $check_out_date = isset($check_out_dates[$index]) ? $check_out_dates[$index] : null;
            $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
            $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
            $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
            $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

            $room_reservation->room_type_id = $room_type_id;
            $room_reservation->room_rate_id = $room_rate_id;
            $room_reservation->room_id = $room_id;
            $room_reservation->room_check_in_date = $formatted_check_in_date;
            $room_reservation->room_check_out_date = $formatted_check_out_date;
            $room_reservation->save();
        }

        $existing_room_reservation_ids = $room_reservations->pluck('id')->all();
        $deleted_room_reservation_ids = array_slice($existing_room_reservation_ids, count($room_type_ids));

        RoomReservation::whereIn('id', $deleted_room_reservation_ids)
            ->each(function ($roomReservation) use ($reservation) {
                $roomReservation->is_delete = true;
                $roomReservation->deleted_by = Auth::user()->id;
                $roomReservation->deleted_at = now();
                $roomReservation->room_type_id = $reservation->room_type_id;
                $roomReservation->room_id = $reservation->room_id;
                $roomReservation->room_rate_id = $reservation->room_rate_id;
                $roomReservation->save();
            });

        $new_room_type_ids = array_slice($room_type_ids, count($room_reservations));
        $new_room_rate_ids = is_array($room_rate_ids) ? array_slice($room_rate_ids, count($room_reservations)) : [];
        $new_room_ids = is_array($room_ids) ? array_slice($room_ids, count($room_reservations)) : [];
        $new_check_in_dates = is_array($check_in_dates) ? array_slice($check_in_dates, count($room_reservations)) : [];
        $new_check_out_dates = is_array($check_out_dates) ? array_slice($check_out_dates, count($room_reservations)) : [];

        foreach ($new_room_type_ids as $index => $new_room_type_id) {
            $check_in_date = isset($new_check_in_dates[$index]) ? $new_check_in_dates[$index] : null;
            $check_out_date = isset($new_check_out_dates[$index]) ? $new_check_out_dates[$index] : null;

            $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
            $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
            $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
            $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

            $new_room_reservation = new RoomReservation();
            $new_room_reservation->reservation_id = $reservation->id;
            $new_room_reservation->room_type_id = $new_room_type_id;
            $new_room_reservation->room_rate_id = isset($new_room_rate_ids[$index]) ? $new_room_rate_ids[$index] : null;
            $new_room_reservation->room_id = isset($new_room_ids[$index]) ? $new_room_ids[$index] : null;
            $new_room_reservation->guest_id = isset($reservation->guest_id) ? $reservation->guest_id : $reservation->company_id;
            $new_room_reservation->room_check_in_date = $formatted_check_in_date;
            $new_room_reservation->room_check_out_date = $formatted_check_out_date;
            $new_room_reservation->remark = $reservation->remark;
            $new_room_reservation->created_by = Auth::user()->id;
            $new_room_reservation->save();
        }
    }

    public function addNewGuest($id)
    {
        $reservation = Reservation::where('id', $id)->select('guest_id')->first();
        $formType = 'add_new_guest';

        return view('reservation::reservation.addNewGuest')->with(compact('reservation', 'formType'));
    }

    public function updateGuestInfo(Request $request, $reservationId)
    {

        try {
            $reservation = Reservation::findOrFail($reservationId);
            if ($reservation) {

                $contact = $reservation->contact;
                $company = $reservation->company;

                $dob_value = $request->input('dob');
                $date = DateTime::createFromFormat('d/m/Y', $dob_value);
                $dob = !empty($dob_value) ? $date->format('Y-m-d') : null;

                if ($contact) {
                    $contact->prefix = $request->input('prefix');
                    $contact->first_name = $request->input('first_name');
                    $contact->middle_name = $request->input('middle_name');
                    $contact->last_name = $request->input('last_name');
                    $contact->email = $request->input('email');
                    $contact->city = $request->input('city');
                    $contact->state = $request->input('state');
                    $contact->country = $request->input('country');
                    $contact->address_line_1 = $request->input('address_line_1');
                    $contact->address_line_2 = $request->input('address_line_2');
                    $contact->dob = $dob;
                    $contact->mobile = $request->input('mobile');
                    $contact->alternate_number = $request->input('alternate_number');
                    $contact->zip_code = $request->input('zip_code');
                    // dd($contact);
                    $contact->save();
                }
                if($company) {
                    $company->company_name = $request->input('company_name');
                    // $company->prefix = $request->input('prefix');
                    // $company->first_name = $request->input('first_name');
                    // $company->middle_name = $request->input('middle_name');
                    // $company->last_name = $request->input('last_name');
                    $company->email = $request->input('email');
                    $company->city = $request->input('city');
                    $company->state = $request->input('state');
                    $company->country = $request->input('country');
                    $company->address_line_1 = $request->input('address_line_1');
                    $company->address_line_2 = $request->input('address_line_2');
                    $company->dob = $dob;
                    $company->mobile = $request->input('mobile');
                    $company->alternate_number = $request->input('alternate_number');
                    $company->zip_code = $request->input('zip_code');
                    $company->save();
                }

                return back()->with(['success' => 'Guest Updated Successfully', 'updated' => 'guestTab']);
            } else {
                return back()->with('error', 'Guest not found');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating the guest');
        }
    }

    public function getFolioInvoicesForAllTab($id)
    {
        $reservationIds = explode(',', $id);

        $reservations = Reservation::whereIn('id', $reservationIds)->with('room_reservations', 'room_sales', 'contact', 'company')->get();
        $folioInvoices = FolioInvoice::whereIn('reservation_id', $reservationIds)->get();
        
        $sales = [];
        $room_sales = [];

        if ($folioInvoices) {
            foreach ($folioInvoices as $folioInvoice) {

                $folioInvoiceDetail = FolioInvoiceDetail::where('folio_invoice_id', $folioInvoice->id)->get();
                if ($folioInvoiceDetail) {
                    foreach ($folioInvoiceDetail as $detail) {
                        if ($detail->transaction_type == 'room') {
                            $room_sale_id = $detail->transaction_id;
                            $room_sale = RoomSale::where('id', $room_sale_id)->first();

                            if($room_sale) {
                                $room_sales[] = $room_sale;
                            }
                        }
                        if ($detail->transaction_type == 'sale') {
                            $sale_id = $detail->transaction_id;
                            $sale = Sales::where('id', $sale_id)->with('sale_details', 'sale_details.product', 'sale_details.uom')->first();

                            if ($sale) {
                                $sales[] = $sale;
                            }
                        }
                    }
                }
            }
        }
        try {
            $invoiceHtml = view('reservation::reservation.invoice.folioInvoiceForAll', compact('reservations', 'folioInvoices', 'folioInvoiceDetail', 'room_sales', 'sales'))->render();
            return response()->json(['html' => $invoiceHtml]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
