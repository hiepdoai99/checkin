<?php

namespace App\Services\Tenant\Event;

use App\Exceptions\GeneralException;
use App\Helpers\Core\Traits\HasWhen;
use App\Helpers\Traits\DateTimeHelper;
use App\Models\Tenant\Event\Event;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Builder;

class EventService extends TenantService
{
    use DateTimeHelper, HasWhen;

    protected $eventDepartments = [];

    public function __construct(Event $event )
    {
        $this->model = $event;
    }

    public function validateForDelete($event): self
    {
        throw_if(
            $this->carbon($event->start_date)->parse()->isPast(),
            new GeneralException(__t('cant_update_Events'))
        );

        return $this;
    }

    public function validateForUpdate($event): self
    {
        $startDate = $this->carbon($event->start_date)->parse();

        throw_if(
            $startDate->isPast() && !$startDate->isCurrentYear(),
            new GeneralException(__t('cant_update_Events'))
        );

        return $this;
    }

    public function validateUpdateRequest($event): self
    {
        $startDate = $this->carbon($event->start_date)->parse();

        $this->when(!$startDate->isPast(),
            function (EventService $service) use($event) {
                validator($service->getAttributes(), $event->createdRules())->validate();
            },
        );


        return $this;
    }

//    public function timePeriod($builder, $period = null)
//    {
//        $period = json_decode(htmlspecialchars_decode($period), true);
//
//        $builder->when($period, function (Builder $builder) use ($period) {
//            $builder->where(function (Builder $builder) use ($period) {
//                $builder->whereBetween('start_date', array_values($period))
//                    ->orWhereBetween('end_date', array_values($period))
//                    ->orWhere(function ($query) use ($period) {
//                        $query->whereDate('start_date', '<=', $period['start'])
//                            ->whereDate('end_date', '>=', $period['end']);
//                    });
//            });
//        });
//    }
}
