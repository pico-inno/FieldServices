<?php

namespace App\Http\Controllers;

use App\Models\LocalTownship;
use App\Repositories\AddressRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function getTownships($region_id)
    {
        $townships = LocalTownship::where('region_id', $region_id)->get();

        return response()->json($townships);
    }

    public function getAddress(AddressRepository $addressRepository)
    {
        $addresses = $addressRepository->all();

        return response()->json($addresses);
    }

    public function storeAddressAndReturnAll(Request $request, AddressRepository $addressRepository)
    {
        try {
            DB::beginTransaction();
            $customerId = Auth::guard('customer')->id();

            $existingAddress = $addressRepository->query()
                ->where('contact_id', $customerId)
                ->exists();


            $address = $addressRepository->create([
                'contact_id' =>  $customerId,
                'address_line_1' => $request->address,
                'township_id' =>  $request->township,
                'region_id' => $request->region,
                'postal_zip_code' => 0,
                'country' => 'Myanmar',
                'phone' => Auth::guard('customer')->user()->phone,
                'is_default' => !$existingAddress,
            ]);

            $addresses = $addressRepository->query()
                ->where('contact_id',Auth::guard('customer')->user()->id)
                ->leftJoin('local_regions', 'local_regions.id', '=', 'local_addresses.region_id')
                ->leftJoin('local_townships', 'local_townships.id', '=', 'local_addresses.township_id')
                ->select(
                    'local_addresses.*',
                    'local_regions.en_name as region_en_name',
                    'local_regions.mm_name as region_mm_name',
                    'local_townships.en_name as township_en_name',
                    'local_townships.mm_name as township_mm_name',

                )
                ->get();
            DB::commit();
            return response()->json(['message' => 'LocalAddress created successfully', 'data' => $address, 'addresses'=> $addresses]);
        }catch (Exception $exception){
            return response()->json(['message' => 'error', 'data' => $exception]);
        }

    }
}
