<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Http\Composer\Helper\EmployeePermissions;

class AdministrationPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('admin_branch'),
                'url' => $this->companyBranchUrl(),
                'permission' => authorize_any(['view_companyBranch'])
            ],
            [
                'name' => __t('company_model'),
                'url' => $this->departmentUrl(),
                'permission' => authorize_any(['view_departments'])
            ],
            // [
            //     'name' => __t('admin_position_com'),
            //     'url' => $this->userUrl(),
            //     'permission' => can('view_roles')
            // ],
            [
                'name' => __t('admin_work_shifts'),
                'url' => $this->workShiftUrl(),
                'permission' => authorize_any(['view_working_shifts'])
            ],
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
            // [
            //     'name' => __t('admin_address'),
            //     'url' => '#',
            //     'permission' => ''
            // ],
            [
                'name' => __t('admin_address'),
                'url' => $this->cameraPlaceUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
            [
                'name' => __t('employee_admin'),
                'url' => route('support.employee.lists', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => true
            ],
            // EmployeePermissions::new(true)->canVisit() ?
            //     [
            //         'name' => __t('employee_admin'),
            //         'icon' => 'users',
            //         'id' => 'employee',
            //         'permission' => EmployeePermissions::new(true)->canVisit(),
            //         'subMenu' => EmployeePermissions::new(true)->permissions(),
            //     ] : [],
        ];
    }

    public function canVisit()
    {
        return authorize_any(['view_users', 'view_roles', 'view_departments', 'view_working_shifts', 'view_announcements', 'view_companyBranch', 'view_cameras']);
    }

    public function departmentUrl()
    {
        return route(
            'support.employee.departments',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function organizationUrl()
    {
        return route(
            'support.organization.structure',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function workShiftUrl()
    {
        return route(
            'support.employee.work_shifts',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function userUrl()
    {
        return route(
            'support.tenant.users',
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

    public function companyBranchUrl()
    {
        return route(
            'support.employee.companyBranch',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function cameraPlaceUrl()
    {
        return route(
            'support.places',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]
        );
    }
}
