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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App;
use File;

class TranslatesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect('/');
            return $next($request);
        });
    }


    public function traverse_hierarchy($path)
    {
        $return_array = array();
        $dir = opendir($path);
        while (($file = readdir($dir)) !== false) {
            if ($file[0] == '.') continue;
            $fullpath = $path . '/' . $file;
            if (is_dir($fullpath)) {
                if (substr(strrchr($fullpath, "/"), 1) == 'assets') continue; // don't scan assets folder
                else $return_array = array_merge($return_array, $this->traverse_hierarchy($fullpath));
            } else 
				if (substr($file, -3) == "php") $return_array[] = $fullpath;
        }
        return $return_array;
    }


    public function index(Request $request)
    {

        $templates = glob('templates/frontend/' . '*', GLOB_ONLYDIR);

        $langs = DB::table('sys_lang')
            ->select('sys_lang.*', DB::raw('(SELECT count(*) FROM sys_lang_translates WHERE sys_lang_translates.lang_id = sys_lang.id) as count_translated_keys'))
            ->paginate(25);

        $count_keys = DB::table('sys_lang_keys')->count();

        return view('admin/account', [
            'view_file' => 'core/translates',
            'active_menu' => 'config',
            'active_submenu' => 'config.langs',
            'templates' => $templates,
            'langs' => $langs,
            'count_keys' => $count_keys ?? 0,
        ]);
    }


    public function scan_template(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        $template = $request->template;
        $area = $request->area;

        $templates = glob('templates/frontend/' . '*', GLOB_ONLYDIR);
        $langs = DB::table('sys_lang')->paginate(25);

        $string1 = "__('";
        $string2 = "')";

        $string3 = '__("';
        $string4 = '")';

        echo '<a href="' . route('admin.translates') . '"><h3>GO BACK</h3></a>';

        foreach ($this->traverse_hierarchy($template) as $file) {
            $content = file_get_contents($file, 'r');

            preg_match_all("(" . preg_quote($string1) . ".*?" . preg_quote($string2) . ")s", $content, $matches);

            foreach ($matches[0] as $lang_key) {
                $lang_key = str_replace($string1, '', $lang_key);
                $lang_key = str_replace($string2, '', $lang_key);

                echo '<b>' . stripslashes($lang_key) . '</b><br>';

                $exist_key = DB::table('sys_lang_keys')->where(DB::raw('BINARY `lang_key`'), stripslashes($lang_key))->where('area', $area)->first();
                // chek if exist key. If not, add this key
                if (!$exist_key) {
                    DB::table('sys_lang_keys')->insert([
                        'lang_key' => stripslashes($lang_key),
                        'area' => $area,
                    ]);

                    echo '<font color="green"><b>Key added in database</b></font>';
                } else echo '<font color="red">Key already in database</font>';
                echo "<hr>";
            }


            preg_match_all("(" . preg_quote($string3) . ".*?" . preg_quote($string4) . ")s", $content, $matches);

            foreach ($matches[0] as $lang_key) {
                $lang_key = str_replace($string3, '', $lang_key);
                $lang_key = str_replace($string4, '', $lang_key);

                echo '<b>' . stripslashes($lang_key) . '</b><br>';

                $exist_key = DB::table('sys_lang_keys')->where('lang_key', stripslashes($lang_key))->where('area', $area)->first();
                // chek if exist key. If not, add this key
                if (!$exist_key) {
                    DB::table('sys_lang_keys')->insert([
                        'lang_key' => stripslashes($lang_key),
                        'area' => $area,
                    ]);

                    echo '<font color="green"><b>Key added in database</b></font>';
                } else echo '<font color="red">Key already in database</font>';
                echo "<hr>";
            }
        } // end foreach

        echo '<a href="' . route('admin.translates') . '"><h3>GO BACK TO TRANSLATES</h3></a>';

        exit;
    }


    /**
     *  Translate language
     */
    public function translate_lang(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        $lang_id = $request->id;
        $search_terms = $request->search_terms;

        $lang = DB::table('sys_lang')
            ->where('id', $lang_id)
            ->first();
        if (!$lang) abort(404);

        $lang_keys = DB::table('sys_lang_keys')
            ->leftJoin('sys_lang_translates', 'sys_lang_translates.key_id', '=', 'sys_lang_keys.id')
            ->select('sys_lang_keys.*', DB::raw('(SELECT translate FROM sys_lang_translates WHERE sys_lang_translates.key_id = sys_lang_keys.id AND lang_id = ' . $lang_id . ') as translate'))
            ->orderBy('translate', 'asc')
            ->orderBy('id', 'desc');

        if ($search_terms)
            $lang_keys = $lang_keys->where('lang_key', 'like', "%$search_terms%")->groupBy('lang_key');

        $lang_keys = $lang_keys->paginate(25);

        $translated_keys = DB::table('sys_lang_translates')->where('lang_id', $lang_id)->count();

        return view('admin/account', [
            'view_file' => 'core/translate-lang',
            'active_menu' => 'config',
            'active_submenu' => 'config.langs',

            'lang' => $lang,
            'lang_keys' => $lang_keys,
            'lang_id' => $lang_id,
            'translated_keys' => $translated_keys,
            'search_terms' => $search_terms,
        ]);
    }

    /**
     * Create new resource
     */
    public function create_key(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $lang_id = $request->lang_id;

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $validator = Validator::make($request->all(), [
            'lang_key' => 'required',
            'file_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.translate_lang', ['id' => $lang_id]))
                ->withErrors($validator)
                ->withInput();
        }

        $exists = DB::table('sys_lang_keys')->where('lang_key', $inputs['lang_key'])->first();
        if ($exists) return redirect(route('admin.translate_lang', ['id' => $lang_id]))->with('error', 'duplicate');

        DB::table('sys_lang_keys')->insert([
            'file_id' => $inputs['file_id'],
            'lang_key' => $inputs['lang_key'],
        ]);

        return redirect(route('admin.translate_lang', ['id' => $lang_id]))->with('success', 'created');
    }


    /**
     * Update the specified resource     
     */
    public function update_key(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $lang_id = $request->lang_id;

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $validator = Validator::make($request->all(), [
            'lang_key' => 'required',
            'file_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.translate_lang', ['lang_id' => $lang_id]))
                ->withErrors($validator)
                ->withInput();
        }

        $exists = DB::table('sys_lang_keys')->where('lang_key', $inputs['lang_key'])->where('id', '!=', $inputs['id'])->first();
        if ($exists) return redirect(route('admin.translate_lang', ['id' => $lang_id]))->with('error', 'duplicate');

        DB::table('sys_lang_keys')
            ->where('id', $inputs['id'])
            ->update([
                'file_id' => $inputs['file_id'],
                'lang_key' => $inputs['lang_key'],
            ]);

        return redirect(route('admin.translate_lang', ['id' => $lang_id]))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function delete_key(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->all(); // retrieve all of the input data as an array 

        DB::table('sys_lang_keys')->where('id', $inputs['key_id'])->delete();
        return redirect(route('admin.translate_lang', ['id' => $inputs['lang_id']]))->with('success', 'deleted');
    }


    /**
     * Update translation
     */
    public function update_translate(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $validator = Validator::make($request->all(), [
            'lang_id' => 'required',
            'key_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.translate_lang', ['id' => $inputs['lang_id']]))
                ->withErrors($validator)
                ->withInput();
        }

        $exists = DB::table('sys_lang_translates')->where('lang_id', $inputs['lang_id'])->where('key_id', $inputs['key_id'])->first();

        if ($exists) {
            DB::table('sys_lang_translates')
                ->where('key_id', $inputs['key_id'])
                ->where('lang_id', $inputs['lang_id'])
                ->update([
                    'translate' => $inputs['translate'] ?? null,
                ]);
        } else {
            DB::table('sys_lang_translates')->insert([
                'lang_id' => $inputs['lang_id'],
                'key_id' => $inputs['key_id'],
                'translate' => $inputs['translate'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'updated');
    }



    /**
     * Create or Regenerate Language file
     */
    public function regenerate_lang_file(Request $request)
    {

        if (!check_access('translates')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->all(); // retrieve all of the input data as an array 

        $lang = DB::table('sys_lang')
            ->where('id', $inputs['lang_id'])
            ->first();

        $path_folder = App::langPath() . '/' . $lang->locale;
        $path_file = App::langPath() . '/' . $lang->locale . '.json';

        if (!File::isDirectory($path_folder)) {
            File::makeDirectory($path_folder, 0777, true, true);
        }

        $translates = DB::table('sys_lang_translates')
            ->leftJoin('sys_lang_keys', 'sys_lang_translates.key_id', '=', 'sys_lang_keys.id')
            ->select('sys_lang_translates.translate', 'sys_lang_keys.lang_key as lang_key')
            ->where('lang_id', $inputs['lang_id'])
            ->pluck('translate', 'lang_key')
            ->toArray();
        //dd($translates);    

        $filecontent = json_encode($translates);

        file_put_contents($path_file, $filecontent);

        return redirect(route('admin.translate_lang', ['id' => $inputs['lang_id']]))->with('success', 'regenerated');
    }
}
