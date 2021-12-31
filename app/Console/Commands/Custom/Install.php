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

namespace App\Console\Commands\Custom;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use DB;
use Artisan;
use App\Models\User;
use App\Models\Core;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class Install extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to install Clevada directly through CLI';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {

        $this->line('Setup database tables');
        Artisan::call('migrate');

        $this->line('Adding core settings into tables');

        // Add a default template
        if (!DB::table('sys_templates')->where('is_default', 1)->exists())
            DB::table('sys_templates')->insert(['label' => 'Clevada default', 'is_default' => 1, 'is_builder' => 1, 'created_at' => now()]);

        DB::table('sys_config')->insertOrIgnore(['name' => 'site_meta_author', 'value' => 'Clevada - https://clevada.com']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'registration_enabled', 'value' => 1]);
        DB::table('sys_config')->insertOrIgnore(['name' => 'favicon', 'value' => '/default/favicon.png']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'logo', 'value' => '/default/logo.png']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'logo_auth', 'value' => '/default/logo-auth.png']);

        // language
        if (!DB::table('sys_lang')->where('is_default', 1)->where('status', 'active')->exists())
            DB::table('sys_lang')->updateOrInsert(['code' => 'en'], ['name' => 'English', 'locale' => 'en_US', 'is_default' => 1, 'status' => 'active', 'timezone' => 'Europe/London', 'site_short_title' => 'Clevada website', 'homepage_meta_title' => 'Clevada website', 'homepage_meta_description' => 'Clevada website']);

        // Modules
        DB::table('sys_modules')->updateOrInsert(['module' => 'accounts'], ['label' => 'Accounts', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.accounts', 'hidden' => 1]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'developer'], ['label' => 'Template developer', 'status' => 'active', 'route_web' => null, 'route_admin' => null, 'hidden' => 1]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'forms'], ['label' => 'Forms', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.forms', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'forum'], ['label' => 'Community', 'status' => 'active', 'route_web' => 'forum', 'route_admin' => 'admin.forum.topics', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'pages'], ['label' => 'Pages', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.pages', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'posts'], ['label' => 'Posts', 'status' => 'active', 'route_web' => 'posts', 'route_admin' => 'admin.posts', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'tasks'], ['label' => 'Tasks', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.tasks', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'translates'], ['label' => 'Translates', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.translates', 'hidden' => 1]);

        // Permissions
        DB::table('sys_permissions')->updateOrInsert(['module' => 'accounts', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 1, 'description' => 'Manage any account']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'accounts', 'permission' => 'operator'], ['label' => 'Operator', 'position' => 2, 'description' => 'View accounts details. Can add internal info and assign account tags']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'developer', 'permission' => 'developer'], ['label' => 'Developer', 'position' => 1, 'description' => 'Developers have access to manage and edit template']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'forms', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 1, 'description' => 'Manage forms content']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'forum', 'permission' => 'moderator'], ['label' => 'Moderator', 'position' => 1, 'description' => 'Moderate forum content (edit or delete posts and topics, restrict or ban users)']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'pages', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 1, 'description' => 'Manage static pages (create, update and delete pages)']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'posts', 'permission' => 'author'], ['label' => 'Author', 'position' => 1, 'description' => 'Manage own posts, which are approved automatically']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'posts', 'permission' => 'contributor'], ['label' => 'Contributor', 'position' => 2, 'description' => 'Manage own posts, which must be manually approved by manager or admin']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'posts', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 3, 'description' => 'Have full access to all posts from any author']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'tasks', 'permission' => 'operator'], ['label' => 'Operator', 'position' => 1, 'description' => 'Manage assigned tasks']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'tasks', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 2, 'description' => 'Manage any tasks']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'translates', 'permission' => 'translator'], ['label' => 'Translator', 'position' => 1, 'description' => 'Translators have access to translates']);

        // Block types
        DB::table('blocks_types')->updateOrInsert(['type' => 'editor'], ['label' => 'Text / HTML', 'icon' => '<i class="bi bi-textarea-t"></i>', 'position' => 10, 'allow_footer' => 1, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'image'], ['label' => 'Image / Banner', 'icon' => '<i class="bi bi-image"></i>', 'position' => 20, 'allow_footer' => 1, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'gallery'], ['label' => 'Images gallery', 'icon' => '<i class="bi bi-images"></i>', 'position' => 30, 'allow_footer' => 0, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'ads'], ['label' => 'Ads code', 'icon' => '<i class="bi bi-badge-ad"></i>', 'position' => 40, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'hero'], ['label' => 'Hero', 'icon' => '<i class="bi bi-card-heading"></i>', 'position' => 50, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'links'], ['label' => 'Links', 'icon' => '<i class="bi bi-list-ul"></i>', 'position' => 60, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'video'], ['label' => 'Video', 'icon' => '<i class="bi bi-play-btn"></i>', 'position' => 70, 'allow_footer' => 1, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'slider'], ['label' => 'Slider', 'icon' => '<i class="bi bi-collection"></i>', 'position' => 80, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'custom'], ['label' => 'Custom', 'icon' => '<i class="bi bi-code"></i>', 'position' => 90, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'accordion'], ['label' => 'Accordion', 'icon' => '<i class="bi bi-menu-up"></i>', 'position' => 100, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'alert'], ['label' => 'Alert', 'icon' => '<i class="bi bi-exclamation-square"></i>', 'position' => 110, 'allow_footer' => 1, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'map'], ['label' => 'Google map', 'icon' => '<i class="bi bi-geo-alt"></i>', 'position' => 120, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'blockquote'], ['label' => 'Blockquote', 'icon' => '<i class="bi bi-chat-left-quote"></i>', 'position' => 130, 'allow_footer' => 1, 'allow_to_users' => 1]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'download'], ['label' => 'Download', 'icon' => '<i class="bi bi-download"></i>', 'position' => 140, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'posts'], ['label' => 'Posts content', 'icon' => '<i class="bi bi-justify"></i>', 'position' => 150, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'forum'], ['label' => 'Forum content', 'icon' => '<i class="bi bi-chat-right-quote"></i>', 'position' => 160, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'form'], ['label' => 'Form', 'icon' => '<i class="bi bi-file-text"></i>', 'position' => 170, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'search'], ['label' => 'Search', 'icon' => '<i class="bi bi-search"></i>', 'position' => 180, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'include'], ['label' => 'Include file', 'icon' => '<i class="bi bi-file-code"></i>', 'position' => 190, 'allow_footer' => 1, 'allow_to_users' => 0]);

        // Accounts roles
        DB::table('users_roles')->updateOrInsert(['role' => 'admin'], ['active' => 1, 'registration_enabled' => 0]);
        DB::table('users_roles')->updateOrInsert(['role' => 'internal'], ['active' => 1, 'registration_enabled' => 0]);
        DB::table('users_roles')->updateOrInsert(['role' => 'user'], ['active' => 1, 'registration_enabled' => 1]);


        $admin_name = $this->askValid('Input administrator full name: ', 'admin_name', ['required', 'min:3']);
        $admin_email = $this->askValid('Input administrator email: ', 'admin_email', ['required', 'email']);
        $admin_pass = $this->askValid('Input administrator password: ', 'admin_pass', ['required', 'min:5']);

        // Add administrator account
        $this->line('Adding administrator account');
        $UserModel = new User();
        $role_id_admin = $UserModel->get_role_id_from_role('admin');
        DB::table('users')->updateOrInsert(['email' => $admin_email], [
            'name' => $admin_name ?? 'Admin',
            'code' => strtoupper(Str::random(8)),
            'slug' => Str::slug(($admin_name ?? 'admin'), '-'),
            'email' => $admin_email,
            'role_id' => $role_id_admin,
            'password' => Hash::make($admin_pass),
            'active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'register_ip' => $request->ip(),
        ]);

        $default_lang_id = default_lang()->id;

        // admin user id
        $admin_user_id = DB::table('users')->where('role_id', $role_id_admin)->orderBy('id', 'desc')->limit(1)->value('id');

        // add a sample page
        $this->line('Adding sample page');
        DB::table('pages')->insert(['parent_id' => null, 'user_id' => $admin_user_id, 'label' => 'About', 'active' => 1, 'created_at' => now()]);
        $sample_page_id = DB::getPdo()->lastInsertId();
        DB::table('pages_content')->insert(['page_id' => $sample_page_id, 'lang_id' => $default_lang_id, 'title' => 'About', 'slug' => 'about', 'meta_title' => 'About']);

        // add a sample block (text / html) to sample page created above
        $block_type_id = DB::table('blocks_types')->where('type', 'editor')->value('id');
        DB::table('blocks')->insert(['type_id' => $block_type_id, 'label' => 'About us - text content', 'module' => 'pages', 'content_id' => $sample_page_id, 'position' => 1, 'created_by_user_id' => $admin_user_id, 'created_at' => now()]);
        $block_id = DB::getPdo()->lastInsertId();
        DB::table('blocks_content')->insert(['block_id' => $block_id, 'lang_id' => $default_lang_id, 'content' => '<p><b>This is a sample page</b></p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>']);
        Core::regenerate_content_blocks('pages', $sample_page_id);
        
        // add homepage in menu
        $this->line('Adding sample menu');
        DB::table('sys_menu')->insert(['parent_id' => null, 'type' => 'homepage', 'label' => 'Home', 'position' => 1]);
        $home_menu_id = DB::getPdo()->lastInsertId();
        DB::table('sys_menu_langs')->insert(['link_id' => $home_menu_id, 'lang_id' => $default_lang_id, 'label' => 'Home']);

        // add sample page in menu
        DB::table('sys_menu')->insert(['parent_id' => null, 'type' => 'page', 'value' => $sample_page_id, 'position' => 2]);
        $page_menu_id = DB::getPdo()->lastInsertId();
        DB::table('sys_menu_langs')->insert(['link_id' => $page_menu_id, 'lang_id' => $default_lang_id, 'label' => 'About']);

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        $this->info('The install was successful!');
    }


    protected function askValid($question, $field, $rules)
    {
        $value = $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
            $fieldName => $value
        ], [
            $fieldName => $rules
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
