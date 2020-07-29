<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Category;

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

Route::post('categories', 'Api\CategoriesController@store');
Route::put('categories', 'Api\CategoriesController@update');

//Route::resource('categories', 'Api\CategoriesController');
Route::get('/categories', function (Request $request) {
    $nested = $request->get('all');
    if ($nested) {
        return response()->json(Category::whereNull('parent_id')->with('allChildrenCategories')->orderBy('order', 'ASC')->get());
    }
    return response()->json(Category::all());
});
