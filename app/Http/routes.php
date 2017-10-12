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
    Route::get('/guests','PageController@showTravelers')->name('show_travelers');
    Route::post('/guides/{guide_id}/candidate','GuestGuideController@candidateGuide')->name('guide_candidate');
    Route::post('/guests/{guest_id}/candidate','GuestGuideController@candidateGuest')->name('guest_candidate');
    Route::post('/guides/{guide_id}/uncandidate','GuestGuideController@uncandidateGuide')->name('guide_uncandidate');
    Route::post('/guests/{guest_id}/uncandidate','GuestGuideController@uncandidateGuest')->name('guest_uncandidate');
    Route::post('/guides/{guide_id}/delete','GuestGuideController@deleteGuide')->name('guide_delete');
    Route::post('/guests/{guest_id}/delete','GuestGuideController@deleteGuest')->name('guest_delete');
    Route::post('/following/{follow_id}','UserController@following')->name('follow_user');
    Route::post('/unfollowing/{follow_id}','UserController@unfollowing')->name('unfollow_user');

    Route::get('/user/{id}','PageController@showUserProfile')->name('show_user');
    Route::group(['prefix' => 'user/{id}'], function () {
        Route::any('message/{send_id}/send','MessageController@sendMessage')->name('send_message');
        Route::any('message/{partner_id}/show','MessageController@loadMessage')->name('load_message');
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
        Route::post('mylog/title/{title_id}/{scene_id}/comments','ItemPostController@postComment')->name('add_comment');
        Route::post('mylog/title/{title_id}/{scene_id}/comments/{comment_user_id}/{comment_id}/delete','ItemPostController@deleteComment')->name('delete_comment');
        Route::post('guestpost','GuestGuideController@postGuest')->name('guest_post');
        Route::post('guidepost','GuestGuideController@postGuide')->name('guide_post');
      });
});
