<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/v1/web/getCategories', 'CategoryController@getCategories');
Route::post('/v1/web/update/{category}', 'CategoryController@update');
Route::post('/v1/web/new/{parent}', 'CategoryController@new');
Route::get('/v1/web/destroy/{category}', 'CategoryController@destroy');
