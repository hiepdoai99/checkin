<?php


namespace App\Models\Tenant\Event\role;


trait EventRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'departments' => 'array',
            'departments.*' => "exists:departments,id"
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
