<?php


namespace App\Http\Composer\Helper;

use App\Helpers\Core\Traits\InstanceCreator;

class CameraPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('cameras_place'),
                'url' => $this->cameraPlaceUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
            [
                'name' => __t('cameras'),
                'url' => $this->cameraUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
            [
                'name' => __t('cameras_person_emp'),
                'url' => $this->personUrl(),
                'permission' => auth()->user()->can('view_cameras')
            ],
        ];
    }

    public function canVisit()
    {
        return authorize_any(['view_cameras']);
    }

    public function cameraPlaceUrl()
    {
        return route(
            'support.places',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function cameraUrl()
    {
        return route(
            'support.cameras',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }

    public function personUrl()
    {
        return route(
            'support.persons',
            optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name ]
        );
    }
}
