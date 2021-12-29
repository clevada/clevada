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
use App\Models\Upload;
use App\Models\Core;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();
        $this->PostModel = new Post();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect('/');
            return $next($request);
        });
    }

    /**
     * Display all posts
     */
    public function index(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        $search_terms = $request->search_terms;
        $search_status = $request->search_status;
        $search_categ_id = $request->search_categ_id;
        $search_lang_id = $request->search_lang_id;

        $posts = DB::table('posts')
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_categ', 'posts.categ_id', '=', 'posts_categ.id')
            ->leftJoin('sys_lang', 'posts.lang_id', '=', 'sys_lang.id')
            ->select(
                'posts.*',
                'sys_lang.name as lang_name',
                'sys_lang.code as lang_code',
                'sys_lang.status as lang_status',
                'users.name as author_name',
                'users.email as author_email',
                'users.avatar as author_avatar',
                DB::raw("(SELECT count(*) FROM posts_comments WHERE posts.id = posts_comments.post_id) count_comments"),
                DB::raw("(SELECT count(*) FROM posts_likes WHERE posts.id = posts_likes.post_id) count_likes")
            );

        if ($search_status)
            $posts = $posts->where('posts.status', 'like', $search_status);

        if ($search_terms) $posts = $posts->where(function ($query) use ($search_terms) {
            $query->where('posts.title', 'like', "%$search_terms%")
                ->orWhere('posts.search_terms', 'like', "%$search_terms%");
        });

        if ($search_categ_id) {
            $categ = DB::table('posts_categ')->where('id', $search_categ_id)->first();
            $categ_tree_ids = $categ->tree_ids ?? null;
            if ($categ_tree_ids) $categ_tree_ids_array = explode(',', $categ_tree_ids);
            else $categ_tree_ids_array = array();
            $posts = $posts->whereIn('posts.categ_id', $categ_tree_ids_array);
        }

        if ($search_lang_id)
            $posts = $posts->where('posts.lang_id', $search_lang_id);

        $posts = $posts->orderBy('posts.featured', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Post::whereNull('parent_id')
            ->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang')
            ->orderBy('title', 'asc')->get();

        $count_pending = DB::table('posts')->where('status', 'pending')->count();

        return view('admin/account', [
            'view_file' => 'posts.posts',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'search_terms' => $search_terms,
            'search_status' => $search_status,
            'search_categ_id' => $search_categ_id,
            'search_lang_id' => $search_lang_id,
            'posts' => $posts,
            'categories' => $categories,
            'count_pending' => $count_pending,
        ]);
    }


    /**
     * Show page to add new resource
     */
    public function create()
    {
        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        $categories = Post::whereNull('parent_id')
            ->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang')
            ->orderBy('title', 'asc')->get();

        if (count($categories) == 0) return redirect(route('admin.posts.categ'))->with('error', 'create_post_no_categ');

        return view('admin/account', [
            'view_file' => 'posts.create',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'categories' => $categories,
        ]);
    }


    /**
     * Create new resource
     */
    public function store(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'categ_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.posts.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $categ = DB::table('posts_categ')
            ->where('id', $inputs['categ_id'])
            ->first();

        if ($inputs['slug']) $slug = Str::slug($inputs['slug'], '-');
        else $slug = Str::slug($inputs['title'], '-');

        // check if there is another post with this slug 
        if (DB::table('posts')->where('slug', $slug)->where('lang_id', $categ->lang_id)->exists()) {
            // if exists, add post ID in slug
            $latestID = DB::table('posts')->latest('id')->value('id');
            $slug = $slug . '-' . ($latestID + 1);
        }

        if ($request->input('featured') == 'on') $featured = 1;
        if ($request->input('disable_comments') == 'on') $disable_comments = 1;
        if ($request->input('disable_likes') == 'on') $disable_likes = 1;

        DB::table('posts')->insert([
            'lang_id' => $categ->lang_id ?? null,
            'title' => $inputs['title'],
            'categ_id' => $inputs['categ_id'],
            'slug' => $slug,
            'user_id' => Auth::user()->id,
            'summary' => $inputs['summary'],
            'status' => 'draft',
            'search_terms' => $inputs['search_terms'],
            'meta_title' => $inputs['meta_title'],
            'meta_description' => $inputs['meta_description'],
            'disable_comments' => $disable_comments ?? 0,
            'disable_likes' => $disable_likes ?? 0,
            'featured' => $featured ?? 0,
            'created_at' => now()
        ]);

        $id = DB::getPdo()->lastInsertId();        

        // process tags
        if ($inputs['tags'] and $inputs['status'] == 'active') {
            $tags_array = explode(',', $inputs['tags']);
            foreach ($tags_array as $tag) {
                $this->PostModel->add_tag($tag, $id);
            }
        }

        // process image        
        if ($request->hasFile('image')) {
            $image_db = $this->UploadModel->upload_image($request, 'image');
            DB::table('posts')->where('id', $id)->update(['image' => $image_db]);
        }

        $this->PostModel->recount_categ_items($inputs['categ_id']);

        return redirect(route('admin.posts.show', ['id' => $id]))->with('success', 'created');
    }


    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        $id = $request->id;

        $post = DB::table('posts')
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name as author_name', 'users.avatar as author_avatar')
            ->where('posts.id', $id)
            ->first();
        if (!$post) redirect(route('admin.posts'));

        $tags_array = DB::table('posts_tags')
            ->where('post_id', $id)
            ->orderBy('tag', 'asc')
            ->pluck('tag')->toArray();

        $tags = implode(", ", $tags_array);

        $categories = Post::whereNull('parent_id')
            ->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang')
            ->orderBy('title', 'asc')->get();

        $block_types = DB::table('blocks_types')
            ->orderBy('position', 'asc')
            ->get();

        $author_count_published_posts = DB::table('posts')->where('user_id', $post->user_id)->where('status', 'active')->count();
        $author_count_pending_posts = DB::table('posts')->where('user_id', $post->user_id)->where('status', 'pending')->count();
        $author_count_soft_reject_posts = DB::table('posts')->where('user_id', $post->user_id)->where('status', 'soft_reject')->count();
        $author_count_hard_reject_posts = DB::table('posts')->where('user_id', $post->user_id)->where('status', 'hard_reject')->count();                    

        return view('admin/account', [
            'view_file' => 'posts.update',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'post' => $post,
            'tags' => $tags,
            'categories' => $categories,
            'block_types' => $block_types,
            'author_count_published_posts' => $author_count_published_posts,
            'author_count_pending_posts' => $author_count_pending_posts,
            'author_count_soft_reject_posts' => $author_count_soft_reject_posts,
            'author_count_hard_reject_posts' => $author_count_hard_reject_posts,
        ]);
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        $id = $request->id;
        
        $post = DB::table('posts')->where('id', $id)->first();
        if (!$post) return redirect(route('admin'));

        // check if author own post
        if (check_access('posts', 'author') && (logged_user()->role) != 'admin' && $post->user_id != Auth::user()->id) return redirect(route('admin.posts'));

        // check if contributor own post
        if (check_access('posts', 'contributor') && (logged_user()->role) != 'admin' && $post->user_id != Auth::user()->id) return redirect(route('admin.posts'));

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'categ_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.posts.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $categ = DB::table('posts_categ')
            ->where('id', $inputs['categ_id'])
            ->first();

        if ($inputs['slug']) $slug = Str::slug($inputs['slug'], '-');
        else $slug = Str::slug($inputs['title'], '-');

        // check if there is another post with this slug (same language)
        if (DB::table('posts')->where('slug', $slug)->where('lang_id', $categ->lang_id)->where('id', '!=', $id)->exists()) {
            // if exists, add post ID in slug
            $slug = $slug . '-' . $id;
        }

        if ($request->input('featured') == 'on') $featured = 1;
        if ($request->input('disable_comments') == 'on') $disable_comments = 1;
        if ($request->input('disable_likes') == 'on') $disable_likes = 1;

        DB::table('posts')
            ->where('id', $id)
            ->update([
                'lang_id' => $categ->lang_id ?? null,
                'title' => $inputs['title'],
                'categ_id' => $inputs['categ_id'],
                'slug' => $slug,
                'summary' => $inputs['summary'],
                'status' => $inputs['status'],
                'search_terms' => $inputs['search_terms'],
                'meta_title' => $inputs['meta_title'],
                'meta_description' => $inputs['meta_description'],
                'disable_comments' => $disable_comments ?? 0,
                'disable_likes' => $disable_likes ?? 0,
                'featured' => $featured ?? 0,
                'minutes_to_read' => estimated_reading_time($id),
                'updated_at' => now(),
                'updated_by_user_id' => Auth::user()->id,
                'reject_reason' => $inputs['reject_reason'],
            ]);

        // process tags
        DB::table('posts_tags')->where('post_id', $id)->delete(); // delete existing post tags
        if ($inputs['tags'] and $inputs['status'] == 'active') {
            $tags_array = explode(',', $inputs['tags']);
            foreach ($tags_array as $tag) {
                $this->PostModel->add_tag($tag, $id);
            }
        }

        // process image        
        if ($request->hasFile('image')) {
            $image_db = $this->UploadModel->upload_image($request, 'image');
            DB::table('posts')->where('id', $id)->update(['image' => $image_db]);
        }

        $this->PostModel->recount_categ_items($inputs['categ_id']);

        return redirect(route('admin.posts'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $q = DB::table('posts')
            ->where('id', $id)
            ->first();

        // delete main image
        $post = DB::table('posts')->where('id', $id)->first();
        if ($post->image) delete_image($post->image);

        // delete content blocks
        $blocks = DB::table('blocks')->where('module', 'posts')->where('content_id', $id)->get();
        foreach ($blocks as $block) {
            DB::table('blocks')->where('id', $block->id)->delete();
            DB::table('blocks_content')->where('block_id', $block->id)->delete();
        }

        DB::table('posts')->where('id', $id)->delete(); // delete post
        DB::table('posts_comments')->where('post_id', $id)->delete(); // delete comments
        DB::table('posts_likes')->where('post_id', $id)->delete(); // delete likes
        DB::table('posts_tags')->where('post_id', $id)->delete(); // delete tags    

        $this->PostModel->recount_categ_items($q->categ_id);

        return redirect(route('admin.posts'))->with('success', 'deleted');
    }


    /**
     * Remove post main image
     */
    public function delete_main_image(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $id = $request->id; // post ID

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        // delete image
        $post = DB::table('posts')->where('id', $id)->first();
        if ($post->image) delete_image($post->image);

        DB::table('posts')->where('id', $id)->update(['image' => null]);

        return redirect(route('admin.posts.show', ['id' => $id]))->with('success', 'main_image_deleted');
    }


    /**
     * Update post content   
     */
    public function update_content(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        $id = $request->id;
        $post = DB::table('posts')->where('id', $id)->first();
        if (!$post) return redirect(route('admin.posts'));

        $type_id = $inputs['type_id'];

        $last_pos = DB::table('blocks')->where('module', 'posts')->where('content_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'created_at' => now(),
            'module' => 'posts',
            'position' => $position,
            'content_id' => $post->id,
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        Core::regenerate_content_blocks('posts', $id);

        // update seconds to read
        DB::table('posts')->where('id', $id)->update(['minutes_to_read' => estimated_reading_time($id)]);

        return redirect(route('admin.blocks.show', ['id' => $block_id]));
    }



    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;  // post ID
        $block_id = $request->block_id;  // block ID

        $post = DB::table('posts')->where('id', $id)->first();
        if (!$post) return redirect(route('admin.posts'));

        // regenerate content blocks and add blocks in module table (for database performance)
        Core::regenerate_content_blocks('posts', $id);

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        // update seconds to read
        DB::table('posts')->where('id', $id)->update(['minutes_to_read' => estimated_reading_time($id)]);
        
        return redirect(route('admin.posts.show', ['id' => $id]))->with('success', 'deleted');
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
                ->where('module', 'posts')
                ->where('content_id', $content_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        Core::regenerate_content_blocks('posts', $content_id);
    }


    /**
     * Config
     */
    public function config()
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        return view('admin/account', [
            'view_file' => 'posts.config',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'menu_section' => 'config.general',
        ]);
    }


    /**
     * Update the specified resource     
     */
    public function update_config(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');
        
        Core::update_config($inputs);

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * SEO
     */
    public function seo()
    {
        return view('admin/account', [
            'view_file' => 'posts.seo',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'menu_section' => 'config.seo',
        ]);
    }


    public function update_seo(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $module_id = DB::table('sys_modules')->where('module', 'posts')->value('id');

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_modules_meta')->updateOrInsert(['module_id' => $module_id, 'lang_id' => $lang->id], ['meta_title' => $request['meta_title_' . $lang->id], 'meta_description' => $request['meta_description_' . $lang->id]]);
        }

        return redirect($request->Url())->with('success', 'updated');
    }
}
