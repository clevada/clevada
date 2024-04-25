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
 * 
 * 
 * IMPORTANT: DO NOT edit this file manually. All changes will be lost after software update.
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPostType extends Model
{

    protected $fillable = [
        'type',
        'name',
        'labels',
        'description',
        'show_in_admin_menu',
        'show_in_site_menu',
        'show_in_search',
        'admin_menu_position',
        'admin_menu_icon',
        'slug',
        'active',
    ];

    protected $table = 'custom_post_type';
}
