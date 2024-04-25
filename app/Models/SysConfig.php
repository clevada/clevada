<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class SysConfig extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];

    protected $table = 'sys_config';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Get configs from database
     *
     * @return object
     */
    public static function config()
    {
        /*
        $results = Cache::remember('config', 60 * 60 * 24, function () {
            return SysConfig::pluck('value', 'name')->toArray();
        });
        */

        $results = SysConfig::pluck('value', 'name')->toArray();

        return (object) $results;
    }


    /**
     * Update sys config
     *
     * @return null
     */
    public static function update_config($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                if ($key != '_token') SysConfig::updateOrCreate(['name' => $key], ['value' => $value]);
            }
        } else {
            SysConfig::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        // clear cache
        //Cache::forget('config');

        return null;
    }

}
