<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * Implements Routes::Resources() from Laravel
 */
$fncResource = function ($uri, $controller) use ($router) {
    $router->group(['prefix' => $uri], function () use ($router, $controller) {
        $router->get('/', $controller . '@index');
        $router->post('/', $controller . '@store');
        $router->get('/{id}', $controller . '@show');
        $router->put('/{id}', $controller . '@update');
        $router->delete('/{id}', $controller . '@destroy');
    });
};

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Authentication
$router->post('auth/login', 'AuthController@login');

$router->group(['middleware' => 'auth:api'], function () use ($router, $fncResource) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
    });

    $fncResource('users', 'UserController');
});
