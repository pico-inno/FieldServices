<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\Building;
use Yajra\DataTables\Facades\DataTables;

class BuildingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index() {

        if(request()->ajax()){
            $buildings = Building::all();

            return DataTables::of($buildings)
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('building.edit', $row->id).'"
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
        return view('App.building.index');
    }

    public function show() {

    }

    public function create() {
        return view('App.building.create');
    }

    public function store(Request $request) {
        try{
            $building = $request->only(['name','description']);
            $building['created_by'] = Auth::user()->id;

            Building::create($building);

            return redirect('/building')->with('success','Building Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the building');
        }
    }

    public function edit($id) {
        $building = Building::find($id);
        return view('App.building.edit')->with(compact('building'));
    }

    public function update(Request $request, $id) {
        if($request->ajax()){
            try{
                $building = Building::find($id);

                $building->name = $request['name'];
                $building->description = $request['description'];
                $building->updated_by = Auth::user()->id;

                $building->update();

                return response()->json(['success' => true, 'msg' => 'Building Updated Successfully']);
            } catch(\Exception $e){
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the building']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
                $building = Building::find($id);
                $building->delete();

                return response()->json(['success' => true, 'msg' => 'Building Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Building not found']);
            }
        }
    }
}
