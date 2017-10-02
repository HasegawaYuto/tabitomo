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
        Route::post('mylog/title/{title_id}/{scene_id}/delete','ItemPostController@deleteScene')->name('scene_delete');
        Route::post('mylog/title/{title_id}/delete','ItemPostController@deleteTitle')->name('title_delete');
        Route::post('mylog/title/{title_id}/edit','ItemPostController@editTitle')->name('edit_title');
        Route::post('mylog/title/{title_id}/{scene_id}/edit','ItemPostController@editScene')->name('edit_scene');
        Route::post('profile/edit','UserOptionController@edit')->name('edit_user_profile');
        Route::get('profile','PageController@showUserProfile')->name('show_user_profile');
        Route::get('message','PageController@showUserMessages')->name('show_user_messages');
        Route::get('favorite','PageController@showUserFavorites')->name('show_user_favorites');
        Route::get('matching','PageController@showUserMatching')->name('show_user_matching');
        Route::get('mylog','PageController@showUserItems')->name('show_user_items');
        Route::post('mylog/creating','ItemPostController@createItems')->name('create_items');
        Route::get('mylog/title/{title_id}','PageController@showTitle')->name('show_title');
        Route::get('mylog/title/',function($id){
            return redirect('user/' . $id .'/mylog');
        });
        Route::post('mylog/title/{title_id}/{scene_id}/favorite','ItemPostController@favoriteScene')->name('favorite_scene');
        Route::post('mylog/title/{title_id}/{scene_id}/unfavorite','ItemPostController@unfavoriteScene')->name('unfavorite_scene');
        Route::post('mylog/title/{title_id}/favorite','ItemPostController@favoriteTitle')->name('favorite_title');
        Route::post('mylog/title/{title_id}/unfavorite','ItemPostController@unfavoriteTitle')->name('unfavorite_title');
      });
});
