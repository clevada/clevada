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

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;

class PostsCategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->PostModel = new Post();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        $search_lang_id = $request->search_lang_id;
        $count_categories = DB::table('posts_categ')->count();

        $categories = Post::whereNull('parent_id')->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang_code', 
                DB::raw('(SELECT label FROM sys_global_sections WHERE posts_categ.top_section_id = sys_global_sections.id) as top_section_label'),
                DB::raw('(SELECT label FROM sys_global_sections WHERE posts_categ.bottom_section_id = sys_global_sections.id) as bottom_section_label'),
                DB::raw('(SELECT label FROM sys_sidebars WHERE posts_categ.sidebar_id = sys_sidebars.id) as sidebar_label'))
            ->orderBy('active', 'desc')
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc');

        if ($search_lang_id) $categories = $categories->where('lang_id', $search_lang_id);

        $categories = $categories->get();

        $sidebars = DB::table('sys_sidebars')
            ->orderBy('label', 'asc')
            ->get();

        $global_sections = DB::table('sys_global_sections')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'posts.categories',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'categories' => $categories,
            'count_categories' => $count_categories,
            'search_lang_id' => $search_lang_id,
            'sidebars' => $sidebars,
            'global_sections' => $global_sections,
        ]);
    }


    /**
     * Create new resource
     */
    public function store(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.posts.categ'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 
        $lang_id = $inputs['lang_id'] ?? default_lang()->id;

        if ($inputs['slug']) $slug = Str::slug($inputs['slug'], '-');
        else $slug = Str::slug($inputs['title'], '-');
        if (strlen($slug) < 3) return redirect(route('admin.posts.categ'))->with('error', 'length');

        if (DB::table('posts_categ')->where('slug', $slug)->where('lang_id', $lang_id)->exists()) return redirect(route('admin.posts.categ'))->with('error', 'duplicate');
        
        // position
        if (!$inputs['position']) {
            if (!$inputs['parent_id']) $last_categ = DB::table('posts_categ')->whereNull('parent_id')->where('lang_id', $lang_id)->orderBy('position', 'desc')->first();
            else $last_categ = DB::table('posts_categ')->where('parent_id', $inputs['parent_id'])->where('lang_id', $lang_id)->orderBy('position', 'desc')->first();
            $last_pos = $last_categ->position ?? 0;
            $next_pos = $last_pos + 1;
        } else $next_pos = $inputs['position'];

        if ($request->has('active')) $active = 1;
        else $active = 0;

        DB::table('posts_categ')->insert([
            'lang_id' => $lang_id,
            'title' => $inputs['title'],
            'parent_id' => $inputs['parent_id'] ?? null,
            'slug' => $slug,
            'description' => $inputs['description'],
            'active' => $active,
            'position' => $next_pos,
            'icon' => $inputs['icon'],
            'badges' => $inputs['badges'],
            'meta_title' => $inputs['meta_title'],
            'meta_description' => $inputs['meta_description'],
            'sidebar_position' => $inputs['sidebar_position'] ?? null,
            'sidebar_id' => $inputs['sidebar_id'] ?? null,
            'top_section_id' => $inputs['top_section_id'] ?? null,
            'bottom_section_id' => $inputs['bottom_section_id'] ?? null
        ]);

        $categ_id = DB::getPdo()->lastInsertId();

        $this->PostModel->regenerate_tree_ids();
        $this->PostModel->recount_categ_items($categ_id);

        return redirect($request->Url())->with('success', 'created');
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.posts.categ'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 
        $lang_id = $inputs['lang_id'] ?? default_lang()->id;

        if ($inputs['slug']) $slug = Str::slug($inputs['slug'], '-');
        else $slug = Str::slug($inputs['title'], '-');
        if (strlen($slug) < 3) return redirect(route('admin.posts.categ'))->with('error', 'length');
        if ($inputs['slug'] == 'uncategorized') $slug = 'uncategorized';

        if (DB::table('posts_categ')->where('slug', $slug)->where('lang_id', $lang_id)->where('id', '!=', $id)->exists()) return redirect(route('admin.posts.categ'))->with('error', 'duplicate');

        if ($request->has('active')) $active = 1;
        else $active = 0;

        // position
        if (!$inputs['position']) {
            if (!$inputs['parent_id']) $last_categ = DB::table('posts_categ')->whereNull('parent_id')->where('lang_id', $lang_id)->where('id', '!=', $id)->orderBy('position', 'desc')->first();
            else $last_categ = DB::table('posts_categ')->where('parent_id', $inputs['parent_id'])->where('lang_id', $lang_id)->where('id', '!=', $id)->orderBy('position', 'desc')->first();
            $last_pos = $last_categ->position ?? 0;
            $next_pos = $last_pos + 1;
        } else $next_pos = $inputs['position'];

        DB::table('posts_categ')
            ->where('id', $id)
            ->update([
                'lang_id' => $lang_id,
                'title' => $inputs['title'],
                'parent_id' => $inputs['parent_id'] ?? null,
                'slug' => $slug,
                'description' => $inputs['description'],
                'active' => $active,
                'position' => $next_pos,
                'icon' => $inputs['icon'],
                'badges' => $inputs['badges'],
                'meta_title' => $inputs['meta_title'],
                'meta_description' => $inputs['meta_description'],
                'sidebar_position' => $inputs['sidebar_position'] ?? null,
                'sidebar_id' => $inputs['sidebar_id'] ?? null,
                'top_section_id' => $inputs['top_section_id'] ?? null,
                'bottom_section_id' => $inputs['bottom_section_id'] ?? null
            ]);

        $categ = DB::table('posts_categ')->where('id', $id)->first();
        DB::table('posts_categ')->where('parent_id', $categ->id)->update(['lang_id' => $lang_id]);
        DB::table('posts')->where('categ_id', $categ->id)->update(['lang_id' => $lang_id]);

        $this->PostModel->regenerate_tree_ids();
        $this->PostModel->recount_all_categs_items();

        return redirect(route('admin.posts.categ'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        DB::table('posts_categ')->where('id', $id)->delete();
        DB::table('posts_categ')->where('parent_id', $id)->delete();
        DB::table('posts')->where('categ_id', $id)->update(['categ_id' => null]);

        $this->PostModel->regenerate_tree_ids();
        $this->PostModel->recount_all_categs_items();

        return redirect(route('admin.posts.categ'))->with('success', 'deleted');
    }
}
