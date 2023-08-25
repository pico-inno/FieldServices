<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\RoomRate;
use Yajra\DataTables\Facades\DataTables;
use DateTime;

class RoomRateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {
        if (request()->ajax()) {
            $room_rates = RoomRate::all();

            return DataTables::of($room_rates)
                ->editColumn('room_type_id', function (RoomRate $room_rate) {
                    return $room_rate->room_type->name;
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
                                    <a href="' . route('room-rate.edit', $row->id) . '" class="dropdown-item p-2"><i class="fas fa-pen-to-square me-3"></i> Edit</a>
                                </li>
                                <li>
                                    <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                        <i class="fas fa-trash me-3"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                        ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('roommanagement::room_rate.index');
    }

    public function create() {
        return view('roommanagement::room_rate.create');
    }

    public function store(Request $request) {
        try {
            $room_rate = $request->only(['room_type_id', 'rate_name', 'rate_amount', 'booking_rule', 'cancellation_rule', 'min_stay', 'max_stay']);
        
            $start_date = $request->input('start_date');
            // dd($start_date);
            $end_date = $request->input('end_date');

            $start_date_format = DateTime::createFromFormat('d/m/Y', $start_date);
            $room_rate['start_date'] = !empty($start_date) ? $start_date_format->format('Y-m-d') : null;

            $end_date_format = DateTime::createFromFormat('d/m/Y', $end_date);
            $room_rate['end_date'] = !empty($end_date) ? $end_date_format->format('Y-m-d') : null;

            $room_rate['created_by'] = Auth::user()->id;
            // dd($room_rate);
            RoomRate::create($room_rate);

            return redirect('/room-management/room-rate')->with('success', 'Room Rate Created Successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the room rate');
        }
    }

    public function edit($id) {
        $room_rate = RoomRate::find($id);
        return view('roommanagement::room_rate.edit')->with(compact('room_rate'));
    }

    public function update(Request $request, $id) {
        try {
            $room_rate = RoomRate::find($id);

            $room_rate->room_type_id = $request['room_type_id'];
            $room_rate->rate_name = $request['rate_name'];
            $room_rate->rate_amount = $request['rate_amount'];
            $start_date = $request['start_date'];
            $start_date_format = DateTime::createFromFormat('d/m/Y', $start_date);
            $room_rate->start_date = !empty($start_date) ? $start_date_format->format('Y-m-d') : null;
            $end_date = $request['end_date'];
            $end_date_format = DateTime::createFromFormat('d/m/Y', $end_date);
            $room_rate->end_date = !empty($end_date) ? $end_date_format->format('Y-m-d') : null;        
            $room_rate->booking_rule = $request['booking_rule'];
            $room_rate->cancellation_rule = $request['cancellation_rule'];
            $room_rate->min_stay = $request['min_stay'];
            $room_rate->max_stay = $request['max_stay'];
            $room_rate->updated_by = Auth::user()->id;

            $room_rate->update();

            return redirect('/room-management/room-rate')->with('success','Room Rate Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the room rate');
        }
    }

    public function destroy($id) 
    {
        if(request()->ajax()){
            try{
                $room_rate = RoomRate::find($id);
                $room_rate->delete();
              
                return response()->json(['success' => true, 'msg' => 'Room Rate Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Room rate not found']);
            }
        }
    }

}
