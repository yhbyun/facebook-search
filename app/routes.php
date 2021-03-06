<?php

Route::pattern('id', '[a-z0-9\-]+');

Route::group(array('prefix' => ''), function() {
    Route::get('', ['as' => 'facebook.main', 'uses' => 'FacebookController@getIndex']);
    Route::get('login', ['as' => 'facebook.login', 'uses' => 'FacebookController@getLogin']);
    Route::get('login/callback', ['as' => 'facebook.callback', 'uses' => 'FacebookController@getCallback']);
    Route::get('logout', ['as' => 'facebook.logout', 'uses' => 'FacebookController@getLogout']);
    Route::get('posts/{id}', ['as' => 'facebook.posts', 'uses' => 'FacebookController@getPosts']);
    Route::get('db_posts/{id}', ['as' => 'facebook.db_posts', 'uses' => 'FacebookController@getDBPosts']);
    Route::get('posts/{id}/import', ['as' => 'facebook.posts.import', 'uses' => 'FacebookController@getPostsImport']);

    Route::get('search', ['as' => 'facebook.search', 'uses' => 'SearchController@getIndex']);

    Route::get('audio/demo1', ['as' => 'facebook.audio.demo1', 'uses' => 'AudioController@getDemo1']);
});
