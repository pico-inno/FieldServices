<?php

namespace Database\Seeders;

use App\Models\Contact\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactWalkInTableSeeder extends Seeder
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
            'created_by' => 1
        ]);
    }
}
