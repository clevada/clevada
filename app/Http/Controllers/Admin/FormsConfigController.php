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
use App\Models\Upload;
use App\Models\User;
use DB;
use Auth;

class FormsConfigController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Show all resources
     */
    public function index(Request $request)
    {

        $forms = DB::table('forms')
            ->orderBy('active', 'desc')
            ->orderBy('label', 'asc')
            ->paginate(25);

        return view('admin/account', [
            'view_file' => 'forms.forms-config',
            'active_menu' => 'forms',
            'forms' => $forms,
        ]);
    }


    /**
     * Create form
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.forms.config'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('recaptcha')) $recaptcha = 1;
        else $recaptcha = 0;      

        if (DB::table('forms')->where('label', $inputs['label'])->exists()) return redirect(route('admin.forms.config'))->with('error', 'duplicate');

        DB::table('forms')->insert([
            'label' => $inputs['label'],
            'active' => $active,
            'recaptcha' => $recaptcha,           
        ]);

        $form_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->where('status', '!=', 'disabled')->orderBy('is_default', 'desc')->orderBy('status', 'asc')->get();

        // insert NAME field
        DB::table('forms_fields')->insert(['form_id' => $form_id, 'type' => 'text', 'required' => 1, 'col_md' => 6, 'active' => 1, 'position' => 0, 'protected' => 1, 'is_default_name' => 1]);
        $field_id_name = DB::getPdo()->lastInsertId();
        foreach ($langs as $lang) {
            DB::table('forms_fields_lang')->insert(['form_id' => $form_id, 'field_id' => $field_id_name, 'lang_id' => $lang->id, 'label' => 'Name']);
        }

        // insert EMAIL field
        DB::table('forms_fields')->insert(['form_id' => $form_id, 'type' => 'email', 'required' => 1, 'col_md' => 6, 'active' => 1, 'position' => 1, 'protected' => 1, 'is_default_email' => 1]);
        $field_id_email = DB::getPdo()->lastInsertId();
        foreach ($langs as $lang) {
            DB::table('forms_fields_lang')->insert(['form_id' => $form_id, 'field_id' => $field_id_email, 'lang_id' => $lang->id, 'label' => 'Email']);
        }

        // insert SUBJECT field
        DB::table('forms_fields')->insert(['form_id' => $form_id, 'type' => 'text', 'required' => 1, 'col_md' => 12, 'active' => 1, 'position' => 2, 'protected' => 1, 'is_default_subject' => 1]);
        $field_id_subject = DB::getPdo()->lastInsertId();
        foreach ($langs as $lang) {
            DB::table('forms_fields_lang')->insert(['form_id' => $form_id, 'field_id' => $field_id_subject, 'lang_id' => $lang->id, 'label' => 'Subject']);
        }

        // insert MESSAGE field
        DB::table('forms_fields')->insert(['form_id' => $form_id, 'type' => 'textarea', 'required' => 1, 'col_md' => 12, 'active' => 1, 'position' => 3, 'protected' => 1, 'is_default_message' => 1]);
        $field_id_message = DB::getPdo()->lastInsertId();
        foreach ($langs as $lang) {
            DB::table('forms_fields_lang')->insert(['form_id' => $form_id, 'field_id' => $field_id_message, 'lang_id' => $lang->id, 'label' => 'Message']);
        }

        return redirect(route('admin.forms.config'))->with('success', 'created');
    }


    /**
     * Show resource
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $form = DB::table('forms')
            ->where('id', $id)
            ->first();
        if (!$form) return redirect(route('admin.forms.config'));

        $fields = DB::table('forms_fields')
            ->where('form_id', $id)
            ->orderBy('active', 'desc')
            ->orderBy('position', 'asc')
            ->get();

        $langs = DB::table('sys_lang')
            ->where('status', '!=', 'disabled')
            ->orderBy('is_default', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        return view('admin/account', [
            'view_file' => 'forms.form-config',
            'active_menu' => 'forms',
            'form' => $form,
            'langs' => $langs,
            'fields' => $fields,
        ]);
    }



    /**
     * Update form
     */
    public function update(Request $request)
    {
        $id = $request->id; // form ID

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.forms.config'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if ($request->has('active')) $active = 1; else $active = 0; 
        if ($request->has('recaptcha')) $recaptcha = 1; else $recaptcha = 0;       

        if (DB::table('forms')->where('label', $inputs['label'])->where('id', '!=', $id)->exists()) return redirect(route('admin.forms.config'))->with('error', 'duplicate');

        DB::table('forms')
            ->where('id', $id)
            ->update([
                'label' => $inputs['label'],
                'active' => $active,
                'recaptcha' => $recaptcha,              
            ]);

        return redirect(route('admin.forms.config'))->with('success', 'updated');
    }


    /**
     * Remove form
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        // check if exists messages
        if(DB::table('forms_data')->where('form_id', $id)->exists()) return redirect(route('admin.forms.config'))->with('error', 'exists_data');
       
        DB::table('forms_data')->where('form_id', $id)->delete();
        DB::table('forms_fields')->where('form_id', $id)->delete();
        DB::table('forms_fields_lang')->where('form_id', $id)->delete();
        DB::table('forms')->where('id', $id)->delete();

        return redirect(route('admin.forms.config'))->with('success', 'deleted');
    }


    /**
     * Add form field 
     */
    public function add_field(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // form ID

        $inputs = $request->except('_token');

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('required')) $required = 1;
        else $required = 0;

        $last_pos = DB::table('forms_fields')->where('form_id', $id)->orderBy('position', 'desc')->value('position');
        $position = ($last_pos ?? 0) + 1;

        DB::table('forms_fields')->insert([
            'form_id' => $id,
            'type' => $inputs['type'],
            'required' => $required,
            'active' => $active,
            'col_md' => $inputs['col_md'] ?? 12,
            'position' => $position,
        ]);

        $field_id = DB::getPdo()->lastInsertId();

        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            //$dropdowns = null;
            //$dropdowns = $request['dropdowns_' . $lang->id];
            //if($dropdowns) $dropdowns_array = serialize(preg_split("/\r\n|\n|\r/", $dropdowns));

            DB::table('forms_fields_lang')->insert([
                'form_id' => $id,
                'field_id' => $field_id,
                'lang_id' => $lang->id,
                'label' => $request['label_' . $lang->id],
                'info' => $request['info_' . $lang->id],
                'dropdowns' => $request['dropdowns_' . $lang->id] ?? null
            ]);
        }

        return redirect(route('admin.forms.config.show', ['id' => $id]))->with('success', 'created');
    }


    /**
     * Update form field 
     */
    public function update_field(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // form ID
        $field_id = $request->field_id;

        $inputs = $request->except('_token');

        if ($request->has('active')) $active = 1;
        else $active = 0;
        if ($request->has('required')) $required = 1;
        else $required = 0;

        DB::table('forms_fields')
            ->where('id', $field_id)
            ->update([
                'type' => $inputs['type'],
                'required' => $required,
                'active' => $active,
                'col_md' => $inputs['col_md'] ?? 12,
            ]);


        $langs = DB::table('sys_lang')->get();
        foreach ($langs as $lang) {
            DB::table('forms_fields_lang')
                ->where('field_id', $field_id)
                ->updateOrInsert(
                    ['field_id' => $field_id, 'lang_id' => $lang->id],
                    [
                        'label' => $request['label_' . $lang->id],
                        'info' => $request['info_' . $lang->id],
                        'dropdowns' => $request['dropdowns_' . $lang->id] ?? null
                    ]
                );
        }

        return redirect(route('admin.forms.config.show', ['id' => $id]))->with('success', 'updated');
    }


    /**
     * Remove form field
     */
    public function delete_field(Request $request)
    {
        $id = $request->id; // form ID
        $field_id = $request->field_id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if (DB::table('forms_fields')->where('id', $field_id)->value('protected') == 1) return redirect(route('admin.forms.config.show', ['id' => $id]))->with('error', 'protected');

        DB::table('forms_fields_lang')->where('field_id', $field_id)->delete();
        DB::table('forms_fields')->where('id', $field_id)->delete();

        return redirect(route('admin.forms.config.show', ['id' => $id]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        $i = 0;

        $id = $request->id; // form ID
        $records = $request->all();

        foreach ($records['item'] as $key => $value) {

            DB::table('forms_fields')
                ->where('form_id', $id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }
    }
}
