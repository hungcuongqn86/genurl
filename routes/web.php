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
Route::post('/delete-url/{id}', 'HomeController@deleteUrl')->name('delete-url');
Route::post('/update-link/{id}', 'HomeController@updateLink')->name('update-link');
Route::post('/delete-link/{id}', 'HomeController@deleteLink')->name('delete-link');
Route::post('/add-link/{id}', 'HomeController@addLink')->name('add-link');
Route::get('/analytics/{id}/{time}', 'HomeController@analytics')->name('analytics');
Route::get('/auto-uri', 'HomeController@getUri')->name('auto-uri');
Route::post('/shortener', 'HomeController@shortener')->name('shortener');
Route::get('/{uri}', 'ProcessController@index')->name('process');
