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

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Forum;
use DB;
use Auth;
use Str;
use App\Models\Upload;

class ForumController extends Controller
{ 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ForumModel = new Forum();
        $this->UploadModel = new Upload();
    }

    /**
     * Forum index
     */
    public function index()
    {

        if (!check_module('forum')) return redirect(route('homepage'));

        $categories = Forum::whereNull('parent_id')->where('active', 1)->with('childCategories')->select('forum_categ.*')->orderBy('position', 'asc')->get();

        return view('frontend/builder/forum', [
            'categories' => $categories,

            'sidebar_id' => template("sidebar_id_forum") ?? null,
            'sidebar_position' => template("sidebar_position_forum") ?? null,
            'top_section_id' => template('top_section_id_forum') ?? null, 
            'bottom_section_id' => template('bottom_section_id_forum') ?? null, 
        ]);
    }



    /**
     * Forum categ
     */
    public function categ(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $slug = $request->slug;

        $categ = DB::table('forum_categ')->where('slug', $slug)->where('active', 1)->first();
        if (!$categ) abort(404);

        $categ_topics = DB::table('forum_topics')
            ->leftJoin('users', 'forum_topics.user_id', '=', 'users.id')
            ->select(
                'forum_topics.*',
                'users.name as author_name',
                'users.email as author_email',
                'users.avatar as author_avatar',
                'users.slug as author_slug',
                DB::raw('(SELECT forum_topics.created_at FROM forum_topics WHERE forum_topics.categ_id = ' . $categ->id . ' ORDER BY forum_topics.id DESC LIMIT 1) as latest_topic_created_at'),
                DB::raw('(SELECT forum_posts.created_at FROM forum_posts WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_created_at'),
                DB::raw('(SELECT users.name FROM users LEFT JOIN forum_posts ON forum_posts.user_id = users.id WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_author_name'),
                DB::raw('(SELECT users.avatar FROM users LEFT JOIN forum_posts ON forum_posts.user_id = users.id WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_author_avatar')
            )
            ->where('forum_topics.status', '!=', 'deleted')
            ->where('forum_topics.categ_id', $categ->id)
            ->orderBy('sticky', 'desc')
            ->orderBy('forum_topics.last_activity_at', 'desc')
            ->orderBy('forum_topics.id', 'desc')
            ->paginate(20);

        return view('frontend/builder/forum-categ', [
            'categ' => $categ,
            'categ_topics' => $categ_topics,
        ]);
    }


    /**
     * Forum topic
     */
    public function topic(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $topic_id = $request->id;
        $topic_slug = $request->slug;

        $topic = DB::table('forum_topics')
            ->leftJoin('users', 'forum_topics.user_id', '=', 'users.id')
            ->select('forum_topics.*', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar', 'users.slug as author_slug', 'users.created_at as author_created_at')
            ->where('forum_topics.id', $topic_id)
            ->where('forum_topics.slug', $topic_slug)
            ->first();
        if (!$topic) abort(404);

        $categ = DB::table('forum_categ')
            ->where('id', $topic->categ_id)
            ->first();

        $posts = DB::table('forum_posts')
            ->leftJoin('users', 'forum_posts.user_id', '=', 'users.id')
            ->select('forum_posts.*', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar', 'users.slug as author_slug', 'users.created_at as author_created_at')
            ->where('topic_id', $topic_id);

        if ($categ->type == 'question') $posts = $posts->orderBy('forum_posts.count_best_answer', 'desc');

        $posts = $posts->orderBy('forum_posts.id', 'asc')
            ->paginate(25);

        // update hits
        DB::table('forum_topics')
            ->where('id', $topic_id)
            ->increment('hits');

        return view('frontend/builder/forum-topic', [
            'categ' => $categ,
            'topic' => $topic,
            'posts' => $posts,
        ]);
    }


    /**
     * Create new topic page
     */
    public function create_topic(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $categ_id = $request->categ_id;

        $categories = Forum::whereNull('parent_id')->where('active', 1)->with('childCategories')->select('forum_categ.*')->orderBy('position', 'asc')->get();

        return view('frontend/builder/forum-new-topic', [
            'categ_id' => $categ_id,
            'categories' => $categories
        ]);
    }


     /**
     * Search results categ
     */
    public function search_results(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $s = $request->s;
        
        $results = DB::table('forum_topics')
            ->leftJoin('users', 'forum_topics.user_id', '=', 'users.id')
            ->select(
                'forum_topics.*',
                'users.name as author_name',
                'users.email as author_email',
                'users.avatar as author_avatar',
                'users.slug as author_slug',                
                DB::raw('(SELECT forum_posts.created_at FROM forum_posts WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_created_at'),
                DB::raw('(SELECT users.name FROM users LEFT JOIN forum_posts ON forum_posts.user_id = users.id WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_author_name'),
                DB::raw('(SELECT users.avatar FROM users LEFT JOIN forum_posts ON forum_posts.user_id = users.id WHERE forum_posts.topic_id = forum_topics.id ORDER BY forum_posts.id DESC LIMIT 1) as latest_post_author_avatar')
            )
            ->where('forum_topics.status', '!=', 'deleted')
            ->where('forum_topics.title', 'like', "%$s%")
            ->orderBy('sticky', 'desc')
            ->orderBy('forum_topics.last_activity_at', 'desc')
            ->orderBy('forum_topics.id', 'desc')
            ->paginate(20);

        return view('frontend/builder/forum-search-results', [
            's' => $s,
            'results' => $results,
        ]);
    }


    /**
     * Create new topic resource
     */
    public function store_topic(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        // check if user is logged
        if (!Auth::user()) return redirect(route('login'));

        // disable action in demo mode:
        if (config('app.demo_mode')) exit('This action is disabled in demo mode');

        $inputs = $request->all(); // retrieve all of the input data as an array 

        if (!$inputs['categ_id']) return redirect($request->Url())->with('error', 'error_categ');
        if (!$inputs['title']) return redirect($request->Url())->with('error', 'error_title');
        if (!$inputs['content']) return redirect($request->Url())->with('error', 'error_content');

        DB::table('forum_topics')->insert([
            'user_id' => Auth::user()->id,
            'categ_id' => $inputs['categ_id'],
            'title' => substr($inputs['title'], 0, 255),
            'slug' => Str::slug($inputs['title'], '-'),
            'content' => $inputs['content'],
            'status' => 'active',
            'count_posts' => 0,
            'hits' => 0,
            'created_at' => now(),
            'last_activity_at' => now(),
        ]);

        $topic_id = DB::getPdo()->lastInsertId();
        $this->ForumModel->update_topic_last_activity($topic_id);
        $this->ForumModel->recount_categ_items($inputs['categ_id']);
        $this->ForumModel->recount_user_activity(Auth::user()->id);

        $categ = DB::table('forum_categ')
            ->where('id', $inputs['categ_id'])
            ->first();

        // upload images            
        if (($this->config->forum_upload_images_enabled ?? null) == 'yes') {
            for ($i = 1; $i <= 6; $i++) {
                if ($request->hasFile('image_' . $i)) {
                    $validator = Validator::make($request->all(), ['image_' . $i => 'mimes:jpeg,bmp,png,gif,webp']);
                    if (!$validator->fails()) {
                        $image_db = $this->UploadModel->upload_image($request, 'image_' . $i, 'resize');
                        DB::table('forum_attachments')->insert([
                            'topic_id' => $topic_id,
                            'file' => $image_db,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            }
        }

        return redirect(route('forum.categ', ['slug' => $categ->slug]))->with('success', 'topic_created');
    }


    /**
     * Create new post
     */
    public function store_post(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        // check if user is logged
        if (!Auth::user()) return redirect(route('login'));

        // disable action in demo mode:
        if (config('app.demo_mode')) exit('This action is disabled in demo mode');

        $topic_id = $request->id;

        $inputs = $request->all(); // retrieve all of the input data as an array 

        if (!$request->content) return redirect($request->Url())->with('error', 'error_content');

        $topic = DB::table('forum_topics')
            ->where('id', $topic_id)
            ->first();

        if (!$topic) abort(404);
        if ($topic->status != 'active') return redirect($request->Url())->with('error', 'error_topic_not_active');

        $categ_id = $topic->categ_id ?? null;

        DB::table('forum_posts')->insert([
            'topic_id' => $topic_id,
            'user_id' => Auth::user()->id,
            'categ_id' => $categ_id,
            'content' => $inputs['content'],
            'status' => 'active',
            'created_at' => now(),
        ]);

        $post_id = DB::getPdo()->lastInsertId();

        $this->ForumModel->update_topic_last_activity($topic_id);
        $this->ForumModel->recount_categ_items($categ_id);
        $this->ForumModel->recount_user_activity(Auth::user()->id);

        // upload images            
        if (($this->config->forum_upload_images_enabled ?? null) == 'yes') {
            for ($i = 1; $i <= 6; $i++) {
                if ($request->hasFile('image_' . $i)) {
                    $validator = Validator::make($request->all(), ['image_' . $i => 'mimes:jpeg,bmp,png,gif,webp']);
                    if (!$validator->fails()) {
                        $image_db = $this->UploadModel->upload_image($request, 'image_' . $i, 'resize');
                        DB::table('forum_attachments')->insert([
                            'topic_id' => $topic_id,
                            'post_id' => $post_id,
                            'file' => $image_db,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            }
        }

        return redirect(route('forum.topic', ['id' => $topic->id, 'slug' => $topic->slug]) . '#' . $post_id)->with('success', 'post_created');
    }



    /**
     * Report content
     */
    public function report(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $type = $request->type;
        $id = $request->id;
        if (!$type or !$id) abort(404);

        if ($type == 'topic') {
            $topic = DB::table('forum_topics')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$topic) abort(404);
        }

        if ($type == 'post') {
            $post = DB::table('forum_posts')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$post) abort(404);
        }

        return view('frontend/buider/forum-report', [
            'type' => $type,
            'topic' => $topic ?? null,
            'post' => $post ?? null,
            'id' => $id,
        ]);
    }


    /**
     * Report topic
     */
    public function create_report(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        // disable action in demo mode:
        if (config('app.demo_mode')) exit('This action is disabled in demo mode');

        // check if user is logged
        if (!Auth::user()) return redirect(route('login'));

        $id = $request->id;
        $type = $request->type;
        $reason = $request->reason;

        if ($type == 'topic') {
            $topic = DB::table('forum_topics')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$topic) return redirect(route('forum'));

            DB::table('forum_reports')->insert([
                'from_user_id' => Auth::user()->id,
                'to_user_id' => $topic->user_id,
                'topic_id' => $id,
                'post_id' => null,
                'reason' => $reason,
                'created_at' => now(),
            ]);

            return redirect(route('forum.topic', ['id' => $id, 'slug' => $topic->slug]))->with('success', 'reported');
        }

        if ($type == 'post') {
            $post = DB::table('forum_posts')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$post) return redirect(route('forum'));
            DB::table('forum_reports')->insert([
                'from_user_id' => Auth::user()->id,
                'to_user_id' => $post->user_id,
                'topic_id' => null,
                'post_id' => $id,
                'reason' => $reason,
                'created_at' => now(),
            ]);

            $topic = DB::table('forum_topics')
                ->where('id', $post->topic_id)
                ->where('status', 'active')
                ->first();

            return redirect(route('forum.topic', ['id' => $topic->id, 'slug' => $topic->slug]))->with('success', 'reported');
        }
    }


    /**
     * Process post like
     */
    public function like(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $type = $request->type;
        $content_id = $request->id;

        // check if logged
        if (!Auth::check()) return response('login_required');

        if ($type == 'post') {

            $post = DB::table('forum_posts')
                ->where('id', $content_id)
                ->first();
            if (!$post) return;

            $topic = DB::table('forum_topics')
                ->where('id', $post->topic_id)
                ->first();
            if (!$topic) return;
            if ($topic->status != 'active') return;
            if ($post->user_id == Auth::user()->id) return; // users can not give like to themselfs

            // check if already liked
            $liked = DB::table('forum_likes')
                ->where('post_id', $content_id)
                ->where('user_id', Auth::user()->id)
                ->first();
            if ($liked) return response('already_liked');

            // like post
            DB::table('forum_likes')->insert([
                'user_id' => Auth::user()->id,
                'post_id' => $content_id,
                'created_at' => now(),
            ]);

            DB::table('forum_posts')
                ->where('id', $content_id)
                ->increment('count_likes');

            $this->ForumModel->recount_user_activity($post->user_id);

            return response('liked');
        }

        if ($type == 'topic') {
            $topic = DB::table('forum_topics')
                ->where('id', $content_id)
                ->first();
            if (!$topic) return;
            if ($topic->status != 'active') return;

            if ($topic->user_id == Auth::user()->id) return; // users can not give like to themselfs

            // check if already liked
            $liked = DB::table('forum_likes')
                ->where('topic_id', $content_id)
                ->where('user_id', Auth::user()->id)
                ->first();
            if ($liked) return response('already_liked');

            // like post
            DB::table('forum_likes')->insert([
                'user_id' => Auth::user()->id,
                'topic_id' => $content_id,
                'created_at' => now(),
            ]);

            DB::table('forum_topics')
                ->where('id', $content_id)
                ->increment('count_likes');

            $this->ForumModel->recount_user_activity($topic->user_id);

            return response('liked');
        }
    }


    /**
     * Process post best answer
     */
    public function best_answer(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        $post_id = $request->id;

        // check if logged
        if (!Auth::check()) return response('login_required');

        // check if already voted
        $marked = DB::table('forum_best_answers')
            ->where('post_id', $post_id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($marked) return response('already_voted');

        $post = DB::table('forum_posts')
            ->where('id', $post_id)
            ->first();
        $topic = DB::table('forum_topics')
            ->where('id', $post->topic_id)
            ->first();
        if (!$topic) return;
        if ($topic->status != 'active') return;
        if ($post->user_id == Auth::user()->id) return; // users can not give vote to themselfs

        DB::table('forum_best_answers')->insert([
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
            'topic_id' => $post->topic_id,
            'created_at' => now(),
        ]);

        DB::table('forum_posts')
            ->where('id', $post_id)
            ->increment('count_best_answer');

        $this->ForumModel->recount_user_activity($post->user_id);

        return response('voted');
    }


    /**
     * Quote content
     */
    public function quote(Request $request)
    {
        if (!check_module('forum')) return redirect(route('homepage'));

        // disable action in demo mode:
        if (config('app.demo_mode')) exit('This action is disabled in demo mode');

        // check if user is logged
        if (!Auth::user()) return redirect(route('login'));

        $id = $request->id;
        $type = $request->type;

        if ($type == 'topic') {
            $topic = DB::table('forum_topics')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$topic) return redirect(route('forum'));

            $quote_content = $topic->content;
        }

        if ($type == 'post') {
            $post = DB::table('forum_posts')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
            if (!$post) return redirect(route('forum'));

            $topic = DB::table('forum_topics')
                ->where('id', $post->topic_id)
                ->where('status', 'active')
                ->first();
            if (!$topic) return redirect(route('forum'));

            $quote_content = $post->content;
        }

        return redirect(route('forum.topic', ['id' => $topic->id, 'slug' => $topic->slug]) . '#reply')->with('quote_content', $quote_content);
    }
}
