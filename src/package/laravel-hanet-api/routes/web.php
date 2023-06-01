<?php

use Illuminate\Support\Facades\Route;
use Htqxd\LaravelHanetApi\Http\Controllers\HanetTestController;
use Htqxd\LaravelHanetApi\Http\Controllers\HanetOAuthController;
use Htqxd\LaravelHanetApi\Http\Controllers\HanetCallbackController;

Route::get('hanet/test', [HanetTestController::class, 'index'])->name('hanet.test');

Route::get('hanet/callback', HanetCallbackController::class)->name('hanet.callback');
Route::get('hanet/oauth', HanetOAuthController::class)->name('hanet.oauth');