<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Http\Composer\Helper\EmployeePermissions;

class AssetManager
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('meeting_room_management'),
                'url' => route('support.asset_management.meeting_room_management', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => can('view_meeting_room_management'),
            ],
            [
                'name' => __t('book_library_management'),
                'url' => route('support.asset_management.book_library_management', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => can('view_book_library_management'),
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_meeting_room_management',
            'view_book_library_management',
        ]);
    }

    // public function organizationUrl()
    // {
    //     return route(
    //         'support.organization.structure',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
    //     );
    // }

    // public function holidayUrl()
    // {
    //     return route(
    //         'support.employee.holidays',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
    //     );
    // }

    // public function announcementUrl()
    // {
    //     return route(
    //         'support.employee.announcements',
    //         optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
    //     );
    // }
}
