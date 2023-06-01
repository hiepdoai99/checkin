<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Http\Composer\Helper\EmployeePermissions;

class Different
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('holiday'),
                'url' => $this->holidayUrl(),
                'permission' => authorize_any(['view_holidays'])
            ],
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
            // [
            //     'name' => __t('designation'),
            //     'url' => route('support.employee.designations', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => authorize_any(['view_designations'])
            // ],
            [
                'name' => __t('employment_status'),
                'url' => route('support.employee.employment-statuses', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_employment_statuses'])
            ],
            [
                'name' => __t('cameras'),
                'url' => $this->cameraUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
            [
                'name' => __t('cameras_person_emp'),
                'url' => $this->personUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
            [
                'name' => __t('attendance_settings'),
                'url' => route('support.settings.attendance', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any(['view_attendance_settings'])
            ],
            [
                'name' => __t('payroll_settings'),
                'url' => route('support.settings.payroll', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any(['view_payroll_settings'])
            ],
            [
                'name' => __t('camera_settings'),
                'url' => route('support.settings.camera', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => can('view_camera_settings')
            ],
            [
                'name' => __t('import'),
                'url' => route('support.settings.import', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
                'permission' => authorize_any(['import_employees', 'import_attendances'])
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any(['view_users', 'view_roles', 'view_departments', 'view_working_shifts', 'view_announcements', 'view_cameras',
            'view_attendance_settings',
            'view_payroll_settings',
            'view_camera_settings',
            'import_employees',
            'import_attendances',
        ]);
    }

    public function organizationUrl()
    {
        return route(
            'support.organization.structure',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function holidayUrl()
    {
        return route(
            'support.employee.holidays',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function announcementUrl()
    {
        return route(
            'support.employee.announcements',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function cameraUrl()
    {
        return route(
            'support.cameras',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }

    public function personUrl()
    {
        return route(
            'support.persons',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }
}
