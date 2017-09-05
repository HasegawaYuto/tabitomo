<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PageController@showItems')->name('show_items');

Route::get('/login',function(){
  return view('auth.login');
})->name('login.get');
Route::get('signup','Auth\AuthController@getRegister')->name('signup.get');
Route::post('signup','Auth\AuthController@postRegister')->name('signup.post');
Route::post('/login',function(){
  return redirect('/');
})->name('login.post');

Route::get('/guides','PageController@showGuides')->name('show_guides');
Route::get('/travelers','PageController@showTravelers')->name('show_travelers');

Route::get('/user/{id}','PageController@showUserProfile')->name('show_user');
Route::group(['prefix' => 'user/{id}'], function () {
Route::get('profile','PageController@showUserProfile')->name('show_user_profile');
Route::get('message','PageController@showUserMessages')->name('show_user_messages');
Route::get('favorite','PageController@showUserFavorites')->name('show_user_favorites');
Route::get('matching','PageController@showUserMatching')->name('show_user_matching');
Route::get('items','PageController@showUserItems')->name('show_user_items');
Route::get('postitems','PageController@createItems')->name('create_items');
});
