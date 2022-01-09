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

use App\Models\Core;
use App\Models\User;
use App\Models\Upload;
use App\Models\Doc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;

class DocsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();
        $this->DocModel = new Doc();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));

        $search_terms = $request->search_terms;
        $search_categ_id = $request->search_categ_id;

        $docs = DB::table('docs')
            ->leftJoin('docs_categ', 'docs.categ_id', '=', 'docs_categ.id')
            ->leftJoin('users', 'docs.user_id', '=', 'users.id')
            ->select('docs.*', 'docs_categ.label as categ_label', 'users.name as author_name',  'users.avatar as author_avatar',)
            ->orderBy('id', 'desc');

        if ($search_terms) $docs = $docs->where(function ($query) use ($search_terms) {
            $query->where('docs.label', 'like', "%$search_terms%")
                ->orWhere('docs.search_terms', 'like', "%$search_terms%");
        });

        if ($search_categ_id) {
            $categ = DB::table('docs_categ')->where('id', $search_categ_id)->first();
            $categ_tree_ids = $categ->tree_ids ?? null;
            if ($categ_tree_ids) $categ_tree_ids_array = explode(',', $categ_tree_ids);
            $docs = $docs->whereIn('docs.categ_id', $categ_tree_ids_array);
        }

        $docs = $docs->paginate(20);

        $categories = Doc::whereNull('parent_id')
            ->with('childCategories')
            ->select('docs_categ.*')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'docs.docs',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'search_terms' => $search_terms,
            'search_categ_id' => $search_categ_id,
            'docs' => $docs,
            'categories' => $categories,
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
            'view_file' => 'docs.create',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'categories' => $categories,
        ]);
    }


    /**
     * Create new item
     */
    public function store(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (!check_access('docs')) return redirect(route('admin'));

        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'categ_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.docs.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if (DB::table('docs')->where('label', $inputs['label'])->exists()) return redirect(route('admin.docs.show', ['id' => $id]))->with('error', 'duplicate');

        if ($request->has('featured')) $featured = 1;
        else $featured = 0;
        if ($request->has('active')) $active = 1;
        else $active = 0;

        // position
        if (!$inputs['position']) {
            $last_article = DB::table('docs')->where('categ_id', $inputs['categ_id'])->orderBy('position', 'desc')->first();
            $last_pos = $last_article->position ?? 0;
            $next_pos = $last_pos + 1;
        } else $next_pos = $inputs['position'];

        DB::table('docs')->insert([
            'user_id' => Auth::user()->id,
            'label' => $inputs['label'],
            'categ_id' => $inputs['categ_id'],
            'active' => $active,
            'featured' => $featured,
            'position' => $next_pos,
            'created_at' => now(),
            'visibility' => $inputs['visibility'],
        ]);

        $doc_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $title = $request['title_' . $lang->id];
            $search_terms = $request['search_terms_' . $lang->id];
            $slug = $request['slug_' . $lang->id];

            if ($slug) $slug = Str::slug($request['slug_' . $lang->id], '-');
            else $slug = Str::slug($request['title_' . $lang->id], '-');

            if (DB::table('docs_content')->where('slug', $slug)->where('lang_id', $lang->id)->exists()) $slug =  $slug . "-" . $doc_id;

            DB::table('docs_content')->insert(['doc_id' => $doc_id, 'lang_id' => $lang->id, 'title' => $title, 'search_terms' => $search_terms, 'slug' => $slug]);
        }

        $this->DocModel->recount_categ_items($inputs['categ_id']);

        return redirect(route('admin.docs.content', ['id' => $doc_id]))->with('success', 'created');
    }



    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));

        $doc = DB::table('docs')->where('id', $request->id)->first();
        if (!$doc) return redirect(route('admin'));

        $langs = DB::table('sys_lang')
            ->select(
                'sys_lang.*',
                DB::raw('(SELECT title FROM docs_content WHERE docs_content.lang_id = sys_lang.id AND doc_id = ' . $doc->id . ') as title'),
                DB::raw('(SELECT slug FROM docs_content WHERE docs_content.lang_id = sys_lang.id AND doc_id = ' . $doc->id . ') as slug'),
                DB::raw('(SELECT search_terms FROM docs_content WHERE docs_content.lang_id = sys_lang.id AND doc_id = ' . $doc->id . ') as search_terms'),
            )
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        $categories = Doc::whereNull('parent_id')
            ->with('childCategories')
            ->select('docs_categ.*')
            ->orderBy('label', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'docs.update',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'doc' => $doc,
            'langs' => $langs,
            'categories' => $categories,
        ]);
    }



    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (!check_access('docs')) return redirect(route('admin'));

        $id = $request->id;
        $doc = DB::table('docs')->where('id', $id)->first();
        if (!$doc) return redirect(route('admin'));
        $original_categ_id = $doc->categ_id; // used if article change category

        // check permission
        if ($this->logged_user_role != 'admin' && check_access('docs', 'author') && $this->logged_user_id != $doc->user_id) return redirect(route('admin'));

        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'categ_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.docs'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('featured')) $featured = 1;
        else $featured = 0;

        if (DB::table('docs')->where('label', $inputs['label'])->where('id', '!=', $id)->exists()) return redirect(route('admin.docs.show', ['id' => $id]))->with('error', 'duplicate');

        DB::table('docs')->where('id', $id)->update(['label' => $inputs['label'], 'categ_id' => $inputs['categ_id'], 'visibility' => $inputs['visibility'] ?? null, 'position' => $inputs['position'] ?? null, 'active' => $active, 'featured' => $featured]);

        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $title = $request['title_' . $lang->id];
            $search_terms = $request['search_terms_' . $lang->id];
            $slug = $request['slug_' . $lang->id];

            if ($slug) $slug = Str::slug($request['slug_' . $lang->id], '-');
            else $slug = Str::slug($request['title_' . $lang->id], '-');

            if (DB::table('docs_content')->where('slug', $slug)->where('lang_id', $lang->id)->where('doc_id', '!=', $id)->exists()) {
                //return redirect(route('admin.docs.show', ['id' => $id]))->with('error', 'duplicate');  
                $slug =  $slug . "-" . $id;
            }

            DB::table('docs_content')
                ->updateOrInsert(
                    ['doc_id' => $id, 'lang_id' => $lang->id],
                    ['title' => $title, 'search_terms' => $search_terms, 'slug' => $slug]
                );
        }

        $this->DocModel->recount_categ_items($inputs['categ_id']);
        $this->DocModel->recount_categ_items($original_categ_id); // for original categfory, if article change category

        return redirect(route('admin.docs'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (!check_access('docs')) return redirect(route('admin'));

        $id = $request->id;
        $doc = DB::table('docs')->where('id', $id)->first();
        if (!$doc) return redirect(route('admin'));

        // check permission        
        if ($this->logged_user_role != 'admin' && check_access('docs', 'author') && $this->logged_user_id != $doc->user_id) return redirect(route('admin'));

        DB::table('docs')->where('id', $id)->delete();
        DB::table('website_media')->where('module', 'docs')->where('content_id', $id)->delete();

        $this->DocModel->recount_categ_items($doc->categ_id);

        return redirect(route('admin.docs'))->with('success', 'deleted');
    }


    /**
     * Show form to edit content     
     */
    public function content(Request $request)
    {

        $id = $request->id;

        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));

        $doc = DB::table('docs')->where('id', $id)->first();
        if (!$doc) return redirect(route('admin'));

        $block_types = DB::table('blocks_types')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'docs.content',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'doc' => $doc,
            'block_types' => $block_types,
            'module' => 'docs',
        ]);
    }


    /**
     * Update article content   
     */
    public function update_content(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs')) return redirect(route('admin'));
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        $id = $request->id;
        $doc = DB::table('docs')->where('id', $id)->first();
        if (!$doc) return redirect(route('admin.docs.content', ['id' => $id]));

        $type_id = $inputs['type_id'];

        if (!$type_id) return redirect(route('admin.docs.content', ['id' => $id]));

        $last_pos = DB::table('blocks')->where('module', 'docs')->where('content_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'module' => 'docs',
            'content_id' => $doc->id,
            'position' => $position,
            'created_at' => now(),
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        Core::regenerate_content_blocks('docs', $id);

        return redirect(route('admin.docs.content', ['id' => $id]))->with('success', 'updated');
    }



    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {

        if (!check_admin_module('docs')) return redirect(route('admin'));
        if (!check_access('docs', 'manager')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;  // article ID
        $block_id = $request->block_id;

        $doc = DB::table('docs')->where('id', $id)->first();
        if (!$doc) return redirect(route('admin'));

        // regenerate content blocks and add blocks in module table (for database performance)
        Core::regenerate_content_blocks('docs', $id);

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        return redirect(route('admin.docs.content', ['id' => $id]))->with('success', 'deleted');
    }


    /**
     * Show block
     */
    public function block(Request $request)
    {
        $block = DB::table('blocks')
            ->leftJoin('blocks_types', 'blocks.type_id', '=', 'blocks_types.id')
            ->select('blocks.*', 'blocks_types.type as type', 'blocks_types.label as type_label')
            ->where('blocks.id', $request->id)
            ->first();
        if (!$block) abort(404);

        $langs = DB::table('sys_lang')
            ->select(
                'sys_lang.*',
                DB::raw('(SELECT content FROM blocks_content WHERE blocks_content.lang_id = sys_lang.id AND block_id = ' . $block->id . ') as block_content'),
                DB::raw('(SELECT header FROM blocks_content WHERE blocks_content.lang_id = sys_lang.id AND block_id = ' . $block->id . ') as block_header')
            )
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();


        return view('admin/account', [
            'view_file' => 'blocks.simple-blocks.' . $block->type,
            'active_menu' => 'website',
            'active_submenu' => 'docs',
            'block' => $block,
            'langs' => $langs,
            'referer' => request()->headers->get('referer'),
        ]);
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
                ->where('module', 'docs')
                ->where('content_id', $content_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        Core::regenerate_content_blocks('docs', $content_id);
    }


    /**
     * SEO
     */
    public function seo()
    {
        return view('admin/account', [
            'view_file' => 'docs.seo',
            'active_menu' => 'website',
            'active_submenu' => 'docs',
        ]);
    }


    public function update_seo(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $module_id = DB::table('sys_modules')->where('module', 'docs')->value('id');

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_modules_meta')->updateOrInsert(['module_id' => $module_id, 'lang_id' => $lang->id], ['meta_title' => $request['meta_title_' . $lang->id], 'meta_description' => $request['meta_description_' . $lang->id]]);
        }

        return redirect($request->Url())->with('success', 'updated');
    }
}
