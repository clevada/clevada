<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item "><a href="{{ route('admin.forms') }}">{{ __('Forms') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Message details') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if ($alert = Session::get('success'))
    <div class="alert alert-success">
        @if ($alert == 'task_created') {{ __('Task created') }} @endif
    </div>
@endif

<section class="section">

    <div class="row">

        <div class="col-12 col-md-7 col-lg-8">

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="alert alert-light border border-3">

                            @if ($message->is_important ?? null)
                                <span class="float-end ms-2">
                                    <a href="{{ route('admin.forms.mark', ['id' => $message->id, 'action' => 'important']) }}" class="btn btn-success btn-sm ms-2"><i class="bi bi-star"></i>
                                        {{ __('Flag as important') }}</a>
                                </span>
                            @else
                                <span class="float-end ms-2">
                                    <a href="{{ route('admin.forms.mark', ['id' => $message->id, 'action' => 'not_important']) }}" class="btn btn-success btn-sm ms-2"><i class="bi bi-star"></i>
                                        {{ __('Flag as normal') }}</a>
                                </span>
                            @endif

                            @if (!$message->subject)
                                <div class="fw-bold text-danger">{{ __('No subject') }}</div>
                            @else <h5>{{ $message->subject }}</h5>
                            @endif

                            @if (!$message->message)
                                <div class="fw-bold text-danger">{{ __('No message') }}</div>
                            @else<p>{!! nl2br($message->message) !!}</p>
                            @endif

                            @if (count($fields) > 0)
                                <hr>
                                @foreach ($fields as $field)
                                    <b title="{{ $field->label_default_lang }}">{{ $field->label_source_lang }}</b>:
                                    @if ($field->value){{ $field->value }}@else <span class="text-danger">{{ __('No value') }}</span>@endif
                                    <div class="mb-2"></div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-3"></div>

                        <h5>{{ $replies->total() }} {{ __('replies') }}</h5>

                        @foreach ($replies as $reply)

                            <div class="alert alert-light mb-4">
                                {!! $reply->message !!}
                                <div class="form-text text-muted small mt-2">
                                    {{ __('Sender') }}: <b>{{ $reply->author_name }}</b> - {{ date_locale($reply->created_at, 'datetime') }}
                                </div>
                            </div>

                        @endforeach

                        @if (!($config->site_email ?? null))
                            <div class="alert alert-danger">
                                {{ __("You can't send reply because you didn't set your email details in email config") }}.
                                <a href="{{ route('admin.config.email') }} ">{{ __('Email config') }}</a>
                            </div>

                        @else
                            <form action="{{ route('admin.forms.reply', ['id' => $message->id]) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="reply" rows="6" required></textarea>

                                            <div class="alert alert-light mt-3" role="alert">
                                                <i class="bi bi-exclamation-circle"></i> {{ __('You will send reply to') }} <b>{{ $message->email }}</b>.
                                                {{ __('If destinatar will reply, you will get destinatar replies in your email') }}: <b>{{ $config->site_email }}</b>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <input type="hidden" name="msg_id" value="{{ $message->id }}" />
                                        <button type="submit" class="btn btn-primary">{{ __('Send reply') }}</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>

                </div>
                <!-- end card-body -->

            </div>

        </div>


        <div class="col-12 col-md-5 col-lg-4">

            <div class="card">

                <div class="card-body">

                    {{ __('Sent at') }}: <b>{{ date_locale($message->created_at, 'datetime') }}</b>
                    <br>
                    {{ __('Message sent using this form') }}: <a href="{{ route('admin.forms.config') }}"><b>{{ $form->label }}</b></a>
                    <br>
                    {{ __('Referer') }}: <a target="_blank" href="{{ $message->referer ?? '#' }}"><b>{{ $message->referer }}</b></a>
                    <br>
                    {{ __('Source language') }}: <b>{{ lang($message->source_lang_id)->name }}</b>

                    <hr>

                    <h5>{{ __('Sender details') }}:</h5>
                    <b>{{ __('Name') }}</b> @if (!$message->name)<span class="text-danger">{{ __('No name') }}</span>@else{{ $message->name }}@endif
                    <br />
                    <b>{{ __('Email') }}</b> @if (!$message->email)<span class="text-danger">{{ __('No email') }}</span>@else{{ $message->email }}@endif
                    <br />
                    <b>IP: </b>: {{ $message->ip }}

                    <div class="mb-2"></div>

                    @if ($message->is_spam == 0)
                        <a href="{{ route('admin.forms.mark', ['id' => $message->id, 'action' => 'important']) }}" class="btn btn-danger btn-sm"><i class="bi bi-exclamation-triangle"></i>
                            {{ __('Spam') }}</a>
                    @else
                        <a href="{{ route('admin.forms.mark', ['id' => $message->id, 'action' => 'not_important']) }}" class="btn btn-success btn-sm"><i class="bi bi-shield"></i>
                            {{ __('Not spam') }}</a>
                    @endif

                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $message->id }}" class="btn btn-danger btn-sm ms-2"><i class="bi bi-trash"></i> {{ __('Delete') }}</a>
                    <div class="modal fade confirm-{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ __('Are you sure you want to delete this item?') }}
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="{{ route('admin.forms.delete', ['id' => $message->id]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @if (check_access('tasks'))
                        @if ($message->task_id)
                            {{ __('This message have a task ') }}
                            @if ($message->task_closed_at)
                                <div class="text-success fw-bold">{{ __('Task closed') }}</div>
                                <a class="btn btn-primary mt-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('Manage task') }}</a>
                            @else
                                <div class="text-danger fw-bold">{{ __('Task not closed') }}</div>
                                <a class="btn btn-primary mt-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('View task') }}</a>
                            @endif
                        @else
                            {{ __('Convert this message into a task to manage it internally. Sender can have access to task details and progress.') }}:
                            <div class="mb-2"></div>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#convert-task"><i class="bi bi-card-checklist"></i>
                                {{ __('Convert into task') }}</a>
                            @include('admin.forms.modals.convert-task')
                        @endif
                    @endif

                </div>

            </div>

        </div>

    </div>

</section>
