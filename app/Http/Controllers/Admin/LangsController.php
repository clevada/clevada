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

use App\Models\Core;
use App\Models\User;
use App\Models\Locale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Str;

class LangsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->LocaleModel = new Locale();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;

            $role = $this->UserModel->get_role_from_id($this->role_id);
            if ($role != 'admin') return redirect(route('homepage'));
            return $next($request);
        });
    }


    /**
     * Display all resources
     */
    public function index(Request $request)
    {
        $langs = DB::table('sys_lang')->orderBy('is_default', 'desc')->orderBy('status', 'asc')->paginate(25);

        return view('admin.account', [
            'view_file' => 'core.config-langs',
            'active_menu' => 'config',
            'active_submenu' => 'config.langs',
            'langs' => $langs,
            'locales_array' => $this->LocaleModel->locales_array(),
            'lang_codes_array' => $this->LocaleModel->lang_codes_array(),
        ]);
    }


    /**
     * Create new resource
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.config.langs'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all(); // retrieve all of the input data as an array 

        if (DB::table('sys_lang')->where('name', $inputs['name'])->exists()) return redirect(route('admin.config.langs'))->with('error', 'duplicate');
        if (DB::table('sys_lang')->where('code', $inputs['code'])->exists()) return redirect(route('admin.config.langs'))->with('error', 'duplicate');

        // only one language can be default
        if ($inputs['is_default'] == 1) {
            DB::table('sys_lang')
                ->where('is_default', 1)
                ->update(
                    ['is_default' => 0]
                );
        }

        DB::table('sys_lang')->insert([
            'name' => $inputs['name'],
            'code' => $inputs['code'],
            'locale' => $inputs['locale'],
            'is_default' => $inputs['is_default'],
            'status' => $inputs['status'],
            'timezone' => $inputs['timezone'] ?? 'Europe/London',
            //'date_format' => $inputs['date_format'] ?? 'j F Y',
            'site_short_title' => $inputs['site_short_title'],
            'homepage_meta_title' => $inputs['homepage_meta_title'],
            'homepage_meta_description' => $inputs['homepage_meta_description'],
        ]);

        return redirect($request->Url())->with('success', 'created');
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.config.langs'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if (DB::table('sys_lang')->where('name', $inputs['name'])->where('id', '!=', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'duplicate');
        if (DB::table('sys_lang')->where('code', $inputs['code'])->where('id', '!=', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'duplicate');

        // only one language can be default
        if ($inputs['is_default'] == 1) {
            DB::table('sys_lang')
                ->where('is_default', 1)
                ->update(
                    ['is_default' => 0]
                );
        }

        DB::table('sys_lang')
            ->where('id', $id)
            ->update([
                'name' => $inputs['name'],
                'code' => $inputs['code'],
                'locale' => $inputs['locale'],
                'is_default' => $inputs['is_default'],
                'status' => $inputs['status'],
                'timezone' => $inputs['timezone'] ?? 'Europe/London',
                //'date_format' => $inputs['date_format'] ?? 'j F Y',
                'site_short_title' => $inputs['site_short_title'],
                'homepage_meta_title' => $inputs['homepage_meta_title'],
                'homepage_meta_description' => $inputs['homepage_meta_description'],
            ]);

        return redirect(route('admin.config.langs'))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (DB::table('sys_lang')->where('id', $id)->value('is_default') == 1) return redirect(route('admin.config.langs'))->with('error', 'default');

        if (DB::table('posts')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');
        if (DB::table('blocks_content')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');
        if (DB::table('docs')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');
        if (DB::table('pages')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');
        if (DB::table('faq')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');
        if (DB::table('slider')->where('lang_id', $id)->exists()) return redirect(route('admin.config.langs'))->with('error', 'exists_content');

        DB::table('sys_lang')->where('id', $id)->delete();

        DB::table('posts')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('posts_categ')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('posts_tags')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('blocks_content')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('custom_fields')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('custom_fields_sections')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('docs')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('docs_categ')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('pages')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('slider')->where('lang_id', $id)->update(['lang_id' => null]);
        DB::table('faq')->where('lang_id', $id)->update(['lang_id' => null]);

        return redirect(route('admin.config.langs'))->with('success', 'deleted');
    }

    /**
     * Update the specified resource     
     */
    public function update_permalinks(Request $request)
    {
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token'); // retrieve all of the input data as an array 

        $posts = Str::slug($inputs['posts'], '-');
        $cart = Str::slug($inputs['cart'], '-');
        $forum = Str::slug($inputs['forum'], '-');
        $docs = Str::slug($inputs['docs'], '-');
        $contact = Str::slug($inputs['contact'], '-');
        $tag = Str::slug($inputs['tag'], '-');
        $search = Str::slug($inputs['search'], '-');
        $profile = Str::slug($inputs['profile'], '-');

        $permalinks = array(
            'posts' => $posts ?? 'blog',
            'cart' => $cart ?? 'shop',
            'forum' => $forum ?? 'forum',
            'docs' => $docs ?? 'docs',
            'contact' => $contact ?? 'contact',
            'tag' => $tag ?? 'tag',
            'search' => $search ?? 'search',
            'profile' => $profile ?? 'profile'
        );


        DB::table('sys_lang')
            ->where('id', $id)
            ->update([
                'permalinks' => serialize($permalinks),
            ]);

        Core::generate_langs_routes();

        return redirect(route('admin.config.langs'))->with('success', 'updated');
    }
}
