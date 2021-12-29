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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use Cookie;

class Core extends Model
{
    protected $table = 'sys_config';

    protected $fillable = ['name', 'value'];

    public function __construct()
    {
    }


    /**
     * Get configs from database
     *
     * @return object
     */
    public static function config()
    {
        
        $results = Cache::remember('config', 60 * 60 * 24, function () {
            return DB::table('sys_config')->pluck('value', 'name')->toArray();
        });

        return (object)$results;
    }


    /**
     * Get default template configs from database
     *
     * @return object
     */
    public static function template_config()
    {
        $results = Cache::remember('template', 60 * 60 * 24, function () {
            // default template ID
            $templateID = DB::table('sys_templates')->where('is_default', 1)->value('id');

            $results['template_id'] = $templateID;
            return DB::table('sys_templates_config')->where('template_id', $templateID)->pluck('value', 'name')->toArray();
        });

        $results['id'] = DB::table('sys_templates')->where('is_default', 1)->value('id'); // Add template ID to config

        $preview_template_id = Cookie::get('template_id_preview_cookie');
        if ($preview_template_id && ($preview_template_id != Core::get_default_template_id())) {
            $results['id'] = $preview_template_id;
        }

        return (object)$results;
    }


    /**
     * Update config
     *
     * @return null
     */
    public static function update_config($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                DB::table('sys_config')->updateOrInsert(['name' => $key], ['value' => $value]);
            }
        } else {
            DB::table('sys_config')->updateOrInsert(['name' => $name], ['value' => $value]);
        }

        // clear cache
        Cache::forget('config');

        return null;
    }


    /**
     * Update config
     *
     * @return null
     */
    public static function update_template_config($template_id, $name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                DB::table('sys_templates_config')->updateOrInsert(['template_id' => $template_id, 'name' => $key], ['value' => $value]);
            }
        } else {
            DB::table('sys_templates_config')->updateOrInsert(['template_id' => $template_id, 'name' => $name], ['value' => $value]);
        }

        // clear cache
        Cache::forget('template');

        return null;
    }


    /**
     * Get active template ID
     *
     * @return integer
     */
    public static function get_active_template_id()
    {
        if (Cookie::get('template_id_preview_cookie') && check_access('developer')) {
            $cookie_template_id = Cookie::get('template_id_preview_cookie');

            // check if template exists
            if (DB::table('sys_templates')->where('id', $cookie_template_id)->exists()) return $cookie_template_id;
            else return Core::get_default_template_id();
        } else {
            return Core::get_default_template_id();
        }
    }


    /**
     * Get default template ID
     *
     * @return integer
     */
    public static function get_default_template_id()
    {
        $default_template_id = DB::table('sys_templates')->where('is_default', 1)->value('id');
        return $default_template_id;
    }


    /**
     * Generate custom routes
     *
     * @return null
     */
    public static function generate_langs_routes()
    {
        $file = resource_path('clevada/routes/routes.xml');
        $fp = fopen($file, 'w');

        $data_header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<routes>\n";
        fwrite($fp, $data_header);


        foreach (sys_langs() as $lang) {
            $permalinks = unserialize($lang->permalinks);

            // POSTS routes			
            $route_posts = $permalinks['posts'] ?? 'blog';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>posts</name>		
			<value>$route_posts</value>
			</route>";
            fwrite($fp, $data_route);

            // DOCS routes
            $route_docs = $permalinks['docs'] ?? 'docs';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>docs</name>		
			<value>$route_docs</value>
			</route>";
            fwrite($fp, $data_route);

            // FORUM routes
            $route_forum = $permalinks['forum'] ?? 'forum';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>forum</name>		
			<value>$route_forum</value>
			</route>";
            fwrite($fp, $data_route);
          
            // CART routes
            $route_cart = $permalinks['cart'] ?? 'shop';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>cart</name>		
			<value>$route_cart</value>
			</route>";
            fwrite($fp, $data_route);

            // TAGS 
            $route_tag = $permalinks['tag'] ?? 'tag';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>tag</name>		
			<value>$route_tag</value>
			</route>";
            fwrite($fp, $data_route);

            // SEARCH 
            $route_search = $permalinks['search'] ?? 'search';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>tag</name>		
			<value>$route_search</value>
			</route>";
            fwrite($fp, $data_route);

            // PROFILE 
            $route_profile = $permalinks['profile'] ?? 'profile';
            if ($lang->is_default == 1) $route_lang = 'default';
            else $route_lang = $lang->code;
            $data_route = "<route>
			<lang>$route_lang</lang>
			<name>tag</name>		
			<value>$route_profile</value>
			</route>";
            fwrite($fp, $data_route);
        }

        $data_footer = "</routes>";
        fwrite($fp, $data_footer);

        fclose($fp);

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return null;
    }



    /**
     * Generate navigation menu links
     *
     * @return null
     */
    public static function generate_langs_menu_links()
    {
        foreach (sys_langs() as $lang) {

            $links = DB::table('sys_menu')->whereNull('parent_id')->orderBy('position', 'asc')->get();

            $items = array();

            foreach ($links as $link) {

                $dropdown = array();

                if ($link->type == 'homepage')
                    $url = route('homepage', ['lang' => ($lang->id == default_lang()->id) ? null : $lang->code]);

                elseif ($link->type == 'custom')
                    $url = $link->value;

                elseif ($link->type == 'module') {
                    $permalinks = DB::table('sys_lang')->where('id', $lang->id)->value('permalinks');
                    $permalinks = unserialize($permalinks);

                    $this_lang = ($lang->id == default_lang()->id) ? null : '/' . lang($lang->id)->code;
                    $url = route('homepage') . $this_lang . '/' . $permalinks[$link->value];
                } elseif ($link->type == 'page')
                    $url = page((int)$link->value, $lang->id)->url;

                elseif ($link->type == 'dropdown') {
                    $url = '#';

                    $dropdown_links = DB::table('sys_menu')->where('parent_id', $link->id)->orderBy('position', 'asc')->get();
                    foreach ($dropdown_links as $dropdown_link) {

                        if ($dropdown_link->type == 'homepage')
                            $dropdown_url = route('homepage', ['lang' => ($lang->id == default_lang()->id) ? null : $lang->code]);

                        elseif ($dropdown_link->type == 'custom')
                            $dropdown_url = $dropdown_link->value;

                        elseif ($dropdown_link->type == 'page')
                            $dropdown_url = page((int)$dropdown_link->value, $lang->id)->url;

                        elseif ($dropdown_link->type == 'module') {
                            $permalinks = DB::table('sys_lang')->where('id', $lang->id)->value('permalinks');
                            $permalinks = unserialize($permalinks);

                            $this_dropdown_lang = ($lang->id == default_lang()->id) ? null : '/' . lang($lang->id)->code;
                            $dropdown_url = route('homepage') . $this_dropdown_lang . '/' . $permalinks[$dropdown_link->value];
                        }


                        $dropdown[] = array('label' => (get_menu_link_label($dropdown_link->id, $lang->id)) ?? '#', 'url' => $dropdown_url ?? null);
                    }
                }

                $items[] = array('label' => (get_menu_link_label($link->id, $lang->id)) ?? '#', 'url' => $url, 'dropdown' => $dropdown);
            }

            Core::update_config('menu_links_' . $lang->id, serialize($items));
        }

        return null;
    }



    /**
     * Regenerate content blocks
     *
     * @return null
     */
    public static function regenerate_content_blocks($module, $content_id)
    {
        if ($module == 'posts') $table = 'posts';
        if ($module == 'pages') $table = 'pages';
        if ($module == 'docs') $table = 'docs';

        $item = DB::table($table)->where('id', $content_id)->first();
        if (!$item) return null;

        //$blocks = DB::table('blocks')->where('module', $module)->where('content_id', $content_id)->where('hide', 0)->orderBy('position', 'asc')->pluck('id')->toArray();

        $blocks = DB::table('blocks')
            ->leftJoin('blocks_types', 'blocks.type_id', '=', 'blocks_types.id')
            ->select(
                'blocks.*',
                'blocks_types.type as type',
                'blocks_types.label as type_label',
                'blocks.created_at as block_created_at',
                'blocks.updated_at as block_updated_at',
                'blocks.extra as block_extra',
                DB::raw("(SELECT content FROM blocks_content WHERE block_id = blocks.id AND lang_id = " . active_lang()->id . ") as block_content_default_lang")
            )
            ->where('blocks.module', $module)
            ->where('blocks.content_id', $content_id)
            ->where('blocks.hide', 0)
            ->orderBy('position', 'asc')
            ->get();


        DB::table($table)->where('id', $content_id)->update(['blocks' => serialize($blocks)]);

        return null;
    }



    /**
     * Generate sitemap
     *
     * @return null
     */
    public static function generate_sitemap()
    {

        header('Content-type: application/xml');

        $file_xml = public_path('sitemap.xml');

        $fp = fopen($file_xml, 'w');

        $data_header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        fwrite($fp, $data_header);


        // First page
        $data = "\n
			<url>
				<loc>" . route('homepage') . "</loc>
				<changefreq>daily</changefreq>
				<priority>1</priority>
			</url>";
        fwrite($fp, $data);

        // pages
        $pages = DB::table('pages')
            ->select('pages.id')
            ->where('active', 1)
            ->orderBy('id', 'desc')
            ->get();
        foreach ($pages as $page) {
            foreach (page_contents($page->id) as $content) {
                $data = "\n
				<url>
					<loc>" . page($page->id, $content->lang_id)->url . "</loc>
					<changefreq>monthly</changefreq>
					<priority>0.8</priority>
				</url>";
                fwrite($fp, $data);
            }
        }


        // posts
        if (check_module('posts')) {

            // posts section
            $data = "\n
					<url>
						<loc>" . posts_url() . "</loc>
						<changefreq>daily</changefreq>
						<priority>0.8</priority>
					</url>";
            fwrite($fp, $data);

            // categories
            $posts_categories = DB::table('posts_categ')
                ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
                ->select('posts_categ.id')
                ->where('sys_lang.status', 'active')
                ->where('posts_categ.active', 1)
                ->orderBy('posts_categ.id', 'desc')
                ->get();
            foreach ($posts_categories as $posts_categ) {
                $data = "\n
					<url>
						<loc>" . posts_url($posts_categ->id) . "</loc>
						<changefreq>weekly</changefreq>
						<priority>0.8</priority>
					</url>";
                fwrite($fp, $data);
            }

            // posts
            $posts = DB::table('posts')
                ->leftJoin('sys_lang', 'posts.lang_id', '=', 'sys_lang.id')
                ->select('posts.id')
                ->where('sys_lang.status', 'active')
                ->where('posts.status', 'active')
                ->orderBy('posts.id', 'desc')
                ->get();
            foreach ($posts as $post) {
                $data = "\n
					<url>
						<loc>" . post($post->id)->url . "</loc>
						<changefreq>weekly</changefreq>
						<priority>0.7</priority>
					</url>";
                fwrite($fp, $data);
            }
        }

        $data_footer = "\n</urlset>";
        fwrite($fp, $data_footer);
        fclose($fp);

        return null;
    }
}
