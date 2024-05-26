<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install API dependencies and setup';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing API...');
        // Tambahkan logika instalasi API di sini
        $this->info('API installed successfully!');
        return 0;
    }
}
