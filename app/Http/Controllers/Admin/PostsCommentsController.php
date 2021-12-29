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
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class PostsCommentsController extends Controller
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

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $search_post_id = $request->search_post_id;

        $comments = DB::table('posts_comments')
            ->leftJoin('posts', 'posts_comments.post_id', '=', 'posts.id')
            ->leftJoin('users', 'posts_comments.user_id', '=', 'users.id')
            ->select('posts_comments.*', 'posts.title as post_title', 'posts.slug as post_slug', 'posts.image as post_image', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar', 'users.slug as author_slug', 
            DB::raw('(SELECT name FROM users WHERE posts_comments.updated_by_user_id = users.id) as updated_by_user_name'),
            DB::raw('(SELECT COUNT(id) FROM posts_comments WHERE ip = posts_comments.ip) as ip_count_comments')
        );

        if ($search_post_id) {
            $comments = $comments->where('posts.id', $search_post_id);
            $post = DB::table('posts')->where('id', $search_post_id)->first();
        }

        $comments = $comments->orderBy('posts_comments.id', 'desc')->paginate(25);

        return view('admin/account', [
            'view_file' => 'posts.comments',
            'active_menu' => 'website',
            'active_submenu' => 'posts',
            'search_post_id' => $search_post_id,
            'post' => $post ?? null,
            'comments' => $comments,
        ]);
    }


      /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $post_id = $request->search_post_id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');       

        $inputs = $request->except('_token');

        DB::table('posts_comments')
            ->where('id', $id)
            ->update([
                'comment' => $inputs['comment'],
                'status' => $inputs['status'],
                'updated_at' => now(),
                'updated_by_user_id' => Auth::user()->id ?? null,                
            ]);

            return redirect(route('admin.posts.comments', ['search_post_id' => $post_id]))->with('success', 'updated');
        }


    /**
     * Delete resource
     */
    public function destroy(Request $request)
    {

        if (!check_admin_module('posts')) return redirect(route('admin'));
        if (!check_access('posts', 'manager')) return redirect(route('admin'));

        $id = $request->id;
        $post_id = $request->search_post_id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $comment_post_id = DB::table('posts_likes')->where('id', $id)->value('post_id');

        DB::table('posts_comments')->where('id', $id)->delete();

        // recount comments
        $this->PostModel->recount_post_comments($comment_post_id);

        return redirect(route('admin.posts.comments', ['search_post_id' => $post_id]))->with('success', 'deleted');
    }
}
