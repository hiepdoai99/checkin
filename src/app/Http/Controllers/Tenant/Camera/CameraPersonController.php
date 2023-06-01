<?php

namespace App\Http\Controllers\Tenant\Camera;

use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Htqxd\LaravelHanetApi\Models\Hanet;
use Htqxd\LaravelHanetApi\Models\HanetPlace;
use App\Services\Tenant\Camera\CameraApiService;
use Htqxd\LaravelHanetApi\Actions\GetHanetPlaces;

class CameraPersonController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! ($place = HanetPlace::first())) {
            if (app(GetHanetPlaces::class)->execute()) {
                $place = HanetPlace::first();
            } else {
                return [];
            }
        }
        return hanet()->person()->getListByPlace(
            $place->placeID
        )->object();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($person)
    {
        return hanet()->person()->getUserInfoByPersonID($person)->getData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request, $person)
    {
        hanet()->person()->updateAliasID($person, $request->aliasID);
        return updated_responses('sync_camera');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HanetPlace $place)
    {
        //
    }
}
