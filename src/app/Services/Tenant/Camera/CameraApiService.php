<?php

namespace App\Services\Tenant\Camera;

use App\Services\Tenant\TenantService;
use Htqxd\LaravelHanetApi\Models\Hanet;

class CameraApiService extends TenantService
{
    public function __construct(Hanet $hanet)
    {
        $this->model = $hanet;
    }

    public function validate()
    {
        validator($this->getAttributes(), app(Hanet::class)->getRules())->validate();

        return $this;
    }

}
