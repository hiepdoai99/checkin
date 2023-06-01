<?php

namespace App\Services\Tenant\AssignedTask;


use App\Models\Tenant\AssignedTask\AssignedTask;
use App\Services\Tenant\TenantService;

class AssignedTaskService extends TenantService
{
    protected $assignedTaskId;

    public function __construct(AssignedTask $assignedTask )
    {
        $this->model = $assignedTask;
    }

    public function assignToUsers($users)
    {

    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }


}
