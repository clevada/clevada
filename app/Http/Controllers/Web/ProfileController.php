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

class ProfileController extends Controller
{

    /**
     * Display static page
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $slug = $request->slug;

        $user = DB::table('users')
            ->where('id', $id)
            ->where('slug', $slug)
            ->where('active', 1)
            ->first();
        if (!$user) return redirect(route('homepage'));

        $posts = DB::table('posts')
            ->where('user_id', $id)
            ->where('status', 'active')
            ->paginate($config->posts_per_page ?? 12);

        $bio = user_extra($user->id, 'bio');

        return view('frontend.builder.profile', [
            'user' => $user,
            'posts' => $posts,
            'bio' => $bio,
        ]);
    }
}
