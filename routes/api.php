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

Route::group([
    'namespace' => 'User',
], function () {
    Route::post('login','LoginController@loginSuper');
    Route::post('register','RegisterController@registerApi');
});

Route::group([
    'namespace' => 'Facutly',
    'prefix' => 'facutly',
    'middleware' => 'auth'
], function () {
    Route::get('list','FacutlyController@FacutlyListApi');
    Route::get('classList','ClassController@ClassListApi');
});
