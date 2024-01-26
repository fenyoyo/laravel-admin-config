<?php


use Illuminate\Support\Facades\Route;

Route::resource('app-config', \Intop\Admin\Config\Http\Controllers\AppConfigController::class);