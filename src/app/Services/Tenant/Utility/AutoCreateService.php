<?php

namespace App\Services\Tenant\Utility;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Models\Tenant\Leave\LeaveType;
use App\Models\Tenant\Leave\LeavePeriod;
use App\Models\Tenant\WorkingShift\WorkingShift;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AutoCreateService extends TenantService
{
    use InstanceCreator;

    public function trigger(array $attributes = []) : void
    {
        if (array_key_exists('tenant_id', $attributes)) {
            $this->createEmploymentStatus($attributes['tenant_id']);
            $this->createLeavePeriod($attributes['tenant_id']);
            $this->createLeaveCategory($attributes['tenant_id']);
            $this->createWorkingShift($attributes['tenant_id']);
        }

        if (array_key_exists('manager_id', $attributes)) {
            /** @var Department $department */
            $department = $this->createDepartment($attributes);

            unset($attributes['manager_id']);

            $this->createDesignation($department, $attributes);
        }

    }

    public function createEmploymentStatus($tenant_id = 1) : void
    {
        $statuses = collect([
            ['name' => 'Thực tập', 'class' => 'warning', 'alias' => 'probation'],
            ['name' => 'Chính thức', 'class' => 'primary', 'alias' => 'permanent'],
            ['name' => 'Chấm dứt', 'class' => 'danger', 'alias' => 'terminated'],
        ])->map(fn ($v) => [
            'name' => $v['name'],
            'class' => $v['class'],
            'is_default' => 1,
            'tenant_id' => $tenant_id,
            'alias' => $v['alias']
        ]);

        EmploymentStatus::query()->insert(
            $statuses->toArray()
        );
    }

    public function createLeavePeriod(int $tenant_id = 1) : void
    {
        LeavePeriod::query()->create([
            'start_date' => now()->firstOfYear(),
            'end_date' => now()->addYear(),
            'tenant_id' => $tenant_id
        ]);
    }

    public function createLeaveCategory($tenant_id = 1) : void
    {
        $categories = collect([
            ['name' => 'Nghỉ có lương', 'type' => 'paid', 'amount' => 5.50],
            ['name' => 'Nghỉ ốm có lương', 'type' => 'paid', 'amount' => 5.50],
            ['name' => 'Nghỉ không lương', 'type' =>  'unpaid', 'amount' => 5.50],
            ['name' => 'Nghỉ ốm không lương', 'type' =>  'unpaid', 'amount' => 5.50],
        ])->map(fn ($v) => [
            'name' => $v['name'],
            'alias' => Str::slug($v['name']),
            'type' => $v['type'],
            'amount' => $v['amount'],
            'tenant_id' => $tenant_id
        ]);

        LeaveType::query()->insert(
            $categories->toArray()
        );
    }

    public function createWorkingShift(int $tenant_id = 1)
    {
        $workingShift = WorkingShift::query()->create([
            'name' => 'Ca làm việc cơ bản',
            'tenant_id' => $tenant_id,
            'is_default' => 1,
            'type' => 'regular'
        ]);

        $this->saveWorkingShiftDetails($workingShift);

    }

    public function createDepartment(array $attributes = []) : Model
    {
        $status_id = resolve(StatusRepository::class)->departmentActive();
        return Department::query()->create(array_merge([
            'name' => 'Trụ sở chính',
            'status_id' => $status_id
        ], $attributes));
    }

    public function createDesignation(Department $department, array $attributes = [])
    {
        $department->designations()->create(array_merge([
            'name' => 'Giám đốc',
            'is_default' => true,
        ], $attributes));
    }

    public function saveWorkingShiftDetails(WorkingShift $workingShift, $start_time = '01:00:00', $end_time = '10:30:00')
    {
        $callback = fn ($day) => [
            'weekday' => $day,
            'is_weekend' => !!in_array($day, ['sun', 'sat']) ? 1 : 0,
            'start_at' => !!in_array($day, ['sun', 'sat']) ? null : $start_time,
            'end_at' => !!in_array($day, ['sun', 'sat']) ? null : $end_time,
            'working_shift_id' => $workingShift->id
        ];

        $workingShift->details()->insert(
            array_map($callback, config('settings.weekdays'))
        );
    }


}
