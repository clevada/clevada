<?php

/**
 * Clevada - Content Management System and Website Builder
 *
 * Copyright (C) 2024  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @author      Chimilevschi Iosif Gabriel <contact@clevada.com>
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Admin
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\AccountInvitationController as AdminAccountInvitationController;
use App\Http\Controllers\Admin\AccountTagController as AdminAccountTagController;
use App\Http\Controllers\Admin\AccountFieldController as AdminAccountFieldController;
use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\AjaxController as AdminAjaxController;
use App\Http\Controllers\Admin\BlockController as AdminBlockController;
use App\Http\Controllers\Admin\CoreController as AdminCoreController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\Admin\PostCategController as AdminPostCategController;
use App\Http\Controllers\Admin\PostTagController as AdminPostTagController;
use App\Http\Controllers\Admin\RecycleBinController as AdminRecycleBinController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\TemplateButtonController as AdminTemplateButtonController;
use App\Http\Controllers\Admin\TemplateFooterController as AdminTemplateFooterController;
use App\Http\Controllers\Admin\TemplateMenuController as AdminTemplateMenuController;
use App\Http\Controllers\Admin\TemplateStyleController as AdminTemplateStyleController;

Route::get('account/admin', [AdminCoreController::class, 'dashboard'])->name('admin');

Route::prefix('account/admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminCoreController::class, 'dashboard'])->name('dashboard');


    Route::resource('taxonomies', AdminTaxonomyController::class)->parameters(['taxonomies' => 'id']);





    // Accounts        
    Route::get('accounts/invitations', [AdminAccountInvitationController::class, 'index'])->name('accounts.invitations');
    Route::post('accounts/send-invitation', [AdminAccountInvitationController::class, 'send'])->name('accounts.send_invitation');
    Route::post('accounts/resend-invitation', [AdminAccountInvitationController::class, 'resend'])->name('accounts.resend_invitation');
    Route::delete('accounts/delete-invitation', [AdminAccountInvitationController::class, 'destroy'])->name('accounts.delete_invitation');

    Route::resource('accounts', AdminAccountController::class)->parameters(['accounts' => 'id']);

    Route::resource('accounts-tags', AdminAccountTagController::class)->parameters(['accounts-tags' => 'id']);

    Route::resource('accounts-fields', AdminAccountFieldController::class)->parameters(['accounts-fields' => 'id']);

    Route::get('account/{id}/tags', [AdminAccountController::class, 'tags'])->where('id', '[0-9]+')->name('account.tags');
    Route::post('account/{id}/tags', [AdminAccountController::class, 'store_tag'])->where('id', '[0-9]+');
    Route::delete('account/{id}/tags', [AdminAccountController::class, 'destroy_tag'])->where('id', '[0-9]+');

    Route::get('account/{id}/internal-notes', [AdminAccountController::class, 'internal_notes'])->where('id', '[0-9]+')->name('account.internal_notes');
    Route::post('account/{id}/internal-notes', [AdminAccountController::class, 'store_internal_note'])->where('id', '[0-9]+');
    Route::delete('account/{id}/internal-notes', [AdminAccountController::class, 'destroy_internal_note'])->where('id', '[0-9]+');

    Route::get('account/{id}/block', [AdminAccountController::class, 'block'])->where('id', '[0-9]+')->name('account.block');
    Route::post('account/{id}/action/{action}', [AdminAccountController::class, 'action'])->where('id', '[0-9]+')->where('action', '[a-zA-Z0-9]+')->name('account.action');

    Route::get('account/{id}/reset-password', [AdminAccountController::class, 'reset_password'])->where('id', '[0-9]+')->name('account.reset_password');
    Route::post('account/{id}/reset-password', [AdminAccountController::class, 'reset_password_action'])->where('id', '[0-9]+');


    // Profile
    Route::get('profile', [AdminCoreController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminCoreController::class, 'profile_update']);
    Route::delete('profile/delete-avatar', [AdminCoreController::class, 'profile_delete_avatar'])->name('profile.delete_avatar');


    // Tools
    Route::get('tools', [AdminCoreController::class, 'tools'])->name('tools');
    Route::post('tools', [AdminCoreController::class, 'tools_action'])->middleware(['password.confirm']);
    Route::get('tools/update-install', [AdminCoreController::class, 'update_install'])->name('tools.update.install');

    // Activity log
    Route::get('activity', [AdminCoreController::class, 'activity'])->name('activity');

    // Static pages
    Route::resource('pages', AdminPageController::class)->parameters(['pages' => 'id']);
    Route::get('/pages/{id}/content', [AdminPageController::class, 'content'])->name('pages.content')->where('id', '[0-9]+');
    Route::post('/pages/{id}/content', [AdminPageController::class, 'content_update'])->name('pages.content.new')->where('id', '[0-9]+');
    Route::delete('/pages/{id}/content/delete/{block_id}', [AdminPageController::class, 'content_destroy'])->name('pages.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');
    Route::post('/pages/{id}/sortable', [AdminPageController::class, 'sortable'])->name('pages.sortable')->where('id', '[0-9]+');
    Route::post('pages/{id}/ajaxPublishSwitch', [AdminPageController::class, 'ajaxPublishSwitch'])->name('pages.ajaxPublishSwitch')->where('id', '[0-9]+');

    // Posts        
    Route::get('posts/comments', [AdminPostController::class, 'comments'])->name('posts.comments');
    Route::put('posts/comments/{id}', [AdminPostController::class, 'update_comment'])->name('posts.comments.update')->where('id', '[0-9]+');
    Route::delete('posts/comments/{id}', [AdminPostController::class, 'destroy_comment'])->name('posts.comments.destroy')->where('id', '[0-9]+');

    Route::get('posts/likes', [AdminPostController::class, 'likes'])->name('posts.likes');
    Route::delete('posts/likes/delete', [AdminPostController::class, 'destroy_like'])->name('posts.likes.destroy');

    Route::get('posts/config', [AdminPostController::class, 'config'])->name('posts.config');
    Route::post('posts/config', [AdminPostController::class, 'update_config']);

    Route::resource('posts', AdminPostController::class)->parameters(['posts' => 'id']);
    Route::post('posts/{id}/sortable', [AdminPostController::class, 'sortable'])->name('posts.sortable')->where('id', '[0-9]+');
    Route::get('posts/{id}/content', [AdminPostController::class, 'content'])->name('posts.content')->where('id', '[0-9]+');
    Route::post('posts/{id}/content', [AdminPostController::class, 'update_content'])->name('posts.content.new')->where('id', '[0-9]+');
    Route::delete('posts/{id}/content/delete/{block_id}', [AdminPostController::class, 'delete_content'])->name('posts.content.delete')->where('id', '[0-9]+')->where('block_id', '[0-9]+');

    Route::get('posts/{id}/delete-main-image', [AdminPostController::class, 'delete_main_image'])->name('posts.delete_main_image')->where('id', '[0-9]+');

    Route::resource('posts-categ', AdminPostCategController::class)->names(['index' => 'posts.categ', 'create' => 'posts.categ.create', 'show' => 'posts.categ.show'])->parameters(['posts-categ' => 'id']);
    Route::resource('posts-tags', AdminPostTagController::class)->names(['index' => 'posts.tags', 'create' => 'posts.tags.create', 'show' => 'posts.tags.show'])->parameters(['posts-tags' => 'id']);


    // Contact forms
    Route::get('contact/config', [AdminContactController::class, 'config'])->name('contact.config');
    Route::put('contact/config', [AdminContactController::class, 'update_config']);

    Route::get('contact/fields', [AdminContactController::class, 'fields'])->name('contact.fields');
    Route::post('contact/fields/add-field', [AdminContactController::class, 'add_field'])->name('contact.add_field');
    Route::get('contact/fields/update-field/{field_id}', [AdminContactController::class, 'show_field'])->name('contact.show_field')->where('field_id', '[0-9]+');
    Route::put('contact/fields/update-field/{field_id}', [AdminContactController::class, 'update_field'])->name('contact.update_field')->where('field_id', '[0-9]+');
    Route::delete('contact/fields/delete-field/{field_id}', [AdminContactController::class, 'destroy_field'])->name('contact.delete_field')->where('field_id', '[0-9]+');
    Route::post('contact/fields/sortable-fields', [AdminContactController::class, 'sortable_fields'])->name('contact.sortable_fields');

    Route::get('contact', [AdminContactController::class, 'index'])->name('contact');
    Route::get('contact/{id}', [AdminContactController::class, 'show'])->name('contact.show')->where('id', '[0-9]+');
    Route::get('contact/{id}/to_trash', [AdminContactController::class, 'to_trash'])->name('contact.to_trash')->where('id', '[0-9]+');
    Route::get('contact/{id}/mark', [AdminContactController::class, 'mark'])->name('contact.mark')->where('id', '[0-9]+');
    Route::post('contact/multiple-action', [AdminContactController::class, 'multiple_action'])->name('contact.multiple_action');
    Route::delete('contact/{id}/delete', [AdminContactController::class, 'to_trash'])->name('contact.delete')->where('id', '[0-9]+');

    // Polls routes
    Route::resource('polls', AdminPollController::class)->parameters(['polls' => 'id']);
    Route::post('polls/{id}/sortable', [AdminPollController::class, 'sortable_option'])->name('polls.sortable_option')->where('id', '[0-9]+');
    Route::post('polls/{id}/create-option', [AdminPollController::class, 'create_option'])->name('polls.options.create')->where('id', '[0-9]+');
    Route::post('polls/{id}/update-option', [AdminPollController::class, 'update_option'])->name('polls.options.update')->where('id', '[0-9]+');
    Route::delete('polls/{id}/delete-option', [AdminPollController::class, 'delete_option'])->name('polls.options.delete')->where('id', '[0-9]+');

    // Template
    Route::get('template/set-default',  [AdminTemplateController::class, 'set_default'])->name('template.set_default');
    Route::resource('templates', AdminTemplateController::class)->parameters(['templates' => 'slug']);
    //Route::get('templates', [AdminTemplateController::class, 'templates'])->name('templates');
    //Route::get('templates/{template}', [AdminTemplateController::class, 'template'])->name('template2')->where('template', '[0-9a-zA-Z-_]+');
    //Route::put('templates/{template}', [AdminTemplateController::class, 'update'])->where('template2', '[0-9a-zA-Z-_]+');

    Route::get('template', [AdminTemplateController::class, 'index'])->name('template');
    //Route::put('template', [AdminTemplateController::class, 'update']);

    Route::get('template/logo', [AdminTemplateController::class, 'logo'])->name('template.logo');
    Route::post('template/logo', [AdminTemplateController::class, 'update_logo']);

    Route::get('template/custom-code', [AdminTemplateController::class, 'custom_code'])->name('template.custom_code');
    Route::post('template/custom-code',  [AdminTemplateController::class, 'update_custom_code']);
    Route::get('template/custom-code/delete',  [AdminTemplateController::class, 'custom_code_delete_file'])->name('template.custom_code.delete_file');

    Route::get('template/menu/dropdown', [AdminTemplateMenuController::class, 'index_dropdowns'])->name('template.menu.dropdown');
    Route::post('template/menu/dropdown', [AdminTemplateMenuController::class, 'store_dropdown']);
    Route::put('template/menu/dropdown', [AdminTemplateMenuController::class, 'update_dropdown']);
    Route::delete('template/menu/dropdown', [AdminTemplateMenuController::class, 'destroy_dropdown']);
    Route::post('template/menu/sortable-dropdowns/{parent_link_id}', [AdminTemplateMenuController::class, 'sortable_dropdowns'])->name('template.menu.sortable_dropdowns')->where('parent_link_id', '[0-9]+');
    Route::resource('template/menu', AdminTemplateMenuController::class)->names(['index' => 'template.menu', 'create' => 'template.menu.create', 'show' => 'template.menu.show'])->parameters(['menu' => 'id']);
    Route::post('template/menu/sortable', [AdminTemplateMenuController::class, 'sortable'])->name('template.menu.sortable');

    Route::resource('template/buttons', AdminTemplateButtonController::class)->names(['index' => 'template.buttons', 'create' => 'template.buttons.create', 'show' => 'template.buttons.show'])->parameters(['buttons' => 'id']);


    // Styles
    Route::get('template/styles', [AdminTemplateStyleController::class, 'index'])->name('template.styles');
    Route::get('template/styles/{style}', [AdminTemplateStyleController::class, 'show'])->name('template.styles.show')->where('style', '[0-9a-zA-Z_-]+');
    Route::put('template/styles/{style}', [AdminTemplateStyleController::class, 'update'])->where('style', '[0-9a-zA-Z_-]+');

    Route::post('template/styles', [AdminTemplateStyleController::class, 'store_custom']);
    Route::get('template/custom-styles/{id}', [AdminTemplateStyleController::class, 'show_custom'])->name('template.custom_styles.show')->where('id', '[0-9]+');
    Route::put('template/custom-styles/{id}', [AdminTemplateStyleController::class, 'update_custom'])->where('id', '[0-9a-zA-Z_-]+');
    Route::delete('template/custom-styles/{id}', [AdminTemplateStyleController::class, 'destroy_custom'])->where('id', '[0-9]+');

    // Template footer content routes
    Route::get('template/footer', [AdminTemplateFooterController::class, 'index'])->name('template.footer');
    Route::put('template/footer', [AdminTemplateFooterController::class, 'update']);

    Route::get('template/footer/{footer}/content', [AdminTemplateFooterController::class, 'content'])->name('template.footer.content')->where(['footer' => '[a-z0-9_-]+']);

    Route::post('template/footer/{footer}/content', [AdminTemplateFooterController::class, 'update_content'])->where(['footer' => '[a-z0-9_-]+']);

    Route::post('template/footer/{footer}/{col}/sortable', [AdminTemplateFooterController::class, 'sortable'])->name('template.footer.sortable')->where('footer', '[a-z0-9_-]+')->where('col', '[0-9]+');
    Route::delete('template/footer/delete/{block_id}', [AdminTemplateFooterController::class, 'delete_content'])->name('template.footer.content.delete')->where('block_id', '[0-9]+');
    Route::get('template/footer/block/{id}', [AdminTemplateFooterController::class, 'block'])->name('template.footer.block')->where('id', '[0-9]+');
    Route::put('template/footer/block/{id}', [AdminTemplateFooterController::class, 'block_update'])->where('id', '[0-9]+');

    // Config
    Route::get('/config/{module}', [AdminCoreController::class, 'module'])->name('config')->where(['module' => '[a-z0-9_-]+']);
    Route::post('/config/{module}', [AdminCoreController::class, 'update_module'])->where(['module' => '[a-z0-9_-]+']);

    // Blocks routes
    Route::resource('blocks', AdminBlockController::class)->parameters(['blocks' => 'id']);

    // Ads routes
    Route::resource('ads', AdminAdController::class)->parameters(['ads' => 'id']);

    // Other routes
    Route::get('ajax/{source}', [AdminAjaxController::class, 'fetch'])->name('ajax')->where('source', '[a-z0-9_-]+');
    Route::get('preview-style/{id}', [AdminCoreController::class, 'preview_style'])->name('preview-style')->where('id', '[a-z0-9_-]+');
    Route::get('tools/generate-sitemap', [AdminCoreController::class, 'generate_sitemap'])->name('sitemap.generate');

    // Recycle Bin
    Route::get('recycle-bin', [AdminRecycleBinController::class, 'index'])->name('recycle_bin');
    Route::get('recycle-bin/empty/{module}', [AdminRecycleBinController::class, 'empty'])->name('recycle_bin.empty')->where('module', '[a-zA-Z0-9]+');
    Route::post('recycle-bin/multiple-action/{module}', [AdminRecycleBinController::class, 'multiple_action'])->name('recycle_bin.multiple_action')->where('module', '[a-zA-Z0-9]+');
    Route::get('recycle-bin/single-action/{module}/{id}', [AdminRecycleBinController::class, 'single_action'])->name('recycle_bin.single_action')->where('module', '[a-zA-Z0-9]+')->where('id', '[0-9]+');
    Route::get('recycle-bin/{module}', [AdminRecycleBinController::class, 'module'])->name('recycle_bin.module')->where('module', '[a-zA-Z0-9]+');
});
