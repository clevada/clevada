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

class TemplateSidebarsController extends Controller
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

        $sidebars = DB::table('sys_sidebars')
            ->orderBy('label', 'asc')
            ->paginate(25);

        return view('admin/account', [
            'view_file' => 'template.sidebars',
            'active_menu' => 'template',
            'menu_section' => 'sidebars',
            'sidebars' => $sidebars,
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
            return redirect(route('admin.template.sidebars'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        $exist = DB::table('sys_sidebars')->where('label', $inputs['label'])->first();
        if ($exist) return redirect(route('admin.template.sidebars'))->with('error', 'duplicate');

        DB::table('sys_sidebars')->insert([
            'label' => $inputs['label'],
        ]);

        return redirect(route('admin.template.sidebars'))->with('success', 'created');
    }


    /**
     * Show resource
     */
    public function show(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;
        $sidebar = DB::table('sys_sidebars')
            ->where('id', $id)
            ->first();
        if (!$sidebar) return redirect(route('admin.template.sidebars'));
       
        return view('admin/account', [
            'view_file' => 'template.sidebars-content',
            'active_menu' => 'template',
            'menu_section' => 'sidebars',
            'sidebar' => $sidebar,

            'content_id' => $id,
            'module' => 'sidebar',
            'layout' => '12',
        ]);
    }


    /**
     * Update blocks content
     */
    public function update_content(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $id = $request->id;
        $inputs = $request->except('_token');

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $sidebar = DB::table('sys_sidebars')->where('id', $id)->first();
        if (!$sidebar) return redirect(route('admin.templates'));
        
        $type_id = $inputs['type_id'];

        if (! $type_id) return redirect(route('admin.template.sidebars', ['id' => $id]));

        $last_pos = DB::table('blocks')->where('module', 'sidebar')->where('content_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('blocks')->insert([
            'type_id' => $type_id,
            'module' => 'sidebar',
            'content_id' => $id,
            'position' => $position,
            'created_at' => now(),
            'created_by_user_id' =>  Auth::user()->id
        ]);
        $block_id = DB::getPdo()->lastInsertId();        

        return redirect(route('admin.blocks.show', ['id' => $block_id]));
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

        // delete sidebar content blocks
        $sidebar_blocks = DB::table('blocks')->where('module', 'sidebar')->where('content_id', $id)->get();
        foreach ($sidebar_blocks as $sidebar_block) {
            DB::table('blocks_content')->where('block_id', $sidebar_block->id)->delete();
            DB::table('blocks')->where('id', $sidebar_block->id)->delete();
        }

        DB::table('sys_sidebars')->where('id', $id)->delete();

        return redirect(route('admin.template.sidebars'))->with('success', 'deleted');
    }


    /**
     * Assign sidebar to content resource
     */
    public function assign(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));

        $sidebar_id = $request->sidebar_id; // sidebar ID
        $sidebar_position = $request->sidebar_position; // left / right
        $module = $request->module;
        $template_id = $request->template_id;

        if($sidebar_id) {
            $key = 'sidebar_id_' . $module;        
            Core::update_template_config($template_id, $key, $sidebar_id);                    

            $key2 = 'sidebar_position_' . $module;        
            Core::update_template_config($template_id, $key2, $sidebar_position);                    
        }

        return redirect(request()->headers->get('referer'))->with('success', 'updated');
    }



    /**
     * Remove the specified block content
     */
    public function delete_content(Request $request)
    {
    if (!(check_access('developer'))) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;  // sidebar ID
        $block_id = $request->block_id; 

        $sidebar = DB::table('sys_sidebars')->where('id', $id)->first();
        if (!$sidebar) return redirect(route('admin.templates'));

        DB::table('blocks_content')->where('block_id', $block_id)->delete();
        DB::table('blocks')->where('id', $block_id)->delete();

        return redirect(route('admin.template.sidebars.show', ['id' => $id]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        if (!(check_access('developer'))) return redirect(route('admin'));
        
        $i = 0;

        $content_id = $request->id;
        $records = $request->all();

        foreach ($records['item'] as $key => $value) {

            DB::table('blocks')
                ->where('module', 'sidebar')
                ->where('content_id', $content_id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }
    }


}
