<?php

namespace App\Actions;

use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\Permission;
use Spatie\QueueableAction\QueueableAction;

class PermissionTaskUpdate
{
    use QueueableAction;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute()
    {
        $appType = Type::findByAlias('app')->id;
        $task = [
            [
                'name' => 'view_assignedTask',
                'type_id' => $appType,
                'group_name' => 'assignedTask'
            ],
            [
                'name' => 'update_assignedTask',
                'type_id' => $appType,
                'group_name' => 'assignedTask'
            ],
            [
                'name' => 'create_assignedTask',
                'type_id' => $appType,
                'group_name' => 'assignedTask'
            ],
            [
                'name' => 'delete_assignedTask',
                'type_id' => $appType,
                'group_name' => 'assignedTask'
            ]
        ];
        Permission::query()->insert($task);
        return 0;
    }
}
