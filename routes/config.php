<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => config('admin.route.prefix'),
], function (Router $router) {
    $router->resource('app-config', \Intop\Admin\Config\Http\Controllers\AppConfigController::class);
});
