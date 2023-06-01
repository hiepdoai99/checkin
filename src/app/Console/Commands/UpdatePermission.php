<?php

namespace App\Console\Commands;

use App\Actions\PermissionTaskUpdate;
use Illuminate\Console\Command;

class UpdatePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Permission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app(PermissionTaskUpdate::class)->execute();
        $this->info('Chay ok');
        return 0;
    }
}
