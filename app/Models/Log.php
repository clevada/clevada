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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Log extends Model
{


    /**
     * Log email activity in database
     *
     * @return null
     */
    public static function log_email($args)
    {
        DB::table('log_email')->insert([
            'module' => $args['module'] ?? null,
            'type' => $args['type'] ?? null,
            'item_id' => $args['item_id'] ?? null,
            'to_user_id' => $args['to_user_id'] ?? null,
            'email' => $args['email'] ?? null,
            'module' => $args['module'] ?? null,
            'module' => $args['module'] ?? null,
            'module' => $args['module'] ?? null,
            'module' => $args['module'] ?? null,
            'module' => $args['module'] ?? null,
        ]);


        return null;
    }
}
