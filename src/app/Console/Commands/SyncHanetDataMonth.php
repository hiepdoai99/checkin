<?php

namespace App\Console\Commands;

use App\Actions\HanetGetCheckinMonth;
use Illuminate\Console\Command;

class SyncHanetDataMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hanet:month {month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dong bo lai data tu Hanet cho mot thang: yyyy-mm (2023-01)';

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
        $this->info('Starting...!');
        app(HanetGetCheckinMonth::class)->execute($this->argument('month'));
        $this->info('Done!');
        return 0;
    }
}
