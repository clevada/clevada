<?php
/*
|--------------------------------------------------------------------------
| Registered Users Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your registerd users area. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'login/user'], function ($lang) {

    // profile
    Route::get('/profile', 'User\UserController@profile')->name('user.profile')->middleware('verified');
    Route::post('/profile', 'User\UserController@update_profile')->middleware('verified');
    Route::get('/profile/delete-avatar', 'User\UserController@delete_avatar')->middleware('verified')->name('user.profile.delete_avatar');

    // forum
    Route::get('/forum/topics', 'User\ForumController@topics')->name('user.forum.topics')->middleware('verified');
    Route::get('/forum/posts', 'User\ForumController@posts')->name('user.forum.posts')->middleware('verified');
    Route::get('/forum/warnings', 'User\ForumController@warnings')->name('user.forum.warnings')->middleware('verified');
    Route::get('/forum/restrictions', 'User\ForumController@restrictions')->name('user.forum.restrictions')->middleware('verified');
    Route::get('/forum/config', 'User\ForumController@config')->name('user.forum.config')->middleware('verified');
    Route::post('/forum/config', 'User\ForumController@update_config')->name('user.forum.config')->middleware('verified');

    // posts
    Route::resource('/posts', 'User\PostsController')
        ->names(['index' => 'user.posts', 'create' => 'user.posts.create', 'show' => 'user.posts.show'])
        ->parameters(['posts' => 'id'])->middleware('verified');
    Route::post('/posts/{id}/sortable', 'User\PostsController@sortable')->name('user.posts.sortable')->where('id', '[0-9]+')->middleware('verified');    
    Route::post('/posts/{id}/content', 'User\PostsController@update_content')->name('user.posts.content.new')->where('id', '[0-9]+')->middleware('verified');
    Route::get('/posts/blocks/{id}', 'User\PostsController@block')->name('user.posts.block')->where('id', '[0-9]+')->middleware('verified');
    Route::put('/posts/blocks/{id}', 'User\PostsController@update_block')->where('id', '[0-9]+')->middleware('verified');

    Route::delete('/posts/{id}/content/delete/{block_id}', 'User\PostsController@delete_content')->name('user.posts.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+')->middleware('verified');

});


// ROUTES FOR ADDITIONAL LANGUAGES
Route::group([
    'prefix' => '{lang?}/login/user',
    'where' => ['lang' => '[a-zA-Z]{2}']
], function ($lang) {

    // profile
    Route::get('/profile', 'User\UserController@profile')->name('user.profile')->middleware('verified');
    Route::post('/profile', 'User\UserController@update_profile')->middleware('verified');
    Route::get('/profile/delete-avatar', 'User\UserController@delete_avatar')->middleware('verified')->name('user.profile.delete_avatar');

    // forum
    Route::get('/forum/topics', 'User\ForumController@topics')->name('user.forum.topics')->middleware('verified');
    Route::get('/forum/posts', 'User\ForumController@posts')->name('user.forum.posts')->middleware('verified');
    Route::get('/forum/warnings', 'User\ForumController@warnings')->name('user.forum.warnings')->middleware('verified');
    Route::get('/forum/restrictions', 'User\ForumController@restrictions')->name('user.forum.restrictions')->middleware('verified');
    Route::get('/forum/config', 'User\ForumController@config')->name('user.forum.config')->middleware('verified');
    Route::post('/forum/config', 'User\ForumController@update_config')->name('user.forum.config')->middleware('verified');

    // posts
    Route::resource('/posts', 'User\PostsController')
        ->names(['index' => 'user.posts', 'create' => 'user.posts.create', 'show' => 'user.posts.show'])
        ->parameters(['posts' => 'id'])->middleware('verified');
    Route::post('/posts/{id}/sortable', 'User\PostsController@sortable')->name('user.posts.sortable')->where('id', '[0-9]+')->middleware('verified');    
    Route::post('/posts/{id}/content', 'User\PostsController@update_content')->name('user.posts.content.new')->where('id', '[0-9]+')->middleware('verified');
    Route::get('/posts/blocks/{id}', 'User\PostsController@block')->name('user.posts.block')->where('id', '[0-9]+')->middleware('verified');
    Route::put('/posts/blocks/{id}', 'User\PostsController@update_block')->where('id', '[0-9]+')->middleware('verified');

    Route::delete('/posts/{id}/content/delete/{block_id}', 'User\PostsController@delete_content')->name('user.posts.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+')->middleware('verified');
});
