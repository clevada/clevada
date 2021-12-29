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

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'subdomain', 'password', 'role_id', 'active', 'slug', 'code', 'recaptcha_response', 'email_verified_at', 'register_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $table = 'users';


    /**
     * Get role slug from role ID
     */
    public function get_role_from_id($id)
    {
        $role = DB::table('users_roles')->where('id', $id)->first();
        return $role->role ?? null;
    }


    /**
     * Get role ID from slug
     */
    public function get_role_id_from_role($role)
    {
        $role = DB::table('users_roles')->where('role', $role)->first();
        return $role->id ?? null;
    }


    /**
     * Get registration enabled roles
     */
    public static function get_registration_enabled_roles()
    {
        $roles = DB::table('users_roles')->where('registration_enabled', 1)->where('active', 1)->where('role', '!=', 'admin')->get();
        return $roles;
    }


    // add user extra details
    public function add_user_extra($user_id, $name, $value)
    {

        $exist_key = DB::table('users_extra_keys')->where('extra_key', $name)->first();

        // chek if exist key. If not, add this key
        if (!$exist_key) {
            DB::table('users_extra_keys')->insert([
                'extra_key' => $name,
            ]);
        }

        // get key id
        $key_id = DB::table('users_extra_keys')->where('extra_key', $name)->first()->id;

        // check if value is defined (note that it can be NULL also)
        $exist_value = DB::table('users_extra_values')->where('key_id', $key_id)->where('user_id', $user_id)->first();


        DB::table('users_extra_values')
            ->updateOrInsert(
                ['key_id' => $key_id, 'user_id' => $user_id],
                ['value' => $value]
            );
    }


    // get user extra details
    public function get_user_extra($user_id, $extra_key)
    {

        // get key id
        $q = DB::table('users_extra_keys')->where('extra_key', $extra_key)->first();
        if ($q) $key_id = $q->id;
        else return null;

        // get value
        $value = DB::table('users_extra_values')->where('key_id', $key_id)->where('user_id', $user_id)->value('value');

        if (!isset($value) or $value == '') return NULL;
        else return $value;
    }



    public function get_module_internals($module, $permission = null)
    {
        // get module
        $module = DB::table('sys_modules')->where('module', $module)->first();
        if (!$module) return null;

        $accounts = array();

        if (!$permission) $permissions = DB::table('users_permissions')->where('module_id', $module->id)->get();
        else {
            $permission_id = DB::table('sys_permissions')->where('module_id', $module->id)->where('permission', $permission)->value('id');
            if (!$permission_id) return array();
            $permissions = DB::table('users_permissions')->where('module_id', $module->id)->where('permission_id', $permission_id)->get();
            if (!$permissions) return array();
        }

        foreach ($permissions as $permission) {
            $user = DB::table('users')->where('id', $permission->user_id)->first();
            $accounts[] = array('id' => $user->id, 'name' => $user->name, 'email' => $user->email);
        }

        return $accounts;
    }
}
