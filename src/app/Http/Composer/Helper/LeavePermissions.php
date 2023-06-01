<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class LeavePermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('leave_request'),
                'url' => route('support.leave.requests', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_leave_requests'])
            ],
            [
                'name' => __t('late_leave_early'),
                'url' => route('support.leave.late_leave_early', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_leave_late_early'])
            ],
            [
                'name' => __t('forgot_timekeeping'),
                // 'url' => $this->timeKeppingUrl(),
                'url' => route('support.attendances.requests', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_attendance_requests'])
            ],
            [
                'name' => __t('business_trip'),
                'url' => route('support.attendances.business_trip', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_business_trip'])
            ],
            [
                'name' => __t('overtime'),
                'url' => route('support.attendances.over_time', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_over_time']),
            ],
            [
                'name' => __t('register_work_shift'),
                'url' => route('support.attendances.work_shift', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_work_shift']),
            ],
            // 
            // [ 
            //     'name' => __t('leave_status'),
            //     'url' => route('support.leave.status',optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]),
            //     'permission' => authorize_any(['view_leave_status'])
            // ],
            // [
            //     'name' => __t('calendar'),
            //     'url' => route('support.leave.calendar', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
            //     'permission' => authorize_any(['view_leave_calendar'])
            // ],
            [
                'name' => __t('summery'),
                'url' => route('support.leave.summaries', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any(['view_leave_summaries'])
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any([
            'view_leave_status',
            'view_leaves',
            'view_leave_calendar',
            'view_leave_summaries',
            'view_leave_periods',
            'view_attendance_requests',
            'view_leave_late_early',
            'view_business_trip',
            'view_over_time',
            'view_work_shift',
        ]);
    }


    public function timeKeppingUrl()
    {
        return route(
            'support.attendances.requests',
            optional(tenant())->is_single ? '' : [
                'tenant_parameter' => optional(tenant())->short_name ?: 'default-tenant'
            ]
        );
    }

    public function requestUrl(): string
    {
        return route(
            'support.leave.requests',
            optional(tenant())->is_single ? '' : [
                'tenant_parameter' => optional(tenant())->short_name ?: 'default-tenant'
            ]
        );
    }
    public function summaryUrl(): string
    {
        return route(
            'support.leave.summaries',
            optional(tenant())->is_single ? '' : [
                'tenant_parameter' => optional(tenant())->short_name ?: 'default-tenant'
            ]
        );
    }

    public function parseNotificationUrl($user_id, $status): string
    {
        $urls = [
            'status_approved' => LeavePermissions::new(true)->summaryUrl() . "?from=email",
            'status_assigned' => LeavePermissions::new(true)->summaryUrl() . "?from=email",
            'status_bypassed' => LeavePermissions::new(true)->requestUrl() . "?from=email",
            'status_pending' => LeavePermissions::new(true)->requestUrl() . "?from=email",
            'status_rejected' => LeavePermissions::new(true)->summaryUrl() . "?from=email",
            'status_canceled' => LeavePermissions::new(true)->summaryUrl() . "?from=email",

            'status_pending' => LeavePermissions::new(true)->timeKeppingUrl() . "?from=email",
        ];

        if (isset($urls[$status])) {
            return $urls[$status];
        }

        return LeavePermissions::new(true)->requestUrl() . "?from=email";
    }
}
