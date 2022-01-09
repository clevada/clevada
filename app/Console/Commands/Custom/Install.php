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
use App\Models\Template;
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

        $template_id = DB::table('sys_templates')->where('is_default', 1)->value('id');

        DB::table('sys_config')->insertOrIgnore(['name' => 'site_meta_author', 'value' => 'Clevada - https://clevada.com']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'registration_enabled', 'value' => 1]);
        DB::table('sys_config')->insertOrIgnore(['name' => 'favicon', 'value' => '/default/favicon.png']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'logo', 'value' => '/default/logo.png']);
        DB::table('sys_config')->insertOrIgnore(['name' => 'logo_auth', 'value' => '/default/logo-auth.png']);

        // language
        if (!DB::table('sys_lang')->where('is_default', 1)->where('status', 'active')->exists())
            DB::table('sys_lang')->updateOrInsert(['code' => 'en'], ['name' => 'English', 'locale' => 'en_US', 'is_default' => 1, 'status' => 'active', 'timezone' => 'Europe/London', 'site_short_title' => 'Clevada website', 'homepage_meta_title' => 'Clevada website', 'homepage_meta_description' => 'Clevada website', 'permalinks' => 'a:8:{s:5:"posts";s:4:"blog";s:4:"cart";s:4:"shop";s:5:"forum";s:5:"forum";s:4:"docs";s:4:"docs";s:7:"contact";s:7:"contact";s:3:"tag";s:3:"tag";s:6:"search";s:6:"search";s:7:"profile";s:7:"profile";}']);

        // Modules
        DB::table('sys_modules')->updateOrInsert(['module' => 'accounts'], ['label' => 'Accounts', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.accounts', 'hidden' => 1]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'cart'], ['label' => 'eCommerce', 'status' => 'active', 'route_web' => 'cart', 'route_admin' => 'admin.cart.products', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'developer'], ['label' => 'Template developer', 'status' => 'active', 'route_web' => null, 'route_admin' => null, 'hidden' => 1]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'docs'], ['label' => 'Knowledge Base', 'status' => 'active', 'route_web' => 'docs', 'route_admin' => 'admin.docs', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'forms'], ['label' => 'Forms', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.forms', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'forum'], ['label' => 'Community', 'status' => 'active', 'route_web' => 'forum', 'route_admin' => 'admin.forum.topics', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'pages'], ['label' => 'Pages', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.pages', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'posts'], ['label' => 'Posts', 'status' => 'active', 'route_web' => 'posts', 'route_admin' => 'admin.posts', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'tasks'], ['label' => 'Tasks', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.tasks', 'hidden' => 0]);
        DB::table('sys_modules')->updateOrInsert(['module' => 'translates'], ['label' => 'Translates', 'status' => 'active', 'route_web' => null, 'route_admin' => 'admin.translates', 'hidden' => 1]);

        // Permissions
        DB::table('sys_permissions')->updateOrInsert(['module' => 'accounts', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 1, 'description' => 'Manage any account']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'accounts', 'permission' => 'operator'], ['label' => 'Operator', 'position' => 2, 'description' => 'View accounts details. Can add internal info and assign account tags']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'cart', 'permission' => 'manager'], ['label' => 'Manager', 'position' => 1, 'description' => 'Have full access to eCommerce content (products, stock, discounts, orders)']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'developer', 'permission' => 'developer'], ['label' => 'Developer', 'position' => 1, 'description' => 'Developers have access to manage and edit template']);
        DB::table('sys_permissions')->updateOrInsert(['module' => 'docs', 'permission' => 'author'], ['label' => 'Author', 'position' => 1, 'description' => 'Manage knowledge base articles']);
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
        DB::table('blocks_types')->updateOrInsert(['type' => 'cart'], ['label' => 'Shop content', 'icon' => '<i class="bi bi-cart-check"></i>', 'position' => 160, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'forum'], ['label' => 'Forum content', 'icon' => '<i class="bi bi-chat-right-quote"></i>', 'position' => 170, 'allow_footer' => 1, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'form'], ['label' => 'Form', 'icon' => '<i class="bi bi-file-text"></i>', 'position' => 180, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'search'], ['label' => 'Search', 'icon' => '<i class="bi bi-search"></i>', 'position' => 190, 'allow_footer' => 0, 'allow_to_users' => 0]);
        DB::table('blocks_types')->updateOrInsert(['type' => 'include'], ['label' => 'Include file', 'icon' => '<i class="bi bi-file-code"></i>', 'position' => 200, 'allow_footer' => 1, 'allow_to_users' => 0]);

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
        $block_type_id_editor = DB::table('blocks_types')->where('type', 'editor')->value('id');
        $block_type_id_links = DB::table('blocks_types')->where('type', 'links')->value('id');

        // admin user id
        $admin_user_id = DB::table('users')->where('role_id', $role_id_admin)->orderBy('id', 'desc')->limit(1)->value('id');

        // Add template styles
        DB::table('sys_templates_config')->updateOrInsert(['template_id' => $template_id, 'name' => 'footer_columns'], ['value' => 2]);
        DB::table('sys_templates_config')->updateOrInsert(['template_id' => $template_id, 'name' => 'footer2_show'], ['value' => 'on']);
        DB::table('sys_templates_config')->updateOrInsert(['template_id' => $template_id, 'name' => 'footer2_columns'], ['value' => 1]);

        DB::table('sys_footer_blocks')->insert(['template_id' => $template_id, 'footer' => 'primary', 'type_id' => $block_type_id_editor, 'layout' => 2, 'col' => 1, 'position' => 1, 'created_at' => now()]);
        $block_id_footer_primary_col1 = DB::getPdo()->lastInsertId();
        DB::table('sys_footer_blocks')->insert(['template_id' => $template_id, 'footer' => 'primary', 'type_id' => $block_type_id_links, 'layout' => 2, 'col' => 2, 'position' => 1, 'created_at' => now()]);
        $block_id_footer_primary_col2 = DB::getPdo()->lastInsertId();
        DB::table('sys_footer_blocks')->insert(['template_id' => $template_id, 'footer' => 'secondary', 'type_id' => $block_type_id_editor, 'layout' => 1, 'col' => 1, 'position' => 1, 'created_at' => now()]);
        $block_id_footer_secondary_col1 = DB::getPdo()->lastInsertId();

        $url_homepage = config('app.url');
        
        $s_homepage = strlen($url_homepage);
        $s_blog = strlen($url_homepage.'/blog');
        $s_about = strlen($url_homepage.'/about');
        DB::table('sys_footer_blocks_content')->insert(['block_id' => $block_id_footer_primary_col1, 'lang_id' => $default_lang_id, 'content' => '<p><a href="https://clevada.com/">Clevada</a> is a free suite for businesses, communities, teams, collaboration or personal websites. Create a free and professional website in minutes.</p>', 'header' => 'a:3:{s:10:"add_header";s:2:"on";s:5:"title";s:15:"About This Site";s:7:"content";N;}']);
        DB::table('sys_footer_blocks_content')->insert(['block_id' => $block_id_footer_primary_col2, 'lang_id' => $default_lang_id, 'content' => 'a:3:{i:0;a:3:{s:5:"title";s:4:"Home";s:3:"url";s:'.$s_homepage.':"'.$url_homepage.'";s:4:"icon";N;}i:1;a:3:{s:5:"title";s:4:"Blog";s:3:"url";s:'.$s_blog.':"'.$url_homepage.'/blog";s:4:"icon";N;}i:2;a:3:{s:5:"title";s:5:"About";s:3:"url";s:'.$s_about.':"'.$url_homepage.'/about";s:4:"icon";N;}}', 'header' => 'a:3:{s:10:"add_header";s:2:"on";s:5:"title";s:10:"Navigation";s:7:"content";N;}']);
        DB::table('sys_footer_blocks_content')->insert(['block_id' => $block_id_footer_secondary_col1, 'lang_id' => $default_lang_id, 'content' => '<p>© 2022 Powered by <strong><a target="_blank" href="https://clevada.com">Clevada</a></strong>: #1 Free Business Suite and Website Builder</p>']);

        // add a sample homepage block with text
        $this->line('Adding sample homepage block content');
        DB::table('blocks')->insert(['type_id' => $block_type_id_editor, 'template_id' => $template_id, 'module' => 'homepage', 'position' => 1, 'created_by_user_id' => $admin_user_id, 'created_at' => now()]);
        $block_id_homepage = DB::getPdo()->lastInsertId();
        DB::table('blocks_content')->insert(['block_id' => $block_id_homepage, 'lang_id' => $default_lang_id, 'content' => '<h1>What is Lorem Ipsum?</h1><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>']);

        // add a sample page
        $this->line('Adding sample page');
        DB::table('pages')->insert(['parent_id' => null, 'user_id' => $admin_user_id, 'label' => 'About', 'active' => 1, 'created_at' => now()]);
        $sample_page_id = DB::getPdo()->lastInsertId();
        DB::table('pages_content')->insert(['page_id' => $sample_page_id, 'lang_id' => $default_lang_id, 'title' => 'About', 'slug' => 'about', 'meta_title' => 'About']);

        // add a sample block (text / html) to sample page created above
        DB::table('blocks')->insert(['type_id' => $block_type_id_editor, 'label' => 'About us - text content', 'module' => 'pages', 'content_id' => $sample_page_id, 'position' => 1, 'created_by_user_id' => $admin_user_id, 'created_at' => now()]);
        $block_id = DB::getPdo()->lastInsertId();
        DB::table('blocks_content')->insert(['block_id' => $block_id, 'lang_id' => $default_lang_id, 'content' => '<p><b>This is a sample page</b></p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>']);
        Core::regenerate_content_blocks('pages', $sample_page_id);

        // add homepage in menu
        $this->line('Adding sample menu');
        DB::table('sys_menu')->insert(['parent_id' => null, 'type' => 'homepage', 'label' => 'Home', 'position' => 1]);
        $home_menu_id = DB::getPdo()->lastInsertId();
        DB::table('sys_menu_langs')->insert(['link_id' => $home_menu_id, 'lang_id' => $default_lang_id, 'label' => 'Home']);

        // add blog section in menu
        DB::table('sys_menu')->insert(['parent_id' => null, 'type' => 'module', 'value' => 'posts', 'position' => 2]);
        $blog_menu_id = DB::getPdo()->lastInsertId();
        DB::table('sys_menu_langs')->insert(['link_id' => $blog_menu_id, 'lang_id' => $default_lang_id, 'label' => 'Blog']);

        // add sample page in menu
        DB::table('sys_menu')->insert(['parent_id' => null, 'type' => 'page', 'value' => $sample_page_id, 'position' => 3]);
        $page_menu_id = DB::getPdo()->lastInsertId();
        DB::table('sys_menu_langs')->insert(['link_id' => $page_menu_id, 'lang_id' => $default_lang_id, 'label' => 'About']);

        // add a sample blog category
        $this->line('Adding sample blog article');
        DB::table('posts_categ')->insert(['lang_id' => $default_lang_id, 'parent_id' => null, 'title' => 'Sample category', 'slug' => 'sample-category', 'active' => 1, 'position' => 1]);
        $sample_posts_categ_id = DB::getPdo()->lastInsertId();
        DB::table('posts_categ')->where('id', $sample_posts_categ_id)->update(['tree_ids' => $sample_posts_categ_id]);

        // add a sample blog article
        DB::table('posts')->insert(['categ_id' => $sample_posts_categ_id, 'lang_id' => $default_lang_id, 'title' => 'This is a sample article', 'slug' => 'this-is-a-sample-article', 'image' => 'default/sample-post-image.jpg', 'user_id' => $admin_user_id, 'status' => 'active', 'summary' => 'He swung back the fishing pole and cast the line which ell 25 feet away into the river. The lure landed in the perfect spot and he was sure he would soon get a bite. He never expected that the bite would come from behind in the form of a bear.', 'created_at' => now()]);
        $sample_posts_id = DB::getPdo()->lastInsertId();

        DB::table('blocks')->insert(['type_id' => $block_type_id_editor, 'label' => 'Sample block content', 'module' => 'posts', 'content_id' => $sample_posts_id, 'position' => 1, 'created_by_user_id' => $admin_user_id, 'created_at' => now()]);
        $block_id_sample_post = DB::getPdo()->lastInsertId();
        DB::table('blocks_content')->insert(['block_id' => $block_id_sample_post, 'lang_id' => $default_lang_id, 'content' => '<p><strong>Bryan had made peace with himself and felt comfortable with the choices he made. This had made all the difference in the world. Being alone no longer bothered him and this was essential since there was a good chance he might spend the rest of his life alone in a cell. He was an expert but not in a discipline that anyone could fully appreciate.</strong></p>
        <p>He knew how to hold the cone just right so that the soft server ice-cream fell into it at the precise angle to form a perfect cone each and every time. It had taken years to perfect and he could now do it without even putting any thought behind it. Nobody seemed to fully understand the beauty of this accomplishment except for the new worker who watched in amazement.</p><p>Time is all relative based on age and experience. When you are a child an hour is a long time to wait but a very short time when that is all the time you are allowed on your iPad. As a teenager time goes faster the more deadlines you have and the more you procrastinate. As a young adult, you think you have forever to live and do not appreciate the time you spend with others. As a middle-aged adult, time flies by as you watch your children grow up. And finally, as you get old and you have fewer responsibilities and fewer demands on you, time slows. You appreciate each day and are thankful you are alive. An hour is the same amount of time for everyone yet it can feel so different in how it goes by.</p>']);        
        Core::regenerate_content_blocks('posts', $sample_posts_id);


        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        // generate custom CSS file for default template
        $css_destination = base_path() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $template_id . '.css';
        Template::generate_global_css($template_id, $css_destination);

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
