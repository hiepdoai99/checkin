<?php

namespace App\Http\Controllers\Tenant\Event;

use App\Filters\Tenant\EventFilter;
use App\Helpers\Traits\DateTimeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Event\EventRequest;
use App\Models\Tenant\Event\Event;
use App\Services\Tenant\Event\EventService;
use Illuminate\Http\Request;


class EventController extends Controller
{
    use DateTimeHelper;

    public function __construct(EventService $service, EventFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with('departments:id,name',)
            ->oldest('start_date')
            ->when(request()->has('view_type') && request()->has('view_type') == 'calender',
                fn ($builder) =>$builder->paginate(Event::query()->count()),
                fn ($builder) =>$builder
                    ->when(!request()->get('time_period') ||
                        !json_decode(htmlspecialchars_decode(request()->get('time_period')), true),
                        fn($query) => $query->whereYear('start_date', nowFromApp()->year),
                        fn($builder) => $this->service->timePeriod($builder, request()->get('time_period'))
                    )->paginate(request()->get('per_page', 10))
            );
    }

    public function store(EventRequest $request)
    {
        /** @var Event $event */
        $event = $this->service
            ->save($request->only('name', 'start_date', 'end_date', 'description', 'tenant_id'));

        $event->departments()->sync($request->get('departments'));

        return created_responses('Event');
    }

    public function show(Event $event)
    {
        return $event->load('departments:id');
    }

    public function update(Event $event, Request $request)
    {
        $this->service
            ->setAttributes(request()->all())
            ->validateForUpdate($event)
            ->validateUpdateRequest($event);

        $startDate = $this->carbon($event->start_date)->parse();

        $attributes = request()->only('name', 'start_date', 'end_date', 'description');


        $event->update($attributes);

        $event->departments()->sync(request()->get('departments'));

        return updated_responses('Event');
    }

    public function destroy(Event $event)
    {
        $this->service->validateForDelete($event);

        $event->departments()->sync([]);

        $event->delete();
        
        return deleted_responses('Event');
    }
}
