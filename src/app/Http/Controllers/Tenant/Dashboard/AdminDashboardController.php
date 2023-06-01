<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use Illuminate\Support\Arr;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Leave\Leave;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Helpers\Traits\DateTimeHelper;
use App\Helpers\Traits\DateRangeHelper;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Models\Tenant\Attendance\Attendance;
use App\Helpers\Traits\UserAccessQueryHelper;
use App\Filters\Tenant\AttendanceSummaryFilter;
use App\Filters\Tenant\Helper\UserAccessFilter;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Filters\Tenant\Helper\DepartmentAccessFilter;
use App\Filters\Tenant\Helper\WhereHasUserAccessFilter;
use App\Filters\Tenant\Helper\WhereHasUsersAccessFilter;
use App\Services\Tenant\Dashboard\AdminDashboardService;
use App\Repositories\Tenant\Employee\DepartmentRepository;
use App\Filters\Tenant\Helper\WhereHasEmployeesAccessFilter;
use App\Filters\Tenant\Helper\DashboardUserAccessQueryFilter;
use App\Filters\Tenant\Helper\DashboardWhereHasUserAccessQueryFilter;

class AdminDashboardController extends Controller
{
    use UserAccessQueryHelper, DateRangeHelper, DateTimeHelper;
    /**
     * @var DepartmentAccessFilter
     */
    private DepartmentAccessFilter $departmentFilter;
    /**
     * @var WhereHasUserAccessFilter
     */
    private WhereHasUserAccessFilter $whereHasUserFilter;
    /**
     * @var UserAccessFilter
     */
    private UserAccessFilter $userFilter;

    private DashboardUserAccessQueryFilter $dashboardUserAccessQueryFilter;
    /**
     * @var WhereHasUsersAccessFilter
     */
    private WhereHasUsersAccessFilter $whereHasUsersAccessFilter;
    /**
     * @var WhereHasEmployeesAccessFilter
     */
    private WhereHasEmployeesAccessFilter $whereHasEmployeesAccessFilter;

    private DashboardWhereHasUserAccessQueryFilter $dashboardWhereHasUserAccessQueryFilter;

    public function __construct(
        AdminDashboardService          $service,
        UserAccessFilter               $userFilter,
        DepartmentAccessFilter         $departmentFilter,
        WhereHasUserAccessFilter       $whereHasUserFilter,
        WhereHasUsersAccessFilter      $whereHasUsersAccessFilter,
        WhereHasEmployeesAccessFilter  $whereHasEmployeesAccessFilter,
        DashboardUserAccessQueryFilter $dashboardUserAccessQueryFilter,
        DashboardWhereHasUserAccessQueryFilter $dashboardWhereHasUserAccessQueryFilter,
        AttendanceSummaryFilter        $filter
    )
    {
        $this->service = $service;
        $this->userFilter = $userFilter;
        $this->departmentFilter = $departmentFilter;
        $this->whereHasUserFilter = $whereHasUserFilter;
        $this->whereHasUsersAccessFilter = $whereHasUsersAccessFilter;
        $this->whereHasEmployeesAccessFilter = $whereHasEmployeesAccessFilter;
        $this->dashboardUserAccessQueryFilter = $dashboardUserAccessQueryFilter;
        $this->dashboardWhereHasUserAccessQueryFilter = $dashboardWhereHasUserAccessQueryFilter;
        $this->filter = $filter;
    }

    public function summery(): array
    {
        $employees = User::query()->filters($this->dashboardUserAccessQueryFilter)
            ->where('is_in_employee', 1)->count();
        $departments = Department::query()->filters($this->departmentFilter)->count();

        [$leave_pending, $leave_approved] = resolve(StatusRepository::class)->leavePendingApproved();
        $leave_requests = Leave::query()->filters($this->dashboardWhereHasUserAccessQueryFilter)
            ->where('status_id', $leave_pending)->count();

        $on_leave_today = Leave::query()->filters($this->dashboardWhereHasUserAccessQueryFilter)
            ->where('status_id', $leave_approved)
            ->whereDate('start_at', '>=', todayFromApp())
            ->whereDate('end_at', '<=', todayFromApp())
            ->groupBy('user_id')->count();

        return [
            'total_employee' => auth()->user()->can('view_employees') ? $employees : 0,
            'total_department' => auth()->user()->can('view_departments') ? $departments : 0,
            'total_leave_request' => auth()->user()->can('view_all_leaves') ? $leave_requests : 0,
            'on_leave_today' => auth()->user()->can('view_all_leaves') ? $on_leave_today : 0,
        ];
    }

    public function onWorking(): array
    {
        $attendances = Attendance::query()->filters($this->dashboardWhereHasUserAccessQueryFilter)
            ->whereDate('in_date', '=', todayFromApp())->get();
        $attendancesStats = $attendances->countBy(fn(Attendance $attendance) => $attendance->behavior);

        return [
            'total' => $attendances->count(),
            'behaviour' => [
                'early' => Arr::get($attendancesStats, 'early') ?: 0,
                'late' => Arr::get($attendancesStats, 'late') ?: 0,
                'regular' => Arr::get($attendancesStats, 'regular') ?: 0,
            ]
        ];
    }

