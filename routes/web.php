<?php

/**
 * Clevada: #1 Free Business Suite and Website Builder.
 *
 * Copyright (C) 2021  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @author      Chimilevschi Iosif Gabriel <office@clevada.com>
 */


/*
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. 
|--------------------------------------------------------------------------
*/

if (isset($_SERVER['REQUEST_URI'])) {
    $reques_uri = $_SERVER['REQUEST_URI'];
    preg_match_all('#/([^/]*)#', $reques_uri, $matches);
    $lang = $matches[1][0];
} else $lang = null;

if (file_exists(resource_path() . '/clevada/routes/routes.xml')) {
    $routes_xml_data = simplexml_load_file(resource_path() . '/clevada/routes/routes.xml');
    $routes = $routes_xml_data->route;

    foreach ($routes as $route) {
        $route_name = (string)$route->name;
        $route_value = (string)$route->value;
        $route_lang = (string)$route->lang;

        if ($route_name == 'posts') $slug_posts[$route_lang] = $route_value;
        if ($route_name == 'forum') $slug_forum[$route_lang] = $route_value;
        if ($route_name == 'cart') $slug_cart[$route_lang] = $route_value;
        if ($route_name == 'docs') $slug_docs[$route_lang] = $route_value;
        if ($route_name == 'posts_tag') $slug_posts_tag[$route_lang] = $route_value;
    }

    if (array_key_exists('default', $slug_posts)) $slug_posts['default'] = (string)$slug_posts['default'];
    if (array_key_exists($lang, $slug_posts)) $slug_posts[$lang] = (string)$slug_posts[$lang];
    if (array_key_exists($lang, $slug_forum)) $slug_forum[$lang] = (string)$slug_forum[$lang];
    if (array_key_exists($lang, $slug_cart)) $slug_cart[$lang] = (string)$slug_cart[$lang];
    if (array_key_exists($lang, $slug_docs)) $slug_docs[$lang] = (string)$slug_docs[$lang];
}

if ($lang && strlen($lang) == 2) {
    $posts_permalink = $slug_posts[$lang] ?? 'blog';
    $forum_permalink = $slug_forum[$lang] ?? 'forum';
    $cart_permalink = $slug_cart[$lang] ?? 'shop';
    $docs_permalink = $slug_docs[$lang] ?? 'docs';
    $profile_permalink = $slug_profile[$lang] ?? 'profile';
    $search_permalink = $slug_search[$lang] ?? 'search';
    $tag_permalink = $slug_tag[$lang] ?? 'tag';
} else {
    $posts_permalink = $slug_posts['default'] ?? 'blog';
    $posts_tag_permalink = $slug_posts_tag['default'] ?? 'tag';
    $forum_permalink = $slug_forum['default'] ?? 'forum';
    $cart_permalink = $slug_cart['default'] ?? 'shop';
    $docs_permalink = $slug_docs['default'] ?? 'docs';
    $profile_permalink = $slug_profile['default']  ?? 'profile';
    $search_permalink = $slug_search['default'] ?? 'search';
    $tag_permalink = $slug_tag['default'] ?? 'tag';
}


// DEFAULT ROUTES FOR DEFAULT LANGUAGE
Auth::routes(['verify' => true]);

Route::get('/', 'Web\HomeController@index')->name('homepage');
Route::get('/{lang}', 'Web\HomeController@index')->name('homepage')->where(['lang' => '[a-zA-Z]{2}']);

Route::get('/login/admin', 'Admin\DashboardController@index')->name('admin')->middleware('verified');
Route::get('/login/user', 'User\UserController@profile')->name('user')->middleware('verified');

// Task access
Route::get('/task/{token}', 'Web\TaskController@index')->name('task')->where(['token' => '[a-zA-Z0-9_-]+']);

// Submit form
Route::put('/form-submit/{id}', 'Web\FormController@submit')->name('form.submit')->where(['id' => '[0-9]+']);

// Profile
Route::get('/' . $profile_permalink . '/{id}/{slug}', 'Web\ProfileController@index')->name('profile')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);

