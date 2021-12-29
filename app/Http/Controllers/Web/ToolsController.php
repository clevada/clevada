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
use App\Models\Core;
use DB;
use Auth;
use Storage;

class ToolsController extends Controller
{

    public function __construct()
    {
        $this->config = Core::config();
    }


    /**
     * Homepage
     */
    public function block_download(Request $request)
    {

        $hash = $request->hash;
        $id = $request->id; // block ID
        $referer = request()->headers->get('referer');
       
        if($referer) $goToSection = $referer.'#'.$id;
        else $goToSection = route('homepage');

        if (!($id && $hash)) return redirect(route('homepage'));

        $block = DB::table('blocks')
            ->where('id', $id)
            ->where('hide', 0)
            ->first();
        if (!$block) return redirect($goToSection);

        $block_extra = unserialize($block->extra);
        
        $file = $block_extra['file'];
        $file_hash = $block_extra['hash'];
        if (!$file) return redirect($goToSection);
        if ($file_hash != $hash) return redirect($goToSection);

        $login_required = $block_extra['login_required'];

        // check if login is required
        if ($login_required && !Auth::user()) return redirect($goToSection)->with('error', 'login_required');
        if ($login_required && !logged_user()->email_verified_at) return redirect($goToSection)->with('error', 'verify_email_required');
        
        $location = 'uploads/' . $file;
        
        if (file_exists($location)) {

            // save download log
            DB::table('log_downloads')->insert([
                'block_id' => $id,
                'file' => $file,
                'filesize' => filesize($location),
                'user_id' => (Auth::user()->id) ?? null,
                'ip' => request()->ip(),
                'created_at' => now(),
            ]);

            // download file
            return Storage::disk('local')->download($file);                      
        }

        return redirect($goToSection)->with('error', 'no_file');        
    }
}
