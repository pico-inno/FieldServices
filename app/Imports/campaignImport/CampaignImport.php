<?php

namespace App\Imports\campaignImport;

use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Actions\location\locationActions;
use App\Models\InvoiceTemplate;
use App\Models\Product\PriceLists;
use App\Models\settings\businessLocation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class CampaignImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts
{
    use Importable;


    private $imageCoordinates = [];

    private $rowCount;


    public function __construct()
    {

        ini_set('max_execution_time', '0');
        ini_set("memory_limit", "-1");
        ini_set("max_allowed_packet", "-1");

    }



    public function collection(Collection $rows)
    {
        dd($rows,'===========');
        try {
            DB::beginTransaction();
            $chunkedRows = $rows->chunk(100);
            foreach ($chunkedRows as  $rows) {
                dd($rows);
                foreach ($rows as  $row) {
                    $action=new locationActions();
                    $location = $action->createLocation($locationData);
                    $action->createLocationAddress($locationAddress, $location);
                }
            }
            DB::commit();
            return back()->with(['success' => 'Successfully Imported']);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return throw new Exception($th->getMessage());
            //throw $th;
        }
    }



    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    // public function rules(): array
    // {

    //     return [
    //         '*.location_name' => [
    //             'required'
    //         ],
    //         '*.location_type'=>[
    //             'required',
    //             function($attribute, $value, $fail) {
    //                 $validValues = ['supplier location', 'customer location', 'view', 'internal location', 'transit location'];
    //                 $value=strtolower(rtrim($value));
    //                 $isExist = in_array($value, $validValues);
    //                 if (!$isExist) {
    //                     $fail('The  Location Type' . $value . ' is invalid.');
    //                 }
    //             }
    //         ],

    //         '*.parent_location' => [
    //             'nullable',
    //             function ($attribute, $value, $fail) {
    //                 $value = strtolower(rtrim($value));
    //                 $checkLocation=businessLocation::where('name',$value)->exists();
    //                 if (!$checkLocation) {
    //                     $fail('The  parent location "' . $value . '" not found.');
    //                 }
    //             }
    //         ],

    //         '*.default_price_list' => [
    //             'nullable',
    //             function ($attribute, $value, $fail) {
    //                 $value = strtolower(rtrim($value));
    //                 $checkLocation = PriceLists::where('name', $value)->exists();
    //                 if (!$checkLocation) {
    //                     $fail('The  Price List name "' . $value . '" not found.');
    //                 }
    //             }
    //         ],

    //         '*.invoice_layout' => [
    //             'nullable',
    //             function ($attribute, $value, $fail) {
    //                 $value = strtolower(rtrim($value));
    //                 $checkIsExist = InvoiceTemplate::where('name', $value)->exists();
    //                 if (!$checkIsExist) {
    //                     $fail('The  Invoice layout name "' . $value . '" not found.');
    //                 }
    //             }
    //         ],

    //         '*.inventory_flow' => [
    //             'required',
    //             function($attribute, $value, $fail) {
    //                 $validValues = ['fifo', 'lifo'];
    //                 $value=strtolower(rtrim($value));
    //                 $isExist = in_array($value, $validValues);
    //                 if (!$isExist) {
    //                     $fail('The  ' . $attribute . ' is invalid.');
    //                 }
    //             }
    //         ],
    //         '*.sale_order'=>[
    //             'nullable',
    //             Rule::in([1,0])
    //         ],
    //         '*.purchase_order' => [
    //             'nullable',
    //             Rule::in([1, 0])
    //         ]

    //     ];
    // }
    // public function customValidationMessages()
    // {
    //     return [
    //         '*.location_name.required' => 'Location Name is Required.',
    //         '*.inventory_flow.required' => 'Inventory Flow is Required.',
    //         '*.location_type.iin' => 'Location Type is Invalid.',
    //     ];
    // }
}
