<?php

/**
 * Clevada - Content Management System and Website Builder
 *
 * Copyright (C) 2024  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @author      Chimilevschi Iosif Gabriel <contact@clevada.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Models\Upload;
use App\Models\SysConfig;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Contact;
use App\Models\TemplateMenu;
use App\Models\TemplateStyle;
use App\Models\DriveFile;
use App\Models\Tools;
use Artisan;
use Str;

class CoreController extends Controller
{


    /**
     * Show the dashboard.
     */
    public function dashboard()
    {
        if (Auth::user()->role == 'admin') $view = 'core.dashboard';
        if (Auth::user()->role == 'internal') $view = 'core.dashboard-internal';

        //$drive_total_mb = DriveFile::sum('size_mb');
        //$drive_count_files = DriveFile::count();

        $count_accounts = User::whereNull('deleted_at')->count();
        //$count_contact_messages = Contact::whereNull('deleted_at')->count();
        //$count_unread_contact_messages = Contact::whereNull('deleted_at')->whereNull('read_at')->count();

        $last_accounts = User::whereNull('deleted_at')->orderByDesc('id')->limit(10)->get();
        //$last_contact_messages = Contact::whereNull('deleted_at')->orderByDesc('id')->limit(10)->get();

        return view('admin.index', [
            'view_file' => $view,
            'active_menu' => 'dashboard',
            'active_submenu' => 'summary',
            'active_tab' => 'summary',
            'count_accounts' => $count_accounts,
            //'count_contact_messages' => $count_contact_messages,
            //'count_unread_contact_messages' => $count_unread_contact_messages,
            'last_accounts' => $last_accounts,
            //'last_contact_messages' => $last_contact_messages,

            //'drive_total_mb' => $drive_total_mb, // MB
            //'drive_count_files' => $drive_count_files,
        ]);
    }


    /**
     * Activity log page
     */
    public function activity(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        $logs = ActivityLog::with('user')->orderByDesc('id')->paginate(25);

        return view('admin.index', [
            'view_file' => 'core.activity',
            'active_menu' => 'dashboard',
            'active_submenu' => 'activity',
            'active_tab' => 'activity',
            'logs' => $logs,
        ]);
    }



    /**
     * Config settings.
     */
    public function module(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        $module = $request->module;
        if (!$module) $module = 'general';
        if (!($module == 'general' || $module == 'email' || $module == 'website' || $module == 'registration' || $module == 'integration' || $module == 'seo' || $module == 'icons' || $module == 'whitelabel')) return redirect(route('admin'));

        return view('admin.index', [
            'view_file' => 'core.config-' . $module,
            'active_menu' => 'config',
            'active_submenu' => 'config.settings',
            'active_tab' => $module,
        ]);
    }

    /**
     * Process config settings
     */
    public function update_module(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');


        $module = $request->module;

        if (!$module) $module = 'general';

        if (!($module == 'general' || $module == 'email' || $module == 'registration' || $module == 'integration' || $module == 'seo' || $module == 'icons' || $module == 'whitelabel')) return redirect(route('admin'));

        $inputs = $request->except('_token');

        if ($module == 'permalinks') {
            $permalinks = array('tag' => $inputs['tag'] ?? 'tag', 'search' => $inputs['search'] ?? 'search', 'profile' => $inputs['profile'] ?? 'profile', 'contact' => $inputs['contact'] ?? 'contact');
            SysConfig::update_config('permalinks', serialize($permalinks));
        } else
            SysConfig::update_config($inputs);

        return redirect(route('admin.config', ['module' => $module]))->with('success', 'updated');
    }






    /**
     * Update the specified resource     
     */
    public function update_config_permalinks(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $lang_id = $request->lang_id;

        if (!$lang_id) return redirect(route('admin.config.seo'));

        $inputs = $request->except('_token');

        $posts = $inputs['posts'] ?? 'posts';
        $forum = $inputs['forum'] ?? 'forum';
        $docs = $inputs['docs'] ?? 'docs';
        $contact = $inputs['contact'] ?? 'contact';
        $search = $inputs['search'] ?? 'search';
        $tag = $inputs['tag'] ?? 'tag';
        $profile = $inputs['profile'] ?? 'profile';

        $permalinks = array(
            'posts' => Str::slug($posts, '-'),
            'forum' => Str::slug($forum, '-'),
            'docs' => Str::slug($docs, '-'),
            'contact' => Str::slug($contact, '-'),
            'search' => Str::slug($search, '-'),
            'tag' => Str::slug($tag, '-'),
            'profile' => Str::slug($profile, '-'),
        );

        SysLang::where('id', $lang_id)->update([
            'permalinks' => serialize($permalinks),
        ]);

        Tools::generatePermalinks();

        // regenerate menu links for each language and store in cache config
        TemplateMenu::generate_menu_links();

        return redirect(route('admin.website.permalinks'))->with('success', 'updated');
    }



    /**
     * Display profile page
     */
    public function profile(Request $request)
    {
        return view('admin.index', [
            'view_file' => 'core.profile',
            'active_menu' => 'dashboard',
            'pagename' => 'Profile',
            'bio' => User::getMeta(Auth::user()->id, 'bio'),
        ]);
    }


    /**
     * Update profile
     */
    public function profile_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email'
        ]);

        if ($validator->fails()) {
            return redirect($request->Url())
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();

        // check if email exist
        if (User::where('email', $request->email)->where('id', '!=', Auth::user()->id)->exists())
            return redirect(route('admin.profile'))->with('error', 'duplicate');

        User::where('id', Auth::user()->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'forum_signature' => $request->forum_signature ?? null,
            ]);

        User::addMeta(Auth::user()->id, 'bio', $request->bio);

        // change password
        if ($request->password) {
            User::where('id', Auth::user()->id)->update(['password' => Hash::make($inputs['password'])]);
        }

        // process image        
        if ($request->hasFile('avatar')) Upload::createAvatar($request->file('avatar'));

        return redirect(route('admin.profile'))->with('success', 'updated');
    }


    /**
     * Delete user profile avatare
     */
    public function profile_delete_avatar()
    {
        $user = User::find(Auth::user()->id);

        $currentAvatarPath = getcwd() . '/uploads/avatars/' . $user->avatar;
        $currentAvatarThumbPath = getcwd() . '/uploads/avatars/thumb_' . $user->avatar;
        @unlink($currentAvatarPath);
        @unlink($currentAvatarThumbPath);

        User::find(Auth::user()->id)->update(['avatar' => NULL]);

        return redirect(route('admin.profile'))->with('success', 'updated');
    }


    /**
     * Show the tools page.
     */
    public function tools()
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        return view('admin.index', [
            'view_file' => 'core.tools',
            'active_menu' => 'system',
            'active_submenu' => 'tools',
            'pagename' => 'Tools',
        ]);
    }


    /**
     * Tools action
     */
    public function tools_action(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        $action = $request->action;

        if ($action == 'sendTestEmail') {
            $email = $request->email;
            if (!$email)
                return redirect(route('admin.tools'));
            Mail::to($email)->send(new TestEmail());
            return redirect(route('admin.tools'))->with('success', 'test_email_sent');
        }

        return redirect(route('admin.tools'));
    }


    public function update_tables(Request $request)
    {

        Artisan::call("tenants:migrate");

        exit('Done');
    }


    public function update_install(Request $request)
    {
        if (!(Auth::user()->role == 'admin')) return redirect(route('admin'));

        Artisan::call('tenants:run', ['commandname' => 'app:install']); // https://tenancyforlaravel.com/docs/v3/console-commands

        exit('Done');
    }


    public function preview_style(Request $request)
    {
        $style = TemplateStyle::find($request->id);

        return view('admin.preview-style-custom', [
            'style' => $style,
        ]);
    }

    public function generate_sitemap()
    {
        Tools::generateSitemap();

        return redirect(route('admin.config', ['module' => 'seo']))->with('success', 'updated');
    }
}
