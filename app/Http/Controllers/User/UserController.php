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

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Upload;
use App\Models\Core;
use DB;
use Auth;

class UserController extends Controller
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
        $this->UploadModel = new Upload();
        $this->config = Core::config();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!$this->logged_user_role == 'user') return redirect('/');
            return $next($request);
        });
    }


    /**
     * User dashboard
     */
    public function dashboard()
    {
        return view('frontend/builder/page', [
            'view_file' => 'user.dashboard',
        ]);
    }


    /**
     * Display profile page
     */
    public function profile(Request $request)
    {
        return view('frontend/builder/user-account', [
            'view_file' => 'profile',
            'nav_menu' => 'profile',
            'bio' => $this->UserModel->get_user_extra(Auth::user()->id, 'bio') ?? null,
        ]);
    }



    /**
     * Update profile
     */
    public function update_profile(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('user'))->with('error', 'demo');

        $lang = $request->lang;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email'
        ]);

        if ($validator->fails()) {
            return redirect($request->Url())
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $slug = Str::slug($inputs['name'], '-');

        // check if email exist
        $exist_email = DB::table('users')->where('email', $inputs['email'])->where('id', '!=', Auth::user()->id)->first();
        if ($exist_email) return redirect(route('user.profile', ['lang' => $lang]))->with('error', 'duplicate');

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update([
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'slug' => $slug,
                'updated_at' => now(),
            ]);

        // change password
        if ($inputs['password']) {
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(['password' => Hash::make($inputs['password'])]);
        }

        // process image        
        if ($request->hasFile('avatar')) {
            $image_db = $this->UploadModel->avatar($request, 'avatar');
            DB::table('users')->where('id', Auth::user()->id)->update(['avatar' => $image_db]);
        }

        $this->UserModel->add_user_extra(Auth::user()->id, 'bio', $inputs['bio']);

        return redirect(route('user.profile', ['lang' => $lang]))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function delete_avatar(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('user'))->with('error', 'demo');

        $lang = $request->lang;

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['avatar' => NULL]);

        return redirect(route('user.profile', ['lang' => $lang]))->with('success', 'updated');
    }
}
