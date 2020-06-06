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

Route::middleware('auth:api')->get('/user/bla', function (Request $request) {
    return $request->user();
});
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('product', 'ProductController@index');
});

// Route::get('product', 'ProductController@index');
Route::get('/pagination/{pagination}/{page}', 'ProductController@pagination');
Route::post('product', 'ProductController@create');
Route::get('/product/{id}', 'ProductController@show');
//Route::put('/product/{id}', 'ProductController@update');
Route::post('/product/{id}', 'ProductController@update'); //update pake post dulu
Route::delete('/product/{id}', 'ProductController@destroy');