// Posts routes
Route::get('/' . $posts_permalink . '/' . $search_permalink, 'Web\PostsController@search')->name('posts.search');
Route::get('/' . $posts_permalink . '/' . $tag_permalink . '/{slug}', 'Web\PostsController@tag')->name('posts.tag')->where(['slug' => '[a-z0-9_-]+']);
Route::get('/' . $posts_permalink . '/{categ_slug}/{slug}', 'Web\PostsController@post')->name('post')->where(['categ_slug' => '[a-z0-9_-]{3,}+', 'slug' => '[a-z0-9_-]+']); // categ_slug - minimum length 3 (to avoid errors related to lang prefix)
Route::get('/' . $posts_permalink . '/{categ_slug}/{slug}/like', 'Web\PostsController@like')->name('post.like')->where(['categ_slug' => '[a-z0-9_-]+', 'slug' => '[a-z0-9_-]+']);
Route::post('/' . $posts_permalink . '/{categ_slug}/{slug}/comment', 'Web\PostsController@comment')->name('post.comment')->where(['categ_slug' => '[a-z0-9_-]+', 'slug' => '[a-z0-9_-]+']);
Route::get('/' . $posts_permalink, 'Web\PostsController@index')->name('posts');
Route::get('/' . $posts_permalink . '/{slug}', 'Web\PostsController@categ')->name('posts.categ')->where(['slug' => '[a-z0-9_-]+']);

// Forum routes
Route::get('/' . $forum_permalink . '/' . $search_permalink, 'Web\ForumController@search_results')->name('forum.search_results');
Route::get('/' . $forum_permalink . '/new', 'Web\ForumController@create_topic')->name('forum.topic.create');
Route::post('/' . $forum_permalink . '/new', 'Web\ForumController@store_topic')->name('forum.topic.store');
Route::post('/' . $forum_permalink . '/{id}/{slug}', 'Web\ForumController@store_post')->name('forum.post.store')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);

Route::get('/' . $forum_permalink, 'Web\ForumController@index')->name('forum');
Route::get('/' . $forum_permalink . '/{id}/{slug}', 'Web\ForumController@topic')->name('forum.topic')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);
Route::get('/' . $forum_permalink . '/{topic_id}/{slug}#{post_id}', 'Web\ForumController@post')->name('forum.post')->where(['topic_id' => '[0-9]+', 'slug' => '[a-z0-9_-]+', 'post_id' => '[0-9]+']);
Route::get('/' . $forum_permalink . '/{slug}', 'Web\ForumController@categ')->name('forum.categ')->where(['slug' => '[a-z0-9_-]+']);

Route::get('/' . $forum_permalink . '/report/{type}/{id}', 'Web\ForumController@report')->name('forum.report')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
Route::post('/' . $forum_permalink . '/report/{type}/{id}', 'Web\ForumController@create_report')->name('forum.report.create')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
Route::get('/' . $forum_permalink . '/like/{type}/{id}', 'Web\ForumController@like')->name('forum.like')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
Route::get('/' . $forum_permalink . '/best-answer/{id}', 'Web\ForumController@best_answer')->name('forum.best_answer')->where(['id' => '[0-9]+']);
Route::get('/' . $forum_permalink . '/quote/{type}/{id}', 'Web\ForumController@quote')->name('forum.quote')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);

// Docs
Route::get('/' . $docs_permalink, 'Web\DocsController@index')->name('docs');
Route::get('/' . $docs_permalink . '/' . $search_permalink, 'Web\DocsController@search')->name('docs.search');
Route::get('/' . $docs_permalink . '/{slug}', 'Web\DocsController@categ')->where(['slug' => '[a-z0-9_-]+'])->name('docs.categ');
Route::get('/search-docs-autocomplete', 'Web\DocsController@search_autocomplete')->name('search_docs_autocomplete');


// static page
Route::get('/{parent_slug}/{slug}', 'Web\PageController@index')->name('child_page')->where(['parent_slug' => '[a-z0-9_-]{3,}+', 'slug' => '[a-z0-9_-]+']); // if page is a child of a parent page
Route::get('/{slug}', 'Web\PageController@index')->name('page')->where(['slug' => '[a-z0-9_-]+']);

// download file (from block type: downoload)
Route::get('/file-download/{id}/{hash}', 'Web\ToolsController@block_download')->name('block.download')->where(['id' => '[0-9]+', 'hash' => '[a-zA-Z0-9_-]+']);


