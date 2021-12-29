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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Auth;
use Image;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();
        $this->config = Core::config();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;

            $role = $this->UserModel->get_role_from_id($this->role_id);
            if (!Auth::user()) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display profile page
     */
    public function index(Request $request)
    {

        return view('admin/account', [
            'view_file' => 'core.profile',
            'active_submenu' => NULL,
        ]);
    }


    /**
     * Update profile
     */
    public function update(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

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
        if (DB::table('users')->where('email', $inputs['email'])->where('id', '!=', Auth::user()->id)->exists()) return redirect(route('admin.profile'))->with('error', 'duplicate');

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

        return redirect(route('admin.profile'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function delete_avatar()
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['avatar' => NULL]);

        return response('avatar_deleted');
    }
}
