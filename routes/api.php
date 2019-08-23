<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('links', 'Api\LinkController')->middleware('auth:api');

Route::post('register', 'Auth\RegisterController@register');
Route::post('auth', 'Auth\LoginController@login');

Route::any('/{any}', function () {
    return response(null, 404);
});
