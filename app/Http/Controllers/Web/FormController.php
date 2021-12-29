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
use Illuminate\Support\Facades\Validator;
use DB;

class FormController extends Controller
{

    /**
     * Process form
     */
    public function submit(Request $request)
    {
        $form_id = $request->id;
        $block_id = $request->block_id;

        $form = DB::table('forms')->where('id', $form_id)->where('active', 1)->first();
        if (!$form) return redirect(route('homepage'));

        $inputs = $request->except(['_token', '_method']);

        $referer = request()->headers->get('referer');

        /*
        if($this->sys_config()->contact_recaptcha_enabled ?? null == 1) {
            // Build POST request:
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = $this->config->google_recaptcha_secret_key ?? null;
            $recaptcha_response = $request->recaptcha_response;

            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            // Take action based on the score returned:
            if ($recaptcha->success) {
                if($recaptcha->score < 0.5) return redirect($request->Url())->with('error', 'recaptcha_error');            
            }
            else return redirect($request->Url())->with('error', 'recaptcha_error');
        }
        */

        // get NAME field (if enabled) for this form 
        $field_id_name = DB::table('forms_fields')->where('form_id', $form_id)->where('is_default_name', 1)->value('id');
        $name = $inputs[$field_id_name] ?? null;

        // get EMAIL field (if enabled) for this form 
        $field_id_email = DB::table('forms_fields')->where('form_id', $form_id)->where('is_default_email', 1)->value('id');
        $email = $inputs[$field_id_email] ?? null;

        // get SUBJECT field (if enabled) for this form 
        $field_id_subject = DB::table('forms_fields')->where('form_id', $form_id)->where('is_default_subject', 1)->value('id');
        $subject = $inputs[$field_id_subject] ?? null;

        // get MESSAGE field (if enabled) for this form 
        $field_id_message = DB::table('forms_fields')->where('form_id', $form_id)->where('is_default_message', 1)->value('id');
        $message = $inputs[$field_id_message] ?? null;

        DB::table('forms_data')->insert([
            'form_id' => $form_id,
            'name' => $name ?? null,
            'email' => $email ?? null,
            'subject' => $subject ?? null,
            'message' => $message ?? null,
            'referer' => $referer ?? null,
            'created_at' =>  now(),
            'ip' => $request->ip(),
            'source_lang_id' => $inputs['source_lang_id'] ?? null,
        ]);

        $form_data_id = DB::getPdo()->lastInsertId();

        $fields = DB::table('forms_fields')->where('form_id', $form_id)->orderBy('position', 'asc')->get();

        foreach ($fields as $field) {
            $value_key = $field->id;

            DB::table('forms_fields_data')->insert([
                'form_id' => $form_id,
                'form_data_id' => $form_data_id,
                'field_id' => $field->id,
                'value' => $inputs[$value_key] ?? null,
            ]);
        }

        if ($referer ?? null) {
            $goToSection = $referer . '#' . $block_id;
            return redirect($goToSection)->with('success', 'form_submited');
        } else return redirect(route('homepage'))->with('success', 'form_submited');
    }
}
