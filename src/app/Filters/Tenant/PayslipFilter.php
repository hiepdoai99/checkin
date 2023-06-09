<?php


namespace App\Filters\Tenant;


use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\FilterThroughDepartment;
use App\Filters\Traits\SearchThroughUserFilter;
use App\Filters\Traits\StatusFilterTrait;
use App\Helpers\Traits\MakeArrayFromString;
use Illuminate\Database\Eloquent\Builder;

class PayslipFilter extends FilterBuilder
{
    use DateRangeFilter,
        MakeArrayFromString,
        SearchThroughUserFilter,
        FilterThroughDepartment,
        StatusFilterTrait;

    public function type($type = null)
    {
//        $types = $this->makeArray($type);
        $this->builder->when($type, function (Builder $builder) use ($type) {
            $builder->whereHas(
                'payrun',
                fn(Builder $b) => $b->whereJsonContains('data->type', $type)
            );
        });
    }
    public function payrunPeriod($period = null)
    {
//        $periods = $this->makeArray($period);
        $this->builder->when($period, function (Builder $builder) use ($period) {
            $builder->whereHas(
                'payrun',
                fn(Builder $b) => $b->whereJsonContains('data->period', $period)
            );
        });
    }

    public function payrun($payrun = null)
    {
        $this->builder->when($payrun, function (Builder $builder) use ($payrun) {
            $builder->whereHas(
                'payrun',
                fn(Builder $b) => $b->where('uid', $payrun)
            );
        });
    }

    public function rangeFilter($attendanceActive, $ranges)
    {
        return function (Builder $builder) use ($attendanceActive, $ranges) {
            if (count($ranges) == 1) {
                return $builder->whereDate('in_date', $ranges[0])
                    ->where('status_id', $attendanceActive);
            }

            return $builder->whereDate('in_date', '>=', $ranges[0])
                ->where('status_id', $attendanceActive)
                ->whereHas(
                    'details',
                    fn(Builder $bl) => $bl->whereDate('out_time', '<=', $ranges[1])
                        ->where('status_id', $attendanceActive)
                );
        };
    }

}