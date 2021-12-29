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

namespace App\Http\Controllers\User;

use App\Models\Core;
use App\Models\User;
use App\Models\Upload;
use App\Models\Post;

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
        $this->config = Core::config();

        $this->categories = DB::table('posts_categ')
            ->where('active', 1)
            ->orderBy('title', 'asc')
            ->get();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;

            $role = $this->UserModel->get_role_from_id($this->role_id);
            if ($role != 'user') return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all user posts
     */
    public function index()
    {
        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        $posts = DB::table('posts')
            ->leftJoin('posts_categ', 'posts.categ_id', '=', 'posts_categ.id')
            ->leftJoin('sys_lang', 'posts.lang_id', '=', 'sys_lang.id')
            ->select('posts.*', 'sys_lang.name as lang_name', 'posts_categ.id as categ_id', 'posts_categ.title as categ_title')
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('frontend/builder/user-account', [
            'view_file' => 'posts.posts',
            'nav_menu' => 'posts',
            'posts' => $posts,
        ]);
    }


    /**
     * Show page to add new resource
     */
    public function create()
    {
        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        $categories = Post::whereNull('parent_id')
            ->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang')
            ->orderBy('title', 'asc')->get();

        if (count($categories) == 0) return redirect(route('user.posts.create'))->with('error', 'create_post_no_categ');

        return view('frontend/builder/user-account', [
            'view_file' => 'posts.create',
            'nav_menu' => 'posts',
            'categories' => $categories,
        ]);
    }


    /**
     * Create new resource
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) exit('Demo mode');

        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'categ_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('user.posts.create'))
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

        return redirect(route('user.posts.show', ['id' => $id]))->with('success', 'created');
    }


    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {
        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        $id = $request->id;

        $post = DB::table('posts')
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$post) return redirect(route('user.posts'));

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
            ->where('allow_to_users', 1)
            ->orderBy('position', 'asc')
            ->get();

        return view('frontend/builder/user-account', [
            'view_file' => 'posts.update',
            'nav_menu' => 'posts',
            'post' => $post,
            'tags' => $tags,
            'categories' => $categories,
            'block_types' => $block_types,
        ]);
    }




    /**
     * Update post content   
     */
    public function update_content(Request $request)
    {

        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('user'))->with('error', 'demo');

        $inputs = $request->except('_token');

        $id = $request->id;
        $post = DB::table('posts')->where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (!$post) return redirect(route('user.posts'));

        $type_id = $inputs['type_id'];

        $last_pos = DB::table('blocks')->where('module', 'posts')->where('content_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'created_at' => now(),
            'module' => 'posts',
            'position' => $position,
            'content_id' => $post->id,
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        Core::regenerate_content_blocks('posts', $id);

        // update seconds to read
        DB::table('posts')->where('id', $id)->update(['minutes_to_read' => estimated_reading_time($id)]);

        return redirect(route('user.posts.block', ['id' => $block_id]));
    }



    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {

        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('user'))->with('error', 'demo');

        $id = $request->id;  // post ID
        $block_id = $request->block_id;  // block ID

        $post = DB::table('posts')->where('id', $id)->first();
        if (!$post) return redirect(route('user.posts'));

        // regenerate content blocks and add blocks in module table (for database performance)
        Core::regenerate_content_blocks('posts', $id);

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        // update seconds to read
        DB::table('posts')->where('id', $id)->update(['minutes_to_read' => estimated_reading_time($id)]);

        return redirect(route('user.posts.show', ['id' => $id]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

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
    }


    /**
     * Show block   
     */
    public function block(Request $request)
    {

        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

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

        $block_module = DB::table('blocks')->where('id', $block->id)->value('module');

        return view('frontend/builder/user-account', [
            'view_file' => 'posts.block-' . $block->type,
            'nav_menu' => 'posts',

            'block' => $block,
            'langs' => $langs,
            'block_module' => $block_module,
            'referer' => request()->headers->get('referer'),

        ]);
    }



    /**
     * Update block content   
     */
    public function update_block(Request $request)
    {
        if (Auth::user()->posts_contributor != 1) return redirect(route('user.profile'));

        $id = $request->id;
        $block_type_id = $request->type_id;
        $referer = $request->referer;

        $type = DB::table('blocks_types')->where('id', $block_type_id)->value('type');

        $block_module = DB::table('blocks')->where('id', $id)->value('module');

        $inputs = $request->except('_token');


        // Extra content ALERT            
        if ($type == 'alert') {
            $block_extra = array('bg_color' => null, 'type' => null);
            if ($inputs['type'] ?? null) $block_extra['type'] = $inputs['type'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content EDITOR / VIDEO               
        if ($type == 'editor' || $type == 'video') {
            $block_extra = array('bg_color' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra = array('bg_color' => $inputs['bg_color']);
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content IMAGE / GALLERY        
        if ($type == 'image' || $type == 'gallery') {
            $block_extra = array('bg_color' => null, 'shaddow' => null, 'cols' => null);
            if ($inputs['shaddow'] ?? null) $block_extra['shaddow'] = $inputs['shaddow'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['cols'] ?? null) $block_extra['cols'] = $inputs['cols'] ?? 4;
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content BLOCKQUOTE       
        if ($type == 'blockquote') {
            $block_extra = array('bg_color' => null, 'shaddow' => null, 'use_avatar' => $inputs['use_avatar'] ?? null, 'avatar' => $request['existing_avatar'] ?? null);
            if ($inputs['use_avatar'] ?? null) {
                if ($request->hasFile('avatar')) {
                    $validator = Validator::make($request->all(), ['avatar' => 'mimes:jpeg,bmp,png,gif,webp']);
                    if (!$validator->fails()) {
                        $image_db = $this->UploadModel->avatar($request, 'avatar');
                        $block_extra['avatar'] = $image_db;
                    }
                }
            }
            if ($inputs['shaddow'] ?? null) $block_extra['shaddow'] = $inputs['shaddow'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // ***************************************************
        // Block CONTENT
        // ***************************************************
        $langs = DB::table('sys_lang')->get();

        foreach ($langs as $lang) {
            $content = null;

            // EDITOR 
            if ($type == 'editor') {
                $content = $request['content_' . $lang->id];
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // VIDEO
            if ($type == 'video') {
                $content = array('embed' => $request['embed_' . $lang->id], 'caption' => $request['caption_' . $lang->id]);
                $content = serialize($content);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }

            // IMAGE
            if ($type == 'image') {
                $image_db = null;
                if ($request->hasFile('image_' . $lang->id)) {
                    $validator = Validator::make($request->all(), ['image' => 'mimes:jpeg,jpg,png,gif,ico']);
                    if (!$validator->fails()) $image_db = $this->UploadModel->upload_file($request, 'image_' . $lang->id);
                }
                $content = array('image' => $image_db ?? $request['existing_image_' . $lang->id] ?? null, 'title' => $request['title_' . $lang->id], 'caption' => $request['caption_' . $lang->id], 'url' => $request['url_' . $lang->id]);
                $content = serialize($content);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }

            // GALLERY           
            if ($type == 'gallery') {
                $post_key_title = 'title_' . $lang->id;
                $post_key_image = 'image_' . $lang->id;
                $post_key_caption = 'caption_' . $lang->id;
                $post_key_position = 'position_' . $lang->id;
                $post_key_url = 'url_' . $lang->id;
                $post_key_existing_image = 'existing_image_' . $lang->id;
                $images_array_key = array();
                $counter_key = count(array_filter($_POST[$post_key_title]));

                $image_db = null;

                for ($i = 0; $i < $counter_key; $i++) {
                    $image_db = null;

                    if ($request->hasFile($post_key_image)) {

                        if ($file = $request->file($post_key_image)[$i] ?? null) {
                            $image_db = null;

                            $filename = $file->getClientOriginalName();
                            $validator = Validator::make($request->all(), [$filename => 'mimes:jpeg,bmp,png,gif,webp']);
                            if (!$validator->fails()) {

                                $width = '1200';
                                $height = '600';
                                $new_filename = Str::random(12) . '-' . $filename;
                                $subfolder = date("Ym");

                                if (!File::isDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder)) {
                                    File::makeDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder, 0777, true, true);
                                }

                                $path_large = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $new_filename;
                                $path_thumb = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . 'thumb_' . $new_filename;
                                $path_thumb_square = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . 'thumb_square_' . $new_filename;

                                Image::make($file)->resize($width, $height, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->save($path_large); // large image

                                Image::make($file)->resize(350, 350, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->save($path_thumb);  // thumb 

                                Image::make($file)->fit(350, 350)->save($path_thumb_square);  // thumb square

                                $image_db[$i] = $subfolder . DIRECTORY_SEPARATOR . $new_filename;
                            }
                        }
                    }

                    $images_array_key[$i] = array('title' => $inputs["$post_key_title"][$i], 'image' => $image_db[$i] ?? $inputs["$post_key_existing_image"][$i] ?? null, 'caption' => $inputs["$post_key_caption"][$i], 'position' => $inputs["$post_key_position"][$i] ?? 0, 'url' => $inputs["$post_key_url"][$i] ?? null);

                    // regenerate array and sort by position (asc)
                    if (count($images_array_key) > 1) {
                        $position = array_column($images_array_key, 'position');
                        array_multisort($position, SORT_ASC, $images_array_key);
                    }
                }

                $content = serialize($images_array_key);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }


            // ALERT
            if ($type == 'alert') {
                $post_key_title = 'title_' . $lang->id;
                $post_key_content = 'content_' . $lang->id;
                $content_array = array('title' => $inputs["$post_key_title"], 'content' => $inputs["$post_key_content"]);
                $content = serialize($content_array);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // BLOCKQUOTE
            if ($type == 'blockquote') {
                $post_key_source = 'source_' . $lang->id;
                $post_key_content = 'content_' . $lang->id;
                $content_array = array('source' => $inputs["$post_key_source"], 'content' => $inputs["$post_key_content"]);
                $content = serialize($content_array);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // IMPORTANT! For posts blocks, there are not multi-language content for each post (post is assigned to one language)
            if ($block_module == 'posts') break;
        } // end langs

        if ($inputs['hide'] ?? null) $hide = 1;
        DB::table('blocks')->where('id', $id)->update(['label' =>  $inputs['label'] ?? null, 'updated_at' => now(), 'hide' => $hide ?? 0]);

        // regenerate content blocks and add blocks in module table (for database performance)
        if ($block_module == 'posts') {
            $content_id = DB::table('blocks')->where('id', $id)->value('content_id');
            Core::regenerate_content_blocks($block_module, $content_id);

            // update seconds to read
            if ($block_module == 'posts') DB::table('posts')->where('id', $content_id)->update(['minutes_to_read' => estimated_reading_time($content_id)]);
        }

        if ($referer) return redirect($referer)->with('success', 'updated');
        else return redirect(route('user.posts'))->with('success', 'updated');
    }
}
