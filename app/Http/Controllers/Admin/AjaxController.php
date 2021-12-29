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
use Illuminate\Support\Str;
use App\Models\User;
use DB;
use Auth;
use File;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     * Check if logged user role is 'admin'. If not, redirect to home
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;
            $role = $this->UserModel->get_role_from_id($this->role_id);
            if (!($role == 'admin' || $role == 'internal')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Search in registered users accounts
     */
    public function fetch(Request $request)
    {
        $source = $request->source;
        if (!$source) return null;

        $term = $request->input('term', '');
        if (empty($term)) {
            return array();
        }

        // Search in registered users accounts     
        if ($source == 'users') {
            $role_id = $this->UserModel->get_role_id_from_role('user');
            $users = DB::table('users')
                ->where('role_id', $role_id)
                ->where('is_deleted', 0)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`, " - ", UPPER(`code`)) AS text')]);

            return ['results' => $users];
        } // end if users


        // Search in contacts
        if ($source == 'contacts') {
            $role_id = $this->UserModel->get_role_id_from_role('contact');
            $users = DB::table('users')
                ->where('role_id', $role_id)
                ->where('is_deleted', 0)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`, " - ", UPPER(`code`)) AS text')]);

            return ['results' => $users];
        } // end if contacts


        // Search in registered users and contacts
        if ($source == 'users_contacts') {
            $role_id_user = $this->UserModel->get_role_id_from_role('user');
            $role_id_contact = $this->UserModel->get_role_id_from_role('contact');

            $users = DB::table('users')
                ->where('is_deleted', 0)
                ->where(function ($query) use ($role_id_user, $role_id_contact) {
                    $query->where('role_id', $role_id_user)
                        ->orwhere('role_id', $role_id_contact);
                })
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`) AS text')]);

            return ['results' => $users];
        } // end if users and contacts


        // Search in internals
        if ($source == 'internals') {
            $role_id = $this->UserModel->get_role_id_from_role('internal');
            $users = DB::table('users')
                ->where('is_deleted', 0)
                ->where('role_id', $role_id)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`) AS text')]);

            return ['results' => $users];
        } // end if 


        // Search in admins
        if ($source == 'admins') {
            $role_id_admin = $this->UserModel->get_role_id_from_role('admin');

            $users = DB::table('users')
                ->where('is_deleted', 0)
                ->where('role_id', $role_id_admin)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`) AS text')]);

            return ['results' => $users];
        } // end if 


        // Search in all accounts
        if ($source == 'accounts') {

            $users = DB::table('users')
                ->where('is_deleted', 0)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                        ->orwhere('email', 'like', "%$term%")
                        ->orwhere('code', 'like', "%$term%");
                })
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`name`, " - ", `email`) AS text')]);

            return ['results' => $users];
        } // end if



        // Search in active pages
        if ($source == 'pages') {
            $pages = DB::table('pages')
                ->where('active', 1)
                ->where('label', 'like', "%$term%")
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`label`) AS text')]);

            return ['results' => $pages];
        } // end if


        // Search in posts tags
        if ($source == 'posts_tags') {
            $term = trim($term);
            $array = array();

            $tags = DB::table('posts_tags')
                ->where('tag', 'like', "%$term%")
                ->orderBy('counter', 'desc')
                ->limit(25)
                ->get();

            foreach ($tags as $tag) {
                if (!in_array($tag->tag, $array))
                    array_push($array, $tag->tag);
            }

            return json_encode($array);
        } // end if


        // Search in active blocks
        if ($source == 'blocks') {
            $pages = DB::table('blocks')
                ->where('active', 1)
                ->where('label', 'like', "%$term%")
                ->limit(25)
                ->get(['id', DB::raw('CONCAT(`label`) AS text')]);

            return ['results' => $pages];
        } // end if


    }


    /**
     * Upload file from editor
     */
    public function editor_upload(Request $request)
    {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $file = array_shift($_FILES);

            $file = $request->fileToUpload;
            $originalname = $file->getClientOriginalName();
            $new_filename = Str::random(12) . '-' . $originalname;
            $subfolder = date("Ym");
            if (!File::isDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder)) {
                File::makeDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder, 0777, true, true);
            }

            $path = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $new_filename;

            if (move_uploaded_file($file, $path)) {
                $data = array(
                    'success' => true,
                    'file'    => image($subfolder . DIRECTORY_SEPARATOR . $new_filename),
                );
            } else {
                $data = array(
                    'success' => false,
                    'message' => 'uploadError',
                );
            }

            //return $uploads_folder.DIRECTORY_SEPARATOR.$subfolder.DIRECTORY_SEPARATOR.$new_filename;

        } else {
            $data = array(
                'success' => false,
                'message' => 'uploadNotAjax',
                'formData' => $_POST
            );
        }

        return json_encode($data);
    }
}
