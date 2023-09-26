<?php

namespace App\Http\Controllers\settings;

use App\Actions\location\locationActions;
use Exception;
use App\Models\locationType;
use Illuminate\Http\Request;
use App\Models\Product\PriceLists;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\locationAddress;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;

class businessLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {
        return view('App.businessSetting.location.location');
    }
    public function addForm()
    {
        $priceLists=PriceLists::get();
        $locationType= locationType::get();
        $locations = businessLocation::orderBy('id','DESC')->get();
        return view('App.businessSetting.location.add',compact('priceLists', 'locationType', 'locations'));
    }
    public function add(Request $request,locationActions $action)
    {
        $location=$action->createLocation($request);
        $action->createLocationAddress($request,$location);
        return redirect()->route('business_location')->with(['success'=>'Successfully Created Location']);

    }
    public function listData()
    {
        $locations = businessLocation::query()->with('locationAddress');
        return DataTables::of($locations)
            ->editColumn('name', function ($location) {
                $parentName = getParentName($location->parentLocation);
                $name=$parentName ? substr($parentName,2).' / '  : '' ;
                return $name.$location->name;
            })
            ->addColumn('address', function ($location) {
                return arr($location->locationAddress, 'address');
            })
            ->addColumn('city', function ($location) {
            return arr($location->locationAddress, 'city');
            })
            ->addColumn('zip_code', function ($location) {
            return arr($location->locationAddress, 'zip_code');
            })
            ->addColumn('state', function ($location) {
            return arr($location->locationAddress, 'state');
            })
            ->addColumn('country', function ($location) {
            return arr($location->locationAddress, 'country');
            })
            ->addColumn('checkbox',function($location){
                return
                '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$location->id.' />
                    </div>
                ';
            })
            ->addColumn('action', function ($location) {
                $activationBtn=$location->is_active ?
                            '<a class="dropdown-item p-2 cursor-pointer bg-active-danger text-danger" data-kt-location-table-filter="deactive_row"  data-id="' . $location->id . '">Deactive</a>' :
                            '<a class="dropdown-item p-2 cursor-pointer bg-active-success text-success" data-kt-location-table-filter="active_row"  data-id="' . $location->id . '">Active</a>' ;
                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light-primary btn-active-light-primary fw-semibold fs-7  dropdown-toggle rotate" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="dropdownMenuButton1" role="menu">'
                            .$activationBtn.'
                            <a href="' . route('location_update_form',$location->id) . '" class="dropdown-item p-2 edit-unit bg-active-primary text-primary" data-id="' . $location->id . '" >Edit</a>
                            <a class="dropdown-item p-2 location_delete_confirm cursor-pointer bg-active-danger text-danger"  data-id="'.$location->id. '" data-kt-location-table-filter="delete_row">Delete</a>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function updateForm(businessLocation $businessLocation)
    {
        $bl= $businessLocation;
        $priceLists=PriceLists::get();
        $locationType = locationType::get();
        $locations = businessLocation::orderBy('id', 'DESC')->get();
        $address = locationAddress::where('location_id',$bl->id)->first();
        return view('App.businessSetting.location.edit',compact('bl','priceLists','locationType','locations', 'address'));


    }
    public function update(Request $request,$id)
    {
        $bl = businessLocation::where('id', $id)->first();
        $request['is_active'] = $request['is_active']?? 0;
        $request['allow_purchase_order']= $request['allow_purchase_order'] ?? 0;
        $request['allow_sale_order'] = $request['allow_sale_order'] ?? 0;
        $data=request()->except('_token');
        $bl->update($data);
        return redirect()->route('business_location')->with(['success' => 'Successfully Updted Location']);
    }

    // destory each items
    public function destory($id)
    {
        $blData=businessLocation::where('id',$id)->first();
        $blData->delete();
        $data=[
            'success'=>'Successfully Deleted'
        ];
        return response()->json($data, 200);

    }
    public function deactive($id)
    {
        $blData = businessLocation::where('id', $id)->first();
        $blData->update(['is_active'=>0]);
        $data = [
            'success' => 'Successfully Deactive'
        ];
        return response()->json($data, 200);
    }
    public function active($id)
    {
        $blData = businessLocation::where('id', $id)->first();
        $blData->update(['is_active' => 1]);
        $data = [
            'success' => 'Successfully Active Location'
        ];
        return response()->json($data, 200);
    }
    //destory all selected items
    public function destorySelected()
    {
        $ids= request('data');
        DB::beginTransaction();

        try {
            logger($ids);
            foreach ($ids as $id) {
                $data = businessLocation::where('id', $id)->first();
                $data->delete();
            }
            $data = [
                'success' => 'Successfully Deleted'
            ];

            DB::commit();
            return response()->json($data, 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
            logger($e);
            return response()->json($e, 200);
        }

    }
}
