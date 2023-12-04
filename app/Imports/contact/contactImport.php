<?php

namespace App\Imports\contact;
use Exception;
use Carbon\Carbon;
use App\Models\Contact\Contact;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Contact\Contact as ContactContact;
use App\Models\Product\PriceLists;

class contactImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();
            $chunkedRows = $rows->chunk(100);
            foreach ($chunkedRows as  $rows) {
                foreach ($rows as  $row) {
                    if($row['contact_type'] == 'supplier' && trim($row['company_name'])== ''){
                        return throw new Exception('If the content type is supplier, Company Name is require.');
                    }
                    elseif($row['contact_type'] == 'Customer' && trim($row['first_name']) == '' && trim($row['middle_name']) == '' && trim($row['last_name']) == ''){
                        return throw new Exception('If the content type is customer,Customer Name is require.');
                    }elseif($row['contact_type'] =='Both' && trim($row['company_name']) == '' && trim($row['first_name']) == '' && trim($row['middle_name']) == '' && trim($row['last_name']) == ''){
                        return throw new Exception('If the content type is Both,Company Name or Customer Name is require.');
                    }
                    $contactData = $this->contactData($row);
                    Contact::create($contactData);
                }
            }
            DB::commit();
            return back()->with(['success'=>'Successfully Imported']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return throw new Exception($th->getMessage());
            //throw $th;
        }

    }
    public function contactData($datas){
        if($datas['dob']){
            $excelDateValue = intval($datas['dob']);
            $unixTimestamp = ($excelDateValue - 25569) * 86400;
            $formattedDate = date('Y-m-d', $unixTimestamp);
        }
        $pricelistId=null;
        if($datas['price_list_name']){
           try {
                $pricelistId = PriceLists::where('name', trim($datas['price_list_name']))->firstOrFail();
           } catch (\Throwable $th) {
                throw new Exception($datas['price_list_name']." Price List not found");

           }
        }
        return [
            'type'=>$datas['contact_type'],
            'business_id'=>Auth::user()->business_id,
            'price_list_id' => $pricelistId,
            'contact_id'=>$datas['contact_id'] ?? $this->contactId(),
            'company_name'=> ucfirst($datas['company_name']),
            'prefix'=>$datas['prefix'],
            'first_name'=>$datas['first_name'],
            'middle_name'=>$datas['middle_name'],
            'last_name' => $datas['last_name'],
            'mobile'=>$datas['mobile'],
            'receivable_amount'=>$datas['receivable_amount'],
            'payable_amount'=>$datas['payable_amount'],
            'email'=>$datas['email'],
            'pay_term_value'=>$datas['pay_term_value'],
            'pay_term_type'=>arr($datas, 'pay_term_type','', 'Months'),
            'credit_limit'=>arr($datas, 'credit_limit','',0),
            'tax_number' => arr($datas, 'tax_number'),
            'alternate_number' => arr($datas, 'alt_contact_no'),
            'landline' => arr($datas, 'landline'),
            'city' => arr($datas, 'city'),
            'state' => arr($datas, 'state'),
            'address_line_1' => arr($datas, 'address_line_1'),
            'address_line_2' => arr($datas, 'address_line_2'),
            'zip_code' => arr($datas, 'zip_code'),
            'dob' => $formattedDate ?? '',
            'shipping_address' => arr($datas, 'shipping_address'),
            'custom_field_1' => arr($datas, 'custom_field_1'),
            'custom_field_2' => arr($datas, 'custom_field_2'),
            'custom_field_3' => arr($datas, 'custom_field_3'),
            'custom_field_4' => arr($datas, 'custom_field_4'),
        ];
    }
    public function contactId($extra = 0)
    {

        $latestContact = Contact::latest()->first();
        $contactId = contactNo($latestContact->id + $extra);
        if (Contact::where('contact_id', $contactId)->exists()) {
            return $this->contactId(1);
        } else {
            return $contactId;
        }
    }
}
