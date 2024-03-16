<?php

namespace App\Console\Commands\gitOps;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class moduelInstaller extends Command
{
    public $modules=[];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:moduel-installer';

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
        $modules=require('./app/infra/github/register.php');
        $modulesName= array_keys($modules);
        $name = $this->choice('Select Module To Install !',$modulesName);
        $this->info('Intalling Modules :'.$name);
        if(is_dir('./Modules')){
            shell_exec('cd ./Modules && git submodule add -f '.$modules[$name]['resource'].' ./'.$name);
        }else{
            shell_exec('git submodule add -f '.$modules[$name]['resource'].' ./modules/'.$name);
        }

    }
}
