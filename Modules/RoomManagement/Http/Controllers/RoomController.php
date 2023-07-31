<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\Room;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index()
    {
        if (request()->ajax()) {
            $rooms = Room::all();

            return DataTables::of($rooms)
                ->editColumn('floor_id', function (Room $room) {
                    return $room->floor->name;
                })
                ->editColumn('room_type_id', function (Room $room) {
                    return $room->room_type->name;
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
                                    <a href="' . route('room.edit', $row->id) . '" class="dropdown-item p-2"><i class="fas fa-pen-to-square me-3"></i> Edit</a>
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
        return view('roommanagement::room.index');
    }

    public function create()
    {
        return view('roommanagement::room.create');
    }

    public function store(Request $request)
    {
        try {
            Room::create([
                'floor_id' => $request['floor_id'],
                'room_type_id' => $request['room_type_id'],
                'name' => $request['name'],
                'description' => $request['description'],
                'max_occupancy' => $request['max_occupancy'],
                'is_active' => !empty($request['is_active']) ? $request['is_active'] : 'Inactive',
                'status' => $request['status'],
                'custom_field_1' => $request['custom_field_1'],
                'custom_field_2' => $request['custom_field_2'],
                'custom_field_3' => $request['custom_field_3'],
                'custom_field_4' => $request['custom_field_4'],
                'created_by' => Auth::user()->id
            ]);

            return redirect('/room-management/room')->with('success', 'Room Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the room');
        }
    }

    public function edit($id)
    {
        $room = Room::find($id);
        return view('roommanagement::room.edit')->with(compact('room'));
    }

    public function update(Request $request, $id)
    {
        try {
            $room = Room::find($id);

            $room->floor_id = $request['floor_id'];
            $room->room_type_id = $request['room_type_id'];
            $room->name = $request['name'];
            $room->description = $request['description'];
            $room->max_occupancy = $request['max_occupancy'];
            $room->is_active = !empty($request['is_active']) ? 'Active' : 'Inactive';
            $room->status = $request['status'];
            $room->custom_field_1 = $request['custom_field_1'];
            $room->custom_field_2 = $request['custom_field_2'];
            $room->custom_field_3 = $request['custom_field_3'];
            $room->custom_field_4 = $request['custom_field_4'];
            $room->updated_by = Auth::user()->id;

            $room->update();

            return redirect('/room-management/room')->with('success','Room Updated Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the room');
        }
    }

    public function destroy($id)
    {
        if(request()->ajax()){
            try{
                $room = Room::find($id);
                $room->delete();

                return response()->json(['success' => true, 'msg' => 'Room Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Room not found']);
            }
        }
    }
}
