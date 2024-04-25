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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriveFile extends Model
{
    protected $fillable = ['code', 'file', 'original_name', 'module', 'item_id', 'extra_item_id', 'created_by_user_id', 'hits', 'mime_type', 'extension', 'size_mb', 'deleted_at', 'data'];

    protected $table = 'files';

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }


    public static function deleteModuleItemFiles($module, $item_id)
    {
        $files = DriveFile::where('module', $module)->where('item_id', $item_id)->get();

        foreach ($files as $file) {
            delete_image($file->code);
        }

        return;
    }
}
