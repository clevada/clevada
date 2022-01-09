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

namespace App\Http\Controllers\Admin;

use App\Models\Core;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect(route('homepage'));
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        $search_terms = $request->search_terms;
        $search_lang_id = $request->search_lang_id;

        $pages = DB::table('pages')
            ->leftJoin('users', 'pages.user_id', '=', 'users.id')
            ->select(
                'pages.*',
                'users.name as author_name',
                'users.avatar as author_avatar',
                'pages.parent_id as parent_page_id',
                DB::raw('(SELECT label FROM pages WHERE id = parent_page_id) as parent_label'),
                DB::raw('(SELECT label FROM sys_global_sections WHERE pages.top_section_id = sys_global_sections.id) as top_section_label'),
                DB::raw('(SELECT label FROM sys_global_sections WHERE pages.bottom_section_id = sys_global_sections.id) as bottom_section_label'),
                DB::raw('(SELECT label FROM sys_sidebars WHERE pages.sidebar_id = sys_sidebars.id) as sidebar_label')
            )
            ->orderBy('pages.active', 'desc')
            ->orderBy('pages.id', 'desc');

        if ($search_terms)
            $pages = $pages->where('pages.label', 'like', "%$search_terms%");

        if ($search_lang_id)
            $pages = $pages->where('pages_content.lang_id', $search_lang_id);

        $pages = $pages->paginate(25);
        
        return view('admin/account', [
            'view_file' => 'pages.pages',
            'active_menu' => 'website',
            'active_submenu' => 'pages',
            'search_terms' => $search_terms,
            'search_lang_id' => $search_lang_id,
            'pages' => $pages,
        ]);
    }


    /**
     * Show form to add new resource
     */
    public function create()
    {
        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        $root_pages = DB::table('pages')
            ->whereNull('parent_id')
            ->orderBy('active', 'desc')
            ->orderBy('label', 'asc')
            ->get();

        $sidebars = DB::table('sys_sidebars')
            ->orderBy('label', 'asc')
            ->get();

        $global_sections = DB::table('sys_global_sections')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'pages.create',
            'active_menu' => 'website',
            'active_submenu' => 'pages',
            'root_pages' => $root_pages,
            'sidebars' => $sidebars,
            'global_sections' => $global_sections,
        ]);
    }


    /**
     * Create new page
     */
    public function store(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.pages.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        $label = $inputs['label'];
        if (DB::table('pages')->where('label', $label)->exists()) {
            $label = $label . ' - ' . date("Y.m.d H:i.s");
        }

        if ($request->has('active')) $active = 1;
        else $active = 0;

        DB::table('pages')->insert([
            'parent_id' => $inputs['parent_id'],
            'sidebar_position' => $inputs['sidebar_position'],
            'sidebar_id' => $inputs['sidebar_id'] ?? null,
            'top_section_id' => $inputs['top_section_id'] ?? null,
            'bottom_section_id' => $inputs['bottom_section_id'] ?? null,
            'user_id' => Auth::user()->id,
            'label' => $label,
            'active' => $active,
            'created_at' => now(),
        ]);

        $page_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $title = $request['title_' . $lang->id];
            $meta_title = $request['meta_title_' . $lang->id];
            $meta_description = $request['meta_description_' . $lang->id];
            $slug = $request['slug_' . $lang->id];

            if ($slug) $slug = Str::slug($request['slug_' . $lang->id], '-');
            else $slug = Str::slug($request['title_' . $lang->id], '-');

            if (DB::table('pages_content')->where('slug', $slug)->where('lang_id', $lang->id)->exists()) {
                //return redirect(route('admin.pages.create'))->with('error', 'duplicate');  
                $slug =  $slug . "-" . $page_id;
            }

            // 2 characters is for language only
            if (strlen($slug) == 2) return redirect(route('admin.pages.create'))->with('error', 'length2');

            DB::table('pages_content')->insert(['page_id' => $page_id, 'lang_id' => $lang->id, 'title' => $title, 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'slug' => $slug]);
        }

        return redirect(route('admin.pages.content', ['id' => $page_id]))->with('success', 'created');
    }


    /**
     * Create page content
     */
    public function store_content(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.pages.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token'); // retrieve all of the input data as an array 

        if (DB::table('pages')->where('label', $inputs['label'])->exists()) return redirect(route('admin.pages.create'))->with('error', 'duplicate');
        if ($request->has('active')) $active = 1;
        else $active = 0;

        DB::table('pages')->insert([
            'parent_id' => $inputs['parent_id'],
            'layout' => $inputs['layout'],
            'sidebar_id' => $inputs['sidebar_id'] ?? null,
            'user_id' => Auth::user()->id,
            'label' => $inputs['label'],
            'active' => $active,
            'created_at' => now(),
        ]);

        $page_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $title = $request['title_' . $lang->id];
            $content = $request['content_' . $lang->id];
            $meta_title = $request['meta_title_' . $lang->id];
            $meta_description = $request['meta_description_' . $lang->id];
            $slug = $request['slug_' . $lang->id];

            if ($slug) $slug = Str::slug($request['slug_' . $lang->id], '-');
            else $slug = Str::slug($request['title_' . $lang->id], '-');

            if (DB::table('pages_content')->where('slug', $slug)->where('lang_id', $lang->id)->exists()) {
                //return redirect(route('admin.pages.create'))->with('error', 'duplicate');  
                $slug =  $slug . "-" . $page_id;
            }

            // 2 characters is for language only
            if (strlen($slug) == 2) return redirect(route('admin.pages.create'))->with('error', 'length2');

            DB::table('pages_content')
                ->updateOrInsert(
                    ['page_id' => $page_id, 'lang_id' => $lang->id],
                    ['title' => $title, 'content' => $content, 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'slug' => $slug]
                );

            // for sub-pages
            $subpages = DB::table('pages')->where('parent_id', $page_id)->get();
            foreach ($subpages as $subpage) {
                DB::table('pages_content')->where('page_id', $subpage->id)->update(['lang_id' => $lang->id]); // for sub-pages
            }
        }

        return redirect($request->Url())->with('success', 'created');
    }


    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        $page = DB::table('pages')->where('id', $request->id)->first();
        if (!$page) return redirect(route('admin'));

        // check permission
        if (check_access('pages', 'author') && $this->logged_user_id != $page->user_id) return redirect(route('admin'));

        $langs = DB::table('sys_lang')
            ->select(
                'sys_lang.*',
                DB::raw('(SELECT title FROM pages_content WHERE pages_content.lang_id = sys_lang.id AND page_id = ' . $page->id . ') as title'),
                DB::raw('(SELECT slug FROM pages_content WHERE pages_content.lang_id = sys_lang.id AND page_id = ' . $page->id . ') as slug'),
                DB::raw('(SELECT meta_title FROM pages_content WHERE pages_content.lang_id = sys_lang.id AND page_id = ' . $page->id . ') as meta_title'),
                DB::raw('(SELECT meta_description FROM pages_content WHERE pages_content.lang_id = sys_lang.id AND page_id = ' . $page->id . ') as meta_description'),
            )
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        $root_pages = DB::table('pages')
            ->whereNull('parent_id')
            ->orderBy('active', 'desc')
            ->orderBy('label', 'asc')
            ->where('id', '!=', $page->id)
            ->get();

        $sidebars = DB::table('sys_sidebars')
            ->orderBy('label', 'asc')
            ->get();

        $global_sections = DB::table('sys_global_sections')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'pages.update',
            'active_menu' => 'website',
            'active_submenu' => 'pages',
            'page' => $page,
            'langs' => $langs,
            'root_pages' => $root_pages,
            'sidebars' => $sidebars,
            'global_sections' => $global_sections,
        ]);
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;
        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) return redirect(route('admin'));

        // check permission
        if (check_access('pages', 'author') && $this->logged_user_id != $page->user_id) return redirect(route('admin'));

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($request->Url())
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token'); // retrieve all of the input data as an array 

        if (DB::table('pages')->where('label', $inputs['label'])->where('id', '!=', $id)->exists()) return redirect(route('admin.pages.show', ['id' => $id]))->with('error', 'duplicate');
        if ($request->has('active')) $active = 1; else $active = 0;

        DB::table('pages')->where('id', $id)->update(['label' => $inputs['label'], 'parent_id' => $inputs['parent_id'], 'sidebar_position' => $inputs['sidebar_position'] ?? null, 'sidebar_id' => $inputs['sidebar_id'] ?? null, 'top_section_id' => $inputs['top_section_id'] ?? null, 'bottom_section_id' => $inputs['bottom_section_id'] ?? null, 'active' => $active, 'updated_at' => now()]);

        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $title = $request['title_' . $lang->id];
            $meta_title = $request['meta_title_' . $lang->id];
            $meta_description = $request['meta_description_' . $lang->id];
            $slug = $request['slug_' . $lang->id];

            if ($slug) $slug = Str::slug($request['slug_' . $lang->id], '-');
            else $slug = Str::slug($request['title_' . $lang->id], '-');

            if (DB::table('pages_content')->where('slug', $slug)->where('lang_id', $lang->id)->where('page_id', '!=', $id)->exists()) {
                //return redirect(route('admin.pages.show', ['id' => $id]))->with('error', 'duplicate');  
                $slug =  $slug . "-" . $id;
            }

            // 2 characters is for language only
            if (strlen($slug) == 2) return redirect(route('admin.pages.show', ['id' => $id]))->with('error', 'length2');

            DB::table('pages_content')
                ->updateOrInsert(
                    ['page_id' => $id, 'lang_id' => $lang->id],
                    ['title' => $title, 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'slug' => $slug]
                );

            // for sub-pages
            $subpages = DB::table('pages')->where('parent_id', $id)->get();
            foreach ($subpages as $subpage) {
                DB::table('pages_content')->where('page_id', $subpage->id)->update(['lang_id' => $lang->id]); // for sub-pages
            }
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.pages'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages', 'manager')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;

        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) return redirect(route('admin'));

        // delete content blocks
        $blocks = DB::table('blocks')->where('module', 'pages')->where('content_id', $id)->get();
        foreach ($blocks as $block) {
            DB::table('blocks')->where('id', $block->id)->delete();
            DB::table('blocks_content')->where('block_id', $block->id)->delete();
        }

        DB::table('pages')->where('parent_id', $id)->update(['parent_id' => null]);
        DB::table('sys_menu')->where('type', 'page')->where('value', $id)->delete();
        DB::table('pages')->where('id', $id)->delete();
        DB::table('pages_content')->where('page_id', $id)->delete();

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.pages'))->with('success', 'deleted');
    }



    /**
     * Show form to edit content     
     */
    public function content(Request $request)
    {

        $id = $request->id;

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) return redirect(route('admin'));

        return view('admin/account', [
            'view_file' => 'pages.content',
            'active_menu' => 'website',
            'active_submenu' => 'pages',
            'page' => $page,
            'module' => 'pages',
        ]);
    }



    /**
     * Update page content   
     */
    public function update_content(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        $id = $request->id;
        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) return redirect(route('admin.pages.content', ['id' => $id]));

        $type_id = $inputs['type_id'];

        if (!$type_id) return redirect(route('admin.pages.content', ['id' => $id]));

        $last_pos = DB::table('blocks')->where('module', 'pages')->where('content_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'module' => 'pages',
            'content_id' => $page->id,
            'position' => $position,
            'created_at' => now(),
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        Core::regenerate_content_blocks('pages', $id);

        return redirect(route('admin.pages.content', ['id' => $id]))->with('success', 'updated');
    }



    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {

        if (!check_admin_module('pages')) return redirect(route('admin'));
        if (!check_access('pages', 'manager')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;  // page ID
        $block_id = $request->block_id;

        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) return redirect(route('admin'));

        // regenerate content blocks and add blocks in module table (for database performance)
        Core::regenerate_content_blocks('pages', $id);
        
        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        return redirect(route('admin.pages.content', ['id' => $id]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        $i = 0;
        $content_id = $request->id;
        $records = $request->all();

        foreach ($records['item'] as $key => $value) {

            DB::table('blocks')
                ->where('module', 'pages')
                ->where('content_id', $content_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        Core::regenerate_content_blocks('pages', $content_id);
    }
}
