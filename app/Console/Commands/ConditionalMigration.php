<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class ConditionalMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'createSession';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('sessions')) {
            $this->info('Running migration...');
            Artisan::call('migrate', ['--path' => 'database/migrations/2023_09_26_103002_create_sessions_table.php']);
            $this->info('Migration complete.');
        } else {
            $this->info('Table already exists. Skipping migration.');
        }
    }
}
