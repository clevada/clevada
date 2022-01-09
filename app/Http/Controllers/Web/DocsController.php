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
use App\Models\Doc;
use DB;
use Auth;

class DocsController extends Controller
{
    /**
     * Docs index
     */
    public function index()
    {

        if (!check_module('docs')) return redirect('/');

        $lang_id = default_lang()->id;

        $featured_articles = DB::table('docs_content')
            ->leftJoin('docs', 'docs_content.doc_id', '=', 'docs.id')
            ->select('docs_content.*', 'docs.categ_id as categ_id')
            ->where('featured', 1)
            ->where('active', 1)
            ->where('lang_id', $lang_id)
            ->orderBy('position', 'asc')
            ->orderBy('docs.id', 'desc')
            ->get(24);

        return view('frontend/builder/docs', [
            'featured_articles' => $featured_articles,
            'is_docs_home' => 1
        ]);
    }


    /**
     * Docs categ
     */
    public function categ(Request $request)
    {
        if (!check_module('docs')) return redirect('/');

        $slug = $request->slug;

        $active_lang_id = active_lang()->id ?? null;

        $categ = DB::table('docs_categ')
            ->leftJoin('docs_categ_content', 'docs_categ.id', '=', 'docs_categ_content.categ_id')		
			->select('docs_categ.*', 'docs_categ_content.title as title', 'docs_categ_content.description as description', 'docs_categ_content.meta_title as meta_title', 'docs_categ_content.meta_description as meta_description', 'docs_categ_content.slug as slug')
            ->where('slug', $slug)
            ->where('active', 1)
            ->first();
        if (!$categ) abort(404);

        $categ_tree_ids = $categ->tree_ids ?? null;
        if ($categ_tree_ids) $categ_tree_ids_array = explode(',', $categ_tree_ids);

        $tree_articles = DB::table('docs')
            ->where('active', 1)
            ->whereIn('docs.categ_id', $categ_tree_ids_array)
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(24);

        $categ_articles = DB::table('docs')
            ->leftJoin('docs_content', 'docs.id', '=', 'docs_content.doc_id')	
            ->select('docs.*', 'docs_content.title as title', 'docs_content.slug as slug')            
            ->where('categ_id', $categ->id)
            ->where('lang_id', $active_lang_id)
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        //dd($categ_articles);

        return view('frontend/builder/docs-categ', [
            'categ' => $categ,
            'tree_articles' => $tree_articles,
            'categ_articles' => $categ_articles,
            'is_docs_home' => 0
        ]);
    }


    /**
     * Docs search
     */
    public function search(Request $request)
    {
        if (!check_module('docs')) return redirect('/');

        $s = $request->s;

        $articles = DB::table('docs')
            ->leftJoin('docs_categ', 'docs_categ.id', '=', 'docs.categ_id')
            ->select('docs.*', 'docs_categ.title as categ_title', 'docs_categ.slug as categ_slug')
            ->where('docs.active', 1)
            ->where(function ($query) use ($s) {
                $query->where('docs.title', 'like', "%$s%")
                    ->orWhere('docs.search_terms', 'like', "%$s%");
            })
            ->orderBy('docs.featured', 'desc')
            ->orderBy('docs.id', 'desc')
            ->paginate(12);

        $categories = Doc::whereNull('parent_id')->where('active', 1)->with('childCategories')->select('docs_categ.*')->orderBy('position', 'asc')->get();

        return view('frontend/builder/docs-search', [
            'categories' => $categories,
            'articles' => $articles,
            's' => $s,
            'is_docs_home' => 0
        ]);
    }


     /**
    *  Search autocomplete
    */
    public function search_autocomplete(Request $request)
    {                         
        $search = $request->s;       
        $lang_id = $request->lang_id;     
        if(! $lang_id) $lang_id = default_lang()->id;

        if(strlen($search) > 1) {

            $results = DB::table('docs_content')
                ->leftJoin('docs', 'docs_content.doc_id', '=', 'docs.id')
                ->select('docs_content.*', 'docs.categ_id as categ_id')
                ->where('active', 1)
                ->where('lang_id', $lang_id)
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('search_terms', 'like', "%$search%");                    
                    })
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();  
                
            $count_results = count($results);                 
            
            foreach ($results as $result){            
                $url = docs_url($result->categ_id).'#'.$result->slug;

                echo '<div class="searchresults">';
                echo '<a href="'.$url.'">';                
                echo '<span class="title">'.substr($result->title, 0, 80).'</span>';
                echo '</a>';
                echo '</div>';
            }

        }
        
    }
}
