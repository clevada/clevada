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
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class TemplateFooterController extends Controller
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

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }




    /**
     * Edit footer content
     */
    public function content(Request $request)
    {
        $footer = $request->footer;
        $template_id = $request->template_id;

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        return view('admin/account', [
            'view_file' => 'template.edit-footer-content',
            'active_menu' => 'template',
            'menu_section' => 'footers',
            'template' => $template,
            'footer' => $footer,
        ]);
    }


    /**
     * Update footer content   
     */
    public function update_content(Request $request)
    {

        $footer = $request->footer;
        $template_id = $request->template_id;
        $col = $request->col; // colum number (1 to 4)

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        $inputs = $request->except('_token');

        $type_id = $inputs['type_id'];

        if (!$type_id) return redirect(route('admin.templates.show', ['id' => $template->id]));

        $last_pos = DB::table('sys_footer_blocks')->where('template_id', $template->id)->where('footer', $footer)->where('col', $col)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        if ($footer == 'primary') $layout = get_template_value($template_id, 'footer_columns') ?? 1;
        if ($footer == 'secondary') $layout = get_template_value($template_id, 'footer2_columns') ?? 1;

        DB::table('sys_footer_blocks')->insert([
            'type_id' => $type_id,
            'footer' => $footer,
            'template_id' => $template->id,
            'col' => $col, // column number
            'layout' => $layout, // number of columns
            'position' => $position,
            'created_at' => now(),
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        return redirect(route('admin.template.footer.block', ['id' => $block_id]));
    }


    /**
     * Remove the specified block content from footer
     */
    public function delete_content(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $footer = $request->footer;
        $template_id = $request->template_id;

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        $block_id = $request->block_id;

        DB::table('sys_footer_blocks_content')->where('block_id', $block_id)->delete();
        DB::table('sys_footer_blocks')->where('id', $block_id)->delete();

        return redirect(route('admin.template.footer.content', ['template_id' => $template->id, 'footer' => $footer]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable footer blocks
     */
    public function sortable(Request $request)
    {
        $i = 0;

        $footer = $request->footer;
        $template_id = $request->template_id;
        $col = $request->col;

        if ($footer == 'primary') $layout = get_template_value($template_id, 'footer_columns') ?? 1;
        if ($footer == 'secondary') $layout = get_template_value($template_id, 'footer2_columns') ?? 1;

        $records = $request->all();

        foreach ($records['item'] as $key => $value) {

            DB::table('sys_footer_blocks')
                ->where('footer', $footer)
                ->where('template_id', $template_id)
                ->where('col', $col)
                ->where('layout', $layout)
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
        $block = DB::table('sys_footer_blocks')
            ->leftJoin('blocks_types', 'sys_footer_blocks.type_id', '=', 'blocks_types.id')
            ->select('sys_footer_blocks.*', 'blocks_types.type as type', 'blocks_types.label as type_label')
            ->where('sys_footer_blocks.id', $request->id)
            ->first();
        if (!$block) abort(404);

        $langs = DB::table('sys_lang')
            ->select(
                'sys_lang.*',
                DB::raw('(SELECT content FROM sys_footer_blocks_content WHERE sys_footer_blocks_content.lang_id = sys_lang.id AND block_id = ' . $block->id . ') as block_content'),
                DB::raw('(SELECT header FROM sys_footer_blocks_content WHERE sys_footer_blocks_content.lang_id = sys_lang.id AND block_id = ' . $block->id . ') as block_header')
            )
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();


        return view('admin/account', [
            'view_file' => 'template.blocks.' . $block->type,
            'active_menu' => 'template',
            'menu_section' => 'footers',
            'block' => $block,
            'langs' => $langs,
            'referer' => request()->headers->get('referer'),
        ]);
    }



    /**
     * Update block    
     */
    public function block_update(Request $request)
    {
        $id = $request->id;
        $block_type_id = $request->type_id;
        $referer = $request->referer;

        $type = DB::table('blocks_types')->where('id', $block_type_id)->value('type');

        $inputs = $request->except('_token');

        // Extra content ALERT 
        if ($type == 'image') {
            $block_extra = array('shaddow' => $inputs['shaddow'] ?? null);
            DB::table('sys_footer_blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content LINKS        
        if ($type == 'links') {
            $block_extra = array('new_tab' => $inputs['new_tab'] ?? null, 'display_style' =>  $inputs['display_style']);
            DB::table('sys_footer_blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content MAP       
        if ($type == 'map') {
            $block_extra = array('height' => $inputs['height'] ?? 400, 'zoom' => $inputs['zoom'] ?? 16, 'address' => $inputs['address']);
            DB::table('sys_footer_blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content POSTS WIDGET            
        if ($type == 'posts') {
            $block_extra = array('items' => $inputs['items'], 'content' => $inputs['content'], 'categ_badge' => $inputs['categ_badge'], 'style' => $inputs['style'], 'show_image' => $inputs['show_image'], 'show_date' => $inputs['show_date'], 'show_author' => $inputs['show_author'], 'titles_font_size' => $inputs['titles_font_size']);

            DB::table('sys_footer_blocks')->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // ***************************************************
        // Block CONTENT
        // ***************************************************
        $langs = DB::table('sys_lang')->get();

        // UPDATE CONTENT
        foreach ($langs as $lang) {
            $content = null;

            // EDITOR / CUSTOM
            if ($type == 'editor' || $type == 'custom') {
                $content = $request['content_' . $lang->id];
                DB::table('sys_footer_blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' => $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('sys_footer_blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
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
                DB::table('sys_footer_blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' => $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('sys_footer_blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
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
                DB::table('sys_footer_blocks_content')->updateOrInsert(['block_id' => $id, 'lang_id' => $lang->id], ['content' => $content]);

                // header data
                $header_array = array();
                if ($inputs['add_header_' . $lang->id] ?? null) {
                    $header_array = array('add_header' => 'on', 'title' => $inputs["header_title_$lang->id"], 'content' =>  $inputs["header_content_$lang->id"]);
                    $header_content = serialize($header_array);
                    DB::table('sys_footer_blocks_content')->where(['block_id' => $id, 'lang_id' => $lang->id])->update(['header' => $header_content]);
                }
            }
        } // end langs

        DB::table('sys_footer_blocks')->where('id', $id)->update(['updated_at' => now()]);

        if ($referer) return redirect($referer)->with('success', 'updated');
        else return redirect(route('admin.templates'))->with('success', 'updated');
    }
}
