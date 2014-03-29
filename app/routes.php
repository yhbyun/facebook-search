<?php

Route::pattern('id', '[a-z0-9\-]+');

Route::group(array('prefix' => ''), function() {
    Route::get('', 'FacebookController@getIndex');
    Route::get('login', 'FacebookController@getLogin');
    Route::get('login/callback', 'FacebookController@getCallback');
    Route::get('logout', 'FacebookController@getLogout');
    Route::get('posts/{id}', ['as' => 'facebook.posts', 'uses' => 'FacebookController@getPosts']);
});