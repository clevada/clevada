<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.tasks') }}">{{ __('Tasks') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $task->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if ($msg = Session::get('success'))
    <div class="alert alert-success">
        @if ($msg == 'updated') {{ __('Updated') }} @endif
    </div>
@endif

<section class="section">

    <div class="row">

        <div class="col-12 col-md-7 col-lg-8">

            <div class="card">

                <div class="card-body">

                    <h5>{{ $task->title }}</h5>
                    <p>{{ $task->description ?? null }}</p>

                    @if ($task->form_data_id)
                        <div class="alert alert-light border border-3">

                            @if (!$form->subject)
                                <div class="fw-bold text-danger">{{ __('No subject') }}</div>
                            @else <h5>{{ $form->subject }}</h5>
                            @endif

                            @if (!$form->message)
                                <div class="fw-bold text-danger">{{ __('No message') }}</div>
                            @else<p>{!! nl2br($form->message) !!}</p>
                            @endif

                            @if (count($form_fields) > 0)
                                <hr>
                                @foreach ($form_fields as $field)
                                    <b title="{{ $field->label_default_lang }}">{{ $field->label_source_lang }}</b>:
                                    @if ($field->value){{ $field->value }}@else <span class="text-danger">{{ __('No value') }}</span>@endif
                                    <div class="mb-2"></div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="mb-4"></div>

                    @if ($task->closed_at)
                        <div class="alert alert-light">
                            <b>{{ __('This task is closed at ') }} {{ date_locale($task->closed_at, 'datetime') }}</b>
                            <div class="mb-1"></div>
                            <a href="{{ route('admin.tasks.action', ['id' => $task->id, 'action' => 'reopen']) }}" class="btn btn-danger btn-sm">{{ __('Reopen task') }}</a>
                        </div>
                    @endif

                    <span class="float-end ms-2">
                        @if (!$task->closed_at)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#reply-task-{{ $task->id }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i>
                                {{ __('Add task activity') }}</a>
                            @include('admin.tasks.modals.reply-task')
                        @endif

                        @if (!$task->closed_at)
                            <a href="{{ route('admin.tasks.action', ['id' => $task->id, 'action' => 'close']) }}" class="btn btn-danger ms-2"><i class="bi bi-check2-square"></i>
                                {{ __('Close task') }}</a>
                        @endif
                    </span>


                    <h4>{{ __('Task activities') }} ({{ $activities->total() ?? 0 }})</h4>

                    <section class='mt-4'>
                        <form action="{{ route('admin.tasks.show', ['id' => $task->id]) }}" method="get" class="row row-cols-lg-auto g-2 align-items-center">

                            <div class="col-12">
                                <input type="text" name="search_terms" placeholder="{{ __('Search in messages') }}" class="form-control @if ($search_terms ?? null) is-valid @endif" value="{{ $search_terms ?? '' }}" />
                            </div>

                            <div class="col-12">
                                <select name="search_important" class="form-select @if ($search_important ?? null) is-valid @endif">
                                    <option value="">- {{ __('All responses') }} -</option>
                                    <option @if (($search_important ?? null) == 'important') selected @endif value="important">{{ __('Only important responses') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <select name="search_type" class="form-select @if ($search_type ?? null) is-valid @endif">
                                    <option value="">- {{ __('Any activity type') }} -</option>
                                    <option @if (($search_type ?? null) == 'message') selected @endif value="message">{{ __('Only messages') }}</option>
                                    <option @if (($search_type ?? null) == 'notif') selected @endif value="notif">{{ __('Only notiffications') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                                <a class="btn btn-light" href="{{ route('admin.tasks.show', ['id' => $task->id]) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </form>
                    </section>

                    <div class="mt-3 mb-2">
                        {{ __('Items marked with') }} <i class="bi bi-share-fill text-success"></i> {!! __('are <b>visible for client</b> (if access link is enabled)') !!}.
                        {{ __('Items marked with') }} <i class="bi bi-shield-lock text-danger"></i> {!! __('are visible for <b>staff only</b>') !!}.
                        {{ __('Click on the icons to change status') }}.
                    </div>

                    <div class="table-responsive-md">
                        <table class="table table-bordered table-hover">
                            <tbody>

                                @foreach ($activities as $activity)
                                    <tr>
                                        <td>
                                            @if ($activity->internal_only == 1)
                                                <span class="float-end ms-3">
                                                    <a title="{{ __('Internal only') }}"
                                                        href="{{ route('admin.tasks.action', ['id' => $task->id, 'activity_id' => $activity->id, 'action' => 'activity_enable_share']) }}">
                                                        <i class="bi bi-shield-lock text-danger"></i>
                                                    </a>
                                                </span>
                                            @else
                                                <span class="float-end ms-3">
                                                    <a title="{{ __('Visible for client') }}"
                                                        href="{{ route('admin.tasks.action', ['id' => $task->id, 'activity_id' => $activity->id, 'action' => 'activity_disable_share']) }}"><i
                                                            class="bi bi-share-fill text-success"></i></a>
                                                </span>
                                            @endif

                                            @if ($activity->is_important == 1)
                                                <span class="float-end ms-3">
                                                    <a title="{{ __('Important') }}"
                                                        href="{{ route('admin.tasks.action', ['id' => $task->id, 'activity_id' => $activity->id, 'action' => 'activity_not_important']) }}"><i
                                                            class="bi bi-star-fill text-danger"></i></a>
                                                </span>
                                            @else
                                                <span class="float-end ms-3">
                                                    <a title="{{ __('Mark as important') }}"
                                                        href="{{ route('admin.tasks.action', ['id' => $task->id, 'activity_id' => $activity->id, 'action' => 'activity_important']) }}"><i
                                                            class="bi bi-star text-secondary"></i></a>
                                                </span>
                                            @endif

                                            @if ($activity->author_avatar)
                                                <span class="float-left mr-2"><img style="max-width:28px; height:auto;" src="{{ image($activity->author_avatar) }}" /></span>
                                            @endif

                                            <b><a target="_blank" href="{{ route('admin.accounts.show', ['id' => $activity->user_id]) }}">{{ $activity->author_name }}</a></b> {{ __('at') }}
                                            {{ date_locale($activity->created_at, 'datetime') }}

                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $activity->id }}" class="btn btn-light btn-sm text-danger px-2 py-0 ms-2 ">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this activity?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                            <a href="{{ route('admin.tasks.action', ['id' => $task->id, 'activity_id' => $activity->id, 'action' => 'activity_delete']) }}" type="submit"
                                                                class="btn btn-danger">{{ __('Yes. Delete') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($activity->progress_old != $activity->progress_new)
                                                <div class="text-secondary">
                                                    <i>{{ __('Progress changed from') }} <b>{{ $activity->progress_old }}%</b> {{ __('to') }} <b>{{ $activity->progress_new }}%</b></i>
                                                </div>
                                            @endif

                                            <div class="mb-2"></div>

                                            @if ($activity->type == 'message')
                                                <b>{!! nl2br($activity->message) !!}</b>
                                            @elseif($activity->type == 'close')
                                                <i>{{ __('Task closed') }}</i>
                                            @elseif($activity->type == 'reopen')
                                                <i>{{ __('Task reopen') }}</i>
                                            @elseif($activity->type == 'priority_changed')
                                                <i>{{ __('Priority changed to') }}:
                                                    @if ($task->priority == 1) {{ __('Urgent') }}
                                                    @elseif ($task->priority == 2) {{ __('Normal') }}
                                                    @elseif ($task->priority == 3) {{ __('Low') }}
                                                    @else {{ $task->priority }}
                                                    @endif

                                                @elseif($activity->type == 'change_due_date')
                                                    <i>{{ __('Due date changed to: ') }}</i>
                                                @elseif($activity->type == 'share_enable')
                                                    <i>{{ __('Enable access link') }}</i>
                                                @elseif($activity->type == 'share_disable')
                                                    <i>{{ __('Disable access link') }}</i>
                                                @elseif($activity->type == 'share_send_mail ')
                                                    <i>{{ __('Email sent with task access URL') }}</i>
                                                @else
                                                    <b>{!! nl2br($activity->message) !!}</b>
                                            @endif


                                            @if ($activity->file)
                                                <div class="mt-2"></div>
                                                <a title="{{ $activity->file }}" target="_blank" href="{{ asset('uploads/' . $activity->file) }}"><i class="bi bi-file-text"></i>
                                                    {{ __('View attachment') }} ({{ $activity->file }})</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{ $activities->appends(['search_terms' => $search_terms, 'search_important' => $search_important, 'search_type' => $search_type])->links() }}

                </div>

            </div>

        </div>


        <div class="col-12 col-md-5 col-lg-4">

            <div class="card">

                <div class="card-body">

                    @if ($task->form_data_id)
                        <div class="alert alert-secondary">
                            <i class="bi bi-exclamation-circle"></i> {{ __('This task was created from form message') }}. <br>
                            {{ __('Message sent at') }}: <b>{{ date_locale($form->created_at, 'datetime') }}</b><br>
                            {{ __('Task created at') }}: <b>{{ date_locale($task->created_at, 'datetime') }}</b><br>
                            <a target="_blank" href="{{ route('admin.forms.show', ['id' => $task->form_data_id]) }}">{{ __('View form message') }}</a>
                            <hr>
                            {{ __('Sender name') }}: <b>@if (!$form->name)<span class="text-danger">{{ __('No name') }}</span>@else{{ $form->name }}@endif </b>
                            <br />
                            {{ __('Sender email') }}: <b>@if (!$form->email)<span class="text-danger">{{ __('No email') }}</span>@else{{ $form->email }}@endif </b>
                        </div>
                    @endif

                    {{ __('Progress') }} ({{ (int) $task->progress }}%)
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar @if ($task->progress < 50) bg-danger @elseif($task->progress >= 50 && $task->progress < 100) bg-warning @else bg-success @endif" role="progressbar" style="width: {{ (int) $task->progress }}%" aria-valuenow="{{ (int) $task->progress }}" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>

                    <div class="form-text small text-muted mb-3">{{ __('Add a task activity to change progress percent') }}</div>

                    <form method="post" action="{{ route('admin.tasks.show', ['id' => $task->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Priority') }}</label>
                                    <select name="priority" class="form-select" required>
                                        <option @if ($task->priority == 3) selected @endif value="3">{{ __('Low') }}</option>
                                        <option @if ($task->priority == 2) selected @endif value="2">{{ __('Normal') }}</option>
                                        <option @if ($task->priority == 1) selected @endif value="1">{{ __('Urgent') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Due date') }}</label>
                                    <input class="form-control" name="due_date" type="date" autocomplete="off" value="{{ $task->due_date }}" />
                                    <small id="duedateHelpBlock" class="form-text text-muted">
                                        {{ __('Leave empty if task have not any due date') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Asign this task to internal operator') }}</label>
                            <select name="operator_user_id" class="form-select select2">
                                <option value="">- {{ __('no operator') }} -</option>
                            </select>
                            <div class="form-text text-muted">{{ __('Leave empty if any internal user (with access to tasks module) can manage this task') }}</div>
                        </div>

                        <div class="form-group mb-1 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="share_access" name="share_access" @if ($task->share_access) checked @endif>
                                <label class="form-check-label" for="share_access">{{ __('Enable access link') }}</label>
                            </div>
                            <div class="form-text">
                                {{ __('Enable access URL and share with destinatar (client). Destinatar will have access to some task details and see task progress') }}
                            </div>
                        </div>

                        <script>
                            $('#share_access').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_share').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_share').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_share" style="display: @if ($task->share_access) block @else none @endif">
                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="SwitchProgress" name="share_show_progress" @if ($task->share_show_progress == 1) checked @endif>
                                    <label class="form-check-label" for="SwitchProgress">{{ __('Show progress bar to client') }}</label>
                                </div>
                                <div class="form-text">
                                    {{ __('If enabled, a progress bar with progress percent will be displayed to destinatar') }}
                                </div>
                            </div>

                            <div class="form-group mb-2 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="SwitchStaffNames" name="share_disable_names" @if ($task->share_disable_names == 1) checked @endif>
                                    <label class="form-check-label" for="SwitchStaffNames">{{ __('Disable staff names') }}</label>
                                </div>
                                <div class="form-text">
                                    {{ __('If enabled, "Operator" text will be displayed instead staff real names') }}
                                </div>
                            </div>

                            <input type="text" readonly id="shareLink" class="form-control" value="{{ route('task', ['token' => $task->access_token]) }}">

                            <div class="mb-2"></div>

                            <a target="_blank" href="{{ route('task', ['token' => $task->access_token]) }}"><b>[{{ __('View access page') }}]</b></a>

                            <a href="#" onclick="shareLink()"><b>[{{ __('Copy link') }}]</b></a>
                            <script>
                                function shareLink() {
                                    var copyText = document.getElementById("shareLink");
                                    copyText.select();
                                    copyText.setSelectionRange(0, 99999); /* For mobile devices */
                                    navigator.clipboard.writeText(copyText.value);
                                    alert("Copied the text: \n" + copyText.value);
                                }
                            </script>

                            <div class="mb-1"></div>
                            <a target="_blank" href="{{ route('task', ['token' => $task->access_token]) }}"><b>[{{ __('Send link by email') }}]</b></a>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Update task') }}</button>
                        </div>

                    </form>

                </div>

            </div>

        </div>


    </div>

</section>
