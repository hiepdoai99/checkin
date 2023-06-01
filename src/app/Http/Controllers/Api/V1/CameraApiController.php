<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\HanetFilter;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Camera\CameraApiService;
use Htqxd\LaravelHanetApi\Actions\GetHanetPlaces;
use Htqxd\LaravelHanetApi\Models\Hanet;
use Illuminate\Http\Request;

class CameraApiController extends Controller
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
    public function index(Request $request)
    {
        return Hanet::query()
            ->paginate($request->get('per_page', 10))
            ->appends($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->service
            ->setAttributes($request->only(array_keys(app(Hanet::class)->getRules())))
            ->validate()
            ->save();
        app(GetHanetPlaces::class)->onQueue('high')->execute();
        return created_responses('hanet');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Hanet $hanet)
    {
        return $hanet;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hanet $hanet)
    {
        $this->service
            ->setAttributes($request->only(array_keys(app(Hanet::class)->getRules())))
            ->validate()
            ->save();
        app(GetHanetPlaces::class)->onQueue('high')->execute();
        return updated_responses('hanet');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hanet $hanet)
    {
        try {
            $hanet->delete();
        } catch (\Exception $e) {
            throw new GeneralException(__t('can_not_delete_used_camera_api'));
        }
        app(GetHanetPlaces::class)->onQueue('high')->execute();
        return deleted_responses('hanet');
    }
}
