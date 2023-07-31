<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\RoomType;
use Yajra\DataTables\Facades\DataTables;

class RoomTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {

        if(request()->ajax()){
            $room_types = RoomType::all();

            return DataTables::of($room_types)
                ->addColumn('action', function($row){
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('room-type.edit', $row->id).'"
                                    >
                                        <i class="fas fa-pen-to-square me-3"></i> Edit
                                    </button>
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
        return view('roommanagement::room_type.index');
    }

    public function show() {

    }

    public function create() {
        return view('roommanagement::room_type.create');
    }

    public function store(Request $request) {
        try{
            $room_type = $request->only(['name','description']);
            $room_type['created_by'] = Auth::user()->id;

            RoomType::create($room_type);

            return redirect('/room-management/room-type')->with('success','Room Type Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the room type');
        }
    }

    public function edit($id) {
        $room_type = RoomType::find($id);
        return view('roommanagement::room_type.edit')->with(compact('room_type'));
    }

    public function update(Request $request, $id) {
        if($request->ajax()){
            try{
                $room_type = RoomType::find($id);

                $room_type->name = $request['name'];
                $room_type->description = $request['description'];
                $room_type->updated_by = Auth::user()->id;

                $room_type->update();

                return response()->json(['success' => true, 'msg' => 'Room Type Updated Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the room type']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
               $room_type = RoomType::find($id);
               $room_type->delete();
              
                return response()->json(['success' => true, 'msg' => 'Room Type Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Room Type not found']);
            }
        }
    }
}
