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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Models\Core;
use App\Models\User;
use App\Models\Upload;
use App\Models\Locale;
use App\Mail\TestEmail;
use DB;
use Artisan;
use Cache;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();
        $this->LocaleModel = new Locale();
        $this->config = Core::config();

        $this->roles = DB::table('users_roles')->where('active', 1)->orderBy('id', 'asc')->get();
        $this->role_id_internal = $this->UserModel->get_role_id_from_role('internal');
        $this->role_id_user = $this->UserModel->get_role_id_from_role('user');

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;
            $role = $this->UserModel->get_role_from_id($this->role_id);
            if (!($role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * General config.
     */
    public function general(Request $request)
    {

        $modules = DB::table('sys_modules')
            ->where('hidden', 0)
            ->orderBy('status', 'asc')
            ->groupBy('module')
            ->get();

        return view('admin/account', [
            'view_file' => 'core.config-general',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'general',
            'modules' => $modules,
        ]);
    }


    /**
     * Email config.
     */
    public function email()
    {
        return view('admin.account', [
            'view_file' => 'core.config-email',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'email',
        ]);
    }

    /**
     * Integration config.
     */
    public function integration()
    {
        return view('admin.account', [
            'view_file' => 'core.config-integration',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'integration',
        ]);
    }

    /**
     * Tools
     */
    public function tools()
    {
        return view('admin/account', [
            'view_file' => 'core.update',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.update',
        ]);
    }


    public function system()
    {
        return view('admin/account', [
            'view_file' => 'core.tools-system',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.system',
        ]);
    }


    public function whitelabel()
    {
        return view('admin/account', [
            'view_file' => 'core.config-whitelabel',
            'active_menu' => 'config',
            'active_submenu' => 'config.whitelabel',
        ]);
    }


    public function sitemap()
    {
        return view('admin/account', [
            'view_file' => 'core.tools-sitemap',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.sitemap',
        ]);
    }


    public function site_offline()
    {
        return view('admin/account', [
            'view_file' => 'core.config-site-offline',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'site-offline',
        ]);
    }

    public function registration()
    {
        return view('admin/account', [
            'view_file' => 'core.config-registration',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'registration',
        ]);
    }

    public function icons()
    {
        return view('admin/account', [
            'view_file' => 'core.config-icons',
            'active_menu' => 'config',
            'active_submenu' => 'config.general',
            'active_tab' => 'icons',
        ]);
    }


    /**
     * Process config general form
     */
    public function update_general(Request $request)
    {
        $inputs = $request->except('_token');
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        DB::table('sys_modules')->where('id', $id)->update(['status' => $inputs['status']]);

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('sys_modules_meta')->updateOrInsert(['module_id' => $id, 'lang_id' => $lang->id], ['meta_title' => $request['meta_title_' . $lang->id], 'meta_description' => $request['meta_description_' . $lang->id]]);
        }

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Process registration form
     */
    public function update_registration(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if ($request->has('registration_disabled')) $registration_disabled = 1;
        else $registration_disabled = null;
        if ($request->has('registration_verify_email_disabled')) $registration_verify_email_disabled = 1;
        else $registration_verify_email_disabled = null;
        if ($request->has('registration_recaptcha')) $registration_recaptcha = 1;
        else $registration_recaptcha = null;
        if ($request->has('registration_posts_contributor')) $registration_posts_contributor = 1;
        else $registration_posts_contributor = null;
        if ($request->has('registration_posts_auto_approve')) $registration_posts_auto_approve = 1;
        else $registration_posts_auto_approve = null;

        Core::update_config('registration_disabled', $registration_disabled);
        Core::update_config('registration_verify_email_disabled', $registration_verify_email_disabled);
        Core::update_config('registration_recaptcha', $registration_recaptcha);
        Core::update_config('registration_posts_contributor', $registration_posts_contributor);
        Core::update_config('registration_posts_auto_approve', $registration_posts_auto_approve);

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Process integration form
     */
    public function update_integration(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        Core::update_config($inputs);

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Process registration form
     */
    public function update_icons(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if ($request->has('use_icons_fontawesome')) $use_icons_fontawesome = 1;
        else $use_icons_fontawesome = null;
        if ($request->has('use_icons_boxicons')) $use_icons_boxicons = 1;
        else $use_icons_boxicons = null;

        Core::update_config('use_icons_fontawesome', $use_icons_fontawesome);
        Core::update_config('use_icons_boxicons', $use_icons_boxicons);

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Process Email config form
     */
    public function update_email(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'mail_from_address' => 'required|email:rfc',
            'mail_from_name' => 'required|max:100'
        ]);

        if ($validator->fails()) return redirect(route('admin.config.email'))->withErrors($validator)->withInput();

        $inputs = $request->except('_token');

        Core::update_config($inputs);

        return redirect($request->Url())->with('success', 'updated');
    }


    /**
     * Process whitelabel
     */
    public function update_whitelabel(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $license_key = $request->input('license_key');

        Core::update_config('license_key', $license_key);

        // process Backend image        
        if ($request->hasFile('logo_backend')) {
            $validator = Validator::make($request->all(), ['logo_backend' => 'mimes:jpeg,jpg,png,gif']);

            if ($validator->fails()) return redirect($request->Url())->withErrors($validator)->withInput();

            $logo_db = $this->UploadModel->upload_file($request, 'logo_backend');

            Core::update_config('logo_backend', $logo_db);
        }

        // process auth logo
        if ($request->hasFile('logo_auth')) {
            $validator = Validator::make($request->all(), ['logo_auth' => 'mimes:jpeg,jpg,png,gif']);

            if ($validator->fails()) return redirect($request->Url())->withErrors($validator)->withInput();

            $logo_db = $this->UploadModel->upload_file($request, 'logo_auth');

            Core::update_config('logo_auth', $logo_db);
        }

        // process meta author NAME
        $site_meta_author = $request->site_meta_author;
        Core::update_config('site_meta_author', $site_meta_author);

        // process meta author URL
        $site_meta_author_url = $request->site_meta_author_url;
        Core::update_config('site_meta_author_url', $site_meta_author_url);

        return redirect($request->Url())->with('success', 'updated');
    }



    /**
     * Send test email
     */
    public function send_test_email(Request $request)
    {
        $email = $request->test_email;

        Mail::to($email)->locale('en')->send(new TestEmail());

        // log email in database       
        Core::log_email(array(
            'email' => $email,
            'subject' => 'This is a test email',
            'message' => (new TestEmail())->render(),
            'module' => 'core',
            'type' => 'test_email',
            'created_at' => now(),
        ));

        return redirect(route('admin.config.email'))->with('success', 'test_email_ok');
    }


    /**
     * Process clear cache
     */
    public function clear_cache(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $section = $request->section;

        if ($section == 'views') Artisan::call('view:clear');
        if ($section == 'routes') Artisan::call('route:clear');
        if ($section == 'config') {
            Artisan::call('config:clear');
            Cache::flush();
        }

        return redirect(route('admin.tools.system'))->with('success', 'updated');
    }


    public function process_sitemap()
    {
        generate_sitemap();

        return redirect(route('admin.tools.sitemap'))->with('success', 'updated');
    }


    /**
     * Process site offline form
     */
    public function update_site_offline(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if ($request->has('site_maintenance')) $site_maintenance = 1;
        else $site_maintenance = null;

        if ($site_maintenance) {
            $token = $request->site_maintenance_token;
            if (!$token) return redirect(route('admin.config.site_offline'))->with('error', 'no_token');
            Artisan::call('down --secret="' . $token . '"');
        } else Artisan::call('up');

        Core::update_config('site_maintenance', $site_maintenance);

        if ($site_maintenance) return redirect(route('homepage') . '/' . $token);
        else return redirect(route('admin.config.site_offline'))->with('success', 'updated');
    }

    public function backup()
    {
        return view('admin/account', [
            'view_file' => 'core.tools-backup',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.backup',
        ]);
    }

    public function process_backup(Request $request)
    {

        $option = $request->option;

        if ($option == 'db') Artisan::call('backup:run --only-db --only-to-disk=backups');
        if ($option == 'full') Artisan::call('backup:run --only-to-disk=backups');

        Core::update_config('last_backup', now());

        return redirect(route('admin.tools.backup'))->with('success', 'updated');
    }
}
