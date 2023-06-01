<?php

namespace App\Actions;

use App\Models\Core\Auth\User;
use Illuminate\Support\Facades\Log;
use App\Actions\Traits\IsHanetWebhook;
use Spatie\QueueableAction\QueueableAction;
use App\Services\Tenant\Attendance\AttendanceService;

class HanetPunchInWebhook
{
    use QueueableAction;
    use IsHanetWebhook;

    private AttendanceService $service;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        activity()->disableLogging();

        $this->service = app(AttendanceService::class);
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute(array $webhook)
    {
        if (! $this->isHanet($webhook)) {
            Log::critical('_HANET__FAKE_');
            return false;
        }
        if ('' === $webhook['aliasID']) {
            // Xu ly th ko co ma NV
            return false;
        }
        $employee = User::find($webhook['aliasID']);        
        if (!$employee) {
            Log::critical('_HANET__NOT_EMPLOYEE_');
            return false;
        }

        app(HanetPunchInManual::class)->execute($webhook);
       
        return true;
    }

}
