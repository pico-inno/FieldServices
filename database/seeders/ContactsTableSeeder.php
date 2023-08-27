<?php

namespace Database\Seeders;

use App\Models\Contact\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create([
            'business_id' => 1,
            'type' => 'Customer',
            'first_name' => 'Walk-In Customer',
            'contact_id' => 'C0001',
            'is_default' => 1,
            'created_by' => 1
        ]);
        $suppliers = [
            [
                'business_id' => 1,
                'type' => 'Supplier',
                'company_name' => 'AliExpress',
                'mobile' => '0912345678',
            ],
            [
                'business_id' => 1,
                'type' => 'Supplier',
                'company_name' => 'Oracle',
                'mobile' => '0912345678',
            ]
        ];

        DB::table('contacts')->insert($suppliers);
    }
}
