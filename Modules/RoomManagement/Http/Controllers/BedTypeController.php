<?php

namespace Modules\RoomManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RoomManagement\Entities\BedType;
use Yajra\DataTables\Facades\DataTables;

class BedTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index() {

        if(request()->ajax()){
            $bed_types = BedType::all();

            return DataTables::of($bed_types)
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
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('bed-type.edit', $row->id).'"
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
        return view('roommanagement::bed_type.index');
    }

    public function create() {
        return view('roommanagement::bed_type.create');
    }

    public function store(Request $request) {
        try{
            $bed_type = $request->only(['name','description']);
            $bed_type['created_by'] = Auth::user()->id;

            BedType::create($bed_type);

            return redirect('/room-management/bed-type')->with('success','Bed Type Created Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the bed type');
        }
    }

    public function edit($id) {
        $bed_type = BedType::find($id);

        return view('roommanagement::bed_type.edit')->with(compact('bed_type'));
    }

    public function update(Request $request, $id){
        if($request->ajax()){
            try{
                $bed_type = BedType::find($id);

                $bed_type->name = $request['name'];
                $bed_type->description = $request['description'];
                $bed_type->updated_by = Auth::user()->id;

                $bed_type->update();

                return response()->json(['success' => true, 'msg' => 'Bed Type Updated Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the bed type']);
            }
        }
    }

    public function destroy($id) {
        if(request()->ajax()){
            try{
               $bed_type =BedType::find($id);
               $bed_type->delete();
              
                return response()->json(['success' => true, 'msg' => 'Bed Type Deleted Successfully']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => 'Bed Type not found']);
            }
        }
    }
}
