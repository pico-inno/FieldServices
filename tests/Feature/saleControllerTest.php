<?php

namespace Tests\Feature;

use App\Services\SaleServices;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class saleControllerTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $this->assertTrue(true);

        // $saleService=new SaleServices();
        // $data= $this->saleData();
        // $data['type']='sale';
        // $updatedData=$saleService->update(15, $data);
        // $excepted=$this->exceptedSaleData();
        // // Remove a specific key from the array


        // unset($updatedData['sold_by']);
        // unset($updatedData['confirm_at']);
        // unset($updatedData['confirm_by']);
        // unset($updatedData['created_at']);
        // unset($updatedData['created_by']);
        // unset($updatedData['updated_at']);
        // unset($updatedData['updated_by']);
        // unset($updatedData['is_delete']);
        // unset($updatedData['deleted_at']);
        // unset($updatedData['deleted_by']);
        // unset($updatedData['delivered_at']);
        // unset($updatedData['channel_id']);
        // unset($updatedData['channel_type']);
        // unset($updatedData['sold_at']);

        // $this->assertSame($updatedData, $excepted);

    }
    public function saleData(){
        return [
            "business_location_id" => "2" ,
            "price_list" => "1" ,
            "contact_id" => "4",
            "currency_id" => "1",
            "status" => "delivered",
            "sold_at" => "2024-01-25 06:52",
            "sale_amount" => "25000",
            "total_item_discount" => "0",
            "extra_discount_type" => "fixed",
            "extra_discount_amount" => "2000.0000",
            "total_sale_amount" => "23000",
        ];
    }
    public function exceptedSaleData()
    {
        return [
            "id" => 15,
            "business_location_id" => 2,
            "table_id" => null,
            "sales_voucher_no" => "SL-000015",
            "contact_id" => "5",
            "status" => "delivered",
            "pos_register_id" => null,
            "sale_amount" => "25000",
            "total_item_discount" => "0",
            "extra_discount_type" => "fixed",
            "extra_discount_amount" => "2000",
            "total_sale_amount" => "23000",
            "paid_amount" => "8000.0000",
            "balance_amount" => "0.0000",
            "currency_id" => "1",
            "payment_status" => "paid",
        ];
    }

}
