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
use App\Models\User;
use App\Models\Core;
use DB;
use Auth;

class TemplateStylesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();

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
     * Show all resources
     */
    public function index()
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $styles = DB::table('sys_styles')
            ->orderBy('label', 'asc')
            ->paginate(25);

        return view('admin/account', [
            'view_file' => 'template.styles',
            'active_menu' => 'template',
            'menu_section' => 'styles',
            'styles' => $styles,
        ]);
    }


    /**
     * Create resource
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
            return redirect(route('admin.template.styles'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');
        
        $exist = DB::table('sys_styles')->where('label', $inputs['label'])->first();
        if ($exist) return redirect(route('admin.template.styles'))->with('error', 'duplicate');

        DB::table('sys_styles')->insert([
            'label' => $inputs['label'],
        ]);

        $style_id = DB::getPdo()->lastInsertId();        
        
        return redirect(route('admin.template.styles.show', ['id' => $style_id]))->with('success', 'created');
    }


    /**
     * Show resource
     */
    public function show(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;
        $style = DB::table('sys_styles')
            ->where('id', $id)
            ->first();
        if (!$style) return redirect(route('admin.template.styles'));            

        return view('admin/account', [
            'view_file' => 'template.style',
            'active_menu' => 'template',
            'menu_section' => 'styles',
            'style' => $style,          
        ]);
    }


    /**
     * Update blocks content
     */
    public function update(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;
        $inputs = $request->except('_token');

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $style = DB::table('sys_styles')->where('id', $id)->first();
        if (!$style) return redirect(route('admin.templates'));
                
        if ($inputs['use_custom_bg'] ?? null) $bg_color = $inputs['bg_color'];

        DB::table('sys_styles')
        ->where('id', $id)
        ->update([
            'bg_color' => $bg_color ?? null,
            'text_color' => $inputs['text_color'],
            'helper_color' => $inputs['helper_color'],
            'title_color' => $inputs['title_color'],
            'text_size' => $inputs['text_size'],
            'title_size' => $inputs['title_size'],
            'helper_size' => $inputs['helper_size'],
            'link_color' => $inputs['link_color'],
            'link_color_hover' => $inputs['link_color_hover'],
            'link_color_underline' => $inputs['link_color_underline'],
            'link_decoration' => $inputs['link_decoration'],
            'link_hover_decoration' => $inputs['link_hover_decoration'],
            'padding_top' => $inputs['padding_top'],
            'padding_bottom' => $inputs['padding_bottom'],
        ]);

    
        return redirect(route('admin.template.styles'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');
      
        DB::table('sys_styles')->where('id', $id)->delete();

        return redirect(route('admin.template.styles'))->with('success', 'deleted');
    }

}
