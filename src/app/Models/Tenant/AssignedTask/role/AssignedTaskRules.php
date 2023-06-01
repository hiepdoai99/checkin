<?php


namespace App\Models\Tenant\AssignedTask\role;


trait AssignedTaskRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable',
            'status_id' => 'nullable',
            'user_id' => 'exists:users,id',
            'job_assignor_id' => 'exists:users,id',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
