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

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/get-url/{id}', 'HomeController@getUrl')->name('get-url');
Route::post('/update-url/{id}', 'HomeController@updateUrl')->name('update-url');
Route::get('/analytics/{id}/{time}', 'HomeController@analytics')->name('analytics');
Route::get('/auto-uri', 'HomeController@getUri')->name('auto-uri');
Route::post('/shortener', 'HomeController@shortener')->name('shortener');
Route::get('/{uri}', 'ProcessController@index')->name('process');
