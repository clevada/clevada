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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Models\Post;

class LogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
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
     * Email log
     */
    public function email(Request $request)
    {
        $search_email = $request->search_email;
        $search_subject = $request->search_subject;

        $emails = DB::table('log_email')
            ->leftJoin('users', 'log_email.to_user_id', '=', 'users.id')
            ->select('log_email.*', 'users.name as to_user_name', 'users.avatar as to_user_avatar');

        if ($search_email)
            $emails = $emails->where('log_email.email', 'like', "%$search_email%");

        if ($search_subject)
            $emails = $emails->where('log_email.subject', 'like', "%$search_subject%");

        $emails = $emails->orderBy('log_email.id', 'desc')->paginate(25);

        return view('admin.account', [
            'view_file' => 'core.log-email',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.log.email',

            'search_email' => $search_email,
            'search_subject' => $search_subject,
            'emails' => $emails,
        ]);
    }


    /**
     * Show email
     */
    public function show_email(Request $request)
    {
        $id = $request->id;

        $email = DB::table('log_email')->where('id', $id)->first();
        if (!$email) return redirect(route('admin.log.email'));

        return view('admin.account', [
            'view_file' => 'core.log-email-show',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.log.email',

            'email' => $email,
        ]);
    }



    /**
     * Delete email log
     */
    public function delete_email(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;
        $search_email = $request->search_email;
        $search_subject = $request->search_subject;

        DB::table('log_email')->where('id', $id)->delete();

        return redirect(route('admin.log.email', ['search_email' => $search_email, 'search_subject' => $search_subject]))->with('success', 'deleted');
    }
}
