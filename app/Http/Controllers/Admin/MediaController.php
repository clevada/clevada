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
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App\Models\Upload;

class MediaController extends Controller
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
     * Display all resources
     */
    public function index(Request $request)
    {

        if (!(check_access('developer') || check_access('pages') || check_access('posts'))) return redirect(route('admin'));

        $items = DB::table('media')->orderBy('id', 'desc')->paginate(25);

        return view('admin.account', [
            'view_file' => 'media.media',
            'active_menu' => 'website',
            'active_submenu' => 'media',
            'items' => $items,
        ]);
    }


    /**
     * Create new resource
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        // process image        
        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), ['image' => 'mimes:jpeg,bmp,png,gif,webp']);
            if ($validator->fails()) {
                return redirect(route('admin.media'))
                    ->withErrors($validator)
                    ->withInput();
            }

            $image_db = $this->UploadModel->upload_image($request, 'image');
        }

        DB::table('media')->insert([
            'file' => $image_db ?? null,
            'created_at' => now(),
            'created_by_user_id' => Auth::user()->id
        ]);

        return redirect(route('admin.media'))->with('success', 'created');
    }


    /**
     * Update resource
     */
    public function update(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;

        $media = DB::table('media')->where('id', $id)->first();
        if (!$media) return redirect(route('admin'));

        // process image        
        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), ['image' => 'mimes:jpeg,bmp,png,gif,webp']);
            if ($validator->fails()) {
                return redirect(route('admin.media'))
                    ->withErrors($validator)
                    ->withInput();
            }
            $image_db = $this->UploadModel->upload_image($request, 'image');
            DB::table('media')->where('id', $id)->update(['file' => $image_db ?? null]);
        }

        return redirect(route('admin.media'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id;

        $media = DB::table('media')->where('id', $id)->first();
        if (!$media) return redirect(route('admin'));

        // delete image
        delete_image($media->file);

        DB::table('media')->where('id', $id)->delete();

        return redirect(route('admin.media'))->with('success', 'deleted');
    }
}
