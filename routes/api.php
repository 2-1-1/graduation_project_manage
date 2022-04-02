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
    Route::post('login','LoginController@loginApi');
    Route::post('register','RegisterController@registerApi');
});

Route::group([
    'namespace' => 'User',
    'prefix' => 'user',
    'middleware' => 'auth'
], function () {
    Route::get('getStudentList','GetListController@getStudentListApi');
});

Route::group([
    'namespace' => 'Faculty',
    'prefix' => 'faculty',
    'middleware' => 'auth'
], function () {
    Route::get('list','FacultyController@facultyListApi');
    Route::get('classList','ClassController@classListApi');
});

Route::group([
    'namespace' => 'Thesis',
    'prefix' => 'thesis',
    'middleware' => 'auth'
], function () {
    Route::post('list','ThesisController@getlistApi');
    Route::post('detail','ThesisController@getdetailApi');
    Route::post('approvalThesis','ThesisController@approvalThesisApi');
    Route::post('upload','ThesisController@uploadApi');
});

Route::group([
    'namespace' => 'Task',
    'prefix' => 'task',
    'middleware' => 'auth'
], function () {
    Route::post('list','TaskController@getlistApi');
    Route::post('detail','TaskController@getdetailApi');
    Route::post('release','TaskController@releaseTaskApi');
    Route::post('upload','TaskController@uploadApi');
    Route::post('approvalTask','TaskController@approvalTaskApi');
    Route::post('uploadStudent','TaskController@uploadStudentApi');
});

Route::group([
    'namespace' => 'Weekly',
    'prefix' => 'weekly',
    'middleware' => 'auth'
], function () {
    Route::post('list','WeeklyController@getlistApi');
    Route::post('detail','WeeklyController@getdetailApi');
    Route::post('upload','WeeklyController@uploadApi');
    Route::post('approvalWeekly','WeeklyController@approvalWeeklyApi'); 
});

Route::group([
    'namespace' => 'Download',
    'middleware' => 'auth'
], function () {
    Route::post('download','DownloadController@downloadApi');
});
