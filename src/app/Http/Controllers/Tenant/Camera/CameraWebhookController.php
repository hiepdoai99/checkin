<?php

namespace App\Http\Controllers\Tenant\Camera;

use Illuminate\Http\Request;
use App\Actions\HanetPunchInWebhook;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Camera\CameraApiService;

class CameraWebhookController extends Controller
{
    function __construct(CameraApiService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $data_type = $request?->data_type;
        switch ($data_type) {
            case 'log':
                app(HanetPunchInWebhook::class)->onQueue()->execute(
                    array_merge($request->all(), ['ip' => $request->ip()])
                );
                break;
            
            case 'device':
                Log::info('DEVICE');
                Log::info(print_r($request->all(), true));
                break;
            
            case 'person':
                Log::info('PERSON');
                Log::info(print_r($request->all(), true));
                break;
            
            case 'place':
                Log::info('PLACE');
                Log::info(print_r($request->all(), true));
                break;
                
            default:
                Log::info('DEFAULT');
                Log::info(print_r($request->all(), true));
                break;
        }

        return created_responses('hanet');
    }

}
