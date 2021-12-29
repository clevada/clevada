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

use App\Models\Core;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App\Models\Email;
use Illuminate\Support\Str;

use App\Mail\NewTask;
use Illuminate\Support\Facades\Mail;

class FormsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->UserModel = new User();
        $this->config = Core::config();

        $this->middleware(function ($request, $next) {
            $this->logged_user_role_id = Auth::user()->role_id;
            $this->logged_user_id = Auth::user()->id;
            $this->logged_user_role = $this->UserModel->get_role_from_id($this->logged_user_role_id);

            if (!($this->logged_user_role == 'admin' || $this->logged_user_role == 'internal')) return redirect('/');
            return $next($request);
        });
    }


    /**
     * Display all messages
     */
    public function index(Request $request)
    {

        $search_form_id = $request->search_form_id;
        $search_terms = $request->search_terms;
        $search_status = $request->search_status;
        $search_replied = $request->search_replied;
        $search_important = $request->search_important;

        $messages = DB::table('forms_data')
            ->leftJoin('forms', 'forms_data.form_id', '=', 'forms.id')
            ->select(
                'forms_data.*',
                'forms.label as form_label',
                DB::raw('(SELECT closed_at FROM tasks WHERE forms_data.task_id = tasks.id) as task_closed_at')
            );

        if ($search_form_id)
            $messages = $messages->where('forms_data.form_id', $search_form_id);

        if ($search_status == 'unread')
            $messages = $messages->whereNull('forms_data.read_at');
        if ($search_status == 'read')
            $messages = $messages->whereNotNull('forms_data.read_at');

        if ($search_replied == 'yes')
            $messages = $messages->whereNotNull('forms_data.responded_at');
        if ($search_replied == 'no')
            $messages = $messages->whereNull('forms_data.responded_at');

        if ($search_important == '1')
            $messages = $messages->where('forms_data.is_important', 1);

        if ($search_terms) $messages = $messages->where(function ($query) use ($search_terms) {
            $query->where('name', 'like', "%$search_terms%")
                ->orWhere('email', 'like', "%$search_terms%")
                ->orWhere('subject', 'like', "%$search_terms%");
        });

        $messages = $messages->orderBy('id', 'desc')->paginate(25);

        $count_messages_unread = DB::table('forms_data')
            ->whereNull('read_at')
            ->count();

        return view('admin/account', [
            'view_file' => 'forms.forms-data',
            'active_menu' => 'forms',
            'search_form_id' => $search_form_id,
            'search_terms' => $search_terms,
            'search_status' => $search_status,
            'search_replied' => $search_replied,
            'search_important' => $search_important,
            'messages' => $messages,
            'count_messages_unread' => $count_messages_unread,
        ]);
    }


    /**
     * Show message     
     */
    public function show(Request $request)
    {
        $id = $request->id;

        $message = DB::table('forms_data')
            ->select('forms_data.*', DB::raw('(SELECT closed_at FROM tasks WHERE forms_data.task_id = tasks.id) as task_closed_at'))
            ->where('id', $id)
            ->first();
        if (!$message) return redirect(route('admin.forms'));

        $form = DB::table('forms')->where('id', $message->form_id)->first();

        DB::table('forms_data')->where('id', $id)->update(['read_at' => now()]);

        $fields = DB::table('forms_fields_data')
            ->leftJoin('forms_fields', 'forms_fields_data.form_data_id', '=', 'forms_fields.id')
            ->select(
                'forms_fields_data.*',
                'forms_fields.type as type',
                DB::raw('(SELECT label FROM forms_fields_lang WHERE field_id = forms_fields_data.field_id AND lang_id = ' . $message->source_lang_id . ') as label_source_lang'),
                DB::raw('(SELECT label FROM forms_fields_lang WHERE field_id = forms_fields_data.field_id AND lang_id = ' . default_lang()->id . ') as label_default_lang')
            )
            ->where('forms_fields_data.form_data_id', $id)
            ->where('forms_fields.is_default_name', 0)
            ->where('forms_fields.is_default_email', 0)
            ->where('forms_fields.is_default_subject', 0)
            ->where('forms_fields.is_default_message', 0)
            ->orderBy('forms_fields.position', 'asc')
            ->get();

        $replies = DB::table('forms_data_reply')
            ->leftJoin('users', 'forms_data_reply.from_user_id', '=', 'users.id')
            ->select('forms_data_reply.*', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar')
            ->where('form_data_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('admin/account', [
            'view_file' => 'forms.message',
            'active_menu' => 'forms',
            'message' => $message,
            'replies' => $replies,
            'form' => $form,
            'fields' => $fields,
        ]);
    }


    /**
     * Delete message
     */
    public function destroy(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // message ID
        $pagenum = $request->pagenum;

        DB::table('forms_data')->where('id', $id)->delete();
        DB::table('forms_fields_data')->where('form_data_id', $id)->delete();

        return redirect(route('admin.forms', ['pagenum' => $pagenum]))->with('success', 'deleted');
    }


    /**
     * Mark message (important, normal...)
     */
    public function mark(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // message ID
        $action = $request->action;

        if ($action == 'important') DB::table('forms_data')->where('id', $id)->update(['is_important' => 1]);

        if ($action == 'not_important') DB::table('forms_data')->where('id', $id)->update(['is_important' => 0]);

        if ($action == 'spam') DB::table('forms_data')->where('id', $id)->update(['is_spam' => 1]);

        if ($action == 'not_spam') DB::table('forms_data')->where('id', $id)->update(['is_spam' => 0]);

        return redirect(route('admin.forms.show', ['id' => $id]))->with('success', 'updated');
    }


    /**
     * Create task from message
     */
    public function create_task(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // message ID

        $message = DB::table('forms_data')->where('id', $id)->first();
        if (!$message) return redirect(route('admin.forms'));

        if ($request->has('share_access')) $share_access = 1;
        else $share_access = 0;

        if ($message->email) {
            if ($request->has('send_email')) $send_email = 1;
            else $send_email = 0;
        }

        DB::table('tasks')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'created_by_user_id' => Auth::user()->id,
            'form_data_id' => $id,
            'due_date' => $request->due_date ?? null,
            'created_at' => now(),
            'status' => 'new',
            'share_access' => $share_access,
            'allow_show_progress' => 1,
            'access_token' => Str::random(40),
        ]);

        $task_id = DB::getPdo()->lastInsertId();

        DB::table('forms_data')->where('id', $id)->update(['task_id' => $task_id]);

        if (($send_email ?? null) == 1) Mail::to($message->email)->send(new NewTask($task_id));

        return redirect(route('admin.forms.show', ['id' => $id]))->with('success', 'task_created');
    }



    /**
     *  Reply to message
     */
    public function reply(Request $request)
    {
        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $id = $request->id; // message ID

        $message = DB::table('forms_data')->where('id', $id)->first();

        if (!$message) return redirect(route('admin.forms'));

        $validator = Validator::make($request->all(), [
            'reply' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.forms.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $reply = $request->reply;

        DB::table('forms_data_reply')->insert([
            'form_id' => $message->form_id,
            'form_data_id' => $id,
            'from_user_id' => Auth::user()->id,
            'message' => $reply,
            'created_at' => now()
        ]);


        DB::table('forms_data')->where('id', $id)->update(['responded_at' => now()]);

        // send email
        if ($this->config->mail_sending_option == 'smtp') {

            $emailModel = new Email();

            $mail_args = array('to_email' => $message->email, 'subject' => 'Contacm form reply - ' . config('app.name'), 'body' => "<p>$reply</p><hr>Your message:<br>$message->message");
            $attachments = null;
            $emailModel->send_email($mail_args, $attachments);
        } else {
            // PHP MAILER	
            //----------------------------------------------------------------------------------------------------------
            $subject = 'Contact form reply - ' . config('app.name');
            $html = '           
            <div style="font-size:12px;font-family:arial;">
            <p>' . $reply . '</p><hr>Your message:<br>' . $message->message . '
            </div>
            ';

            // HTML mail
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $headers .= 'From: ' . $this->config->site_email . "\r\n" .
                'Reply-To: ' . $this->config->site_email . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($message->email, $subject, $html, $headers);
        }

        return redirect(route('admin.forms'))->with('success', 'replied');
    }
}
