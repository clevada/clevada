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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;

class Forum extends Model
{
    protected $fillable = ['parent_id', 'title', 'slug', 'active'];
    protected $table = 'forum_categ';

    public function children()
    {
        return $this->hasMany('App\Models\Forum', 'parent_id')->orderBy('position', 'asc')->orderBy('title', 'asc');
    }


    public function childCategories()
    {
        return $this->hasMany('App\Models\Forum', 'parent_id')->with('children')->orderBy('position', 'asc')->orderBy('title', 'asc');
    }


    public function active_children()
    {
        return $this->hasMany('App\Models\Forum', 'parent_id')->where('active', 1)->orderBy('position', 'asc')->orderBy('title', 'asc');
    }


    public static function get_uncategorized_categ_id()
    {
        $q = DB::table('forum_categ')
            ->where('slug', 'uncategorized')
            ->first();

        return $q->id ?? null;
    }


    public static function recount_categ_items($categ_id)
    {
        $topics_counter = DB::table('forum_topics')
            ->where('categ_id', $categ_id)
            ->count();

        $posts_counter = DB::table('forum_posts')
            ->where('categ_id', $categ_id)
            ->count();

        $q = DB::table('forum_categ')
            ->where('id', $categ_id)
            ->first();
        if ($q) {
            $tree_ids = $q->tree_ids;
            $topics_tree_counter = 0;
            $posts_tree_counter = 0;

            $array_tree = explode(',', $tree_ids);
            foreach ($array_tree as $tree_categ_id) {
                $categ_topics_tree_counter = DB::table('forum_topics')
                    ->where('categ_id', $tree_categ_id)
                    ->count();
                $topics_tree_counter = $topics_tree_counter + $categ_topics_tree_counter;

                $categ_posts_tree_counter = DB::table('forum_posts')
                    ->where('categ_id', $tree_categ_id)
                    ->count();
                $posts_tree_counter = $posts_tree_counter + $categ_posts_tree_counter;
            }
        }

        DB::table('forum_categ')
            ->where('id', $categ_id)
            ->update([
                'count_topics' => $topics_counter ?? 0,
                'count_tree_topics' => $topics_tree_counter ?? 0,
                'count_posts' => $posts_counter ?? 0,
                'count_tree_posts' => $posts_tree_counter ?? 0,
            ]);

        return;
    }



    public function regenerate_tree_ids()
    {
        $root_categories = DB::table('forum_categ')->get();
        foreach ($root_categories as $root) {

            $id = $root->id;

            $tree = array($id);

            $q = DB::table('forum_categ')->where('parent_id', $id)->first();

            if ($q) {
                $tree = array_unique(array_merge($tree, array($q->id)));

                $q2 = DB::table('forum_categ')->where('parent_id', $q->id)->orWhere('parent_id', $q->parent_id)->get();

                foreach ($q2 as $item) {
                    $tree = array_unique(array_merge($tree, array($item->id)));

                    $q3 = DB::table('forum_categ')->where('parent_id', $item->id)->orWhere('parent_id', $item->parent_id)->get();
                    foreach ($q3 as $item2) {
                        $tree = array_unique(array_merge($tree, array($item2->id)));

                        $q4 = DB::table('forum_categ')->where('parent_id', $item2->id)->orWhere('parent_id', $item2->parent_id)->get();
                        foreach ($q4 as $item3) {
                            $tree = array_unique(array_merge($tree, array($item3->id)));

                            $q5 = DB::table('forum_categ')->where('parent_id', $item3->id)->orWhere('parent_id', $item3->parent_id)->get();
                            foreach ($q5 as $item4) {
                                $tree = array_unique(array_merge($tree, array($item4->id)));

                                $q6 = DB::table('forum_categ')->where('parent_id', $item4->id)->orWhere('parent_id', $item4->parent_id)->get();
                                foreach ($q6 as $item5) {
                                    $tree = array_unique(array_merge($tree, array($item5->id)));
                                }
                            }
                        }
                    }
                }
            }

            $values = implode(",", $tree);

            DB::table('forum_categ')
                ->where('id', $id)
                ->update([
                    'tree_ids' => $values ?? null,
                ]);
        } // end foreach        


        $inactive_categs = DB::table('forum_categ')->where('active', 0)->get();
        foreach ($inactive_categs as $categ) {
            $inactive_tree = DB::table('forum_categ')->where('id', $categ->id)->first();
            $inactive_tree_ids = $inactive_tree->tree_ids;

            $myArray = explode(',', $inactive_tree_ids);

            foreach ($myArray as $categ_id) {
                DB::table('forum_categ')->where('id', $categ_id)->update(['active' => 0]);
            }
        }

        return;
    }



    public function recount_user_activity($user_id)
    {
        $UserModel = new User();

        $topics_counter = DB::table('forum_topics')
            ->where('user_id', $user_id)
            ->count();

        $posts_counter = DB::table('forum_posts')
            ->where('user_id', $user_id)
            ->count();

        // likes received            
        $posts_likes = DB::table('forum_posts')
            ->where('user_id', $user_id)
            ->sum('count_likes');
        $topics_likes = DB::table('forum_topics')
            ->where('user_id', $user_id)
            ->sum('count_likes');

        // best answers received            
        $posts_best_answers = DB::table('forum_posts')
            ->where('user_id', $user_id)
            ->sum('count_best_answer');

        $UserModel->add_user_extra($user_id, 'count_forum_topics', $topics_counter);
        $UserModel->add_user_extra($user_id, 'count_forum_posts', $posts_counter);
        $UserModel->add_user_extra($user_id, 'count_forum_likes_received', $posts_likes + $topics_likes);
        $UserModel->add_user_extra($user_id, 'count_forum_best_answers_received', $posts_best_answers);

        return;
    }


    public function update_topic_last_activity($topic_id)
    {
        $topic = DB::table('forum_topics')
            ->where('id', $topic_id)
            ->first();
        if (!$topic) return;

        $latest_post = DB::table('forum_posts')
            ->where('topic_id', $topic_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($latest_post) {
            $q_last_activity = DB::table('forum_topics')
                ->where('id', $topic_id)
                ->update(['last_activity_at' => $latest_post->created_at]);
        } else {
            $q_last_activity = DB::table('forum_topics')
                ->where('id', $topic_id)
                ->update(['last_activity_at' => now()]);
        }

        return;
    }



    public static function check_signature($user_id)
    {
        $q = DB::table('forum_categ')
            ->where('slug', 'uncategorized')
            ->first();

        return $q->id ?? null;
    }


    public static function regenerate_types($categ_id)
    {
        $parent_id = DB::table('forum_categ')->where('id', $categ_id)->value('parent_id');
        if ($parent_id) {
            $tree_ids = DB::table('forum_categ')->where('id', $parent_id)->value('tree_ids');
            $type = DB::table('forum_categ')->where('id', $parent_id)->value('type');
            $array_tree = explode(',', $tree_ids);
            foreach ($array_tree as $tree_categ_id) {
                DB::table('forum_categ')->where('id', $tree_categ_id)->update(['type' => $type]);
            }
        }

        return;
    }
}
