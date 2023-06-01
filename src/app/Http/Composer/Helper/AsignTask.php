<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class AsignTask
{
  use InstanceCreator;

  public function permissions()
  {
    return [
      [
        'name' => __t('assigned_task'),
        'url' => route('support.asign_task.asign_task', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
        'permission' =>  true,
      ],
    ];
  }
 
  public function canVisit()
  {
    return authorize_any(['view_employees']);
  }

  public function assignTask()
  {
    return route(
      'support.employee.details',
      !optional(tenant())->is_single ?
        [
          'tenant_parameter' => optional(tenant())->short_name
        ] : ''
    );
  }

}
