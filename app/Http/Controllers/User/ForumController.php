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

use App\Models\Core;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Image;

class ForumController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->config = Core::config();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;

            $role = $this->UserModel->get_role_from_id($this->role_id);
            if ($role != 'user') return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all user topics
     */
    public function topics()
    {
        $topics = DB::table('forum_topics')
            ->select(
                'forum_topics.*',
                DB::raw('(SELECT count(*) FROM forum_posts WHERE forum_posts.topic_id = forum_topics.id) as count_posts'),
                DB::raw('(SELECT forum_posts.created_at FROM forum_posts WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_created_at')
            )
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('frontend/builder/user-account', [
            'view_file' => 'forum.topics',
            'nav_menu' => 'forum',
            'topics' => $topics,
        ]);
    }


    /**
     * Display all user posts
     */
    public function posts()
    {
        $posts = DB::table('forum_posts')
            ->leftJoin('forum_topics', 'forum_posts.topic_id', '=', 'forum_topics.id')
            ->select('forum_posts.*', 'forum_topics.id as topic_id', 'forum_topics.title as topic_title', 'forum_topics.slug as topic_slug')
            ->where('forum_posts.user_id', Auth::user()->id)
            ->orderBy('forum_posts.id', 'desc')
            ->paginate(25);

        return view('frontend/builder/user-account', [
            'view_file' => 'forum.posts',
            'posts' => $posts,
        ]);
    }


    /**
     * Forum settings page
     */
    public function config()
    {
        $user = DB::table('users')
            ->where('id', Auth::user()->id)
            ->first();

        $user_created_at = $user->created_at;
        $dCreated = new \DateTime($user_created_at);
        $dNow  = new \DateTime(now());
        $dDiff = $dCreated->diff($dNow);
        $registration_days = $dDiff->format('%a');

        return view('frontend/builder/user-account', [
            'view_file' => 'forum.config',
            'signature' => $this->UserModel->get_user_extra(Auth::user()->id, 'forum_signature') ?? null,
            'count_forum_posts' => $this->UserModel->get_user_extra(Auth::user()->id, 'count_forum_posts') ?? 0,
            'count_forum_topics' => $this->UserModel->get_user_extra(Auth::user()->id, 'count_forum_topics') ?? 0,
            'count_forum_likes_received' => $this->UserModel->get_user_extra(Auth::user()->id, 'count_forum_likes_received') ?? 0,
            'registration_days' => $registration_days ?? 0,
        ]);
    }


    /**
     * Update settings
     */
    public function update_config(Request $request)
    {
        $inputs = $request->all(); // retrieve all of the input data as an array 

        $signature = $inputs['signature'];

        if ($signature) $this->UserModel->add_user_extra(Auth::user()->id, 'forum_signature', strip_tags($signature, '<p><a><br><b><i>'));

        return redirect(route('user.forum.config'))->with('success', 'updated');
    }


    /**
     * User warnings
     */
    public function warnings()
    {
        $warnings = DB::table('forum_user_warnings')
            ->leftJoin('forum_topics', 'forum_user_warnings.topic_id', '=', 'forum_topics.id')
            ->leftJoin('forum_posts', 'forum_user_warnings.post_id', '=', 'forum_posts.id')
            ->select('forum_user_warnings.*', 'forum_topics.id as topic_id', 'forum_topics.title as topic_title', 'forum_topics.slug as topic_slug', 'forum_posts.content as post_content')
            ->where('forum_user_warnings.user_id', Auth::user()->id)
            ->orderBy('forum_user_warnings.id', 'desc')
            ->paginate(25);

        return view('frontend/builder/user-account', [
            'view_file' => 'forum.warnings',
            'warnings' => $warnings,
        ]);
    }



    /**
     * User restrictions
     */
    public function restrictions()
    {
        $restrictions = DB::table('forum_restrictions')
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('frontend/builder/user-account', [
            'view_file' => 'forum.restrictions',
            'today' => date("Y-m-d"),
            'restrictions' => $restrictions,
        ]);
    }
}
