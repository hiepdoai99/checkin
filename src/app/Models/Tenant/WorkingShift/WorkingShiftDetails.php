<?php

namespace App\Models\Tenant\WorkingShift;

use App\Helpers\Core\Traits\Memoization;
use App\Helpers\Traits\DateTimeHelper;
use App\Models\Tenant\Attendance\AttendanceDetails;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\WorkingShift\Relationship\WorkingShiftDetailsRelationship;
use App\Services\Tenant\Attendance\AttendanceService;
use App\Services\Tenant\Attendance\AttendanceSummaryService;
use Illuminate\Support\Carbon;

class WorkingShiftDetails extends TenantModel
{
    use WorkingShiftDetailsRelationship;
    use Memoization, DateTimeHelper;

    protected $fillable = [
        'weekday', 'working_shift_id', 'is_weekend', 'start_at', 'end_at'
    ];


    public function getWorkingHourInSeconds()
    {
        return $this->memoize('details-total-work-hour-' . $this->id, function () {
            $end_at = $this->carbon($this->end_at)->parse();
            $start_at = $this->carbon($this->start_at)->parse();

            if ($end_at->isAfter($start_at)) {
                return $end_at->diffInSeconds($start_at);
            }

            $start_at = $this->carbon(nowFromApp()->format('Y-m-d') . " " . $this->start_at)->parse();
            $end_at = $this->carbon(nowFromApp()->addDay()->format('Y-m-d') . " " . $this->end_at)->parse();

            return $end_at->diffInSeconds($start_at);
        });
    }
    public function getLunchTime(): array
    {
        return [
            'start' => $this->carbon($this->carbon($this->start_at)->parse()->toDateString().' 05:00:00', 'UTC')->parse(),
            'end' => $this->carbon($this->carbon($this->end_at)->parse()->toDateString().' 06:30:00', 'UTC')->parse(),
        ];
    }


    public function getWorkingWorks()
    {
        return $this->memoize('details-total-work-hour-' . $this->id, function () {

            $lunchTime = $this->getLunchTime();
            $end_at = $this->carbon($this->end_at)->parse();
            $start_at = $this->carbon($this->start_at)->parse();

            $works = 0.0;

            if ($start_at->isBefore($lunchTime['start'])) {
                $works += 0.5;
            }
            if ($end_at->isAfter($lunchTime['end'])){
                $works += 0.5;
            }
            return $works;
        });

    }

}
