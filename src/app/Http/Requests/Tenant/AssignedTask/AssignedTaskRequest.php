<?php

namespace App\Http\Requests\Tenant\AssignedTask;


use App\Http\Requests\BaseRequest;
use App\Models\Tenant\AssignedTask\AssignedTask;

class AssignedTaskRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new AssignedTask());
    }

}
