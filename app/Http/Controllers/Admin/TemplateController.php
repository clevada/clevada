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
use App\Models\Core;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Cache;

class TemplateController extends Controller
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
     * All templats
     */
    public function index()
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $templates = DB::table('sys_templates')->orderBy('is_default', 'desc')->paginate(20);

        return view('admin/account', [
            'view_file' => 'template.templates',
            'active_menu' => 'template',
            'menu_section' => 'template',
            'templates' => $templates,
        ]);
    }


    /**
     * Create new template
     */
    public function store(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.templates'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if (DB::table('sys_templates')->where('label', $inputs['label'])->exists()) return redirect(route('admin.templates'))->with('error', 'duplicate');

        if ($request->has('is_default')) $is_default = 1;
        else $is_default = 0;

        // only one template can be default
        if ($is_default == 1) DB::table('sys_templates')->where('is_default', 1)->update(['is_default' => 0]);

        DB::table('sys_templates')->insert([
            'label' => $inputs['label'],
            'is_default' => $is_default,
            'created_at' => now(),
        ]);

        $template_id = DB::getPdo()->lastInsertId();

        // source
        if ($inputs['source']) {
            // duplicate template config
            $original_configs = DB::table('sys_templates_config')->where('template_id', $inputs['source'])->get();
            foreach ($original_configs as $original_config) {
                DB::table('sys_templates_config')->insert(['template_id' => $template_id, 'name' => $original_config->name, 'value' => $original_config->value]);
            }

            // duplicate template blocks
            $original_blocks = DB::table('blocks')->where('template_id', $inputs['source'])->get();
            foreach ($original_blocks as $original_block) {
                DB::table('blocks')->insert([
                    'type_id' => $original_block->type_id, 
                    'label' => $original_block->label, 
                    'extra' => $original_block->extra, 
                    'module' => $original_block->module, 
                    'content_id' => $original_block->content_id, 
                    'template_id' => $template_id, 
                    'position' => $original_block->position, 
                    'hide' => $original_block->hide,
                    'created_at' => now(),
                    'created_by_user_id' =>  Auth::user()->id
                ]);

                $new_block_id = DB::getPdo()->lastInsertId();

                $original_block_id = $original_block->id;
                $original_blocks_content = DB::table('blocks_content')->where('block_id', $original_block_id)->get();
                
                foreach ($original_blocks_content as $original_block_content) {
                    DB::table('blocks_content')->insert([
                        'block_id' => $new_block_id, 
                        'lang_id' => $original_block_content->lang_id, 
                        'content' => $original_block_content->content, 
                        'header' => $original_block_content->header,                         
                    ]);
                }    
            }

            // duplicate footer blocks
            $original_footer_blocks = DB::table('sys_footer_blocks')->where('template_id', $inputs['source'])->get();
            foreach ($original_footer_blocks as $original_footer_block) {
                DB::table('sys_footer_blocks')->insert([
                    'template_id' => $template_id,                     
                    'type_id' => $original_footer_block->type_id, 
                    'extra' => $original_footer_block->extra, 
                    'footer' => $original_footer_block->footer, 
                    'layout' => $original_footer_block->layout,                     
                    'position' => $original_footer_block->position, 
                    'col' => $original_footer_block->col,
                    'created_at' => now()
                ]);

                $new_footer_block_id = DB::getPdo()->lastInsertId();

                $original_footer_block_id = $original_footer_block->id;
                $original_footer_blocks_content = DB::table('sys_footer_blocks_content')->where('block_id', $original_footer_block_id)->get();
                
                foreach ($original_footer_blocks_content as $original_footer_block_content) {
                    DB::table('sys_footer_blocks_content')->insert([
                        'block_id' => $new_footer_block_id, 
                        'lang_id' => $original_footer_block_content->lang_id, 
                        'content' => $original_footer_block_content->content, 
                        'header' => $original_footer_block_content->header,                         
                    ]);
                }    
            }

            // generate css for this template
            generate_global_css($template_id);
        }

        return redirect($request->Url())->with('success', 'created');
    }


    /**
     * Set default template
     */
    public function set_default(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;

        // only one template can be default
        DB::table('sys_templates')->where('is_default', 1)->update(['is_default' => 0]);
        DB::table('sys_templates')->where('id', $id)->update(['is_default' => 1]);

        return redirect(route('admin.templates'))->with('success', 'updated');
    }



    /**
     * Remove template
     */
    public function destroy(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (DB::table('sys_templates')->where('id', $id)->value('is_default') == 1) return redirect(route('admin.templates'))->with('error', 'delete_default');

        DB::table('sys_templates_config')->where('template_id', $id)->delete();
        
        $blocks = DB::table('blocks')->where('template_id', $id)->get();
        foreach ($blocks as $block) {
            DB::table('blocks_content')->where('block_id', $block->id)->delete();
        }
        DB::table('blocks')->where('template_id', $id)->delete();


        // delete cache
        Cache::flush();

        DB::table('sys_templates')->where('id', $id)->delete();

        return redirect(route('admin.templates'))->with('success', 'deleted');
    }



    /**
     * Template module
     */
    public function show(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $template_id = $request->id;
        $module = $request->module;

        if (!$template_id) return redirect(route('admin.templates'));
        if (!$module) $module = 'global';

        $sidebars = DB::table('sys_sidebars')
            ->orderBy('label', 'asc')
            ->get();

        $global_sections = DB::table('sys_global_sections')
            ->orderBy('label', 'asc')
            ->get();

        $top_section_key = 'top_section_id_' . $module;
        $bottom_section_key = 'bottom_section_id_' . $module;

        $top_section_id = DB::table('sys_templates_config')->where('template_id', $template_id)->where('name', $top_section_key)->value('value');
        $bottom_section_id = DB::table('sys_templates_config')->where('template_id', $template_id)->where('name', $bottom_section_key)->value('value');

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        return view('admin/account', [
            'view_file' => 'template.edit-' . $module,
            'active_menu' => 'template',
            'menu_section' => 'template',
            'module' => $module,
            'template' => $template,
            'sidebars' => $sidebars,
            'global_sections' => $global_sections,
            'top_section_id' => $top_section_id,
            'bottom_section_id' => $bottom_section_id,
        ]);
    }


    /**
     * Update settings
     */
    public function update(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $template_id = $request->id;
        $module = $request->module;
        $inputs = $request->except(['_token', 'template_id', 'module', '_method']);

        if (!$template_id) return redirect(route('admin.templates'));
        if (!$module) $module = 'global';

        // insert new values
        Core::update_template_config($template_id, $inputs);

        // regenerate css file
        Template::generate_global_css($template_id);

        return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]))->with('success', 'updated');
    }



    /**
     * Logos config page
     */
    public function logo()
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        return view('admin/account', [
            'view_file' => 'template.logo',
            'active_menu' => 'template',
            'menu_section' => 'logo',
        ]);
    }

    /**
     * Process Template logos
     */
    public function update_logo(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        // process Main logo image
        if ($request->hasFile('logo')) {

            $validator = Validator::make($request->all(), [
                'logo' => 'mimes:jpeg,jpg,png,gif',
            ]);

            if ($validator->fails()) {
                return redirect($request->Url())
                    ->withErrors($validator)
                    ->withInput();
            }

            $logo_db = $this->UploadModel->upload_file($request, 'logo');
            Core::update_config('logo', $logo_db);
        }


        // process alt logo image
        if ($request->hasFile('logo_alt')) {

            $validator = Validator::make($request->all(), [
                'logo_alt' => 'mimes:jpeg,jpg,png,gif',
            ]);

            if ($validator->fails()) {
                return redirect($request->Url())
                    ->withErrors($validator)
                    ->withInput();
            }

            $logo_db = $this->UploadModel->upload_file($request, 'logo_alt');
            Core::update_config('logo_alt', $logo_db);
        }


        // pricess favicon image
        if ($request->hasFile('favicon')) {

            $validator = Validator::make($request->all(), ['favicon' => 'mimes:jpeg,jpg,png,gif,ico']);

            if ($validator->fails()) return redirect($request->Url())->withErrors($validator)->withInput();

            $favicon_db = $this->UploadModel->upload_file($request, 'favicon');
            Core::update_config('favicon', $favicon_db);
        }

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Template tools page
     */
    public function custom_code()
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $path = 'custom/files/';
        $custom_files = array_diff(scandir($path), array('.', '..'));

        return view('admin/account', [
            'view_file' => 'template.custom-code',
            'active_menu' => 'template',
            'menu_section' => 'custom_code',
            'custom_files' => $custom_files,
        ]);
    }

    /**
     * Process temmplate tools page
     */
    public function update_custom_code(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        Core::update_config($inputs);

        // process file        
        if ($request->hasFile('file')) {

            $file = $request->file;
            $originalname = $file->getClientOriginalName();

            $validator = Validator::make($request->all(), ['file' => 'mimes:css,js,txt']);

            if ($validator->fails()) return redirect($request->Url())->with('error', 'invalid_file');

            $new_filename = date("YmdHis") . '-' . $originalname;

            $path = 'custom' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $new_filename;
            move_uploaded_file($file, $path);
        }

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Remove custom file
     */
    public function custom_code_delete_file(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $file = $request->file;

        $filepath = 'custom/files/' . $file;

        if (file_exists($filepath)) @unlink($filepath);

        return redirect(route('admin.template.custom_code'))->with('success', 'updated');
    }


    public function update_layout(Request $request)
    {

        if (!(check_access('developer'))) return redirect(route('admin'));

        $module = $request->module;
        $template_id = $request->template_id;

        if (!$template_id) return redirect(route('admin.config.templates'));
        if (!$module) return redirect(route('admin.templates'));

        $inputs = $request->except('_token');

        $key = 'layout_' . $module;
        if (!isset($inputs['layout'])) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]))->with('error', 'select_layout');

        Core::update_template_config($template_id, $key, $inputs['layout']);

        return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]))->with('success', 'layout_updated');
    }


    public function add_block(Request $request)
    {
        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        $module = $request->module;
        $template_id = $request->template_id;

        $inputs = $request->except('_token');

        if (!($module && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]));

        $type_id = $inputs['type_id'];

        if (!$type_id) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]));

        $last_pos = DB::table('blocks')->where('template_id', $template_id)->where('module', $module)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'template_id' => $template_id,
            'module' => $module,
            'position' => $position,
            'created_at' => now(),
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        return redirect(route('admin.blocks.show', ['id' => $block_id]));
    }


    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {

        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $module = $request->module;
        $template_id = $request->template_id;
        $block_id = $request->block_id;

        if ($module == 'footer') {
            if ($content_id == 1) $footer = 'primary';
            if ($content_id == 2) $footer = 'secondary';
        }

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        if ($module == 'footer')
            return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]))->with('success', 'deleted');
        else
            return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => $module]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        $i = 0;

        $module = $request->module;
        $template_id = $request->template_id;

        $records = $request->all();

        foreach ($records['item'] as $key => $value) {
            DB::table('blocks')
                ->where('module', $module)
                ->where('template_id', $template_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);
            $i++;
        }
    }


    /**
     * Edit footer content
     */
    public function footer_content(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $footer = $request->footer;
        $template_id = $request->template_id;

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        if ($footer == 'primary') {
            $layout = get_template_value($template_id, 'footer1_layout');
            $content_id = 1;
            $module = 'footer';
        }
        if ($footer == 'secondary') {
            $layout = get_template_value($template_id, 'footer2_layout');
            $content_id = 2;
            $module = 'footer2';
        }

        return view('admin/account', [
            'view_file' => 'template.edit-footer-content',
            'active_menu' => 'template',
            'menu_section' => 'footers',
            'layout' => $layout ?? '12',
            'template' => $template,
            'module' => $module,
            'footer' => $footer,
            'content_id' => $content_id,
        ]);
    }


    /**
     * Update footer content   
     */
    public function footer_update_content(Request $request)
    {

        if (!(check_access('developer'))) return redirect(route('admin'));

        $footer = $request->footer;
        $template_id = $request->template_id;
        $pos = $request->pos; // colum number (1 to 4)


        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        if ($footer == 'primary') $module = 'footer';

        if ($footer == 'secondary') $module = 'footer2';

        $inputs = $request->except('_token');

        $type_id = $inputs['type_id'];

        if (!$type_id) return redirect(route('admin.templates.show', ['id' => $template->id]));

        $last_pos = DB::table('blocks')->where('template_id', $template->id)->where('module', $module)->where('content_id', $pos)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'module' => $module,
            'template_id' => $template->id,
            'content_id' => $pos, // column number
            'position' => $position,
            'created_at' => now(),
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();

        return redirect(route('admin.blocks.show', ['id' => $block_id]));
    }


    /**
     * Remove the specified block content from footer
     */
    public function footer_delete_content(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $footer = $request->footer;
        $template_id = $request->template_id;

        $template = DB::table('sys_templates')->where('id', $template_id)->first();
        if (!$template) return redirect(route('admin.templates'));

        if (!($footer && $template_id)) return redirect(route('admin.templates.show', ['id' => $template_id, 'module' => 'footer']));

        $block_id = $request->block_id;

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        return redirect(route('admin.template.footer-content', ['template_id' => $template->id, 'footer' => $footer]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable footer blocks
     */
    public function footer_sortable(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $i = 0;

        $footer = $request->footer;
        $template_id = $request->template_id;

        $records = $request->all();

        foreach ($records['item'] as $key => $value) {
            DB::table('blocks')
                ->where('module', $module)
                ->where('template_id', $template_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);
            $i++;
        }
    }
}
