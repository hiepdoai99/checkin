<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\Tenant\AssignedTaskFilter;
use App\Helpers\Traits\DateTimeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\AssignedTask\AssignedTaskRequest;
use App\Models\Tenant\AssignedTask\AssignedTask;
use App\Services\Tenant\AssignedTask\AssignedTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AssignedTaskController extends Controller
{
    use DateTimeHelper;

    public function __construct(AssignedTaskService $service, AssignedTaskFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }
    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with('user','assignBy','status')
            ->latest('id')
            ->paginate(request()->get('per_page', 10));
    }
    public function show(AssignedTask $assignedTask)
    {
        $assignedTask = $assignedTask->load( 'user','assignBy');

        return $assignedTask;
    }

    public function store(AssignedTaskRequest $request)
    {
        DB::transaction(function () use ($request) {
            AssignedTask::query()->create(
                $request->only('name', 'start_date', 'end_date', 'status_id', 'user_id', 'description','job_assignor_id')
            );
        });
        return created_responses('assigned_task');
    }
    public function update(AssignedTask $assignedTask ,AssignedTaskRequest $request)
    {
        $assignedTask = DB::transaction(function () use ($request, $assignedTask) {
            $this->service
                ->setAttributes($request
                    ->only('name', 'start_date', 'end_date', 'status_id', 'user_id', 'description','job_assignor_id'))
                ->setModel($assignedTask)
                ->update();
        });
        return updated_responses('assigned_task');
    }
    public function destroy(AssignedTask $assignedTask)
    {
        $this->service
            ->setModel($assignedTask)
            ->delete();
        return deleted_responses('assigned_task');
    }
}
