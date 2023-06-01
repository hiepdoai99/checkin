<?php

namespace App\Console;

use App\Jobs\Tenant\AssignLeaveByStatusJob;
use App\Jobs\Tenant\AssignLeaveJob;
use App\Jobs\Tenant\AssignUpcomingWorkingShiftJob;
use App\Jobs\Tenant\RenewHolidayJob;
use App\Jobs\Tenant\UpdateWorkingShiftJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // \App\Console\Commands\AddStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --queue=high,default --tries=2 --stop-when-empty')->everyMinute()->withoutOverlapping();

        $schedule->job(new UpdateWorkingShiftJob)->everyTwoHours();
        $schedule->job(new AssignUpcomingWorkingShiftJob())->everyTwoHours();
        $schedule->job(new AssignLeaveByStatusJob)->everyTwoHours();
        $schedule->job(new AssignLeaveJob)->everyTwoHours();
        $schedule->job(new RenewHolidayJob())->yearlyOn(12, 31, '22:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
