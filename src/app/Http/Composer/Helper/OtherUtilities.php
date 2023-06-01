<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Http\Composer\Helper\EmployeePermissions;

class OtherUtilities
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            // [
            //     'name' => __t('calendar'),
            //     'url' => route('support.leave.calendar', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => authorize_any(['view_leave_calendar'])
            // ],
            [
                'name' => __t('calender'),
                'url' => route('support.other_utilities.calender', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => can('view_calender'),
            ],
            // [
            //     'name' => __t('numerology'),
            //     'url' => route('support.other_utilities.numerology', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => can('view_numerology'),
            // ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_leave_calendar',
            'view_calender',
            'view_numerology',
        ]);
    }

    // public function organizationUrl()
    // {
    //     return route(
    //         'support.organization.structure',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
    //     );
    // }

    // public function holidayUrl()
    // {
    //     return route(
    //         'support.employee.holidays',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
    //     );
    // }

    // public function announcementUrl()
    // {
    //     return route(
    //         'support.employee.announcements',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
    //     );
    // }
}