    public function employeeStatistics()
    {
        if (\request()->get('key') === 'by_employment_status' && auth()->user()->can('view_employment_statuses')) {
            return EmploymentStatus::filters($this->whereHasEmployeesAccessFilter)
                ->get()
                ->flatMap(function (EmploymentStatus $status) {
                return [
                    $status->name => $status->employees()
                        ->filters($this->dashboardUserAccessQueryFilter)
                        ->whereNull('end_date')
                        ->where('is_in_employee', 1)
                        ->count()
                ];
            });
        }
        if (\request()->get('key') === 'by_designation' && auth()->user()->can('view_designations')) {
            return Designation::filters($this->whereHasUsersAccessFilter)
                ->get()
                ->flatMap(function (Designation $designation) {
                return [
                    $designation->name => $designation->users()
                        ->filters($this->dashboardUserAccessQueryFilter)                        ->whereNull('end_date')
                        ->where('is_in_employee', 1)
                        ->count()
                ];
            });
        }
        if (\request()->get('key') === 'by_department' && auth()->user()->can('view_departments')) {
            return Department::filters($this->whereHasUsersAccessFilter)->get()->flatMap(function (Department $department) {
                return [$department->name => $department->users()
                    ->filters($this->dashboardUserAccessQueryFilter)                    ->whereNull('end_date')
                    ->where('is_in_employee', 1)
                    ->count()
                ];
            });
        }

        throw new GeneralException('can_not_fetch_data');

    }
    public function reportEmployee()
    {

        $within = request()->get('within');
        $month = $within ?: request('month_number') + 1;
        $ranges = $this->getStartAndEndOf($month, request()->get('year'));

        $attendanceApprove = resolve(StatusRepository::class)->attendanceApprove();

        $users = User::filters($this->userFilter)
            ->select(['id', 'first_name', 'last_name'])
            ->whereHas('attendances', $this->filter->rangeFilter($attendanceApprove, $ranges))
            ->with([
                'attendances' => function (HasMany $builder) use ($attendanceApprove, $ranges) {
                    $builder->select(['id', 'in_date', 'user_id', 'behavior']);
                    if (count($ranges) == 1) {
                        return $builder->whereDate('in_date', $ranges[0])
                            ->where('status_id', $attendanceApprove);
                    }
                    return $builder->whereDate('in_date', '>=', $ranges[0])
                        ->where('status_id', $attendanceApprove)
                        ->whereHas(
                            'details',
                            fn(Builder $bl) => $bl->whereDate('out_time', '<=', $ranges[1])
                                ->where('status_id', $attendanceApprove)
                        );
                },
                'attendances.details' => function (HasMany $details) use ($attendanceApprove) {
                    $details->where('status_id', $attendanceApprove)
                        ->orderBy('in_time', 'DESC')
                        ->select([
                            'id',
                            'in_time',
                            'out_time',
                            'attendance_id',
                            'status_id',
                            'review_by',
                            'added_by',
                            'attendance_details_id',
                            'in_ip_data',
                            'out_ip_data',
                            'in_time_late',
                            'out_time_early',
                            'works',
                        ]);
                },'department','roles'
            ])
            ->latest('id')
            ->paginate(request()->get('per_page', 10));
        $data = $users->reduce(function ($user, $currentUser) {

            $attendance = $currentUser->attendances;
            $a = $attendance->reduce(function ($attendance,$currentAttendance){
                $details = $currentAttendance->details;
                $t = $details->reduce(function ($details,$currentDetails){
                    $works = $currentDetails->works;
                    $totalMinutesLate = ($currentDetails->in_time_late > 0 ? $currentDetails->in_time_late : 0 );
                    $totalMinutesEarly = ($currentDetails->out_time_early > 0 ? $currentDetails->out_time_early : 0 );
                    return [
                        'work' => $details['work'] + $works,
                        'in_time_late' => $details['in_time_late'] + $totalMinutesLate,
                        'out_time_early' => $details['out_time_early'] + $totalMinutesEarly
                    ];
                },['work' => 0, 'in_time_late'=>0,'out_time_early'=>0]);

                return [
                    'work' => $attendance['work'] + $t['work'],
                    'in_time_late' => $attendance['in_time_late'] + $t['in_time_late'],
                    'out_time_early' => $attendance['out_time_early'] + $t['out_time_early']
                ];
            }, ['work' => 0, 'in_time_late'=>0,'out_time_early'=>0]);

            $currentUser->work = $a['work'];
            $currentUser->in_time_late = $a['in_time_late'];
            $currentUser->out_time_early = $a['out_time_early'];
            unset($currentUser['attendances']);

            array_push($user,$currentUser);
            $user = collect($user)->sortBy('in_time_late')->toArray();

            return $user;

        }, []);

        return ['data'=>[...$data]];

    }
}
