<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Service\Entities\ServiceType;
use Modules\Service\Http\Requests\ServiceType\ServiceTypeCreateRequest;
use Modules\Service\Http\Requests\ServiceType\ServiceTypeUpdateRequest;

class ServiceTypeController extends Controller
{
    public function datas()
    {
        $service_types = ServiceType::all();

        return DataTables::of($service_types)
        ->addColumn('action', function($service_type){
            return $service_type->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }


    public function index()
    {
        return view('service::service-type.service-typeList');
    }

    public function add()
    {
        return view('service::service-type.service-typeAdd');
    }

    public function create(ServiceTypeCreateRequest $request)
    {
        ServiceType::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active') ?? 0
        ]);

        return redirect(route('service-type'))->with('message', 'Created sucessfully service type');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service::service-type.service-typeEdit', compact('serviceType'));
    }

    public function update(ServiceType $serviceType, ServiceTypeUpdateRequest $request)
    {
        $serviceType->name = $request->name;
        $serviceType->description = $request->description;
        $serviceType->is_active = $request->is_active ?? 0;

        $serviceType->save();

        return redirect(route('service-type'))->with('message', 'Updated sucessfully service type');
    }

    public function delete(ServiceType $serviceType)
    {
        $serviceType->delete();

        return response()->json(['message' => 'Deleted sucessfully service type']);
    }
}
