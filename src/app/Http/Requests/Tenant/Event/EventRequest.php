<?php

namespace App\Http\Requests\Tenant\Event;


use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Event\Event;

class EventRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new Event());
    }

    public function messages(): array
    {
        return [
            'start_date.after' => 'The start date must be a date after now'
        ];
    }
}
