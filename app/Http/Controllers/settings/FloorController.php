<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\Floor;
use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index() {

        if(request()->ajax()){
            $floors =Floor::all();

            return DataTables::of($floors)
                ->editColumn('business_location_id', function(Floor $floor){
                    return $floor->business_location->name;
                })
                ->editColumn('building_id' , function(Floor $floor){
                    return $floor->building->name;
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('floor.edit', $row->id).'"
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
        return view('App.floor.index');
    }

    public function create() {
        return view('App.floor.create');
    }

    public function store(Request $request) {

        try{

            $floor = $request->only(['building_id','business_location_id','name']);
            $floor['created_by'] = Auth::user()->id;

            Floor::create($floor);

            return redirect('/floor')->with('success','Floor Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the floor');
        }
    }

    public function edit($id) {
        $floor = Floor::find($id);

        return view('App.floor.edit')->with(compact('floor'));
    }

    public function update(Request $request, $id) {

        if($request->ajax()){
            try{
                $floor = Floor::find($id);

                $floor->building_id = $request['building_id'];
                $floor->business_location_id = $request['business_location_id'];
                $floor->name = $request['name'];
                $floor->updated_by = Auth::user()->id;

                $floor->update();

                return response()->json(['success' => true, 'msg' => 'Floor Updated Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the floor']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
               $floor = Floor::find($id);
               $floor->delete();

                return response()->json(['success' => true, 'msg' => 'Floor Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Floor not found']);
            }
        }
    }
}
