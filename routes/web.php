<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/analytics/{uri}/{time}', 'HomeController@analytics')->name('analytics');
Route::get('/auto-uri', 'HomeController@getUri')->name('auto-uri');
Route::post('/shortener', 'HomeController@shortener')->name('shortener');
Route::get('/{uri}', 'HomeController@process')->name('process');
