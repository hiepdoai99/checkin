<?php

use App\Http\Controllers\Common\Utility\ThanSoHocPytagoController;
use App\Http\Controllers\Tenant\Camera\CameraWebhookController;
use Illuminate\Support\Facades\Route;


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('hanet/webhook', [CameraWebhookController::class, 'webhook'])->name('hanet.webhook');
    Route::post('pytago', [ThanSoHocPytagoController::class, 'get'])->name('pyphptago');

    Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\Auth\RegisteredUserController::class, 'register']);
    Route::get('/employees/profile/employee-id', [\App\Http\Controllers\Api\V1\EmployeeProfileController::class,'employeeId']);

    Route::group(['middleware' => ['auth:api']], function ($router) {
        Route::post('/verify-token', [\App\Http\Controllers\Api\Auth\AuthController::class, 'verifyToken']);
        Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

        Route::get('/attendanceDaily', [\App\Http\Controllers\Api\V1\AttendanceDailyLogController::class, 'index']);
        Route::get('/attendances/details', [\App\Http\Controllers\Api\V1\AttendanceDetailsController::class, 'index']);
        Route::get('/attendances/periods', [\App\Http\Controllers\Api\V1\AttendanceDetailsController::class, 'attendancePeriods']);
        Route::get('/attendances/{details}/log', [\App\Http\Controllers\Api\V1\AttendanceLogController::class, 'index']);
        Route::get('/attendances/details/{attendance_details}', [\App\Http\Controllers\Api\V1\AttendanceUpdateController::class, 'index']);
        Route::patch('/attendances/{attendance_details}/request', [\App\Http\Controllers\Api\V1\AttendanceUpdateController::class, 'request']);
        Route::patch('/attendances/comments/{comment}', [\App\Http\Controllers\Api\V1\AttendanceCommentController::class, 'update']);
        Route::get('/attendances/request', [\App\Http\Controllers\Api\V1\AttendanceRequestController::class, 'index']);
        Route::post('/attendances/request/update', [\App\Http\Controllers\Api\V1\AttendanceStatusController::class, 'updateAll']);
        Route::post('/attendances/{details}/status/approve', [\App\Http\Controllers\Api\V1\AttendanceStatusController::class, 'update']);
        Route::post('/attendances/{details}/status/cancel', [\App\Http\Controllers\Api\V1\AttendanceStatusController::class, 'update']);
        Route::get('/attendances/{employee}/summaries', [\App\Http\Controllers\Api\V1\AttendanceSummaryController::class, 'index']);
        Route::get('/attendances/{employee}/summaries-data-table', [\App\Http\Controllers\Api\V1\AttendanceSummaryController::class, 'summaries']);
        Route::get('/attendance/{user}/users', [\App\Http\Controllers\Api\V1\AttendanceSummaryController::class, 'users']);

        Route::apiResource('/working-shifts', \App\Http\Controllers\Api\V1\WorkingShiftController::class);
        Route::apiResource('/custom-fields', \App\Http\Controllers\Api\V1\CustomFieldController::class);
        Route::get('/custom-field-types', [\App\Http\Controllers\Api\V1\CustomFieldTypeController::class,'index']);
        Route::apiResource('/user', \App\Http\Controllers\Api\V1\UserController::class);
        Route::apiResource('/roles', \App\Http\Controllers\Api\V1\RoleController::class);
        Route::get('/permission', [\App\Http\Controllers\Api\V1\PermissionController::class,'index']);
        Route::post('/roles/attach-permissions/{role}', [\App\Http\Controllers\Api\V1\RolePermissionController::class,'store']);
        Route::patch('/roles/detach-permissions/{role}', [\App\Http\Controllers\Api\V1\RolePermissionController::class,'update']);

        Route::apiResource('/announcements', \App\Http\Controllers\Api\V1\AnnouncementController::class);
        Route::apiResource('/beneficiaries', \App\Http\Controllers\Api\V1\BeneficiaryBadgeController::class);
        Route::apiResource('/cameras/hanets', \App\Http\Controllers\Api\V1\CameraApiController::class);
        Route::delete('/departments/upcoming/working-shift/{id}', [\App\Http\Controllers\Api\V1\DepartmentController::class,'deleteUpcomingWorkShift']);
        Route::apiResource('/departments', \App\Http\Controllers\Api\V1\DepartmentController::class);
        Route::apiResource('/assigned-task', \App\Http\Controllers\Api\V1\AssignedTaskController::class);

        Route::apiResource('/designations', \App\Http\Controllers\Api\V1\DesignationController::class);
        Route::apiResource('/employee/documents', \App\Http\Controllers\Api\V1\DocumentController::class);
        Route::apiResource('/employees', \App\Http\Controllers\Api\V1\EmployeeController::class);

        Route::get('/employees/{employee}/job-history', [\App\Http\Controllers\Api\V1\EmployeeJobHistoryController::class,'index']);
        Route::get('/employees/{employee}/payrun-setting', [\App\Http\Controllers\Api\V1\EmployeePayrunController::class,'index']);
        Route::get('/employees/{employee}/payrun-setting/restore', [\App\Http\Controllers\Api\V1\EmployeePayrunController::class,'restore']);
        Route::post('/employees/{employee}/payrun-setting/update-beneficiary', [\App\Http\Controllers\Api\V1\EmployeePayrunController::class,'updateBeneficiary']);
        Route::post('/employees/{employee}/payrun-setting/update-payrun', [\App\Http\Controllers\Api\V1\EmployeePayrunController::class,'updatePayrun']);
        Route::patch('/employees/{employee}/profile-update', [\App\Http\Controllers\Api\V1\EmployeeProfileController::class,'update']);
        Route::post('/employees/{type}/update', [\App\Http\Controllers\Api\V1\EmployeeProfileController::class,'updateEmployees']);

        Route::patch('/employees/{employee}/rejoin', [\App\Http\Controllers\Api\V1\EmployeeEmploymentStatusController::class,'rejoin']);
        Route::patch('/employees/{employee}/terminate', [\App\Http\Controllers\Api\V1\EmployeeEmploymentStatusController::class,'terminate']);
        Route::patch('/employees/{employee}/update-status/{status}', [\App\Http\Controllers\Api\V1\EmployeeEmploymentStatusController::class,'update']);
        Route::patch('/employees/{employee}/update-termination-note', [\App\Http\Controllers\Api\V1\EmployeeEmploymentStatusController::class,'updateTerminationNote']);

        Route::get('/employees/{employee}/salaries', [\App\Http\Controllers\Api\V1\EmployeeSalaryController::class,'index']);
        Route::get('/employees/salary-range', [\App\Http\Controllers\Api\V1\EmployeeSalaryController::class,'range']);

        Route::get('/employees/{employee}/social-links', [\App\Http\Controllers\Api\V1\EmployeeSocialLinkController::class,'index']);
        Route::patch('/employees/{employee}/social-links', [\App\Http\Controllers\Api\V1\EmployeeSocialLinkController::class,'update']);

        Route::patch('/employees/{employee}/{type}/update', [\App\Http\Controllers\Api\V1\EmployeeUpdateController::class,'update']);

        Route::get('/export/attendance/all', [\App\Http\Controllers\Api\V1\AttendanceExportController::class,'exportAllEmployeeAttendance']);
        Route::get('/export/attendance/daily-log', [\App\Http\Controllers\Api\V1\AttendanceExportController::class,'exportDailyLogAttendance']);
        Route::get('/export/{employee}/attendance', [\App\Http\Controllers\Api\V1\AttendanceExportController::class,'exportEmployeeAttendance']);

        Route::post('/import/attendances', [\App\Http\Controllers\Api\V1\AttendanceImportController::class,'importAttendances']);
        Route::post('/import/employees', [\App\Http\Controllers\Api\V1\UserImportController::class,'importEmployees']);

        Route::apiResource('/employment-statuses', \App\Http\Controllers\Api\V1\EmploymentStatusController::class);
        Route::apiResource('/holidays', \App\Http\Controllers\Api\V1\HolidayController::class);
        Route::apiResource('/events', \App\Http\Controllers\Api\V1\EventController::class);
        Route::apiResource('/leave-periods', \App\Http\Controllers\Api\V1\LeavePeriodController::class);
        Route::apiResource('/leave-types', \App\Http\Controllers\Api\V1\LeaveTypeController::class);
        Route::post('/leaves/assign', [\App\Http\Controllers\Api\V1\LeaveAssignController::class,'store']);
        Route::get('/leaves/calendar', [\App\Http\Controllers\Api\V1\LeaveCalendarController::class,'index']);
        Route::get('/leaves/calendar-table', [\App\Http\Controllers\Api\V1\LeaveCalendarController::class,'summaries']);

        Route::get('/leaves/data-table', [\App\Http\Controllers\Api\V1\LeaveStatusSummaryController::class,'index']);
        Route::get('/leaves/summaries', [\App\Http\Controllers\Api\V1\LeaveStatusSummaryController::class,'summaries']);

        Route::get('/leaves/request', [\App\Http\Controllers\Api\V1\LeaveRequestController::class,'index']);

        Route::patch('/leaves/request/comments/{comment}', [\App\Http\Controllers\Api\V1\LeaveCommentController::class,'update']);

        Route::patch('/leaves/request/{leave}/approved', [\App\Http\Controllers\Api\V1\LeaveStatusController::class,'update']);
        Route::patch('/leaves/request/{leave}/bypassed', [\App\Http\Controllers\Api\V1\LeaveStatusController::class,'update']);
        Route::patch('/leaves/request/{leave}/canceled', [\App\Http\Controllers\Api\V1\LeaveStatusController::class,'update']);
        Route::patch('/leaves/request/{leave}/rejected', [\App\Http\Controllers\Api\V1\LeaveStatusController::class,'update']);

        Route::get('/leaves/{employee}/allowances', [\App\Http\Controllers\Api\V1\EmployeeLeaveAllowanceController::class,'index']);
        Route::get('/leaves/{user_leave}/leave-type', [\App\Http\Controllers\Api\V1\EmployeeLeaveAllowanceController::class,'showUserLeave']);
        Route::patch('/leaves/{user_leave}/leave-type', [\App\Http\Controllers\Api\V1\EmployeeLeaveAllowanceController::class,'update']);

        Route::get('/leaves/{employee}/summaries', [\App\Http\Controllers\Api\V1\LeaveSummeryController::class,'index']);
        Route::get('/leaves/{employee}/summaries-data-table', [\App\Http\Controllers\Api\V1\LeaveSummeryController::class,'summaries']);
        Route::get('/selectable/leave-periods', [\App\Http\Controllers\Api\V1\LeaveSummeryController::class,'leavePeriods']);

        Route::get('/leaves/{leave}/log', [\App\Http\Controllers\Api\V1\LeaveLogController::class,'index']);

        Route::get('/organization-structure', [\App\Http\Controllers\Api\V1\OrganizationStructureController::class,'index']);

        Route::get('/payroll/{employee}/summaries', [\App\Http\Controllers\Api\V1\PayrollSummeryController::class,'summery']);
        Route::get('/payroll/{employee}/summery-table', [\App\Http\Controllers\Api\V1\PayrollSummeryController::class,'index']);

        Route::get('/payrun/default', [\App\Http\Controllers\Api\V1\DefaultPayrunController::class,'index']);
        Route::get('/payrun/default/employees', [\App\Http\Controllers\Api\V1\DefaultPayrunController::class,'employees']);

        Route::post('/payrun/manual', [\App\Http\Controllers\Api\V1\ManualPayrunController::class,'store']);
        Route::patch('/payruns/{payrun}', [\App\Http\Controllers\Api\V1\ManualPayrunController::class,'update']);
        Route::get('/payruns/{payrun}', [\App\Http\Controllers\Api\V1\ManualPayrunController::class,'index']);

        Route::get('/payrun/{payrun}/user/{user}/conflicted', [\App\Http\Controllers\Api\V1\ConflictPayrunController::class,'userPayslips']);
        Route::get('/payrun/{payrun}/users/conflicted', [\App\Http\Controllers\Api\V1\ConflictPayrunController::class,'users']);

        Route::get('/payruns ', [\App\Http\Controllers\Api\V1\PayrunController::class,'index']);
        Route::delete('/payruns/{payrun}', [\App\Http\Controllers\Api\V1\PayrunController::class,'delete']);
        Route::get('/payruns/{payrun}/batch/update', [\App\Http\Controllers\Api\V1\PayrunController::class,'updateBatch']);
        Route::get('/payruns/{payrun}/send-payslip', [\App\Http\Controllers\Api\V1\PayrunController::class,'sendPayslips']);

        Route::get('/payslip', [\App\Http\Controllers\Api\V1\PayslipController::class,'index']);
        Route::get('/provisional-salary', [\App\Http\Controllers\Api\V1\PayslipController::class,'provisionalSalary']);
        Route::get('/payslip/send-monthly', [\App\Http\Controllers\Api\V1\PayslipController::class,'sendMonthlyPayslip']);
        Route::get('/payslip/{payslip}/delete', [\App\Http\Controllers\Api\V1\PayslipController::class,'destroy']);
        Route::get('/payslip/{payslip}/pdf', [\App\Http\Controllers\Api\V1\PayslipController::class,'showPdf']);
        Route::get('/payslip/{payslip}/send', [\App\Http\Controllers\Api\V1\PayslipController::class,'sendPayslip']);
        Route::patch('/payslip/{payslip}/update', [\App\Http\Controllers\Api\V1\PayslipController::class,'update']);

        Route::get('database-2/credentials', [\App\Http\Controllers\Api\V1\RestoreController::class,'settings']);
        Route::patch('database-2/credentials', [\App\Http\Controllers\Api\V1\RestoreController::class,'updateSettings']);
        Route::post('/restore-data', [\App\Http\Controllers\Api\V1\RestoreController::class,'index']);
        Route::post('/restore-data/attendance', [\App\Http\Controllers\Api\V1\RestoreController::class,'attendanceImport']);

        Route::get('/selectable/{user}/next-user/{type}', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'nextUser']);
        Route::get('/selectable/department/users', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'index']);
        Route::get('/selectable/payrun/users', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'index']);
        Route::get('/selectable/role/user', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'index']);
        Route::get('/selectable/users', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'index']);
        Route::get('/selectable/work-shift/users', [\App\Http\Controllers\Api\V1\TenantUserAPIController::class,'index']);

        Route::patch('/users/{user}/add-to-employee', [\App\Http\Controllers\Api\V1\UserEmployeeController::class,'addToEmployee']);
        Route::patch('/users/{user}/remove-from-employee', [\App\Http\Controllers\Api\V1\UserEmployeeController::class,'removeFromEmployee']);

        Route::get('/dashboard/employee-statistics', [\App\Http\Controllers\Api\V1\AdminDashboardController::class,'employeeStatistics']);
        Route::get('/dashboard/on-working', [\App\Http\Controllers\Api\V1\AdminDashboardController::class,'onWorking']);
        Route::get('/dashboard/summery', [\App\Http\Controllers\Api\V1\AdminDashboardController::class,'summery']);
        Route::get('/dashboard/report-employee', [\App\Http\Controllers\Api\V1\AdminDashboardController::class,'reportEmployee']);
        Route::get('/dashboard/employee/announcements', [\App\Http\Controllers\Api\V1\EmployeeDashboardController::class,'announcements']);
        Route::get('/dashboard/employee/attendance', [\App\Http\Controllers\Api\V1\EmployeeDashboardController::class,'employeeAttendance']);
        Route::get('/dashboard/employee/attendance-log', [\App\Http\Controllers\Api\V1\EmployeeDashboardController::class,'employeeMonthlyAttendanceLog']);

        Route::get('/settings', [\App\Http\Controllers\Api\V1\SettingController::class,'index']);
        Route::post('/settings', [\App\Http\Controllers\Api\V1\SettingController::class,'update']);

        Route::get('/notification-channels', [\App\Http\Controllers\Api\V1\NotificationChannelController::class,'index']);
        Route::get('/notification-events', [\App\Http\Controllers\Api\V1\NotificationEventController::class,'index']);
        Route::get('/notification-events/{notification_event}', [\App\Http\Controllers\Api\V1\NotificationEventController::class,'show']);
        Route::get('/notification-settings/{notification_setting}', [\App\Http\Controllers\Api\V1\NotificationSettingController::class,'show']);
        Route::patch('/notification-settings/{notification_setting}', [\App\Http\Controllers\Api\V1\NotificationSettingController::class,'update']);
        Route::post('/notification-events/{event}/attach-templates', [\App\Http\Controllers\Api\V1\NotificationEventTemplateController::class,'store']);
        Route::patch('/notification-events/{event}/detach-templates', [\App\Http\Controllers\Api\V1\NotificationEventTemplateController::class,'update']);
        Route::apiResource('/notification-templates', \App\Http\Controllers\Api\V1\NotificationTemplateController::class);

        Route::apiResource('/users', \App\Http\Controllers\Api\V1\TenantUserController::class);

    });
});
