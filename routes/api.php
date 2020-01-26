<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Attribute', 'prefix' => 'attribute'], function () {
        Route::get('/', 'AttributeController')->middleware('throttle:240,1');
    });
    Route::group(['namespace' => 'Search', 'prefix' => 'search'], function () {
        Route::get('/', 'SearchController')->middleware('throttle:240,1');
    });
});
