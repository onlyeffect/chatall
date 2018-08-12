<?php

Auth::routes();

Route::get('/', 'PostsController@index');

Route::resource('posts', 'PostsController');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::get('users', 'UsersController@index')->name('users');

Route::post('posts/{id}/comments', 'CommentsController@create')->name('posts.addComment');

Route::get('/chat', 'ChatController@index')->name('chat');

Route::get('/messages', 'MessagesController@index')->name('messages');
Route::post('/message', 'MessagesController@store')->name('message.store');