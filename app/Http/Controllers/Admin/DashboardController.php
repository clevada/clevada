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

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Core;
use DB;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->config = Core::config();  

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect('/');
            return $next($request);
        });
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {

        $count_accounts = DB::table('users')->count();

        $count_forms_unread = DB::table('forms_data')->whereNull('read_at')->count();

        $latest_forms = DB::table('forms_data')->orderBy('id', 'desc')->limit(20)->get();

        $latest_accounts = DB::table('users')->orderBy('id', 'desc')->limit(20)->get();
        
        if ($this->logged_user_role == 'admin') $view_file = 'core.dashboard';
        if ($this->logged_user_role == 'internal') $view_file = 'core.dashboard-internal';

        // internal logged user permissions
        $user_permissions = DB::table('users_permissions')
            ->leftJoin('sys_modules', 'users_permissions.module', '=', 'sys_modules.module')
            ->leftJoin('sys_permissions', 'users_permissions.permission_id', '=', 'sys_permissions.id')
            ->select('users_permissions.*', 'sys_modules.module as module', 'sys_modules.label as module_label', 'sys_permissions.permission as permission', 'sys_permissions.label as permission_label', 'sys_permissions.description as permission_description')
            ->where('user_id', Auth::user()->id)
            ->get();


        return view('admin/account', [
            'view_file' => $view_file,
            'active_menu' => 'dashboard',

            'count_accounts' => $count_accounts,
            'count_forms_unread' => $count_forms_unread,
            'latest_forms' => $latest_forms,
            'latest_accounts' => $latest_accounts,
            'user_permissions' => $user_permissions,
        ]);
    }

}
