<?php

namespace App\Models\Tenant\Attendance;

use App\Helpers\Traits\DateTimeHelper;
use App\Models\Tenant\Attendance\Relationship\AttendanceDetailsRelationship;
use App\Models\Tenant\TenantModel;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceDetails extends TenantModel
{
    use AttendanceDetailsRelationship,DateTimeHelper;

    protected $fillable = [
        'in_time', 'out_time', 'attendance_id', 'status_id', 'review_by','added_by', 'attendance_details_id', 'in_ip_data', 'out_ip_data',
        'in_time_late', 'out_time_early', 'works',
    ];

    public static function getUnPunchedOut($user_id)
    {
        $attendanceApprove = resolve(StatusRepository::class)->attendanceApprove();

        return self::query()
            ->whereNull('out_time')
            ->whereHas('attendance', fn(Builder $builder) => $builder->where('user_id', $user_id))
            ->where('status_id', $attendanceApprove)
            ->first();
    }
}
