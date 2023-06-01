<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Models\Core\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Actions\Traits\IsHanetWebhook;
use App\Helpers\Traits\DateTimeHelper;
use Spatie\QueueableAction\QueueableAction;
use App\Helpers\Traits\DepartmentAuthentications;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Attendance\AttendanceService;
use Illuminate\Support\Facades\Auth;

class HanetPunchInManual
{
    use QueueableAction;
    use DepartmentAuthentications;
    // use DateTimeHelper;

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
        Auth::loginUsingId(1);

        if (isset($webhook['checkinTime'])) {
            $hanetTime = Carbon::createFromTimestamp(intdiv($webhook['checkinTime'], 1000));
        } else {
            $hanetTime = Carbon::createFromTimestamp(intdiv($webhook['time'], 1000));
        }

        try {
            $employee = User::findOrFail($webhook['aliasID']);

            $this->departmentAuthentications($employee->id, true);
            
            $data = [
                'employee_id' => $employee->id,
                'in_date' => $hanetTime->toDateString(),
                // 'note' => $webhook['detected_image_url'],
                'note' => null,
                'in_time' => $hanetTime->toDateTimeString(),
                'out_time' => $hanetTime->addSecond()->toDateTimeString(),
                'ip_data' => ['ip' => '127.0.0.1'],
                'review_by' => Auth::id(),
            ];

            print($employee->id . ' checkin ' . $hanetTime->toDateTimeString() . "\r\n");

            $this->service
                ->setRefreshMemoization(true)
                ->setAttributes($data)
                ->validateManual()
                ->validateIfNotFuture();

            $this->service->setModel($employee);

            $status_name = $this->service->autoApproval() ? 'approve' : 'pending';
            $status_methods = 'attendance' . ucfirst($status_name);
            $status = app(StatusRepository::class)->$status_methods();

            DB::transaction(function () use ($employee, $status_name, $status){
                $this->service
                    ->setRefreshMemoization(true)
                    ->setAttr('status_id', $status)
                    ->setAttr('note_type', $status_name == 'approve' ? 'manual' : 'request')
                    // ->when($status_name == 'approve',
                    //     fn(AttendanceService $service) => $service->setAttr('review_by', Auth::id())
                    // )->setAttr('added_by', Auth::id())
                    ->manualAddPunchHanet()
                    // ->when($status_name == 'approve',
                    //     function (AttendanceService $service) use ($status) {
                    //         $attributes = ['status_id' => $status];

                    //         if(!$service->isNotFirstAttendance()){
                    //             $attributes = array_merge([
                    //                 'behavior' => $service->getUpdateBehavior()
                    //             ], $attributes) ;
                    //         }

                    //         $service->updateAttendance($attributes);
                    //     }
                    // );
                    ;
            });

        } catch (\Exception $e) {
            Log::critical('FAIL__HanetPunchInManual ' . $e->getMessage());
            // print('FAIL__ ' . $webhook['aliasID'] . ' checkin ' . $hanetTime->toDateTimeString() . "\r\n");
            return false;
        }

        return true;
    }

}
