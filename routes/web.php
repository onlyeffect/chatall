<?php

Route::get('/', 'PostsController@index');

Route::resource('posts', 'PostsController');

Auth::routes();

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::post('posts/{id}/comments', 'CommentsController@create')->name('posts.addComment');