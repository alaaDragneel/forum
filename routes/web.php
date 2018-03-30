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

Route::get('/', function ()
{
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Threads Routes
Route::get('threads', 'ThreadsController@index')->name('threads.index');
Route::get('threads/create', 'ThreadsController@create')->name('threads.create');
Route::get('threads/search', 'SearchController@show')->name('threads.search');
Route::post('threads', 'ThreadsController@store')->name('threads.store')->middleware('must-be-confirmed');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::post('threads/{thread}/lock', 'LockedThreadsController@store')->name('threads.lock.store')->middleware('admin');
Route::delete('threads/{thread}/lock', 'LockedThreadsController@destroy')->name('threads.lock.destroy')->middleware('admin');
Route::patch('threads/{channel}/{thread}', 'ThreadsController@update')->name('threads.update');
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy')->name('threads.destroy');
Route::get('threads/{channel}', 'ThreadsController@index')->name('threads.channels'); // must be down here
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

// replies Routes
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('replies/{reply}/favorites', 'FavoriteController@store')->name('favorites.replies.store');
Route::delete('replies/{reply}/favorites', 'FavoriteController@destroy')->name('favorites.replies.destroy');
Route::patch('replies/{reply}', 'RepliesController@update')->name('replies.update');
Route::delete('replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');
Route::post('/replies/{reply}/best', "BestRepliesController@store")->name('best-replies.store');

// users routes
Route::get('profiles/{profileUser}', 'ProfilesController@show')->name('profiles.show');
Route::get('profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
Route::get('api/users', 'Api\UsersController@index');
Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');
Route::post('api/users/{user}/avatar', 'Api\UsersAvatarController@store')->name('avatar.upload')->middleware('auth');

