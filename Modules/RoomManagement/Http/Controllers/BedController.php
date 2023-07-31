<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\Bed;
use Yajra\DataTables\Facades\DataTables;

class BedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {
        if(request()->ajax()){
            $beds = Bed::all();

            return DataTables::of($beds)
                ->editColumn('room_id' , function(Bed $bed){
                    return $bed->room->name;
                })
                ->editColumn('bed_type_id' , function(Bed $bed){
                    return $bed->bed_type->name;
                })
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('bed.edit', $row->id).'"
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
        return view('roommanagement::bed.index');
    }

    public function create() {
        return view('roommanagement::bed.create');
    }

    public function store(Request $request) {
        try{
            
            Bed::create([
                'room_id' => $request['room_id'],
                'bed_type_id' => $request['bed_type_id'],
                'is_active' => !empty($request['is_active']) ? $request['is_active'] : 'Inactive',
                'created_by' => Auth::user()->id
            ]);

            return redirect('/room-management/bed')->with('success','Bed Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the bed');
        }
    }

    public function edit($id) {
        $bed = Bed::find($id);

        return view('roommanagement::bed.edit')->with(compact('bed'));
    }

    public function update(Request $request,$id) {
        if(request()->ajax()){
            try{
                $bed = Bed::find($id);

                $bed->room_id = $request['room_id'];
                $bed->bed_type_id = $request['bed_type_id'];
                $bed->is_active = !empty($request['is_active']) ? 'Active' : 'Inactive';
                $bed->updated_by = Auth::user()->id;
                
                $bed->save();

                return response()->json(['success' => true, 'msg' => 'Bed Updated Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the bed']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
               $bed = Bed::find($id);
               $bed->delete();
              
                return response()->json(['success' => true, 'msg' => 'Bed Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Bed not found']);
            }
        }
    }
}
