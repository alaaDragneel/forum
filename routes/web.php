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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Threads Routes
Route::get('threads', 'ThreadsController@index')->name('threads.index');
Route::get('threads/create', 'ThreadsController@create')->name('threads.create');
Route::post('threads', 'ThreadsController@store')->name('threads.store');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::get('threads/{channel}', 'ThreadsController@index')->name('threads.channels'); // must be down here
// replies Routes
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('replies/{reply}/favorites', 'FavoriteController@store')->name('favorites.replies.store');