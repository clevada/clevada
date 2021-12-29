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
use App\Models\Core;

class HomeController extends Controller
{

    public function __construct()
    {                
        $this->config = Core::config();  

        $this->template_config = Core::template_config();  
    }


    /**
     * Homepage
     */
    public function index()
    {              
        return view('frontend/builder/index', [
            'module' => 'homepage',            
            'content_id' => null, 

            'sidebar_id' => template("sidebar_id_homepage") ?? null,
            'sidebar_position' => template("sidebar_position_homepage") ?? null,
            'top_section_id' => template('top_section_id_homepage') ?? null, 
            'bottom_section_id' => template('bottom_section_id_homepage') ?? null, 
        ]);
    }
}
