<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact\Contact;
use App\Models\hospitalRegistrations;
use App\Models\BusinessUser;
use App\Models\hospitalFolioInvoiceDetails;
use App\Models\hospitalFolioInvoices;
use Modules\Reservation\Entities\FolioInvoice;
use Modules\Reservation\Entities\FolioInvoiceDetail;
use Modules\Reservation\Entities\Reservation;
use Modules\RoomManagement\Entities\RoomSale;
use Modules\RoomManagement\Entities\RoomSaleDetail;
use Modules\RoomManagement\Entities\Room;
use Modules\RoomManagement\Entities\RoomRate;
use Modules\RoomManagement\Entities\RoomType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Yajra\DataTables\Facades\DataTables;

class RoomSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {
        if (request()->ajax()) {
            $room_sales = RoomSale::with('room_sale_details')->get();

            return DataTables::of($room_sales)
            ->editColumn('transaction_id', function($room_sale) {
                if ($room_sale->transaction_type == 'reservation') {
                    $reservation = Reservation::find($room_sale->transaction_id);
                    if ($reservation) {
                        return $reservation->reservation_code;
                    }
                } elseif ($room_sale->transaction_type == 'registration') {
                    $registration = hospitalRegistrations::find($room_sale->transaction_id);
                    if ($registration) {
                        return $registration->registration_code;
                    }
                }
            
                return '';
            })
            ->addColumn('room', function ($room_sale) {
                $roomName = RoomSaleDetail::whereIn('id', $room_sale->room_sale_details->pluck('id'))
                ->pluck('room_id')
                ->map(function ($roomId) {
                    $room = Room::find($roomId);
                    return $room ? $room->name : '';
                })
                ->toArray();

                return implode(', ', $roomName);
            })
            ->addColumn('room_type', function ($room_sale) {
                $roomTypeName = RoomSaleDetail::whereIn('id', $room_sale->room_sale_details->pluck('id'))
                ->pluck('room_type_id')
                ->map(function ($roomTypeId) {
                    $roomType = RoomType::find($roomTypeId);
                    return $roomType ? $roomType->name : '';
                })
                ->toArray();

            return implode(', ', $roomTypeName);
            })
            ->addColumn('room_rate', function ($room_sale) {
                $roomRateName = RoomSaleDetail::whereIn('id', $room_sale->room_sale_details->pluck('id'))
                ->pluck('room_rate_id')
                ->map(function ($roomRateId) {
                    $roomRate = RoomRate::find($roomRateId);
                    return $roomRate ? $roomRate->rate_name : '';
                })
                ->toArray();

            return implode(', ', $roomRateName);
            })
            ->addColumn('before_discount_amount', function (RoomSale $room_sale) {
                $before_discount_amount = $room_sale->room_sale_details->first()->before_discount_amount;
                return $before_discount_amount;
            })
            ->addColumn('qty', function (RoomSale $room_sale) {
                $qty = $room_sale->room_sale_details->first()->qty;
                return $qty;
            })
            ->editColumn('confirm_by', function ($room_sale) {
                $confirmed_user = BusinessUser::find($room_sale->confirm_by);
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
                                <button type="button" class="dropdown-item p-2 view_room_sale" data-href="'.route('room-sale.show', $row->id).'"
                                >
                                    <i class="fa-solid fa-eye me-3"></i> View
                                </button>
                            </li>                            
                            <li>
                                <a href="' . route('room-sale.edit', $row->id) . '" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                            </li>
                            <li>
                                <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                    <i class="fa-solid fa-trash me-3"></i> Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('roommanagement::room_sale.index');
    }

    public function show($id) {
        $room_sale = RoomSale::where('id', $id)
        ->with('room_sale_details',
                'reservation',
                'hospital_registration',
                'business_location'
        )
        ->first();
        return view('roommanagement::room_sale.show')->with(compact('room_sale'));
    }

    public function create() {
        $contacts = Contact::where('type', 'Customer')->get();

        $reservations = Reservation::with(
            'contact',
            'company',
            'room_reservations',
            'room_reservations.room_type',
            'room_reservations.room_rate',
            'room_reservations.room'
            )->get();

        //for registration
        $registrations = hospitalRegistrations::where('registration_type','IPD')
                                                ->with('patient', 'hospitalRoomRegistrations')->get();
        return view('roommanagement::room_sale.create')->with(compact('contacts', 'registrations', 'reservations', 'registrations'));
    }

    public function store(Request $request) {
        try {
            $roomsale_data = $request->only([
                'transaction_type',
                'business_location_id',
                'sale_amount',
                'total_item_discount',
                'total_sale_amount',
                'paid_amount',
                'balance_amount'
            ]);
            // dd($roomsale_data);
            $roomSaleCount = RoomSale::count();
            $roomsale_data['room_sales_voucher_no'] = 'INV-' . str_pad($roomSaleCount+ 1, 6, '0', STR_PAD_LEFT);
            $roomsale_data['transaction_id']=($request->transaction_type== 'reservation')? $request->reservation_id : $request->registration_id;
            $roomsale_data['contact_id']=($request->transaction_type== 'reservation')? $request->guest_id : $request->patient_id;
            $roomsale_data['confirm_at'] = now();
            $roomsale_data['confirm_by'] = Auth::user()->id;
            $roomsale_data['created_by'] = Auth::user()->id;

            $room_type_ids = $request->input('room_type_id');
            $room_rate_ids = $request->input('room_rate_id');
            $room_ids = $request->input('room_id');
            $check_in_dates = $request->input('check_in_date');
            $check_out_dates = $request->input('check_out_date');
            $qtys = $request->input('qty');
            $uoms = $request->input('uom');
            $currencies = $request->input('currencies');
            $room_fees = $request->input('room_fees');
            $subtotals = $request->input('subtotal');
            $discount_types = $request->input('discount_type');
            $per_item_discounts = $request->input('per_item_discount');

            DB::beginTransaction();

            $roomSale = RoomSale::create($roomsale_data);
            foreach ($room_type_ids as $index => $room_type_id) {
                $room_type_id = isset($room_type_ids[$index]) ? $room_type_ids[$index] : null;
                $room_rate_id = isset($room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
                $room_id = isset($room_ids[$index]) ? $room_ids[$index] : null;
                $room_fee = isset($room_fees[$index]) ? $room_fees[$index] : null;
                $subtotal = isset($subtotals[$index]) ? $subtotals[$index] : null;
                $discount_type = isset($discount_types[$index]) ? $discount_types[$index] : null;
                $per_item_discount = isset($per_item_discounts[$index]) ? $per_item_discounts[$index] : null;

                $check_in_date = isset($check_in_dates[$index]) ? $check_in_dates[$index] : null;
                $check_out_date = isset($check_out_dates[$index]) ? $check_out_dates[$index] : null;

                $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
                $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
                $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
                $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

                $roomSale->room_sale_details()->create([
                    'room_type_id' => $room_type_id,
                    'room_id' => $room_id,
                    'contact_id' => $roomSale->contact_id,
                    'room_rate_id' => $room_rate_id,
                    'check_in_date' => $formatted_check_in_date,
                    'check_out_date' => $formatted_check_out_date,
                    'qty' => $qtys[$index],
                    'uom_id' => $uoms[$index] ?? null,
                    'currency_id' => $currencies[$index] ?? null,
                    'room_fees' => $room_fee,
                    'subtotal' => $subtotal,
                    'discount_type' => $discount_type,
                    'per_item_discount' => $per_item_discount,
                ]);
            }

            if($request->transaction_type == 'reservation'){
                $reservation_id = $request->input('reservation_id');
                $folioInvoice = FolioInvoice::where('reservation_id', $reservation_id)->select('id')->first();
                if($folioInvoice) {
                    $folioInvoiceDetail = FolioInvoiceDetail::where('folio_invoice_id', $folioInvoice->id)->get();
                    foreach ($folioInvoiceDetail as $detail) {
                        $detail->transaction_type = 'room';
                        $detail->transaction_id = $roomSale->id;
                        $detail->confirm_at = now();
                        $detail->confirm_by = Auth::user()->id;
                        $detail->save();
                    }
                }
            }elseif($request->transaction_type == 'registration'){
                $registration_id = $request->registration_id;
                $folioInvoice = hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first();
                if ($folioInvoice) {
                    hospitalFolioInvoiceDetails::create([
                        'folio_invoice_id'=> $folioInvoice->id,
                        'transaction_type' => 'room',
                        'transaction_id' => $roomSale->id,
                        'confirm_at' => now(),
                        'confirm_by' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect('/room-management/room-sale')->with('success', 'Room Sale Created Successfully');

        } catch (\Exception $e) {
            dd($e);
                DB::rollBack();
                return redirect()->back()->with('error', 'An error occurred while creating the reservation');
        }
    }

    public function edit($id) {
        $room_sale = RoomSale::with('room_sale_details', 'reservation')->find($id);
        $reservations = Reservation::with('contact', 'company')->get();
        $registrations = hospitalRegistrations::where('registration_type', 'IPD')
                        ->with('patient')->get();
        return view('roommanagement::room_sale.edit')->with(compact('room_sale', 'reservations','registrations'));
    }

    public function update(Request $request, $id) {
        try {
            $room_sale = RoomSale::find($id);

            $room_sale->contact_id = $room_sale->transaction_type == 'reservation' ? $request->guest_id : $request->patient_id;
            $room_sale->business_location_id = $request['business_location_id'];
            $room_sale->sale_amount = $request['sale_amount'];
            $room_sale->total_item_discount = $request['total_item_discount'];
            $room_sale->total_sale_amount = $request['total_sale_amount'];
            $room_sale->paid_amount = $request['paid_amount'];
            $room_sale->balance_amount = $request['balance_amount'];
            $room_sale->updated_by = Auth::user()->id;
            DB::beginTransaction();
            $room_sale->update();

            $room_type_ids = $request->input('room_type_id');
            $room_rate_ids = $request->input('room_rate_id');
            $room_ids = $request->input('room_id');
            $check_in_dates = $request->input('check_in_date');
            $check_out_dates = $request->input('check_out_date');
            $qtys = $request->input('qty');
            $uoms = $request->input('uom');
            $currencies = $request->input('currencies');
            $room_fees = $request->input('room_fees');
            $subtotals = $request->input('subtotal');
            $discount_types = $request->input('discount_type');
            $per_item_discounts = $request->input('per_item_discount');

            // dd($qtys);
            $room_sale_details = RoomSaleDetail::where('room_sale_id', $room_sale->id)->get();

            foreach ($room_sale_details as $index => $room_sale_detail) {
                $room_type_id = isset($room_type_ids[$index]) ? $room_type_ids[$index] : null;
                $room_rate_id = isset($room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
                $room_id = isset($room_ids[$index]) ? $room_ids[$index] : null;
                $room_fee = isset($room_fees[$index]) ? $room_fees[$index] : null;
                $subtotal = isset($subtotals[$index]) ? $subtotals[$index] : null;
                $discount_type = isset($discount_types[$index]) ? $discount_types[$index] : null;
                $per_item_discount = isset($per_item_discounts[$index]) ? $per_item_discounts[$index] : null;

                $check_in_date = isset($check_in_dates[$index]) ? $check_in_dates[$index] : null;
                $check_out_date = isset($check_out_dates[$index]) ? $check_out_dates[$index] : null;

                $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_in_date);
                $formatted_check_in_date = !empty($check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
                $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $check_out_date);
                $formatted_check_out_date = !empty($check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

                $room_sale_detail->room_type_id = $room_type_id;
                $room_sale_detail->room_rate_id = $room_rate_id;
                $room_sale_detail->room_id = $room_id;
                $room_sale_detail->check_in_date = $formatted_check_in_date;
                $room_sale_detail->check_out_date = $formatted_check_out_date;
                $room_sale_detail->qty = $qtys[$index] ?? null;
                $room_sale_detail->uom_id =  $uoms[$index] ?? null;
                $room_sale_detail->currency_id = $currencies[$index] ?? null;
                $room_sale_detail->room_fees =   $room_fee;
                $room_sale_detail->subtotal =   $subtotal;
                $room_sale_detail->discount_type =   $discount_type;
                $room_sale_detail->per_item_discount =  $per_item_discount;
                $room_sale_detail->check_in_date =  $formatted_check_in_date;
                $room_sale_detail->check_out_date =  $formatted_check_out_date;

                $room_sale_detail->updated_by = Auth::user()->id;
                $room_sale_detail->save();
            }


            $existing_room_sale_ids = $room_sale_details->pluck('id')->all();
            // $kept_room_reservation_ids = array_slice($existing_room_sale_ids, 0, count($room_type_ids));
            $deleted_room_sale_ids = array_slice($existing_room_sale_ids, count($room_fees));


            RoomSaleDetail::whereIn('id', $deleted_room_sale_ids)
            ->each(function ($roomSaleDetail) {
                $roomSaleDetail->is_delete = true;
                $roomSaleDetail->deleted_by = Auth::user()->id;
                $roomSaleDetail->deleted_at = now();
                $roomSaleDetail->save();
            });


            // Add new room reservation
            $new_room_type_ids = array_slice($room_type_ids, count($room_sale_details));
            $new_room_rate_ids = is_array($room_rate_ids) ? array_slice($room_rate_ids, count($room_sale_details)) : [];
            $new_room_ids = is_array($room_ids) ? array_slice($room_ids, count($room_sale_details)) : [];
            $new_room_fees = is_array($room_fees) ? array_slice($room_fees, count($room_sale_details)) : [];
            $new_subtotals= is_array($subtotals) ? array_slice($subtotals, count($room_sale_details)) : [];
            $new_qtys = is_array($qtys) ? array_slice($qtys, count($room_sale_details)) : [];
            $new_uoms= is_array($uoms) ? array_slice($uoms, count($room_sale_details)) : [];
            $new_discount_type = is_array($discount_type) ? array_slice($discount_type, count($room_sale_details)) : [];
            $new_per_item_discount = is_array($per_item_discounts) ? array_slice($per_item_discounts, count($room_sale_details)) : [];
            $new_check_in_dates = is_array($check_in_dates) ? array_slice($check_in_dates, count($room_sale_details)) : [];
            $new_check_out_dates = is_array($check_out_dates) ? array_slice($check_out_dates, count($room_sale_details)) : [];


            foreach ($new_room_type_ids as $index => $room_type_id) {


                $new_room_type_id = isset($new_room_type_ids[$index]) ? $new_room_type_ids[$index] : null;
                $new_room_rate_id = isset($new_room_rate_ids[$index]) ? $room_rate_ids[$index] : null;
                $new_room_id = isset($new_room_ids[$index]) ? $new_room_ids[$index] : null;
                $new_room_fee = isset($new_room_fees[$index]) ? $new_room_fees[$index] : null;
                $new_subtotal = isset($new_subtotals[$index]) ? $new_subtotals[$index] : null;
                $new_qty = isset($new_qtys[$index]) ? $new_qtys[$index] : null;
                $new_uom = isset($new_uoms[$index]) ? $new_uoms[$index] : null;
                $new_discount_type = isset($new_discount_type[$index]) ? $new_discount_type[$index] : null;
                $new_per_item_discount = isset($new_per_item_discount[$index]) ? $new_per_item_discount[$index] : null;

                $new_check_in_date = isset($new_check_in_dates[$index]) ? $new_check_in_dates[$index] : null;
                $new_check_out_date = isset($new_check_out_dates[$index]) ? $new_check_out_dates[$index] : null;

                $check_in_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $new_check_in_date);
                $formatted_check_in_date = !empty($new_check_in_date) ? $check_in_date_format->format('Y-m-d H:i:s') : null;
                $check_out_date_format = DateTime::createFromFormat('d/m/Y, h:i A', $new_check_out_date);
                $formatted_check_out_date = !empty($new_check_out_date) ? $check_out_date_format->format('Y-m-d H:i:s') : null;

                $room_sale->room_sale_details()->create([
                    'room_type_id' => $new_room_type_id,
                    'room_id' => $new_room_id,
                    'room_rate_id' => $new_room_rate_id,
                    'contact_id' => $room_sale->contact_id,
                    'check_in_date' => $formatted_check_in_date,
                    'check_out_date' => $formatted_check_out_date,
                    'qty' => $new_qty,
                    'uom_id' => $new_uom,
                    'room_fees' => $new_room_fee,
                    'subtotal' => $new_subtotal,
                    'discount_type' => $new_discount_type,
                    'per_item_discount' => $new_per_item_discount,
                ]);
            }

            $folioInvoiceDetail = FolioInvoiceDetail::where('transaction_id', $id)->get();
            foreach($folioInvoiceDetail as $detail) {
                $detail->updated_by = Auth::user()->id;
                $detail->save();
            }

            DB::commit();

            return redirect('/room-management/room-sale')->with('success', 'Room Sale Updated Successfully');

        } catch(\Exception $e) {
            dd($e);
            DB::rollback();
        return redirect()->back()->with('error', 'An error occurred while updating the reservation');
        }
    }

    public function destroy($id) {
        if (request()->ajax()) {
            try {
                $room_sale = RoomSale::find($id);

                $room_sale->is_delete = true;
                $room_sale->deleted_by = Auth::user()->id;
                $room_sale->save();

                RoomSaleDetail::where('room_sale_id', $id)
                    ->update([
                        'is_delete' => true,
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => now(),
                    ]);

                FolioInvoiceDetail::where('transaction_id', $id)
                    ->update([
                        'is_delete' => true,
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => now(),
                    ]);

                $room_sale->delete();

                return response()->json(['success' => true, 'msg' => 'Room Sale Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Room Sale not found']);
            }
        }
    }
}
