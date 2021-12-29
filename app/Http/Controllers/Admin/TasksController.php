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

class TasksController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->UploadModel = new Upload();

        $this->middleware(function ($request, $next) {
            $this->role_id = Auth::user()->role_id;

            $role = $this->UserModel->get_role_from_id($this->role_id);
            if ($role != 'admin') return redirect('/');
            return $next($request);
        });
    }


    /**
     * Show all resources
     */
    public function index(Request $request)
    {

        $search_terms = $request->search_terms;
        $search_status = $request->search_status;
        $search_priority = $request->search_priority;
        $search_source = $request->search_source;
        $orderBy = $request->orderBy;

        $tasks = DB::table('tasks')
            ->leftJoin('forms_data', 'tasks.form_data_id', '=', 'forms_data.id')
            ->select(
                'tasks.*',
                'forms_data.name as form_sender_name',
                'forms_data.email as form_sender_email',
                DB::raw('(SELECT name FROM users WHERE tasks.created_by_user_id = users.id) as author_name'),
                DB::raw('(SELECT avatar FROM users WHERE tasks.created_by_user_id = users.id) as author_avatar'),
                DB::raw('(SELECT name FROM users WHERE tasks.operator_user_id = users.id) as operator_name'),
                DB::raw('(SELECT avatar FROM users WHERE tasks.operator_user_id = users.id) as operator_avatar'),
                DB::raw('(SELECT name FROM users WHERE tasks.client_user_id = users.id) as client_name'),
                DB::raw('(SELECT avatar FROM users WHERE tasks.client_user_id = users.id) as client_avatar')
            );

        if ($search_status == 'new') $tasks = $tasks->whereNull('tasks.updated_at')->whereNull('tasks.closed_at');
        if ($search_status == 'closed') $tasks = $tasks->whereNotNull('tasks.closed_at');
        if ($search_status == 'progress') $tasks = $tasks->whereNotNull('tasks.updated_at')->whereNull('tasks.closed_at');
        if ($search_status == 'new_progress') $tasks = $tasks->whereNull('tasks.closed_at');

        if (isset($search_priority)) $tasks = $tasks->where('tasks.priority', $search_priority);
        if ($search_terms) $tasks = $tasks->where('title', 'like', "%$search_terms%");

        if ($search_source == 'forms') $tasks = $tasks->whereNotNull('form_data_id');
        if ($search_source == 'manual') $tasks = $tasks->whereNull('form_data_id');

        if (!$orderBy) $tasks = $tasks->orderBy('tasks.id', 'desc');
        if ($orderBy == 'due_date') $tasks = $tasks->orderBy('tasks.due_date', 'desc');
        if ($orderBy == 'progress_low') $tasks = $tasks->orderBy('tasks.progress', 'asc');
        if ($orderBy == 'progress_high') $tasks = $tasks->orderBy('tasks.progress', 'desc');

        $tasks = $tasks->paginate(25);

        $count_tasks_not_completed = $tasks->whereNull('completed_at')->count();

        return view('admin.account', [
            'view_file' => 'tasks.tasks',
            'active_menu' => 'productivity',
            'active_submenu' => 'tasks',
            'search_terms' => $search_terms,
            'search_status' => $search_status,
            'search_priority' => $search_priority,
            'search_source' => $search_source,
            'orderBy' => $orderBy,
            'tasks' => $tasks,
            'count_tasks_not_completed' => $count_tasks_not_completed,
        ]);
    }


    /**
     * Show form to add new resource
     */
    public function create()
    {
        $internals = $this->UserModel->get_module_internals('tasks');

        return view('admin.account', [
            'view_file' => 'tasks.create',
            'active_menu' => 'productivity',
            'active_submenu' => 'tasks',
            'internals' => json_decode(json_encode($internals)),
        ]);
    }


    /**
     * Show resource
     */
    public function show(Request $request)
    {
        $id = $request->id;

        $search_terms = $request->search_terms;
        $search_important = $request->search_important;
        $search_type = $request->search_type;

        $task = DB::table('tasks')->where('id', $id)->first();
        if (!$task) return redirect(route('admin.tasks'));

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

        $activities = DB::table('tasks_activity')
            ->leftJoin('users', 'tasks_activity.user_id', '=', 'users.id')
            ->select('tasks_activity.*', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar')
            ->where('task_id', $id);

        if ($search_terms) $activities = $activities->where('tasks_activity.message', 'like', "%$search_terms%");
        if ($search_important == 'important') $activities = $activities->where('tasks_activity.is_important', 1);
        if ($search_type == 'message') $activities = $activities->where('tasks_activity.type', 'message');
        if ($search_type == 'notif') $activities = $activities->where('tasks_activity.type', '!=', 'message');

        $activities = $activities->orderBy('id', 'desc')
            ->paginate(25);

        return view('admin.account', [
            'view_file' => 'tasks.task',
            'active_menu' => 'productivity',
            'active_submenu' => 'tasks',
            'task' => $task,
            'activities' => $activities,
            'search_terms' => $search_terms,
            'search_important' => $search_important,
            'search_type' => $search_type,

            'form' => $form ?? null, // if task is created from form message
            'form_fields' => $form_fields ?? null, // if task is created from form message
        ]);
    }

    /**
     * Create resource
     */
    public function store(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.tasks'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->all();

        DB::table('tasks')->insert([
            'title' => $inputs['title'],
            'description' => $inputs['description'],
            'operator_user_id' => $inputs['operator_user_id'],
            'client_user_id' => $inputs['client_user_id'],
            'priority' => $inputs['priority'],
            'due_date' => $inputs['due_date'],
            'created_at' => now(),
            'created_by_user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('file')) {
            $id = DB::getPdo()->lastInsertId();
            $file_db = $this->UploadModel->upload_file($request, 'file');
            DB::table('tasks')->where('id', $id)->update(['file' => $file_db]);
        }

        return redirect(route('admin.tasks'))->with('success', 'created');
    }


    /**
     * Update resource
     */
    public function update(Request $request)
    {
        $id = $request->id; // task ID

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $inputs = $request->except('_token');

        if ($request->has('share_access')) $share_access = 1;
        else $share_access = 0;
        if ($request->has('share_show_progress')) $share_show_progress = 1;
        else $share_show_progress = 0;
        if ($request->has('share_disable_names')) $share_disable_names = 1;
        else $share_disable_names = 0;

        $priority_old = DB::table('tasks')->where('id', $id)->value('priority');

        DB::table('tasks')
            ->where('id', $id)
            ->update([
                'operator_user_id' => $inputs['operator_user_id'] ?? null,
                'priority' => $inputs['priority'],
                'due_date' => $inputs['due_date'],
                'share_access' => $share_access,
                'share_show_progress' => $share_show_progress,
                'share_disable_names' => $share_disable_names,
            ]);

        if ($priority_old != $inputs['priority']) {
            // add activity
            DB::table('tasks_activity')->insert([
                'task_id' => $id,
                'user_id' => Auth::user()->id,
                'type' => 'priority_changed',
                'created_at' => now(),
                'internal_only' => 1,
            ]);
        }


        return redirect(route('admin.tasks.show', ['id' => $id]))->with('success', 'updated');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        DB::table('tasks')->where('id', $id)->delete();
        DB::table('tasks_activity')->where('task_id', $id)->delete();

        return redirect(route('admin.tasks'))->with('success', 'deleted');
    }


    /**
     * Reply to task (add activity)
     */
    public function reply(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // task ID

        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.tasks.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('_token');

        if ($request->has('is_important')) $is_important = 1;
        else $is_important = 0;
        if ($request->has('visible_for_client')) $internal_only = 0;
        else $internal_only = 1;

        $progress_old = DB::table('tasks')->where('id', $id)->value('progress');

        DB::table('tasks_activity')->insert([
            'task_id' => $id,
            'user_id' => Auth::user()->id,
            'type' => 'message',
            'message' => $inputs['message'],
            'created_at' => now(),
            'is_important' => $is_important,
            'internal_only' => $internal_only,
            'progress_old' => $progress_old,
            'progress_new' => $inputs['progress'],
        ]);

        $activity_id = DB::getPdo()->lastInsertId();

        DB::table('tasks')
            ->where('id', $id)
            ->update([
                'updated_at' => now(),
                'progress' => $inputs['progress'],
            ]);

        // process file
        if ($request->hasFile('file')) {
            $file_db = $this->UploadModel->upload_file($request, 'file');
            DB::table('tasks_activity')->where('id', $activity_id)->update(['file' => $file_db]);
        }

        return redirect(route('admin.tasks.show', ['id' => $id]))->with('success', 'updated');
    }


    /**
     * Task action
     */
    public function action(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // task ID
        $action = $request->action;

        if ($action == 'close') {
            // close the task
            $progress_old = DB::table('tasks')->where('id', $id)->value('progress');
            DB::table('tasks')->where('id', $id)->update(['closed_at' => now(), 'progress' => 100]);

            // add activity
            DB::table('tasks_activity')->insert([
                'task_id' => $id,
                'user_id' => Auth::user()->id,
                'type' => 'close',
                'created_at' => now(),
                'progress_old' => $progress_old,
                'progress_new' => 100,
                'internal_only' => 1,
            ]);
        }

        if ($action == 'reopen') {
            // reopen the task
            DB::table('tasks')->where('id', $id)->update(['closed_at' => null]);

            // add activity
            DB::table('tasks_activity')->insert([
                'task_id' => $id,
                'user_id' => Auth::user()->id,
                'type' => 'reopen',
                'created_at' => now(),
                'internal_only' => 1,
            ]);
        }

        if ($action == 'activity_important') {
            $activity_id = $request->activity_id;
            // mark he activity as important
            DB::table('tasks_activity')->where('id', $activity_id)->update(['is_important' => 1]);
        }

        if ($action == 'activity_not_important') {
            $activity_id = $request->activity_id;
            // mark he activity as normal
            DB::table('tasks_activity')->where('id', $activity_id)->update(['is_important' => 0]);
        }

        if ($action == 'activity_enable_share') {
            $activity_id = $request->activity_id;
            // enable share
            DB::table('tasks_activity')->where('id', $activity_id)->update(['internal_only' => 0]);
        }

        if ($action == 'activity_disable_share') {
            $activity_id = $request->activity_id;
            // disable share
            DB::table('tasks_activity')->where('id', $activity_id)->update(['internal_only' => 1]);
        }

        if ($action == 'activity_delete') {
            $activity_id = $request->activity_id;
            // delete activity
            DB::table('tasks_activity')->where('id', $activity_id)->where('task_id', $id)->delete();
        }

        return redirect(route('admin.tasks.show', ['id' => $id]))->with('success', 'updated');
    }
}
