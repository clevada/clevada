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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\Core;
use DB;

class TemplateMenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        $links = DB::table('sys_menu')->whereNull('parent_id')->orderBy('position', 'asc')->get();

        $modules = DB::table('sys_modules')->whereNotNull('route_web')->where('status', '!=', 'disabled')->orderBy('module', 'asc')->get();

        $langs = DB::table('sys_lang')
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'template.config-menu',
            'active_menu' => 'template',
            'menu_section' => 'menus',
            'links' => $links,
            'modules' => $modules,
            'langs' => $langs,
        ]);
    }



    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.template.menu'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();
        $type = $inputs['type'];

        if ($inputs['type'] == 'custom') {
            $value = $inputs['custom_url'];
        }

        if ($inputs['type'] == 'page') {
            $value = $inputs['page_id'];
        }

        if (substr($inputs['type'], 0, 7) == 'module_') {
            $array = explode('module_', $inputs['type']);
            $type = 'module';
            $value = $array[1];
        }

        $last_pos = DB::table('sys_menu')->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('sys_menu')->insert([
            'type' => $type,
            'value' => $value ?? null,
            'position' => $position,
        ]);

        $link_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_menu_langs')->insert(['link_id' => $link_id, 'lang_id' => $lang->id, 'label' => $request['label_' . $lang->id]]);
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu'))->with('success', 'created');
    }



    public function update(Request $request)
    {
        $link_id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.template.menu'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();
        $type = $inputs['type'];

        if ($type == 'custom') {
            $value = $inputs['custom_url'];
        }

        if ($type == 'page') {
            $value = $inputs['page_id'] ?? null;
            if (!$value) return redirect(route('admin.template.menu'));
        }

        if (substr($type, 0, 7) == 'module_') {
            $array = explode('module_', $type);
            $type = 'module';
            $value = $array[1];
        }

        DB::table('sys_menu')->where('id', $link_id)->update(['type' => $type, 'value' => $value ?? null]);

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_menu_langs')
                ->updateOrInsert(
                    ['link_id' => $link_id, 'lang_id' => $lang->id],
                    ['label' => $request['label_' . $lang->id]]
                );
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu'))->with('success', 'updated');
    }


    public function destroy(Request $request)
    {
        $link_id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $parent_ids = DB::table('sys_menu')->where('parent_id', $link_id)->get();
        foreach ($parent_ids as $parent) {
            DB::table('sys_menu_langs')->where('link_id', $parent->parent_id)->delete();
        }

        DB::table('sys_menu_langs')->where('link_id', $link_id)->delete();
        DB::table('sys_menu')->where('id', $link_id)->delete();
        DB::table('sys_menu')->where('parent_id', $link_id)->delete();

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu'))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        $i = 1;

        $items = $request->all();

        foreach ($items['item'] as $key => $value) {

            DB::table('sys_menu')
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();
    }


    public function index_dropdowns(Request $request)
    {
        $link_id = $request->link_id;
        if (!$link_id) return redirect(route('admin'));

        $parent_link = DB::table('sys_menu')
            ->where('id', $link_id)
            ->first();
        if (!$parent_link) return redirect(route('admin'));

        $links = DB::table('sys_menu')->where('parent_id', $link_id)->orderBy('position', 'asc')->get();

        $modules = DB::table('sys_modules')->whereNotNull('route_web')->where('status', '!=', 'disabled')->orderBy('module', 'asc')->get();

        $langs = DB::table('sys_lang')
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'template.config-menu-dropdown',
            'active_menu' => 'template',
            'menu_section' => 'menus',
            'parent_link' => $parent_link,
            'is_dropdown' => 1,
            'links' => $links,
            'modules' => $modules,
            'langs' => $langs,
        ]);
    }


    public function store_dropdown(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $link_id = $request->link_id;
        if (!$link_id) return redirect(route('admin'));

        $parent_link = DB::table('sys_menu')->where('id', $link_id)->first();
        if (!$parent_link) return redirect(route('admin'));

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.template.menu.dropdown', ['link_id' => $link_id]))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();
        $type = $inputs['type'];

        if ($inputs['type'] == 'custom') {
            $value = $inputs['custom_url'];
        }

        if ($inputs['type'] == 'page') {
            $value = $inputs['page_id'] ?? null;
            if (!$value) return redirect(route('admin.template.menu'));
        }

        if (substr($inputs['type'], 0, 7) == 'module_') {
            $array = explode('module_', $inputs['type']);
            $type = 'module';
            $value = $array[1];
        }

        $last_pos = DB::table('sys_menu')->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('sys_menu')->insert([
            'parent_id' => $link_id,
            'type' => $type,
            'value' => $value ?? null,
            'position' => $position,
        ]);

        $dropdown_link_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_menu_langs')->insert(['link_id' => $dropdown_link_id, 'lang_id' => $lang->id, 'label' => $request['label_' . $lang->id]]);
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu.dropdown', ['link_id' => $link_id]))->with('success', 'created');
    }


    public function update_dropdown(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $link_id = $request->id;
        $parent_id = $request->parent_id;

        if (!$link_id) return redirect(route('admin'));

        $parent_link = DB::table('sys_menu')->where('id', $link_id)->first();
        if (!$parent_link) return redirect(route('admin'));

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.template.menu.dropdown', ['link_id' => $parent_id]))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();
        $type = $inputs['type'];

        if ($type == 'custom') {
            $value = $inputs['custom_url'];
        }

        if ($type == 'page') {
            $value = $inputs['page_id'];
            if (!$value) return redirect(route('admin.template.menu'));
        }

        if (substr($type, 0, 7) == 'module_') {
            $array = explode('module_', $type);
            $type = 'module';
            $value = $array[1];
        }

        DB::table('sys_menu')->where('id', $link_id)->update(['type' => $type, 'value' => $value ?? null]);

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_menu_langs')->where('link_id', $link_id)->where('lang_id', $lang->id)->update(['label' => $request['label_' . $lang->id]]);
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu.dropdown', ['link_id' => $parent_id]))->with('success', 'updated');
    }


    public function destroy_dropdown(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $link_id = $request->id;
        $parent_id = $request->parent_id;

        if (!$link_id) return redirect(route('admin'));

        $parent_link = DB::table('sys_menu')->where('id', $parent_id)->first();
        if (!$parent_link) return redirect(route('admin'));

        DB::table('sys_menu')->where('id', $link_id)->delete();
        DB::table('sys_menu_langs')->where('link_id', $link_id)->delete();

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();

        return redirect(route('admin.template.menu.dropdown', ['link_id' => $parent_id]))->with('success', 'deleted');
    }

    /**
     * Ajax sortable dropdown links
     */
    public function sortable_dropdowns(Request $request)
    {
        $i = 1;

        $items = $request->all();

        foreach ($items['item'] as $key => $value) {

            DB::table('sys_menu')
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        // regenerate menu links for each language and store in cache config
        Core::generate_langs_menu_links();
    }
}
