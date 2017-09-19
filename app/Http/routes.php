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

//ユーザー登録
Route::get('signup','Auth\AuthController@getRegister')->name('signup.get');
Route::post('signup','Auth\AuthController@postRegister')->name('signup.post');
Route::get('logout', 'Auth\AuthController@getLogout')->name('logout.get');

//ログイン認証
Route::get('login','Auth\AuthController@getLogin')->name('login.get');
Route::post('/login','Auth\AuthController@postLogin')->name('login.post');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/newprofilecreate','PageController@newProfileCreate');
    Route::get('/guides','PageController@showGuides')->name('show_guides');
    Route::get('/travelers','PageController@showTravelers')->name('show_travelers');

    Route::get('/user/{id}','PageController@showUserProfile')->name('show_user');
    Route::group(['prefix' => 'user/{id}'], function () {
        Route::post('profile/edit','UserOptionController@edit')->name('edit_user_profile');
        Route::get('profile','PageController@showUserProfile')->name('show_user_profile');
        Route::get('message','PageController@showUserMessages')->name('show_user_messages');
        Route::get('favorite','PageController@showUserFavorites')->name('show_user_favorites');
        Route::get('matching','PageController@showUserMatching')->name('show_user_matching');
        Route::get('mylog','PageController@showUserItems')->name('show_user_items');
        Route::post('mylog/creating','PageController@createItems')->name('create_items');
        Route::get('mylog/title/{title_id}','PageController@showTitle')->name('show_title');
      });
});
