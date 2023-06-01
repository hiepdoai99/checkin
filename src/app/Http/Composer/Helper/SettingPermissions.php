<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;

class SettingPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('app_settings'),
                'url' => route('support.tenant.settings', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_settings',
                    'view_notification_settings',
                    'check_for_updates',
                    'view_delivery_settings'
                ])
            ],
            [
                'name' => __t('users_roles'),
                'url' => route('support.tenant.users',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => can('view_roles')
            ],
            [
                'name' => __t('setting_doc'),
                'url' => route('support.settings.leave', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => can('view_leave_settings'),
            ],
            // [
            //     'name' => __t('setting_noti'),
            //     'url' => route('support.settings.setting_notification', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => can('view_setting_notification'),
            // ],
            // [
            //     'name' => __t('set_day_off'),
            //     'url' => route('support.settings.day_off', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => can('view_day_off'),
            // ],
            
            // [
            //     'name' => __t('attendance_settings'),
            //     'url' => route('support.settings.attendance', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
            //     'permission' => authorize_any(['view_attendance_settings'])
            // ],
            // [
            //     'name' => __t('payroll_settings'),
            //     'url' => route('support.settings.payroll', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
            //     'permission' => authorize_any(['view_payroll_settings'])
            // ],
            // [
            //     'name' => __t('camera_settings'),
            //     'url' => route('support.settings.camera', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
            //     'permission' => can('view_camera_settings')
            // ],
            // [
            //     'name' => __t('import'),
            //     'url' => route('support.settings.import', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
            //     'permission' => authorize_any(['import_employees', 'import_attendances'])
            // ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_settings',
            'view_notification_settings',
            'check_for_updates',
            'view_delivery_settings',
            'view_leave_settings',
            'view_attendance_settings',
            'view_payroll_settings',
            'view_camera_settings',
            'import_employees',
            'import_attendances',
            'view_notification_templates',
            'view_setting_notification',
            'view_day_off',
            'view_roles',
        ]);
    }
}
