<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>

    <title>{{ $task->title ?? __('Task details') }}</title>
    <meta name="description" content="{{ $task->description ?? __('Task details') }}">

    @include("{$template_view}.global.head")

</head>

<body>

    <!-- Main Content -->
    <div class="content">

        @if ($message = Session::get('success'))
            <div class="container py-4">
                <div class="alert alert-success">
                    @if ($message == 'form_submited'){{ __('Form submitted successfully') }} @endif
                </div>
            </div>
        @endif

        <div class="container">
            <div class="row">

                <div class="col-12 mt-4 mb-3">
                    <h2 class="float-end">{{ __('Task details') }}</h2>

                    @if ($config->logo)
                        <div class="float-start">
                            <img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <div class='alert alert-light border border-3'>
                        <h4>{{ $task->title ?? null }}</h4>
                        <b>{{ $task->description ?? null }}</b>
                        <div class="mt-2 mb-2 text-secondary small">{{ __('Created at') }}: {{ date_locale($task->created_at, 'datetime') }}</div>

                        <b>{{ __('Task status') }}:</b>
                        <div class="mb-1"></div>
                        @if ($task->closed_at) <button class="btn btn-secondary">{{ __('Closed at') }}: {{ date_locale($task->closed_at, 'datetime') }}</button>
                        @else <button class="btn btn-warning">{{ __('In progress') }}</button>
                        @endif

                        @if ($task->form_data_id)
                            <hr>
                            <div class="fw-bold text-info">{{ __('Task is created from your form') }}:</div>

                            @if (count($form_fields) > 0)
                                @foreach ($form_fields as $field)
                                    <b title="{{ $field->label_default_lang }}">{{ $field->label_source_lang }}</b>:
                                    @if ($field->value){{ $field->value }}@else <span class="text-danger">{{ __('No value') }}</span>@endif
                                    @if (!$loop->last)<div class="mb-2"></div>@endif
                                @endforeach
                            @endif
                        @endif
                    </div>

                    @if ($task->share_show_progress == 1)
                        <h5 class="mt-4">{{ __('Progress') }} ({{ (int) $task->progress }}%)</h5>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar @if ($task->progress < 50) bg-danger @elseif($task->progress >= 50 && $task->progress < 100) bg-warning @else bg-success @endif" role="progressbar" style="width: {{ (int) $task->progress }}%" aria-valuenow="{{ (int) $task->progress }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    @endif

                    @if ($activities->total() > 0)
                        <h5 class="mt-4">{{ __('Task activities') }}:</h5>

                        <div class="table-responsive-md">
                            <table class="table table-bordered table-hover">
                                <tbody>

                                    @foreach ($activities as $activity)
                                        <tr>
                                            <td>
                                                <b class="text-primary">@if ($task->share_disable_names == 1){{ __('Operator') }}@else{{ $activity->author_name }}@endif</b>
                                                {{ __('at') }} {{ date_locale($activity->created_at, 'datetime') }}

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
                                                    @else
                                                        <b>{!! nl2br($activity->message) !!}</b>
                                                @endif

                                                @if ($activity->file)
                                                    <div class="mt-2"></div>
                                                    <a title="{{ $activity->file }}" target="_blank" href="{{ asset('uploads/' . $activity->file) }}"><i class="bi bi-file-text"></i>
                                                        {{ __('View attachment') }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{ $activities->links() }}

                    @endif


                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                <div class="col-12">
                    <hr>
                    <p class="text-center mb-4">
                        &copy; {{ __('Powered by') }} <a target="_blank" href="https://clevada.com"><b>Clevada</b></a> - Free Website Builder for Businesses, Communities, Teams and Personal Websites.
                    </p>
                </div>
            </div>
        </div>

    </div>
    <!-- End Main Content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
