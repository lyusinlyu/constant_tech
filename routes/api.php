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

Route::post('auth/register', 'Api\RegisterController@register');
Route::post('auth/login', 'Api\LoginController@login');

Route::group(['middleware' => ['auth:api']], function (){
    Route::get('me', function () {
        return response()->json(Auth::user());
    });
    Route::get('categories', 'Api\CategoriesController@index');
    Route::post('categories', 'Api\CategoriesController@store');
    Route::put('categories', 'Api\CategoriesController@update');
});
