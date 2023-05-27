<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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

$router->get('/manage/health', function () use ($router) {
    // @todo use https://spatie.be/docs/laravel-health/v1/available-checks/db-connection
    try {
        DB::connection()->getPdo();
        return new Response('OK', 200);
    } catch (\Exception $e) {
        return new Response('Service Unavailable', 503);
    }
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('/cinema', 'CinemaController@index');
    $router->get('/cinema/{cinemaUid}/films', 'CinemaController@films');
    $router->get('/film-session', 'FilmSessionController@filmSession');
    $router->post('/film-session/{sessionUid}/book-seat', 'FilmSessionController@bookSeat');
});
