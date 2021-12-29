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
use App\Models\Forum;
use App\Models\Core;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class ForumController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->ForumModel = new Forum();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display topics reports
     */
    public function reports_topics(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));
        if (!check_access('forum')) return redirect(route('admin'));

        $reports = DB::table('forum_reports')
            ->leftJoin('forum_topics', 'forum_reports.topic_id', '=', 'forum_topics.id')
            ->leftJoin('users', 'forum_reports.to_user_id', '=', 'users.id')
            ->select('forum_reports.*', 'forum_topics.title as topic_title', 'forum_topics.slug as topic_slug', 'forum_topics.content as topic_content', 'users.name as reported_user_name', 'users.email as reported_user_email', 'users.avatar as reported_user_avatar')
            ->whereNotNull('topic_id')
            ->groupBy('forum_reports.topic_id')
            ->orderBy('processed_at', 'asc')
            ->orderBy('forum_reports.id', 'desc')
            ->paginate(20);

        $reports_pending = DB::table('forum_reports')
            ->whereNotNull('topic_id')
            ->whereNull('processed_at')
            ->count();

        return view('admin/account', [
            'view_file' => 'forum.reports-topics',
            'active_submenu' => 'forum.reports.topics',
            'reports' => $reports,
            'reports_pending' => $reports_pending,
        ]);
    }


    /**
     * Display posts reports
     */
    public function reports_posts(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));
        if (!check_access('forum')) return redirect(route('admin'));

        $reports = DB::table('forum_reports')
            ->leftJoin('forum_posts', 'forum_reports.post_id', '=', 'forum_posts.id')
            ->leftJoin('users', 'forum_reports.to_user_id', '=', 'users.id')
            ->select(
                'forum_reports.*',
                'forum_posts.content as post_content',
                'users.name as reported_user_name',
                'users.email as reported_user_email',
                'users.avatar as reported_user_avatar',
                DB::raw("(SELECT id FROM forum_topics WHERE forum_posts.topic_id = forum_topics.id) topic_id"),
                DB::raw("(SELECT title FROM forum_topics WHERE forum_posts.topic_id = forum_topics.id) topic_title"),
                DB::raw("(SELECT slug FROM forum_topics WHERE forum_posts.topic_id = forum_topics.id) topic_slug")
            )
            ->whereNotNull('post_id')
            ->orderBy('processed_at', 'asc')
            ->orderBy('forum_reports.id', 'desc')
            ->paginate(20);

        $reports_pending = DB::table('forum_reports')
            ->whereNotNull('post_id')
            ->whereNull('processed_at')
            ->count();


        return view('admin/account', [
            'view_file' => 'forum.reports-posts',
            'active_submenu' => 'forum.reports.posts',
            'reports' => $reports,
            'reports_pending' => $reports_pending,
        ]);
    }



    /**
     * Update the specified resource     
     */
    public function reports_topics_update(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));
        if (!check_access('forum')) return redirect(route('admin'));

        $id = $request->id; // report id

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $q = DB::table('forum_reports')->where('id', $id)->first();
        $topic = DB::table('forum_topics')->where('id', $q->topic_id)->first();

        $inputs = $request->all(); // retrieve all of the input data as an array 
        if ($request->has('close_topic')) $close_topic = 1;
        else $close_topic = 0;
        if ($request->has('delete_topic')) $delete_topic = 1;
        else $delete_topic = 0;

        if ($inputs['warning']) {
            if ($delete_topic) {
                $topic_id = null;
                $report_id = null;
            } else {
                $topic_id = $q->topic_id;
                $report_id = $id;
            }

            DB::table('forum_user_warnings')->insert([
                'user_id' => $q->to_user_id,
                'restricted_by_user_id' => Auth::user()->id,
                'topic_id' => $topic_id,
                'warning' => $inputs['warning'],
                'report_id' => $report_id,
                'created_at' => now(),
            ]);
        }

        if ($inputs['deny_topic_create_days'] or $inputs['deny_post_create_days']) {
            $days_deny_topic = $inputs['deny_topic_create_days'];
            $days_deny_post = $inputs['deny_post_create_days'];
            if ($days_deny_topic) $deny_topic_create_expire_at = date("Y-m-d", strtotime("+$days_deny_topic day"));
            if ($days_deny_post) $deny_post_create_expire_at = date("Y-m-d", strtotime("+$days_deny_post day"));

            DB::table('forum_restrictions')->insert([
                'user_id' => $q->to_user_id,
                'restricted_by_user_id' => Auth::user()->id,
                'report_id' => $id,
                'deny_topic_create_days' => $days_deny_topic,
                'deny_topic_create_expire_at' => $deny_topic_create_expire_at ?? null,
                'deny_post_create_days' => $days_deny_post,
                'deny_post_create_expire_at' => $deny_post_create_expire_at ?? null,
                'created_at' => now(),
            ]);
        }

        if ($inputs['internal_notes']) {
            DB::table('users_internal_notes')->insert([
                'user_id' => $q->to_user_id,
                'created_by_user_id' => Auth::user()->id,
                'note' => $inputs['internal_notes'],
                'created_at' => now(),
            ]);
        }

        if ($close_topic == 1) {
            DB::table('forum_topics')
                ->where('id', $q->topic_id)
                ->update(['status' => 'closed']);
        }


        if ($delete_topic == 1) {
            // get topic users (used to recount activity for each user)
            $topic_posts = DB::table('forum_posts')->where('topic_id', $q->topic_id)->get();
            $topic_users = array();
            foreach ($topic_posts as $post) {
                $post_user_id = $post->user_id;
                array_push($topic_users, $post_user_id);
            }
            $topic_users = array_unique($topic_users);

            DB::table('forum_posts')->where('topic_id', $q->topic_id)->delete();
            DB::table('forum_topics')->where('id', $q->topic_id)->delete();
            DB::table('forum_reports')->where('topic_id', $q->topic_id)->delete();

            $this->ForumModel->recount_categ_items($topic->categ_id);

            // recount user activity for topic author
            $this->ForumModel->recount_user_activity($topic->user_id);

            // recount users activity for topic posts users            
            foreach ($topic_users as $post_user_id) {
                echo ($post_user_id);
                $this->ForumModel->recount_user_activity($post_user_id);
            }
        }

        DB::table('forum_reports')
            ->where('topic_id', $q->topic_id)
            ->update(['processed_at' => now()]);

        return redirect(route('admin.forum.reports.topics'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function reports_topics_delete(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));
        if (!check_access('forum')) return redirect(route('admin'));

        $id = $request->id; // report id

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $q = DB::table('forum_reports')->where('id', $id)->first();
        $topic_id = $q->topic_id;

        DB::table('forum_reports')->where('topic_id', $topic_id)->delete();

        return redirect(route('admin.forum.reports.topics'))->with('success', 'deleted');
    }



    /**
     * Update the specified resource     
     */
    public function reports_posts_update(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));
        if (!check_access('forum')) return redirect(route('admin'));

        $id = $request->id; // report id

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $q = DB::table('forum_reports')->where('id', $id)->first();
        $post = DB::table('forum_posts')->where('id', $q->post_id)->first();
        $topic = DB::table('forum_topics')->where('id', $post->topic_id)->first();

        $inputs = $request->all(); // retrieve all of the input data as an array 
        if ($request->has('delete_post')) $delete_post = 1;
        else $delete_post = 0;

        if ($inputs['warning']) {
            if ($delete_post) {
                $post_id = null;
                $report_id = null;
            } else {
                $post_id = $q->post_id;
                $report_id = $id;
            }

            DB::table('forum_user_warnings')->insert([
                'user_id' => $q->to_user_id,
                'restricted_by_user_id' => Auth::user()->id,
                'post_id' => $post_id,
                'warning' => $inputs['warning'],
                'report_id' => $report_id,
                'created_at' => now(),
            ]);
        }

        if ($inputs['deny_topic_create_days'] or $inputs['deny_post_create_days']) {
            $days_deny_topic = $inputs['deny_topic_create_days'];
            $days_deny_post = $inputs['deny_post_create_days'];
            if ($days_deny_topic) $deny_topic_create_expire_at = date("Y-m-d", strtotime("+$days_deny_topic day"));
            if ($days_deny_post) $deny_post_create_expire_at = date("Y-m-d", strtotime("+$days_deny_post day"));

            DB::table('forum_restrictions')->insert([
                'user_id' => $q->to_user_id,
                'restricted_by_user_id' => Auth::user()->id,
                'report_id' => $id,
                'deny_topic_create_days' => $days_deny_topic,
                'deny_topic_create_expire_at' => $deny_topic_create_expire_at ?? null,
                'deny_post_create_days' => $days_deny_post,
                'deny_post_create_expire_at' => $deny_post_create_expire_at ?? null,
                'created_at' => now(),
            ]);
        }

        if ($inputs['internal_notes']) {
            DB::table('users_internal_notes')->insert([
                'user_id' => $q->to_user_id,
                'created_by_user_id' => Auth::user()->id,
                'note' => $inputs['internal_notes'],
                'created_at' => now(),
            ]);
        }

        if ($delete_post == 1) {
            // get topic users (used to recount activity for each user)
            $topic_posts = DB::table('forum_posts')->where('topic_id', $q->topic_id)->get();
            $topic_users = array();
            foreach ($topic_posts as $post) {
                $post_user_id = $post->user_id;
                array_push($topic_users, $post_user_id);
            }
            $topic_users = array_unique($topic_users);

            DB::table('forum_posts')->where('id', $q->post_id)->delete();
            DB::table('forum_reports')->where('post_id', $q->post_id)->delete();

            $this->ForumModel->recount_categ_items($topic->categ_id);

            // recount user activity for post author
            $this->ForumModel->recount_user_activity($post->user_id);
        }

        DB::table('forum_reports')
            ->where('post_id', $q->post_id)
            ->update(['processed_at' => now()]);

        return redirect(route('admin.forum.reports.posts'))->with('success', 'updated');
    }


    public function config(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));

        return view('admin/account', [
            'view_file' => 'forum.config',
            'active_menu' => 'forum',
            'active_submenu' => 'forum.config',
            'menu_section' => 'config.general',
        ]);
    }


    public function update_config(Request $request)
    {

        if (!check_admin_module('forum')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        Core::update_config($inputs);

        return redirect($request->Url())->with('success', 'updated');
    }


    public function seo()
    {
        return view('admin/account', [
            'view_file' => 'forum.seo',
            'active_menu' => 'forum',
            'active_submenu' => 'forum.config',
            'menu_section' => 'config.seo',
        ]);
    }


    public function update_seo(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $module_id = DB::table('sys_modules')->where('module', 'forum')->value('id');

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_modules_meta')->updateOrInsert(['module_id' => $module_id, 'lang_id' => $lang->id], ['meta_title' => $request['meta_title_' . $lang->id], 'meta_description' => $request['meta_description_' . $lang->id]]);
        }

        return redirect($request->Url())->with('success', 'updated');
    }
}
