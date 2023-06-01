<?php

namespace App\Http\Controllers;

use App\Filters\FilterBuilder;
use App\Traits\HtqApiResponse;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller.
 */
class Controller extends BaseController
{
    /**
     * @var FilterBuilder
     */
    protected $filter;

    /**
     * @var BaseService
     */
    protected $service;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HtqApiResponse;

    public function __construct()
    {
        if (request()->is('api*')) {
            Auth::setDefaultDriver('api');
        }
    }
}