/*
|--------------------------------------------------------------------------
|  WEB ROUTES FOR ADDITIONAL LANGUAGES
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => '{lang?}',
    'where' => ['lang' => '[a-zA-Z]{2}']
], function ($lang) use ($posts_permalink, $forum_permalink, $docs_permalink, $profile_permalink, $search_permalink, $tag_permalink) {

    Auth::routes(['verify' => true, 'lang' => $lang]);

    Route::get('/', 'Web\HomeController@index')->name('homepage');

    // Task access
    Route::get('/task/{token}', 'Web\TaskController@index')->name('task')->where(['token' => '[a-zA-Z0-9_-]+']);

    // Submit form
    Route::put('/form-submit/{id}', 'Web\FormController@submit')->name('form.submit')->where(['id' => '[0-9]+']);

    // Profile
    Route::get('/' . $profile_permalink . '/{id}/{slug}', 'Web\ProfileController@index')->name('profile')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);

    // Posts routes
    Route::get('/' . $posts_permalink . '/' . $search_permalink, 'Web\PostsController@search')->name('posts.search');
    Route::get('/' . $posts_permalink . '/' . $tag_permalink . '/{slug}', 'Web\PostsController@tag')->name('posts.tag')->where(['slug' => '[a-z0-9_-]+']);
    Route::get('/' . $posts_permalink . '/{categ_slug}/{slug}', 'Web\PostsController@post')->name('post')->where(['categ_slug' => '[a-z0-9_-]{3,}+', 'slug' => '[a-z0-9_-]+']); // categ_slug - minimum length 3 (to avoid errors related to lang prefix)
    Route::get('/' . $posts_permalink . '/{categ_slug}/{slug}/like', 'Web\PostsController@like')->name('post.like')->where(['categ_slug' => '[a-z0-9_-]+', 'slug' => '[a-z0-9_-]+']);
    Route::post('/' . $posts_permalink . '/{categ_slug}/{slug}/comment', 'Web\PostsController@comment')->name('post.comment')->where(['categ_slug' => '[a-z0-9_-]+', 'slug' => '[a-z0-9_-]+']);
    Route::get('/' . $posts_permalink, 'Web\PostsController@index')->name('posts');
    Route::get('/' . $posts_permalink . '/{slug}', 'Web\PostsController@categ')->name('posts.categ')->where(['slug' => '[a-z0-9_-]+']);

    // Forum routes
    Route::get('/' . $forum_permalink . '/' . $search_permalink, 'Web\ForumController@search_results')->name('forum.search_results');
    Route::get('/' . $forum_permalink . '/new', 'Web\ForumController@create_topic')->name('forum.topic.create');
    Route::post('/' . $forum_permalink . '/new', 'Web\ForumController@store_topic')->name('forum.topic.store');
    Route::post('/' . $forum_permalink . '/{id}/{slug}', 'Web\ForumController@store_post')->name('forum.post.store')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);

    Route::get('/' . $forum_permalink, 'Web\ForumController@index')->name('forum');
    Route::get('/' . $forum_permalink . '/{id}/{slug}', 'Web\ForumController@topic')->name('forum.topic')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9_-]+']);
    Route::get('/' . $forum_permalink . '/{topic_id}/{slug}#{post_id}', 'Web\ForumController@post')->name('forum.post')->where(['topic_id' => '[0-9]+', 'slug' => '[a-z0-9_-]+', 'post_id' => '[0-9]+']);
    Route::get('/' . $forum_permalink . '/{slug}', 'Web\ForumController@categ')->name('forum.categ')->where(['slug' => '[a-z0-9_-]+']);

    Route::get('/' . $forum_permalink . '/report/{type}/{id}', 'Web\ForumController@report')->name('forum.report')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
    Route::post('/' . $forum_permalink . '/report/{type}/{id}', 'Web\ForumController@create_report')->name('forum.report.create')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
    Route::get('/' . $forum_permalink . '/like/{type}/{id}', 'Web\ForumController@like')->name('forum.like')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);
    Route::get('/' . $forum_permalink . '/best-answer/{id}', 'Web\ForumController@best_answer')->name('forum.best_answer')->where(['id' => '[0-9]+']);
    Route::get('/' . $forum_permalink . '/quote/{type}/{id}', 'Web\ForumController@quote')->name('forum.quote')->where(['type' => '[a-z0-9_-]+', 'id' => '[0-9]+']);

    // Docs
    Route::get('/' . $docs_permalink, 'Web\DocsController@index')->name('docs');
    Route::get('/' . $docs_permalink . '/' . $search_permalink, 'Web\DocsController@search')->name('docs.search');
    Route::get('/' . $docs_permalink . '/{slug}', 'Web\DocsController@categ')->where(['slug' => '[a-z0-9_-]+'])->name('docs.categ');
    Route::get('/search-docs-autocomplete', 'Web\DocsController@search_autocomplete')->name('search_docs_autocomplete');

    // static page
    Route::get('/{parent_slug}/{slug}', 'Web\PageController@index')->name('child_page')->where(['parent_slug' => '[a-z0-9_-]{3,}+', 'slug' => '[a-z0-9_-]+']); // if page is a child of a parent page
    Route::get('/{slug}', 'Web\PageController@index')->name('page')->where(['slug' => '[a-z0-9_-]+']);

    // download file (from block type: downoload)
    Route::get('/file-download/{id}/{hash}', 'Web\ToolsController@block_download')->name('block.download')->where(['id' => '[0-9]+', 'hash' => '[a-zA-Z0-9_-]+']);
});
