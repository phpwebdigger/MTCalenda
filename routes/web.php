<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::resource('gcalendar', 'MTCalendarController');

Route::get('create', 'MTCalendarController@create');
Route::post('store', 'MTCalendarController@store')->name('store');

 Route::get('loadcalapidata','MTCalendarController@getapihoursdata');  
 Route::get('loadcalapidaysdata','MTCalendarController@getapidaysdata');  


Route::get('oauth', ['as' => 'oauthCallback', 'uses' => 'MTCalendarController@oauth']);