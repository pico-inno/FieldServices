<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class DropDeliveryChannelsTable extends Command
{
    protected $signature = 'table:drop-delivery-channels';

    protected $description = 'Drop the delivery_channels table safely';

    public function handle()
    {
        if ($this->confirm('Do you really want to drop the delivery_channels table?')) {
            if (Schema::hasTable('delivery_channels')) {
                Schema::dropIfExists('delivery_channels');
                $this->info('The delivery_channels table has been dropped successfully.');
            } else {
                $this->warn('The delivery_channels table does not exist.');
            }
        } else {
            $this->info('Operation cancelled. The delivery_channels table was not dropped.');
        }
    }
}
