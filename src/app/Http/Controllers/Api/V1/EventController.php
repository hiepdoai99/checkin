<?php

namespace App\Http\Controllers\Api\V1;

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
        /** @var Event $Event */
        $Event = $this->service
            ->save($request->only('name', 'start_date', 'end_date', 'description', 'tenant_id'));

        $Event->departments()->sync($request->get('departments'));

        return created_responses('event');
    }

    public function show(Event $Event)
    {
        return $Event->load('departments:id');
    }

    public function update(Event $Event, Request $request)
    {
        $this->service
            ->setAttributes(request()->all())
            ->validateForUpdate($Event)
            ->validateUpdateRequest($Event);

        $startDate = $this->carbon($Event->start_date)->parse();

        $attributes = request()->only('name', 'start_date', 'end_date', 'description');

       
        $Event->update($attributes);

        $Event->departments()->sync(request()->get('departments'));

        return updated_responses('Event');
    }

    public function destroy(Event $Event)
    {
        $this->service->validateForDelete($Event);

        $Event->departments()->sync([]);

        $Event->delete();
        
        return deleted_responses('Event');
    }
}
