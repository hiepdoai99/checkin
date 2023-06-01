<?php

namespace App\Export;

use App\Helpers\Traits\DateRangeHelper;
use App\Helpers\Traits\DateTimeHelper;
use App\Models\Tenant\Attendance\Attendance;
use App\Models\Tenant\Attendance\AttendanceDetails;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllEmployeeAttendanceExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable, DateTimeHelper, DateRangeHelper;


    private Collection $users;
    private float $total;
    private float $total_minutes_late;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function headings(): array
    {
        return [
            __t('name'),
            __t('date'),
            __t('punch_in'),
            // __t('in_ip'),
            // __t('in_note'),
            __t('punch_out'),
            // __t('out_ip'),
            // __t('out_note'),
            __t('total_hours'),
            __t('behavior'),
            // __t('type'),
            __t('request_note'),
            __t('punch_in_late_min'),
            __t('punch_out_early_min'),
            __t('total_works_of_day'),
            __t('total_works'),
            __t('total_minutes_late'),
        ];
    }

    public function array(): array
    {
        return $this->users->map(function ($user) {
            $this->total = 0.0;
            $this->total_minutes_late = 0.0;

            return $this->getUserAttendancesRows($user->attendances, $user->full_name);
        })->flatten(1)->toArray();

    }

    public function getUserAttendancesRows($attendances, $name)
    {
        return $attendances->map(fn(Attendance $attendance) => $this->makeAttendanceRow($attendance, $name))->toArray();
    }

    public function makeAttendanceRow($attendance, $name): array
    {
        return $attendance->details->map(function (AttendanceDetails $attendanceDetails) use ($attendance, $name) {
            $in_time = $this->carbon($attendanceDetails->in_time)->parse()->setTimezone(request('timeZone'))->toTimeString();
            $out_time = $attendanceDetails->out_time ?
                $this->carbon($attendanceDetails->out_time)->parse()->setTimezone(request('timeZone'))->toTimeString()
                : __t('not_yet');
            $total_hours = $attendanceDetails->out_time ?
                $this->convertSecondsToHoursMinutes(
                    $this->carbon($attendanceDetails->in_time)->parse()->diffInSeconds($attendanceDetails->out_time)
                )
                : '00:00';
            
            $works = $attendanceDetails->works;
            $this->total += $works;
            $minutes = ($attendanceDetails->in_time_late > 0 ? $attendanceDetails->in_time_late : 0) 
                + ($attendanceDetails->out_time_early > 0 ? $attendanceDetails->out_time_early : 0);
            $this->total_minutes_late += $minutes;
            return [
                $name,
                $this->carbon($attendance->in_date)->parse()->setTimezone(request('timeZone'))->format('d-m-Y'),
                $in_time,
                // json_decode($attendanceDetails->in_ip_data)->ip ?? '',
                // $this->getNote($attendanceDetails->comments, 'in-note'),
                $out_time,
                // json_decode($attendanceDetails->out_ip_data)->ip ?? '',
                // $this->getNote($attendanceDetails->comments, 'out-note'),
                $total_hours,
                $attendance->behavior,
                // $attendanceDetails->review_by || $attendanceDetails->added_by ? __t('manual') : __t('auto'),
                $this->getNote($attendanceDetails->comments, 'request'),
                $attendanceDetails->in_time_late > 0 ? $attendanceDetails->in_time_late : 0,
                $attendanceDetails->out_time_early > 0 ? $attendanceDetails->out_time_early : 0,
                $works,
                $this->total,
                $this->total_minutes_late,
            ];
        })->toArray();

    }

    private function getNote(Collection $comments, $type)
    {
        if (!$comments->count()) return null;

        return optional($comments->where('type', $type)->sortByDesc('parent_id')->first())->comment;
    }
}