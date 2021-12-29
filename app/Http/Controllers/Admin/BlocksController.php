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
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;
use File;
use Image;

class BlocksController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();

        $this->roles = DB::table('users_roles')->where('active', 1)->orderBy('id', 'asc')->get();
        $this->role_id_internal = $this->UserModel->get_role_id_from_role('internal');
        $this->role_id_user = $this->UserModel->get_role_id_from_role('user');

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;
            $role = $this->UserModel->get_role_from_id($this->role_id);
            if (!($role == 'admin' || $role == 'internal')) return redirect('/');
            return $next($request);
        });
    }

    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {

        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

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

        // posts categories (used in posts block)
        $posts_categories = Post::whereNull('parent_id')
            ->with('childCategories')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang')
            ->orderBy('title', 'asc')->get();

        $block_module = DB::table('blocks')->where('id', $block->id)->value('module');

        // forms (used in form block)
        $forms = DB::table('forms')->where('active', 1)->orderBy('label', 'asc')->get();

        return view('admin/account', [
            'view_file' => 'blocks.update-' . $block->type,
            'active_menu' => 'website',
            'active_submenu' => 'blocks',
            'block' => $block,
            'langs' => $langs,
            'block_module' => $block_module,
            'referer' => request()->headers->get('referer'),

            'posts_categories' => $posts_categories ?? null, // posts categories (used in posts block)
            'forms' => $forms ?? null, // forms (used in form block)
        ]);
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {
        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        $id = $request->id;
        $block_type_id = $request->type_id;
        $referer = $request->referer;

        $type = DB::table('blocks_types')->where('id', $block_type_id)->value('type');

        $block_module = DB::table('blocks')->where('id', $id)->value('module');

        $inputs = $request->except('_token');

        // Extra content HERO            
        if ($type == 'hero') {
            $block_extra = array('bg_color' => null, 'image_position' => $request['image_position'], 'image' => $request['existing_image'] ?? null, 'cover_fixed' => null, 'cover_dark' => null, 'img_container_width' => $inputs['img_container_width'] ?? null, 'img_click' => null, 'title_font_size' => $inputs['title_font_size'] ?? null, 'text_font_size' => $inputs['text_font_size'] ?? null, 'font_color' => $inputs['font_color'] ?? null);

            if ($inputs['use_image'] ?? null) $block_extra['use_image'] = $inputs['use_image'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['shaddow'] ?? null) $block_extra['shaddow'] = $inputs['shaddow'];
            if ($inputs['cover_fixed'] ?? null) $block_extra['cover_fixed'] = $inputs['cover_fixed'];
            if ($inputs['cover_dark'] ?? null) $block_extra['cover_dark'] = $inputs['cover_dark'];
            if ($inputs['img_click'] ?? null) $block_extra['img_click'] = $inputs['img_click'];

            if ($request->hasFile('image')) {
                $validator = Validator::make($request->all(), ['image' => 'mimes:jpeg,jpg,png,gif,ico']);
                if (!$validator->fails()) {
                    if ($inputs['image_position'] == 'cover') $image_db = $this->UploadModel->upload_file($request, 'image');
                    else $image_db = $this->UploadModel->upload_image($request, 'image');
                    $block_extra['image'] = $image_db;
                }
            }

            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content DOWNLOAD            
        if ($type == 'download') {
            $block_extra = array('bg_color' => null, 'login_required' => null, 'file' => $request['existing_file'] ?? null, 'version' => $request['version'], 'release_date' => $request['release_date'] ?? null, 'download_btn_style' => $inputs['download_btn_style'] ?? null, 'hash' =>  $request['hash'] ?? Str::random(16));

            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['login_required'] ?? null) $block_extra['login_required'] = $inputs['login_required'];

            if ($request->hasFile('file')) {
                $file_db = $this->UploadModel->upload_file($request, 'file');
                $block_extra['file'] = $file_db;
            }

            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content SLIDER
        if ($type == 'slider') {
            $block_extra = array('bg_style' => $inputs['bg_style'], 'bg_color' => null, 'bg_image' => $request['existing_bg_image'] ?? null, 'cover_fixed' => null, 'cover_dark' => null, 'font_color' => $inputs['font_color'], 'title_font_size' => $inputs['title_font_size'], 'content_font_size' => $inputs['content_font_size'], 'delay_seconds' => $inputs['delay_seconds']);
            if ($inputs['bg_style'] == 'color') $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['cover_fixed'] ?? null) $block_extra['cover_fixed'] = $inputs['cover_fixed'];
            if ($inputs['cover_dark'] ?? null) $block_extra['cover_dark'] = $inputs['cover_dark'];

            if ($request->hasFile('bg_image')) {
                $validator = Validator::make($request->all(), ['bg_image' => 'mimes:jpeg,jpg,png,gif,ico']);
                if (!$validator->fails()) {
                    $image_db = $this->UploadModel->upload_file($request, 'bg_image');
                    $block_extra['bg_image'] = $image_db;
                }
            }

            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content ACCORDION            
        if ($type == 'accordion') {
            $block_extra = array('bg_color' => null, 'title_size' => $inputs['title_size'], 'title_color' => $inputs['title_color'], 'title_bg_color' => $inputs['title_bg_color'], 'font_color' => $inputs['font_color'] ?? null, 'collapse_first_item' => $inputs['collapse_first_item'] ?? null);
            if ($inputs['use_custom_font_color'] ?? null) $block_extra['use_custom_font_color'] = $inputs['use_custom_font_color'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content ALERT            
        if ($type == 'alert') {
            $block_extra = array('bg_color' => null, 'type' => null);
            if ($inputs['type'] ?? null) $block_extra['type'] = $inputs['type'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content EDITOR            
        if ($type == 'editor') {
            $block_extra = array('bg_color' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra = array('bg_color' => $inputs['bg_color']);
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

         // Extra content VIDEO               
         if ($type == 'video') {
            $block_extra = array('bg_color' => null, 'full_width_responsive' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra = array('bg_color' => $inputs['bg_color']);
            if ($inputs['full_width_responsive'] ?? null) $block_extra['full_width_responsive'] = $inputs['full_width_responsive'];
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

        // Extra content LINKS        
        if ($type == 'links') {
            $block_extra = array('bg_color' => null, 'new_tab' => null, 'display_style' =>  $inputs['display_style']);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['new_tab'] ?? null) $block_extra['new_tab'] = $inputs['new_tab'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content MAPS       
        if ($type == 'map') {
            $block_extra = array('height' => $inputs['height'] ?? 400, 'zoom' => $inputs['zoom'] ?? 16, 'address' => $inputs['address']);
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


        // POSTS            
        if ($type == 'posts') {
            $block_extra = array('items' => $inputs['items'], 'content' => $inputs['content'], 'categ_badge' => $inputs['categ_badge'], 'style' => $inputs['style'], 'columns' => $inputs['columns'], 'columns_style' => $inputs['columns_style'], 'card_border_color' => $inputs['card_border_color'], 'show_image' => $inputs['show_image'], 'show_date' => $inputs['show_date'], 'show_author' => $inputs['show_author'], 'titles_font_size' => $inputs['titles_font_size'], 'show_summary' => $inputs['show_summary'], 'show_time_read' => $inputs['show_time_read'], 'show_comments_count' => $inputs['show_comments_count'], 'show_read_more' => $inputs['show_read_more']);

            if ($inputs['card_use_custom_bg'] ?? null) $block_extra['card_bg_color'] = $inputs['card_bg_color'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['img-shaddow'] ?? null) $block_extra['img-shaddow'] = $inputs['img-shaddow'];

            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // FORM
        if ($type == 'form') {
            $block_extra = array('bg_color' => null, 'form_id' => $inputs['form_id'] ?? null, 'submit_btn_style' => $inputs['submit_btn_style'] ?? null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // ADS
        if ($type == 'ads') {
            $block_extra = array('bg_color' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // CUSTOM
        if ($type == 'custom') {
            $block_extra = array('bg_color' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // INCLUDE
        if ($type == 'include') {
            if ($request->hasFile('file')) {
                $file = $request->file;
                $originalname = $file->getClientOriginalName();
                $new_filename = date("Ymd-His") . '-' . $originalname;

                $path = public_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'custom-files' . DIRECTORY_SEPARATOR . $new_filename;
                move_uploaded_file($file, $path);
            }

            $block_extra = array('bg_color' => null, 'file' => $new_filename ?? $request['existing_file'] ?? null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table('blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // ***************************************************
        // Block CONTENT
        // ***************************************************
        $langs = DB::table('sys_lang')->get();

        // UPDATE CONTENT
        foreach ($langs as $lang) {
            $content = null;

            // EDITOR 
            if ($type == 'editor') {
                $content = $request['content_' . $lang->id];
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // CUSTOM
            if ($type == 'custom') {
                $content = $request['content_' . $lang->id];
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // ADS
            if ($type == 'ads') {
                $content = $request['content_' . $lang->id];
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }

            // VIDEO
            if ($type == 'video') {
                $content = array('embed' => $request['embed_' . $lang->id], 'caption' => $request['caption_' . $lang->id]);
                $content = serialize($content);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
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
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }


            // HERO
            if ($type == 'hero') {
                $content = array('title' => $request['title_' . $lang->id], 'content' => $request['content_' . $lang->id], 'btn1_label' => $request['btn1_label_' . $lang->id], 'btn1_style' => $request['btn1_style_' . $lang->id], 'btn1_info' => $request['btn1_info_' . $lang->id], 'btn2_label' => $request['btn2_label_' . $lang->id], 'btn2_style' => $request['btn2_style_' . $lang->id], 'btn1_url' => $request['btn1_url_' . $lang->id], 'btn2_url' => $request['btn2_url_' . $lang->id], 'btn2_info' => $request['btn2_info_' . $lang->id]);
                $content = serialize($content);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }

            // LINKS
            if ($type == 'links') {
                $post_key_title = 'a_title_' . $lang->id;
                $post_key_url = 'a_url_' . $lang->id;
                $post_key_icon = 'a_icon_' . $lang->id;
                $links_array_key = 'links_array_' . $lang->id;
                $links_array_key = array();
                $counter_key = 'numb_items_' . $lang->id;
                $counter_key = count(array_filter($_POST[$post_key_url]));

                for ($i = 0; $i < $counter_key; $i++) {
                    if (!(empty(array_filter($_POST[$post_key_title])) && empty(array_filter($_POST[$post_key_url]))))
                        $links_array_key[$i] = array('title' => $inputs["$post_key_title"][$i], 'url' => $inputs["$post_key_url"][$i], 'icon' => $inputs["$post_key_icon"][$i]);
                }
                $content = serialize($links_array_key);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }


            // SLIDER
            if ($type == 'slider') {
                $post_key_title = 'title_' . $lang->id;
                $post_key_content = 'content_' . $lang->id;
                $post_key_image = 'image_' . $lang->id;
                $post_key_existing_image = 'existing_image_' . $lang->id;
                $post_key_url = 'url_' . $lang->id;
                $post_key_position = 'position_' . $lang->id;
                $post_key_btn_style = 'btn_style_' . $lang->id;
                $slides_array_key = array();
                $counter_key = count(array_filter($_POST[$post_key_title]));

                $image_db = null;

                for ($i = 0; $i < $counter_key; $i++) {
                    $image_db = null;

                    if (!empty(array_filter($_POST[$post_key_title]))) {

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

                        $slides_array_key[$i] = array('title' => $inputs["$post_key_title"][$i], 'content' => $inputs["$post_key_content"][$i], 'image' => $image_db[$i] ?? $inputs["$post_key_existing_image"][$i] ?? null, 'url' => $inputs["$post_key_url"][$i], 'position' => $inputs["$post_key_position"][$i] ?? 0, 'btn_style' => $inputs["$post_key_btn_style"][$i] ?? null);

                        // regenerate array and sort by position (asc)
                        if (count($slides_array_key) > 1) {
                            $position = array_column($slides_array_key, 'position');
                            array_multisort($position, SORT_ASC, $slides_array_key);
                        }
                    }
                }

                $content = serialize($slides_array_key);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }


            // ACCORDION
            if ($type == 'accordion') {
                $post_key_title = 'title_' . $lang->id;
                $post_key_content = 'content_' . $lang->id;
                $group_array_key = array();
                $counter_key = count(array_filter($_POST[$post_key_title]));

                for ($i = 0; $i < $counter_key; $i++) {
                    if (!(empty(array_filter($_POST[$post_key_title]))))
                        $group_array_key[$i] = array('title' => $inputs["$post_key_title"][$i], 'content' => $inputs["$post_key_content"][$i]);
                }
                $content = serialize($group_array_key);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
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
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
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


            // DOWNLOAD
            if ($type == 'download') {
                $post_key_content = 'content_' . $lang->id;
                $content_array = array('content' => $inputs["$post_key_content"]);
                $content = serialize($content_array);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);
            }


            // FORM
            if ($type == 'form') {
                $post_key_submit_btn_text = 'submit_btn_text_' . $lang->id;
                $content_array = array('submit_btn_text' => $inputs["$post_key_submit_btn_text"]);
                $content = serialize($content_array);
                DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // Header data
                // delete old header data
                DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => null]);
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }


            // MAP
            if ($type == 'map') {
                // Header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' =>  $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['header' => $header_content]);
                }
            }


            // IMPORTANT! For posts blocks, there are not multi-language content for each post (post is assigned to one language)
            if ($block_module == 'posts') break;
        } // end langs

        if ($inputs['hide'] ?? null) $hide = 1;
        DB::table('blocks')->where('id', $id)->update(['label' =>  $inputs['label'] ?? null, 'custom_css' =>  $inputs['custom_css'] ?? null, 'updated_at' => now(), 'updated_by_user_id' =>  Auth::user()->id, 'hide' => $hide ?? 0]);

        // regenerate content blocks and add blocks in module table (for database performance)
        if ($block_module == 'posts' || $block_module == 'pages') {
            $content_id = DB::table('blocks')->where('id', $id)->value('content_id');
            Core::regenerate_content_blocks($block_module, $content_id);

            // update seconds to read
            if ($block_module == 'posts') DB::table('posts')->where('id', $content_id)->update(['minutes_to_read' => estimated_reading_time($content_id)]);
        }

        if ($referer) return redirect($referer)->with('success', 'updated');
        else return redirect(route('admin.blocks'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        $id = $request->id;

        $block = DB::table('blocks')->where('id', $id)->first();

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        // regenerate content blocks and add blocks in module table (for database performance)
        if ($block->module == 'posts' || $block->module == 'pages') {
            Core::regenerate_content_blocks($block->module, $block->content_id);
        }

        DB::table('blocks')->where('id', $id)->delete();
        DB::table('blocks_content')->where('block_id', $id)->delete();

        return redirect(route('admin.blocks'))->with('success', 'deleted');
    }
}
