<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'domain'        => config('baseAdmin.route.domain'),
    'prefix'        => config('baseAdmin.route.prefix'),
    'middleware'    => config('baseAdmin.route.middleware'),
    'namespace'     => config('baseAdmin.route.namespace'),
    'as'            => config('baseAdmin.route.as')
], function ($router) {
    $router->group([

    ], function ($router) {

    });

    //管理员
    $router->get('/admin', 'Admin@index')->name('admin.index');
});
