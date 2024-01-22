<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->get('api-project', [ApiController::class, 'apiTest']);
    $router->get('search-api', [ApiController::class, 'searchApi'])->name('search.api');

});
