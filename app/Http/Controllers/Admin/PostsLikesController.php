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

class PostsLikesController extends Controller
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
     * All resources
     */
    public function index(Request $request)
    {
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $search_post_id = $request->search_post_id;

        $likes = DB::table('posts_likes')
            ->leftJoin('posts', 'posts_likes.post_id', '=', 'posts.id')
            ->select('posts_likes.*', 'posts.title as post_title', 'posts.slug as post_slug', 'posts.image as post_image', 'posts.likes as post_count_likes');

        if ($search_post_id) {
            $likes = $likes->where('posts.id', $search_post_id);
            $post = DB::table('posts')->where('id', $search_post_id)->first();
        }

        $likes = $likes->orderBy('posts.id', 'desc')->paginate(25);

        return view('admin.account', [
            'view_file' => 'posts.likes',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'search_post_id' => $search_post_id,
            'likes' => $likes,
            'post' => $post ?? null,
        ]);
    }


    /**
     * Delete resource
     */
    public function destroy(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $id = $request->id;
        $post_id = $request->search_post_id;

        $like_post_id = DB::table('posts_likes')->where('id', $id)->value('post_id');

        DB::table('posts_likes')->where('id', $id)->delete();

        // recount likes
        $this->PostModel->recount_post_likes($like_post_id);

        return redirect(route('admin.posts.likes', ['search_post_id' => $post_id]))->with('success', 'deleted');
    }
}
