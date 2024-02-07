<?php

namespace App\Console\Commands;

use App\Models\systemSetting;
use Illuminate\Console\Command;

class systemValueSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:svs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->ask('Please Enter Default Customer Id ?');
        try {
            $defCusId=getSystemData('defaultCustomer');
            if($defCusId){
                systemSetting::where('key', 'defaultCustomer')->update([
                    'key' => 'defaultCustomer',
                    'value' => $id,
                ]);

                $this->info('Success fully Updated');
            }else{
                systemSetting::create([
                    'key' => 'defaultCustomer',
                    'value' => $id,
                ]);
                $this->info('Success fully Added');
            }
        } catch (\Throwable $th) {
            systemSetting::create([
                'key' => 'defaultCustomer',
                'value' => $id,
            ]);
            $this->info('Success fully Added');
        }
    }
}
