<?php


namespace App\Models\Tenant\Event\Relationship;


use App\Models\Tenant\Employee\Department;

trait EventRelationship
{
    public function departments()
    {
        return $this->belongsToMany(
            Department::class,
            'event_department',
            'event_id',
            'department_id'
        );
    }
}
