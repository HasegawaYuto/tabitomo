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

//Route::get('/', function(){
//    return view('bodys.toppage');
//})->name('show_items');
Route::get('/', 'PageController@showItems')->name('show_items');
Route::post('/search', 'PageController@showItemsSearch')->name('show_items_search');
Route::post('/', 'PageController@showItems')->name('break_condition');

//ユーザー登録
Route::get('signup','Auth\AuthController@getRegister')->name('signup.get');
Route::post('signup','Auth\AuthController@postRegister')->name('signup.post');
Route::get('logout', 'Auth\AuthController@getLogout')->name('logout.get');

//ログイン認証
Route::get('login','Auth\AuthController@getLogin')->name('login.get');
Route::post('/login','Auth\AuthController@postLogin')->name('login.post');

//FACEBOOKログイン
Route::get('auth/login/facebook', 'Auth\SocialController@getFacebookAuth');
Route::get('auth/login/facebook/callback', 'Auth\SocialController@getFacebookCallback');

//Googleログイン
Route::get('auth/login/google','Auth\SocialController@getGoogleAuth');
Route::get('auth/login/google/callback','Auth\SocialController@getGoogleCallback');

//Twitterログイン
Route::get('auth/login/twitter', 'Auth\SocialController@getTwitterAuth');
Route::get('auth/login/twitter/callback', 'Auth\SocialController@getTwitterCallback');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/guides','PageController@showGuides')->name('show_guides');
    Route::get('/guests','PageController@showTravelers')->name('show_travelers');
    Route::post('/guides','PageController@showGuides')->name('break_guides_condition');
    Route::post('/guests','PageController@showTravelers')->name('break_travelers_condition');
    Route::post('/guides/search','PageController@searchGuides')->name('search_guides');
    Route::post('/guests/search','PageController@searchTravelers')->name('search_travelers');
    Route::post('/guides/{guide_id}/candidate','GuestGuideController@candidateGuide')->name('guide_candidate');
    Route::post('/guests/{guest_id}/candidate','GuestGuideController@candidateGuest')->name('guest_candidate');
    Route::post('/guides/{guide_id}/uncandidate','GuestGuideController@uncandidateGuide')->name('guide_uncandidate');
    Route::post('/guests/{guest_id}/uncandidate','GuestGuideController@uncandidateGuest')->name('guest_uncandidate');
    Route::post('/guides/{guide_id}/delete','GuestGuideController@deleteGuide')->name('guide_delete');
    Route::post('/guests/{guest_id}/delete','GuestGuideController@deleteGuest')->name('guest_delete');
    Route::post('/following/{follow_id}','UserController@following')->name('follow_user');
    Route::post('/unfollowing/{follow_id}','UserController@unfollowing')->name('unfollow_user');
    Route::post('mylog/title/{title_id}/favorite','ItemPostController@favoriteTitle')->name('favorite_title');
    Route::post('mylog/title/{title_id}/unfavorite','ItemPostController@unfavoriteTitle')->name('unfavorite_title');
    Route::post('mylog/scene/{scene_id}/favorite','ItemPostController@favoriteScene')->name('favorite_scene');
    Route::post('mylog/scene/{scene_id}/unfavorite','ItemPostController@unfavoriteScene')->name('unfavorite_scene');
    Route::post('/mylog/{scene_id}/delete','ItemPostController@deleteScene')->name('scene_delete');
    Route::post('mylog/scene/{scene_id}/comment','ItemPostController@postComment')->name('add_comment');
    Route::post('mylog/comment/{comment_id}/delete','ItemPostController@deleteComment')->name('delete_comment');
    Route::post('mylog/scene/{scene_id}/delete','ItemPostController@deleteScene')->name('scene_delete');
    Route::post('mylog/title/{title_id}/delete','ItemPostController@deleteTitle')->name('title_delete');
    Route::post('guestpost','GuestGuideController@postGuest')->name('guest_post');
    Route::post('guidepost','GuestGuideController@postGuide')->name('guide_post');
    Route::post('user/{id}/avatardelete','UserOptionController@avatardelete')->name('avatar_delete');
    Route::post('user/{id}/avatarsns','UserOptionController@avatarsns')->name('avatar_sns_change');
    
    Route::get('/user/{id}','PageController@showUserProfile')->name('show_user');
    Route::group(['prefix' => 'user/{id}'], function () {
        Route::post('editplans/{title_id}','PlanController@addSpots')->name('add_spots');
        Route::get('plans/{title_id}','PageController@showPlan')->name('show_plan_detail');
        Route::post('plansheet/{title_id}', 'PlanController@getPlansheet')->name('get_pdf');
        Route::post('plan/create', 'PlanController@createPlan')->name('make_plan');
        Route::get('plans','PageController@showPlans')->name('show_user_plans');
        Route::post('message/{send_id}/send','MessageController@sendMessage')->name('send_message');
        Route::post('message/{partner_id}/show','MessageController@loadMessage')->name('load_message');
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
        Route::post('guestpost','GuestGuideController@postGuest')->name('guest_post');
        Route::post('guidepost','GuestGuideController@postGuide')->name('guide_post');
      });
});
