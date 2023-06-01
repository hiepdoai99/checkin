<?php

namespace App\Console\Commands;

use App\Models\Core\Status;
use Illuminate\Console\Command;

class AddStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:addStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add data to table Status';

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
        $data = [
            [
                'name' => 'status_slacking',
                'type' => 'assignedTask',
                'class' => 'primary'
            ],
            [
                'name' => 'status_done',
                'type' => 'assignedTask',
                'class' => 'success'
            ],
            [
                'name' => 'status_late',
                'type' => 'assignedTask',
                'class' => 'warning'
            ],
            [
                'name' => 'status_unfinished',
                'type' => 'assignedTask',
                'class' => 'danger'
            ],
        ];

        Status::insert($data);
        $this->info('Done!');
        return 0;
    }

}
