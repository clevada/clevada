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
use Illuminate\Support\Carbon;
use Auth;
use App\Models\User;
use App\Models\Core;
use DB;
use Artisan;

class UpdateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->config = Core::config();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    public function index()
    {
        return view('admin/account', [
            'view_file' => 'core.update',
            'active_menu' => 'config',
            'active_submenu' => 'config.tools',
            'menu_section' => 'tools.update',
        ]);
    }


    public function check_update()
    {

        $checked_version = file_get_contents('https://version.clevada.com');

        Core::update_config('last_update_check', now());

        if ($checked_version == config('clevada.clevada_version')) return redirect(route('admin.tools.update'))->with('success', 'update_not_available');
        else return redirect(route('admin.tools.update'))->with('success', 'update_available');
    }


    public function update()
    {

        //dd(base_path());

        $updatesFolder = storage_path() . '/updates';

        
        // get latest update file                           
        $url  = 'https://version.clevada.com/updates/clevada-latest.zip';
        $zip_file = $updatesFolder . '/clevada-latest.zip';

        /*

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        file_put_contents($zip_file, $data);       
      

        // unzip
        $fnNoExt = basename($zip_file, ".zip");
        $destinationFolder = storage_path() . '/updates';

        $zip = new \ZipArchive;
        if ($zip->open($zip_file, \ZipArchive::CREATE) === TRUE) {
            if (!is_dir($updatesFolder)) {
                mkdir($updatesFolder,  0777);
            }
            $zip->extractTo($updatesFolder);
            $zip->close();
        } else {
            return FALSE;
        }
        */

        exit;
        // move migration file
        $migration_filename = date("Y_m_d_") . Carbon::now()->timestamp . '_latest.php';
        copy($updatesFolder . "/migrations/latest.php", database_path() . "/migrations/update/" . $migration_filename);

        // update database tables
        //Artisan::call('migrate --force --path=database/migrations/update/'.$migration_filename);

        // copy public folders and files
        recurseCopy($updatesFolder . "/public", public_path());

        // copy app folders and files
        recurseCopy($updatesFolder . "/clevada", base_path());

        return redirect(route('admin.config.tools.update'))->with('success', 'updated');
    }
}
