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
 * @version     2.1.1
 * @author      Chimilevschi Iosif Gabriel <office@clevada.com>
 */


/*
|--------------------------------------------------------------------------
| Here is where you can register web routes for your admin application.
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'login/admin',
], function () {

    /*
    |--------------------------------------------------------------------------
    | Accounts routes
    |--------------------------------------------------------------------------
    */
    Route::get('/accounts/permissions', 'Admin\AccountsController@permissions')->name('admin.accounts.permissions');
    Route::post('/accounts/permissions', 'Admin\AccountsController@update_permissions')->name('admin.accounts.permissions.update');

    Route::get('/accounts/deleted', 'Admin\AccountsController@deleted_accounts')->name('admin.accounts.deleted');
    Route::get('/accounts/deleted/action/{action}/{id}', 'Admin\AccountsController@deleted_action')->name('admin.accounts.deleted.action')->where(['action' => '[a-z0-9_-]+'])->where('id', '[0-9]+');

    Route::resource('/accounts/tags', 'Admin\AccountsTagsController')
        ->names(['index' => 'admin.accounts.tags', 'create' => 'admin.accounts.tags.create', 'show' => 'admin.accounts.tags.show'])
        ->parameters(['tags' => 'id']);

    Route::get('/accounts/{id}/tags', 'Admin\AccountsController@tags')->name('admin.account.tags')->where('id', '[0-9]+');
    Route::post('/accounts/{id}/tags', 'Admin\AccountsController@create_tag')->name('admin.account.tags.create')->where('id', '[0-9]+');
    Route::delete('/accounts/{id}/tags', 'Admin\AccountsController@delete_tag')->where('id', '[0-9]+');

    Route::get('/accounts/{id}/notes', 'Admin\AccountsController@notes')->name('admin.account.notes')->where('id', '[0-9]+');
    Route::post('/accounts/{id}/notes', 'Admin\AccountsController@create_note')->name('admin.account.notes.create')->where('id', '[0-9]+');
    Route::delete('/accounts/{id}/notes', 'Admin\AccountsController@delete_note')->where('id', '[0-9]+');

    Route::resource('/accounts', 'Admin\AccountsController')
        ->names(['index' => 'admin.accounts', 'create' => 'admin.accounts.create', 'show' => 'admin.accounts.show'])
        ->parameters(['accounts' => 'id']);


    /*
    |--------------------------------------------------------------------------
    | Posts routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/posts/categ', 'Admin\PostsCategoriesController')
        ->names(['index' => 'admin.posts.categ', 'create' => 'admin.posts.categ.create', 'show' => 'admin.posts.categ.show'])
        ->parameters(['categ' => 'id']);

    Route::resource('/posts/likes', 'Admin\PostsLikesController')
        ->names(['index' => 'admin.posts.likes', 'show' => 'admin.posts.likes.show'])
        ->parameters(['likes' => 'id']);

    Route::resource('/posts/comments', 'Admin\PostsCommentsController')
        ->names(['index' => 'admin.posts.comments', 'show' => 'admin.posts.comments.show'])
        ->parameters(['comments' => 'id']);

    Route::get('/posts/{id}/delete-main-image', 'Admin\PostsController@delete_main_image')->name('admin.posts.delete_main_image')->where('id', '[0-9]+');

    Route::resource('/posts', 'Admin\PostsController')
        ->names(['index' => 'admin.posts', 'create' => 'admin.posts.create', 'show' => 'admin.posts.show'])
        ->parameters(['posts' => 'id']);

    Route::get('/posts-config', 'Admin\PostsController@config')->name('admin.posts.config');
    Route::post('/posts-config', 'Admin\PostsController@update_config')->name('admin.posts.config.update');

    Route::get('/posts-config/seo', 'Admin\PostsController@seo')->name('admin.posts.config.seo');
    Route::post('/posts-config/seo', 'Admin\PostsController@update_seo');

    Route::post('/posts/{id}/sortable', 'Admin\PostsController@sortable')->name('admin.posts.sortable')->where('id', '[0-9]+');
    Route::get('/posts/{id}/content', 'Admin\PostsController@content')->name('admin.posts.content')->where('id', '[0-9]+');
    Route::post('/posts/{id}/content', 'Admin\PostsController@update_content')->name('admin.posts.content.new')->where('id', '[0-9]+');
    Route::delete('/posts/{id}/content/delete/{block_id}', 'Admin\PostsController@delete_content')->name('admin.posts.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');


    /*
    |--------------------------------------------------------------------------
    | Pages routes
    |--------------------------------------------------------------------------
    */
    Route::post('/pages/{id}/sortable', 'Admin\PagesController@sortable')->name('admin.pages.sortable')->where('id', '[0-9]+');
    Route::get('/pages/{id}/content', 'Admin\PagesController@content')->name('admin.pages.content')->where('id', '[0-9]+');
    Route::post('/pages/{id}/content', 'Admin\PagesController@update_content')->name('admin.pages.content.new')->where('id', '[0-9]+');
    Route::delete('/pages/{id}/content/delete/{block_id}', 'Admin\PagesController@delete_content')->name('admin.pages.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');

    Route::resource('/pages', 'Admin\PagesController')
        ->names(['index' => 'admin.pages', 'create' => 'admin.pages.create', 'show' => 'admin.pages.show'])
        ->parameters(['pages' => 'id']);


    /*
    |--------------------------------------------------------------------------
    | Config routes
    |--------------------------------------------------------------------------
    */
    Route::get('/config/general', 'Admin\ConfigController@general')->name('admin.config.general');
    Route::post('/config/general', 'Admin\ConfigController@update_general');

    Route::get('/config/registration', 'Admin\ConfigController@registration')->name('admin.config.registration');
    Route::post('/config/registration', 'Admin\ConfigController@update_registration');

    Route::get('/config/integration', 'Admin\ConfigController@integration')->name('admin.config.integration');
    Route::post('/config/integration', 'Admin\ConfigController@update_integration');

    Route::get('/config/email', 'Admin\ConfigController@email')->name('admin.config.email');
    Route::post('/config/email', 'Admin\ConfigController@update_email');
    Route::post('/config/email/test', 'Admin\ConfigController@send_test_email')->name('admin.send_test_email');

    Route::get('/config/site-offline', 'Admin\ConfigController@site_offline')->name('admin.config.site_offline');
    Route::post('/config/site-offline', 'Admin\ConfigController@update_site_offline');

    Route::get('/config/icons', 'Admin\ConfigController@icons')->name('admin.config.icons');
    Route::post('/config/icons', 'Admin\ConfigController@update_icons');

    Route::put('/config/langs/permalinks/{id}', 'Admin\LangsController@update_permalinks')->name('admin.config.langs.permalinks')->where('id', '[0-9]+');
    Route::resource('/config/langs', 'Admin\LangsController')
        ->names(['index' => 'admin.config.langs', 'create' => 'admin.config.langs.create', 'show' => 'admin.config.langs.show'])
        ->parameters(['langs' => 'id']);

    Route::get('/tools/update', 'Admin\ConfigController@update')->name('admin.tools');
    Route::get('/tools/sitemap', 'Admin\ConfigController@sitemap')->name('admin.tools.sitemap');
    Route::post('/tools/sitemap', 'Admin\ConfigController@process_sitemap');
    Route::get('/tools/system', 'Admin\ConfigController@system')->name('admin.tools.system');
    Route::get('/tools/clear-cache/{section}', 'Admin\ConfigController@clear_cache')->where(['section' => '[a-z0-9_-]+'])->name('admin.tools.clear_cache');
    Route::get('/tools/clear-logs/{section}', 'Admin\ConfigController@clear_logs')->where(['section' => '[a-z0-9_-]+'])->name('admin.tools.clear_logs');

    Route::get('/log/email', 'Admin\LogController@email')->name('admin.log.email');
    Route::get('/log/email/show/{id}', 'Admin\LogController@show_email')->name('admin.log.email.show')->where('id', '[0-9]+');
    Route::delete('/log/email/show/{id}', 'Admin\LogController@delete_email')->name('admin.log.email.delete')->where('id', '[0-9]+');


    // update routes
    Route::get('/tools/update', 'Admin\UpdateController@index')->name('admin.tools.update');
    Route::post('/tools/update/check', 'Admin\UpdateController@check_update')->name('admin.tools.update.check');
    Route::post('/tools/update/process', 'Admin\UpdateController@update')->name('admin.tools.update.process');

    /*
    |--------------------------------------------------------------------------
    | Translates routes
    |--------------------------------------------------------------------------
    */
    Route::get('/translates', 'Admin\TranslatesController@index')->name('admin.translates');
    Route::get('/translate-lang', 'Admin\TranslatesController@translate_lang')->name('admin.translate_lang');
    Route::post('/translates/create_key', 'Admin\TranslatesController@create_key')->name('admin.translates.create_key');
    Route::post('/translates/update_key', 'Admin\TranslatesController@update_key')->name('admin.translates.update_key');
    Route::post('/translates/delete_key', 'Admin\TranslatesController@delete_key')->name('admin.translates.delete_key');
    Route::post('/translates/update_translate', 'Admin\TranslatesController@update_translate')->name('admin.translates.update_translate');
    Route::get('/translates/regenerate_lang_file', 'Admin\TranslatesController@regenerate_lang_file')->name('admin.translates.regenerate_lang_file');
    Route::post('/translates/scan_template', 'Admin\TranslatesController@scan_template')->name('admin.translates.scan_template');


    /*
    |--------------------------------------------------------------------------
    | Forum routes
    |--------------------------------------------------------------------------
    */
    Route::get('/forum/config/general', 'Admin\ForumController@config')->name('admin.forum.config');
    Route::post('/forum/config/general', 'Admin\ForumController@update_config');

    Route::get('/forum/config/seo', 'Admin\ForumController@seo')->name('admin.forum.config.seo');
    Route::post('/forum/config/seo', 'Admin\ForumController@update_seo');

    Route::get('/forum/reports', 'Admin\ForumController@reports')->name('admin.forum.reports');
    Route::post('/forum/reports/delete/{id}', 'Admin\ForumController@delete_report')->name('admin.forum.reports.delete')->where('id', '[0-9]+');
    Route::post('/forum/reports/update/{id}', 'Admin\ForumController@update_report')->name('admin.forum.reports.update')->where('id', '[0-9]+');

    Route::get('/forum/restrictions', 'Admin\ForumController@restrictions')->name('admin.forum.restrictions');
    Route::post('/forum/restrictions', 'Admin\ForumController@restrictions_update');

    Route::get('/forum/moderators', 'Admin\ForumController@moderators')->name('admin.forum.moderators');
    Route::post('/forum/moderators', 'Admin\ForumController@moderators_update');

    Route::resource('/forum/categories', 'Admin\ForumCategoriesController')
        ->names(['index' => 'admin.forum.categ', 'create' => 'admin.forum.categ.create', 'show' => 'admin.forum.categ.show'])
        ->parameters(['categories' => 'id']);

    Route::get('/forum/topics', 'Admin\ForumActivityController@topics')->name('admin.forum.topics');
    Route::post('/forum/topics/delete/{id}', 'Admin\ForumActivityController@delete_topic')->name('admin.forum.topics.delete')->where('id', '[0-9]+');
    Route::post('/forum/topics/update/{id}', 'Admin\ForumActivityController@update_topic')->name('admin.forum.topics.update')->where('id', '[0-9]+');
    Route::get('/forum/posts', 'Admin\ForumActivityController@posts')->name('admin.forum.posts');
    Route::post('/forum/posts/delete/{id}', 'Admin\ForumActivityController@delete_post')->name('admin.forum.posts.delete')->where('id', '[0-9]+');


    /*
    |--------------------------------------------------------------------------
    | Template routes
    |--------------------------------------------------------------------------
    */

    Route::resource('/templates', 'Admin\TemplateController')
        ->names(['index' => 'admin.templates', 'create' => 'admin.templates.create', 'show' => 'admin.templates.show'])
        ->parameters(['templates' => 'id']);

    Route::get('/templates/set-default/{id}', 'Admin\TemplateController@set_default')->name('admin.templates.set_default')->where(['id' => '[0-9]+']);

    Route::post('/templates/{template_id}', 'Admin\TemplateController@add_block')->where('template_id', '[0-9]+');

    Route::post('/templates/{template_id}/{module}/sortable/', 'Admin\TemplateController@sortable')->name('admin.templates.sortable')->where('template_id', '[0-9]+')->where('module', '[a-z0-9_-]+')->where('layout', '[a-z0-9_-]+');
    Route::post('/templates/{template_id}/{module}/update-layout', 'Admin\TemplateController@update_layout')->name('admin.templates.update-layout')->where('template_id', '[0-9]+')->where('module', '[a-z0-9_-]+');

    Route::resource('/template/styles', 'Admin\TemplateStylesController')
        ->names(['index' => 'admin.template.styles', 'create' => 'admin.template.styles.create', 'show' => 'admin.template.styles.show'])
        ->parameters(['styles' => 'id']);

    Route::resource('/template/sidebars', 'Admin\TemplateSidebarsController')
        ->names(['index' => 'admin.template.sidebars', 'create' => 'admin.template.sidebars.create', 'show' => 'admin.template.sidebars.show'])
        ->parameters(['sidebars' => 'id']);
    Route::post('/template/sidebars/assign/{module}', 'Admin\TemplateSidebarsController@assign')->name('admin.template.sidebars.assign')->where(['module' => '[a-z0-9_-]+']);
    Route::post('/template/sidebars/{id}/sortable', 'Admin\TemplateSidebarsController@sortable')->name('admin.template.sidebars.sortable')->where('id', '[0-9]+');
    Route::post('/template/sidebars/{id}/content', 'Admin\TemplateSidebarsController@update_content')->name('admin.template.sidebars.content.new')->where('id', '[0-9]+');
    Route::delete('/template/sidebars/{id}/content/delete/{block_id}', 'Admin\TemplateSidebarsController@delete_content')->name('admin.template.sidebars.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');

    Route::resource('/template/global_sections', 'Admin\TemplateGlobalSectionsController')
        ->names(['index' => 'admin.template.global_sections', 'create' => 'admin.template.global_sections.create', 'show' => 'admin.template.global_sections.show'])
        ->parameters(['global_sections' => 'id']);
    Route::post('/template/global_sections/assign/{module}', 'Admin\TemplateGlobalSectionsController@assign')->name('admin.template.global_sections.assign')->where(['module' => '[a-z0-9_-]+']);
    Route::post('/template/global_sections/{id}/sortable', 'Admin\TemplateGlobalSectionsController@sortable')->name('admin.template.global_sections.sortable')->where('id', '[0-9]+');
    Route::post('/template/global_sections/{id}/content', 'Admin\TemplateGlobalSectionsController@update_content')->name('admin.template.global_sections.content.new')->where('id', '[0-9]+');
    Route::delete('/template/global_sections/{id}/content/delete/{block_id}', 'Admin\TemplateGlobalSectionsController@delete_content')->name('admin.template.global_sections.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');


    Route::get('/template/logo', 'Admin\TemplateController@logo')->name('admin.template.logo');
    Route::post('/template/logo', 'Admin\TemplateController@update_logo');

    Route::get('/template/custom-code', 'Admin\TemplateController@custom_code')->name('admin.template.custom_code');
    Route::post('/template/custom-code', 'Admin\TemplateController@update_custom_code');
    Route::get('/template/custom-code/delete', 'Admin\TemplateController@custom_code_delete_file')->name('admin.template.custom_code.delete_file');

    Route::get('/template/menu/dropdown', 'Admin\TemplateMenuController@index_dropdowns')->name('admin.template.menu.dropdown');
    Route::post('/template/menu/dropdown', 'Admin\TemplateMenuController@store_dropdown')->name('admin.template.menu.dropdown');
    Route::put('/template/menu/dropdown', 'Admin\TemplateMenuController@update_dropdown')->name('admin.template.menu.dropdown');
    Route::delete('/template/menu/dropdown', 'Admin\TemplateMenuController@destroy_dropdown')->name('admin.template.menu.dropdown');
    Route::post('/template/menu/sortable-dropdowns/{parent_link_id}', 'Admin\TemplateMenuController@sortable_dropdowns')->name('admin.template.menu.sortable_dropdowns')->where('parent_link_id', '[0-9]+');
    Route::resource('/template/menu', 'Admin\TemplateMenuController')
        ->names(['index' => 'admin.template.menu', 'create' => 'admin.website.menu.create', 'show' => 'admin.template.menu.show'])
        ->parameters(['menu' => 'id']);
    Route::post('/template/menu/sortable', 'Admin\TemplateMenuController@sortable')->name('admin.template.menu.sortable');

    Route::get('/template/{module}/content', 'Admin\TemplateController@content')->name('admin.templates.content')->where(['module' => '[a-z0-9_-]+']);
    Route::post('/template/{module}/content', 'Admin\TemplateController@update_content')->where(['module' => '[a-z0-9_-]+']);
    Route::delete('/template/{module}/content', 'Admin\TemplateController@delete_content')->name('admin.templates.content.delete')->where(['module' => '[a-z0-9_-]+']);


    // Template footer content routes
    Route::get('/template/{template_id}/footer-content/{footer}', 'Admin\TemplateFooterController@content')->name('admin.template.footer.content')->where('template_id', '[0-9]+')->where(['footer' => '[a-z0-9_-]+']);
    Route::post('/template/{template_id}/footer-content/{footer}', 'Admin\TemplateFooterController@update_content')->name('admin.template.footer.content.new')->where('template_id', '[0-9]+')->where('footer', '[a-z0-9_-]+');
    Route::post('/template/{template_id}/footer-content/{footer}/{col}/sortable', 'Admin\TemplateFooterController@sortable')->name('admin.template.footer.sortable')->where('template_id', '[0-9]+')->where('footer', '[a-z0-9_-]+')->where('col', '[0-9]+');
    Route::delete('/template/{template_id}/footer-content/delete/{block_id}', 'Admin\TemplateFooterController@delete_content')->name('admin.template.footer.content.delete')->where('template_id', '[0-9]+')->where('block_id', '[0-9]+');
    Route::get('/template/footer/block/{id}', 'Admin\TemplateFooterController@block')->name('admin.template.footer.block')->where('id', '[0-9]+');
    Route::put('/template/footer/block/{id}', 'Admin\TemplateFooterController@block_update')->where('id', '[0-9]+');


    /*
    |--------------------------------------------------------------------------
    | Blocks routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/blocks', 'Admin\BlocksController')
        ->names(['index' => 'admin.blocks', 'create' => 'admin.blocks.create', 'show' => 'admin.blocks.show'])
        ->parameters(['blocks' => 'id']);


    /*
    |--------------------------------------------------------------------------
    | Media routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/media', 'Admin\MediaController')
        ->names(['index' => 'admin.media', 'create' => 'admin.media.create', 'show' => 'admin.media.show'])
        ->parameters(['media' => 'id']);


    /*
    |--------------------------------------------------------------------------
    | Forms routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/forms/config', 'Admin\FormsConfigController')
        ->names(['index' => 'admin.forms.config', 'create' => 'admin.forms.config.create', 'show' => 'admin.forms.config.show'])
        ->parameters(['config' => 'id']);
    Route::post('/forms/config/{id}/add-field', 'Admin\FormsConfigController@add_field')->name('admin.forms.config.add_field')->where('id', '[0-9]+');
    Route::put('/forms/config/{id}/update-field/{field_id}', 'Admin\FormsConfigController@update_field')->name('admin.forms.config.update_field')->where('id', '[0-9]+')->where('field_id', '[0-9]+');
    Route::delete('/forms/config/{id}/delete-field/{field_id}', 'Admin\FormsConfigController@delete_field')->name('admin.forms.config.delete_field')->where('id', '[0-9]+')->where('field_id', '[0-9]+');
    Route::post('/forms/config/{id}/sortable', 'Admin\FormsConfigController@sortable')->name('admin.forms.config.sortable')->where('id', '[0-9]+');

    Route::get('/forms', 'Admin\FormsController@index')->name('admin.forms');
    Route::get('/forms/{id}', 'Admin\FormsController@show')->name('admin.forms.show')->where('id', '[0-9]+');
    Route::delete('/forms/{id}', 'Admin\FormsController@destroy')->name('admin.forms.delete')->where('id', '[0-9]+');
    Route::post('/forms/{id}/reply', 'Admin\FormsController@reply')->name('admin.forms.reply')->where('id', '[0-9]+');
    Route::get('/forms/{id}/mark', 'Admin\FormsController@mark')->name('admin.forms.mark')->where('id', '[0-9]+');
    Route::post('/forms/{id}/create-task', 'Admin\FormsController@create_task')->name('admin.forms.create-task');


    /*
    |--------------------------------------------------------------------------
    | Tasks routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/tasks', 'Admin\TasksController')
        ->names(['index' => 'admin.tasks', 'create' => 'admin.tasks.create', 'show' => 'admin.tasks.show'])
        ->parameters(['tasks' => 'id']);
    Route::get('/tasks/{id}/action/{action}', 'Admin\TasksController@action')->name('admin.tasks.action')->where('id', '[0-9]+')->where('action', '[a-z0-9_-]+');
    Route::post('/tasks/{id}/reply', 'Admin\TasksController@reply')->name('admin.tasks.reply')->where('id', '[0-9]+');


    Route::post('/tasks/{id}/activity/important/{activity_id}/{action}', 'Admin\TasksController@reply')->name('admin.tasks.activity.important')->where('id', '[0-9]+')->where('activity_id', '[0-9]+')->where('action', '[a-z0-9_-]+');
   

    /*
    |--------------------------------------------------------------------------
    | Ajax routes
    |--------------------------------------------------------------------------
    */
    Route::post('/ajax/editor-upload', 'Admin\AjaxController@editor_upload')->name('admin.ajax.editor-upload');
    Route::get('/ajax/{source}', 'Admin\AjaxController@fetch')->name('admin.ajax')->where('source', '[a-z0-9_-]+');


    /*
    |--------------------------------------------------------------------------
    | Profile routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', 'Admin\ProfileController@index')->name('admin.profile');
    Route::post('/profile', 'Admin\ProfileController@update');
    Route::get('/profile/delete-avatar', 'Admin\ProfileController@delete_avatar')->name('admin.profile.delete_avatar');
});
