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
use DB;

class TaskController extends Controller
{

    /**
     * Display static page
     */
    public function index(Request $request)
    {
        $token = $request->token;

        if (!$token) return redirect(route('homepage'));

        $task = DB::table('tasks')->where('access_token', $token)->where('share_access', 1)->first();

        if (!$task) return redirect(route('homepage'));

        $activities = DB::table('tasks_activity')
            ->leftJoin('users', 'tasks_activity.user_id', '=', 'users.id')
            ->select('tasks_activity.*', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar')
            ->where('task_id', $task->id)
            ->where('internal_only', 0)
            ->orderBy('id', 'desc')
            ->paginate(25);

        if ($task->form_data_id) {
            $form = DB::table('forms_data')->where('id', $task->form_data_id)->first();

            $form_fields = DB::table('forms_fields_data')
                ->leftJoin('forms_fields', 'forms_fields_data.form_data_id', '=', 'forms_fields.id')
                ->select(
                    'forms_fields_data.*',
                    'forms_fields.type as type',
                    DB::raw('(SELECT label FROM forms_fields_lang WHERE field_id = forms_fields_data.field_id AND lang_id = ' . $form->source_lang_id . ') as label_source_lang'),
                    DB::raw('(SELECT label FROM forms_fields_lang WHERE field_id = forms_fields_data.field_id AND lang_id = ' . default_lang()->id . ') as label_default_lang')
                )
                ->where('forms_fields_data.form_data_id', $form->id)
                ->where('forms_fields.is_default_name', 0)
                ->where('forms_fields.is_default_email', 0)
                ->where('forms_fields.is_default_subject', 0)
                ->where('forms_fields.is_default_message', 0)
                ->orderBy('forms_fields.position', 'asc')
                ->get();
        }

        return view('frontend/builder/task', [
            'task' => $task,
            'activities' => $activities,
            'form' => $form ?? null, // if task is created from form message
            'form_fields' => $form_fields ?? null, // if task is created from form message
        ]);
    }
}
