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

namespace App\Http\Middleware;

use Closure;
use App;
use DB;
use View;
use Config;
use Cookie;
use App\Models\User;
use App\Models\Core;

class Clevada
{

    public function sys_template_config($templateID)
    {
        $results = DB::table('sys_templates_config')->where('template_id', $templateID)->pluck('value', 'name')->toArray();
        return (object)$results;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // config
        $config = Core::config();

        // template config (for default template)
        $template = Core::template_config();

        // preview template (other than default template)        
        if (check_access('developer')) {
            $preview_template_id = $request->preview_template_id;
            if ($preview_template_id) {
                if (DB::table('sys_templates')->where('id', $preview_template_id)->exists()) {
                    $template_id_preview_cookie = Cookie::make('template_id_preview_cookie', $preview_template_id, 60);

                    if ($preview_template_id != Core::get_default_template_id()) {
                        $alert_preview_template = 1;
                        $template->id = $preview_template_id;
                    } else {
                        $alert_preview_template = null;
                        Cookie::forget('template_id_preview_cookie');
                    }

                    return redirect(route('homepage'))->withCookie($template_id_preview_cookie);
                }
            }
        }

        // mail config
        config()->set('mail', array_merge(config('mail'), [
            'driver' => $config->mail_driver ?? 'smtp',
            'host' => $config->smtp_host ?? 'smtp.mailgun.org',
            'port' => $config->smtp_port ?? '587',
            'encryption' => $config->smtp_encryption ?? 'tls',
            'username' => $config->smtp_username ?? null,
            'password' => $config->smtp_password ??null,
            'from' => [
                'address' => $config->mail_from_address ?? null,
                'name' => $config->mail_from_name ?? null
            ]
        ]));
        
        config()->set('services', array_merge(config('services'), [
            'mailgun' => [
                'domain' => $config->mailgun_domain ?? null,
                'secret' => $config->mailgun_secret ?? null,
                'endpoint' => $config->mailgun_endpoint ?? 'api.mailgun.net'
            ],
            'ses' => [
                'key' => $config->aws_key ?? null,
                'secret' => $config->aws_secret ?? null,
                'region' => $config->aws_region ?? 'us-east-1'
            ]
        ]));

        $preview_template_id = Cookie::get('template_id_preview_cookie');        
        if ($preview_template_id && ($preview_template_id != Core::get_default_template_id())) {
            $alert_preview_template = 1;
        }


        $lang = $request->lang;

        if ($lang) {
            $sys_lang_query = DB::table('sys_lang')->where('code', $lang)->where('status', 'active')->first();
            if (!$sys_lang_query) return redirect('/');

            $locale = $sys_lang_query->code ?? null;
            $setlocale = $sys_lang_query->locale ?? null;
        } else {
            $locale = default_lang()->code ?? 'en';
            $setlocale = default_lang()->locale ?? 'en_US';
        }

        //$lang = get_config_value('lang') ?? 'en';        
        //$locale = get_config_value('locale') ?? 'en_US';                               
        $timezone = get_config_value('timezone') ?? 'Europe/London';


        // admin count unread forms (for navbar notification)
        $admin_count_unread_forms = DB::table('forms_data')->whereNull('read_at')->count();

        $UserModel = new User();
        $role_id_internal = $UserModel->get_role_id_from_role('internal');
        $role_id_user = $UserModel->get_role_id_from_role('user');
        $role_id_admin = $UserModel->get_role_id_from_role('admin');
        $role_id_contact = $UserModel->get_role_id_from_role('contact');


        setlocale(LC_ALL, $setlocale);
        App::setLocale($setlocale);
        Config::set('app.timezone', $timezone);
        Config::set('app.locale', $setlocale);
        Config::set('app.faker_locale', $setlocale);

        // menu links
        $menu_lang_key = 'menu_links_' . active_lang()->id;
        if($config->$menu_lang_key ?? null) $menu_links = unserialize(($config->$menu_lang_key)); else $menu_links = array();
        $menu_links = json_decode(json_encode($menu_links));

        View::share('lang', $lang ?? null);

        View::share('locale', $setlocale ?? null);

        View::share('config', $config);

        View::share('template', $template);

        View::share('menu_links', $menu_links ?? array());

        View::share('role_id_internal', $role_id_internal);

        View::share('role_id_user', $role_id_user);

        View::share('role_id_admin', $role_id_admin);

        View::share('role_id_contact', $role_id_contact);

        View::share('admin_count_unread_forms', $admin_count_unread_forms);

        View::share('alert_preview_template', $alert_preview_template ?? null);

        View::share('template_path', 'templates/frontend/builder');

        View::share('template_view', 'frontend/builder');

        View::share('request_path', $request->path() ?? null);

        if ($template_id_preview_cookie ?? null) return $next($request)->withCookie($template_id_preview_cookie);
        else return $next($request);
    }
}
