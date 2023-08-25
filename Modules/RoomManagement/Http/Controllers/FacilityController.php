<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\Facility;
use Yajra\DataTables\Facades\DataTables;

class FacilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {
        if(request()->ajax()){
            $facilities =Facility::all();

            return DataTables::of($facilities)
                ->editColumn('building_id' , function(Facility $facility){
                    return $facility->building->name;
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('facility.edit', $row->id).'"
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
        return view('roommanagement::facility.index');
    }

    public function create() {
        return view('roommanagement::facility.create');
    }

    public function store(Request $request) {
        try{
            
            Facility::create([
                'building_id' => $request['building_id'],
                'name' => $request['name'],
                'description' => $request['description'],
                'is_active' => !empty($request['is_active']) ? $request['is_active'] : 'Inactive',
                'created_by' => Auth::user()->id
            ]);

            return redirect('/room-management/facility')->with('success','Facility Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the facility');
        }
    }

    public function edit($id) {
        $facility = Facility::find($id);

        return view('roommanagement::facility.edit')->with(compact('facility'));
    }

    public function update(Request $request, $id) {
        if($request->ajax()){
            try{
                $facility = Facility::find($id);
            
                $facility->building_id = $request['building_id'];
                $facility->name = $request['name'];
                $facility->description = $request['description'];
                $facility->is_active = !empty($request['is_active']) ? 'Active' : 'Inactive';
                $facility->updated_by = Auth::user()->id;

                $facility->update();

                return response()->json(['success' => true, 'msg' => 'Facility Updated Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the facility']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
               $facility = Facility::find($id);
               $facility->delete();
              
                return response()->json(['success' => true, 'msg' => 'Facility Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Facility not found']);
            }
        }
    }
}
