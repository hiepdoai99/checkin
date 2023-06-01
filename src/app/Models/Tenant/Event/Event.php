<?php

namespace App\Models\Tenant\Event;

use App\Helpers\Traits\DateRangeHelper;
use App\Models\Tenant\Event\Relationship\EventRelationship;
use App\Models\Tenant\Event\role\EventRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Event extends TenantModel
{
    use EventRules, EventRelationship;

    use DateRangeHelper;

    protected $fillable = ['name', 'start_date', 'end_date', 'description', 'tenant_id'];

    public function scopeRanges(Builder $builder, $ranges)
    {
        if (count($ranges) == 1) {
            return $builder->whereBetween(DB::raw('DATE(start_date)'), [$ranges[0], $ranges[0]])
                ->whereBetween(DB::raw('DATE(end_date)'), [$ranges[0], $ranges[0]]);
        }

        return $builder->whereBetween(DB::raw('DATE(start_date)'), $ranges)
            ->whereBetween(DB::raw('DATE(end_date)'), $ranges);
    }

    public function scopeGeneral(Builder $builder): void
    {
        $builder->whereDoesntHave('departments');
    }

    public function scopeWhereDepartments(Builder $builder, array $departments): void
    {
        $builder->whereHas(
            'departments',
            fn(Builder $bl) => $bl->whereIn('id', $departments)
        );
    }

    public static function getDatesFromEvents($events)
    {
        return $events->reduce(fn($events, Event $Event) => array_merge(
            $events,
            (new static())->dateRange(Carbon::parse($Event->start_date, 'UTC'), Carbon::parse($Event->end_date, 'UTC'))
        ), []);
    }

    public static function generalEvents($ranges): Collection
    {
        return self::ranges($ranges)->general()->get(['id', 'name', 'start_date', 'end_date']);
    }
}
