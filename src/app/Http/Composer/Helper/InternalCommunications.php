<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Http\Composer\Helper\EmployeePermissions;

class InternalCommunications
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('internal_newsletter'),
                'url' => $this->announcementUrl(),
                'permission' => auth()->user()->can('view_announcements')
            ],
            // [
            //     'name' => __t('internal_newsletter'),
            //     'url' => route('support.internal_communications.internal_newsletter', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => can('view_internal_newsletter'),
            // ],
            // [
            //     'name' => __t('internal_events'),
            //     'url' => route('support.internal_communications.internal_events', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => can('view_internal_events'),
            // ],
            // [
            //     'name' => __t('holiday'),
            //     'url' => $this->holidayUrl(),
            //     'permission' => authorize_any(['view_holidays'])
            // ],
            // [
            //     'name' => __t('org_structure'),
            //     'url' => $this->organizationUrl(),
            //     'permission' => authorize_multiple(['view_departments', 'update_departments'])
            // ],
            // [
            //     'name' => __t('announcements'),
            //     'url' => $this->announcementUrl(),
            //     'permission' => auth()->user()->can('view_announcements')
            // ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_internal_newsletter',
            'view_internal_events',
            'view_announcements',
        ]);
    }

    public function organizationUrl()
    {
        return route(
            'support.organization.structure',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

    public function holidayUrl()
    {
        return route(
            'support.employee.holidays',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

    public function announcementUrl()
    {
        return route(
            'support.employee.announcements',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }
}
