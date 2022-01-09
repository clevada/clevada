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

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Doc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;

class DocsCategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->DocModel = new Doc();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!$this->logged_user_role == 'admin') return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        $categories = Doc::whereNull('parent_id')
            ->select(
                'docs_categ.*',
                DB::raw('(SELECT label FROM sys_global_sections WHERE docs_categ.top_section_id = sys_global_sections.id) as top_section_label'),
                DB::raw('(SELECT label FROM sys_global_sections WHERE docs_categ.bottom_section_id = sys_global_sections.id) as bottom_section_label'),
                DB::raw('(SELECT label FROM sys_sidebars WHERE docs_categ.sidebar_id = sys_sidebars.id) as sidebar_label')
            )
            ->with('childCategories')
            ->orderBy('docs_categ.active', 'desc')
            ->orderBy('docs_categ.position', 'asc')
            ->orderBy('docs_categ.label', 'asc')
            ->paginate(10);

        $count_categories = DB::table('docs_categ')->count();

        return view('admin/account', [
            'view_file' => 'docs.categories',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'categories' => $categories,
            'count_categories' => $count_categories,
        ]);
    }


    /**
     * Show form to add new resource
     */
    public function create()
    {
        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));

        $categories = Doc::whereNull('parent_id')->with('childCategories')
            ->orderBy('docs_categ.active', 'desc')
            ->orderBy('position', 'asc')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'docs.create-categ',
            'active_menu' => 'help',
            'active_submenu' => 'docs',
            'categories' => $categories,
        ]);
    }


    /**
     * Show form to add update resource
     */
    public function show(Request $request)
    {
        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));

        $id = $request->id;

        $categ = DB::table('docs_categ')->where('id', $id)->first();
        if (!$categ) return redirect(route('admin.docs.categ'));

        $categories = Doc::whereNull('parent_id')->with('childCategories')
            ->orderBy('docs_categ.active', 'desc')
            ->orderBy('position', 'asc')
            ->orderBy('label', 'asc')
            ->get();

        $langs = DB::table('sys_lang')
            ->select(
                'sys_lang.*',
                DB::raw('(SELECT title FROM docs_categ_content WHERE docs_categ_content.lang_id = sys_lang.id AND categ_id = ' . $id . ') as title'),
                DB::raw('(SELECT slug FROM docs_categ_content WHERE docs_categ_content.lang_id = sys_lang.id AND categ_id = ' . $id . ') as slug'),
                DB::raw('(SELECT description FROM docs_categ_content WHERE docs_categ_content.lang_id = sys_lang.id AND categ_id = ' . $id . ') as description'),
                DB::raw('(SELECT meta_title FROM docs_categ_content WHERE docs_categ_content.lang_id = sys_lang.id AND categ_id = ' . $id . ') as meta_title'),
                DB::raw('(SELECT meta_description FROM docs_categ_content WHERE docs_categ_content.lang_id = sys_lang.id AND categ_id = ' . $id . ') as meta_description'),
            )
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'docs.update-categ',
            'active_menu' => 'help',
            'active_submenu' => 'docs',
            'categ' => $categ,
            'langs' => $langs,
            'categories' => $categories,
        ]);
    }

    /**
     * Create new resource
     */
    public function store(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.docs.categ.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        $label = $inputs['label'];
        if (DB::table('docs_categ')->where('label', $label)->exists()) return redirect(route('admin.docs.categ.create'))->with('error', 'duplicate');

        // position
        if (!$inputs['position']) {
            if (!$inputs['parent_id']) $last_categ = DB::table('docs_categ')->whereNull('parent_id')->orderBy('position', 'desc')->first();
            else $last_categ = DB::table('docs_categ')->where('parent_id', $inputs['parent_id'])->orderBy('position', 'desc')->first();
            $last_pos = $last_categ->position ?? 0;
            $next_pos = $last_pos + 1;
        } else $next_pos = $inputs['position'];

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('featured')) $featured = 1;
        else $featured = 0;

        DB::table('docs_categ')->insert([
            'label' => $inputs['label'],
            'parent_id' => $inputs['parent_id'] ?? null,
            'active' => $active,
            'featured' => $featured,
            'position' => $next_pos,
            'icon' => $inputs['icon'],
        ]);

        $categ_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            $post_key_title = 'title_' . $lang->id;
            $post_key_slug = 'slug_' . $lang->id;
            $post_key_description = 'description_' . $lang->id;
            $post_key_meta_title = 'meta_title_' . $lang->id;
            $post_key_meta_description = 'meta_description_' . $lang->id;

            if (!(empty($_POST[$post_key_title]))) {
                if ($inputs["$post_key_slug"])
                    $slug = Str::slug($inputs["$post_key_slug"], '-');
                else
                    $slug = Str::slug($inputs["$post_key_title"], '-');

                DB::table('docs_categ_content')->insert([
                    'categ_id' => $categ_id,
                    'lang_id' => $lang->id,
                    'title' => $inputs["$post_key_title"],
                    'description' => $inputs["$post_key_description"],
                    'slug' => $slug,
                    'meta_title' => $inputs["$post_key_meta_title"],
                    'meta_description' => $inputs["$post_key_meta_description"]
                ]);
            }
        }

        $this->DocModel->regenerate_tree_ids();
        $this->DocModel->recount_categ_items($categ_id);

        return redirect(route('admin.docs.categ'))->with('success', 'created');
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.docs.categ.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        $label = $inputs['label'];
        if (DB::table('docs_categ')->where('label', $label)->where('id', '!=', $id)->exists()) return redirect(route('admin.docs.categ.create'))->with('error', 'duplicate');

        // position
        if (!$inputs['position']) {
            if (!$inputs['parent_id']) $last_categ = DB::table('docs_categ')->whereNull('parent_id')->where('id', '!=', $id)->orderBy('position', 'desc')->first();
            else $last_categ = DB::table('docs_categ')->where('parent_id', $inputs['parent_id'])->where('id', '!=', $id)->orderBy('position', 'desc')->first();
            $last_pos = $last_categ->position ?? 0;
            $next_pos = $last_pos + 1;
        } else $next_pos = $inputs['position'];

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('featured')) $featured = 1;
        else $featured = 0;

        DB::table('docs_categ')
            ->where('id', $id)
            ->update([
                'label' => $inputs['label'],
                'parent_id' => $inputs['parent_id'] ?? null,
                'active' => $active,
                'featured' => $featured,
                'position' => $next_pos,
                'icon' => $inputs['icon'],
            ]);

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            $post_key_title = 'title_' . $lang->id;
            $post_key_slug = 'slug_' . $lang->id;
            $post_key_description = 'description_' . $lang->id;
            $post_key_meta_title = 'meta_title_' . $lang->id;
            $post_key_meta_description = 'meta_description_' . $lang->id;

            if (!(empty($_POST[$post_key_title]))) {
                if ($inputs["$post_key_slug"])
                    $slug = Str::slug($inputs["$post_key_slug"], '-');
                else
                    $slug = Str::slug($inputs["$post_key_title"], '-');

                DB::table('docs_categ_content')->updateOrInsert(
                    ['categ_id' => $id, 'lang_id' => $lang->id],
                    ['title' => $inputs["$post_key_title"], 'slug' => $slug, 'description' => $inputs["$post_key_description"], 'meta_title' => $inputs["$post_key_meta_title"], 'meta_description' => $inputs["$post_key_meta_description"]]
                );
            }
        }

        $this->DocModel->regenerate_tree_ids();
        $this->DocModel->recount_categ_items($id);

        return redirect(route('admin.docs.categ'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        DB::table('docs_categ')->where('id', $id)->delete();
        DB::table('docs_categ')->where('parent_id', $id)->delete();
        DB::table('docs_categ_content')->where('categ_id', $id)->delete();
        DB::table('docs')->where('categ_id', $id)->update(['categ_id' => null]);

        $this->DocModel->regenerate_tree_ids();
        $this->DocModel->recount_categ_items($id);

        return redirect(route('admin.docs.categ'))->with('success', 'deleted');
    }
}
