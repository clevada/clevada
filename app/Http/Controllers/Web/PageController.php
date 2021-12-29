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
use DB;

class PageController extends Controller
{

    /**
     * Display static page
     */
    public function index(Request $request)
    {

        $slug = $request->slug;
        $parent_slug = $request->parent_slug;

        $content = DB::table('pages_content')
            ->where('slug', $slug)
            ->where('lang_id', active_lang()->id ?? null)
            ->first();
        if (!$content) return redirect(route('homepage'));

        $page = DB::table('pages')
            ->where('id', $content->page_id)
            ->where('active', 1)
            ->first();
        if (!$page) return redirect(route('homepage'));

        if ($parent_slug) {
            $parent_content = DB::table('pages_content')
                ->where('slug', $parent_slug)
                ->where('lang_id', active_lang()->id ?? null)
                ->first();
            if (!$parent_content) return redirect(route('homepage'));
        }

        return view('frontend/builder/page', [
            'page' => $page,
            'content' => $content,

            'module' => 'pages',
            'content_id' => $page->id,
            
            'top_section_id' => $page->top_section_id, 
            'bottom_section_id' => $page->bottom_section_id, 
            'sidebar_id' => $page->sidebar_id, 
            'sidebar_position' => $page->sidebar_position, 
        ]);
    }
}
