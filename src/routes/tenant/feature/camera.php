<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Camera\CameraApiController;
use App\Http\Controllers\Tenant\Camera\CameraDeviceController;
use App\Http\Controllers\Tenant\Camera\CameraPersonController;
use App\Http\Controllers\Tenant\Camera\CameraPlaceController;
use App\Http\Controllers\Tenant\Camera\CameraSettingController;

Route::group(['prefix' => 'app', 'middleware' => ['check_behavior', 'can_access:view_cameras']], function (Router $router) {
    $router->apiResource('cameras/hanets', CameraApiController::class);
    $router->apiResource('cameras/places', CameraPlaceController::class);
    $router->apiResource('cameras/devices', CameraDeviceController::class);
    $router->patch('cameras/persons/{person}/sync', [CameraPersonController::class, 'sync'])->name('cameras.persons.sync');
    $router->apiResource('cameras/persons', CameraPersonController::class);
    
    $router->get('settings/cameras', [CameraSettingController::class, 'index'])
        ->name('camera-settings.index');
});
