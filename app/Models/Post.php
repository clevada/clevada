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
use Illuminate\Support\Str;
use DB;

class Post extends Model
{

    protected $fillable = ['parent_id', 'title', 'slug', 'active', 'lang_id', 'cf_group_id'];
    protected $table = 'posts_categ';

    public function children()
    {
        return $this->hasMany('App\Models\Post', 'parent_id')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select(
                'posts_categ.*',
                'sys_lang.name as lang_name',
                'sys_lang.code as lang_code',
                DB::raw('(SELECT label FROM sys_global_sections WHERE posts_categ.top_section_id = sys_global_sections.id) as top_section_label'),
                DB::raw('(SELECT label FROM sys_global_sections WHERE posts_categ.bottom_section_id = sys_global_sections.id) as bottom_section_label'),
                DB::raw('(SELECT label FROM sys_sidebars WHERE posts_categ.sidebar_id = sys_sidebars.id) as sidebar_label')
            )
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc');
    }


    public function childCategories()
    {
        return $this->hasMany('App\Models\Post', 'parent_id')
            ->with('children')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang_code')
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc');
    }


    public function active_children()
    {
        return $this->hasMany('App\Models\Post', 'parent_id')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang_code')
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc');
    }


    public function active_childCategories()
    {
        return $this->hasMany('App\Models\Post', 'parent_id')
            ->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
            ->select('posts_categ.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang_code')
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc')
            ->with('active_children');
    }


    public static function recount_categ_items($categ_id)
    {
        // count categ posts
        $counter = DB::table('posts')
            ->where('categ_id', $categ_id)
            ->count();

        // count categ posts
        $q = DB::table('posts_categ')
            ->where('id', $categ_id)
            ->first();
        if ($q) {
            $tree_ids = $q->tree_ids;
            $categ_tree_counter = 0;

            $array_tree = explode(',', $tree_ids);
            foreach ($array_tree as $tree_categ_id) {
                $tree_counter = DB::table('posts')
                    ->where('categ_id', $tree_categ_id)
                    ->count();
                $categ_tree_counter = $categ_tree_counter + $tree_counter;
            }
        }

        DB::table('posts_categ')
            ->where('id', $categ_id)
            ->update([
                'count_items' => $counter ?? 0,
                'count_tree_items' => $categ_tree_counter ?? 0,
            ]);

        return;
    }


    public static function recount_all_categs_items()
    {
        $categs = DB::table('posts_categ')
            ->get();

        foreach ($categs as $categ) {
            // count categ posts
            $counter = DB::table('posts')
                ->where('categ_id', $categ->id)
                ->count();

            // count categ posts
            $q = DB::table('posts_categ')
                ->where('id', $categ->id)
                ->first();
            if ($q) {
                $tree_ids = $q->tree_ids;
                $categ_tree_counter = 0;

                $array_tree = explode(',', $tree_ids);
                foreach ($array_tree as $tree_categ_id) {
                    $tree_counter = DB::table('posts')
                        ->where('categ_id', $tree_categ_id)
                        ->count();
                    $categ_tree_counter = $categ_tree_counter + $tree_counter;
                }
            }

            DB::table('posts_categ')
                ->where('id', $categ->id)
                ->update([
                    'count_items' => $counter ?? 0,
                    'count_tree_items' => $categ_tree_counter ?? 0,
                ]);
        }

        return;
    }


    public function regenerate_tree_ids()
    {
        $root_categories = DB::table('posts_categ')->get();
        foreach ($root_categories as $root) {

            $id = $root->id;

            $tree = array($id);

            $q = DB::table('posts_categ')->where('parent_id', $id)->first();

            if ($q) {
                $tree = array_unique(array_merge($tree, array($q->id)));

                $q2 = DB::table('posts_categ')->where('parent_id', $q->id)->orWhere('parent_id', $q->parent_id)->get();

                foreach ($q2 as $item) {
                    $tree = array_unique(array_merge($tree, array($item->id)));

                    $q3 = DB::table('posts_categ')->where('parent_id', $item->id)->orWhere('parent_id', $item->parent_id)->get();
                    foreach ($q3 as $item2) {
                        $tree = array_unique(array_merge($tree, array($item2->id)));

                        $q4 = DB::table('posts_categ')->where('parent_id', $item2->id)->orWhere('parent_id', $item2->parent_id)->get();
                        foreach ($q4 as $item3) {
                            $tree = array_unique(array_merge($tree, array($item3->id)));

                            $q5 = DB::table('posts_categ')->where('parent_id', $item3->id)->orWhere('parent_id', $item3->parent_id)->get();
                            foreach ($q5 as $item4) {
                                $tree = array_unique(array_merge($tree, array($item4->id)));

                                $q6 = DB::table('posts_categ')->where('parent_id', $item4->id)->orWhere('parent_id', $item4->parent_id)->get();
                                foreach ($q6 as $item5) {
                                    $tree = array_unique(array_merge($tree, array($item5->id)));
                                }
                            }
                        }
                    }
                }
            }

            $values = implode(",", $tree);

            DB::table('posts_categ')
                ->where('id', $id)
                ->update([
                    'tree_ids' => $values ?? null,
                ]);
        } // end foreach        


        $inactive_categs = DB::table('posts_categ')->where('active', 0)->get();
        foreach ($inactive_categs as $categ) {
            $inactive_tree = DB::table('posts_categ')->where('id', $categ->id)->first();
            $inactive_tree_ids = $inactive_tree->tree_ids;

            $myArray = explode(',', $inactive_tree_ids);

            foreach ($myArray as $categ_id) {
                DB::table('posts_categ')->where('id', $categ_id)->update(['active' => 0]);
            }
        }

        return;
    }


    public static function recount_post_likes($post_id)
    {
        // count likes
        $counter = DB::table('posts_likes')->where('post_id', $post_id)->count();

        DB::table('posts')
            ->where('id', $post_id)
            ->update([
                'likes' => $counter,
            ]);

        return;
    }


    public static function recount_post_comments($post_id)
    {
        // count comments
        $counter = DB::table('posts_comments')->where('post_id', $post_id)->count();

        DB::table('posts')
            ->where('id', $post_id)
            ->update([
                'comments' => $counter,
            ]);

        return;
    }


    public static function add_tag($tag, $post_id)
    {

        $post = DB::table('posts')
            ->where('id', $post_id)
            ->first();
        if (!$post) return;

        $slug = Str::slug($tag, '-');

        DB::table('posts_tags')
            ->updateOrInsert(
                ['tag' => $tag, 'post_id' => $post->id, 'lang_id' => $post->lang_id],
                ['tag' => $tag, 'post_id' => $post->id, 'lang_id' => $post->lang_id, 'slug' => $slug]
            );

        // count tags
        $counter = DB::table('posts_tags')->where('tag', $tag)->where('lang_id', $post->lang_id)->count();

        DB::table('posts_tags')
            ->where(['tag' => $tag, 'lang_id' => $post->lang_id])
            ->update([
                'counter' => $counter,
            ]);


        return;
    }


    public static function get_uncategorized_categ_id()
    {
        $categ = DB::table('posts_categ')
            ->where('slug', 'uncategorized')
            ->first();

        return $categ->id ?? null;
    }
}
