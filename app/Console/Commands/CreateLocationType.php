<?php

namespace App\Console\Commands;

use App\Models\locationType;
use App\Models\location_type;
use Illuminate\Console\Command;

class CreateLocationType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pico:make-locationType';

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
        $name=$this->ask('name');
        $description=$this->ask('description');

        locationType::create([
            'name'=>$name,
            'description' => $description
        ]);

        $this->info('Type Successfully Created!');
    }
}
