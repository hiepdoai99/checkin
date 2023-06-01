<?php

use App\Http\Controllers\Common\Setting\SettingsFormatController;
use Illuminate\Support\Facades\Route;

Route::get('app/settings-format', [SettingsFormatController::class, 'configs'])
    ->name('settings.config');