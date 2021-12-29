<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tasks') }}">{{ __('Tasks') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">
                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Tasks') }} ({{ $tasks->total() }} {{ __('total') }}, {{ $count_tasks_not_completed }} {{ __('not completed') }})</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <a class="btn btn-primary" href="{{ route('admin.tasks.create') }}"> {{ __('Create new task') }}</a>
                    </div>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            <section>
                <form action="{{ route('admin.tasks') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="{{ __('Search task') }}" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? null }}" />
                    </div>

                    <div class="col-12">
                        <select name="search_status" class="form-select @if ($search_status) is-valid @endif">
                            <option selected="selected" value="">- {{ __('All statuses') }} -</option>
                            <option @if ($search_status == 'new') selected @endif value="new"> {{ __('New tasks') }}</option>
                            <option @if ($search_status == 'progress') selected @endif value="progress"> {{ __('In progress') }}</option>
                            <option @if ($search_status == 'new_progress') selected @endif value="new_progress"> {{ __('New or in progress') }}</option>
                            <option @if ($search_status == 'closed') selected @endif value="closed"> {{ __('Closed tasks') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_priority" class="form-select @if ($search_priority) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Any priority') }} -</option>
                            <option @if ($search_priority == '3') selected @endif value="3"> {{ __('Low') }}</option>
                            <option @if ($search_priority == '2') selected @endif value="2"> {{ __('Normal') }}</option>
                            <option @if ($search_priority == '1') selected @endif value="1"> {{ __('High') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_source" class="form-select @if ($search_source) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Any source') }} -</option>
                            <option @if ($search_source == 'manual') selected @endif value="manual"> {{ __('Manually created') }}</option>
                            <option @if ($search_source == 'forms') selected @endif value="forms"> {{ __('Forms') }}</option>                            
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="orderBy" class="form-select @if ($orderBy) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Default order (latest tasks)') }} -</option>
                            <option @if ($orderBy == 'due_date') selected @endif value="due_date"> {{ __('Order by due date') }}</option>
                            <option @if ($orderBy == 'progress_low') selected @endif value="progress_low"> {{ __('Order by progress (low to high)') }}</option>                            
                            <option @if ($orderBy == 'progress_high') selected @endif value="progress_high"> {{ __('Order by progress (high to low)') }}</option>                            
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light" href="{{ route('admin.tasks') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>

            <div class="mb-3"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="220">{{ __('Progress') }}</th>
                            <th width="280">{{ __('Client') }}</th>
                            <th width="160">{{ __('Priority') }}</th>
                            <th width="100">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tasks as $task)
                            <tr @if ($task->closed_at) class="bg-light" @endif>
                                <td>

                                    <div class="listing">                                        
                                        @if ($task->closed_at)<span class="float-end ms-2 badge bg-secondary">{{ __('Closed') }}</span>@endif
                                        @if (! $task->closed_at && ! $task->updated_at)<span class="float-end ms-2 badge bg-danger">{{ __('New') }}</span>@endif
                                        @if (! $task->closed_at && $task->updated_at)<span class="float-end ms-2 badge bg-warning">{{ __('In progress') }}</span>@endif
                                        <a href="{{ route('admin.tasks.show', ['id' => $task->id]) }}"><b>{{ $task->title }}</b></a>
                                    </div>

                                    @if ($task->operator_user_id)
                                        @if ($task->operator_avatar)
                                            <span class="float-start me-2"><img style="max-width:25px; height:auto;" src="{{ image($task->operator_avatar) }}" /></span>
                                        @endif
                                        <h5>{{ $task->operator_name }}</h5>
                                    @else
                                        {{ __('Operator') }}: {{ __('any') }}
                                    @endif

                                    <div class="text-muted small">
                                        {{ __('Created') }}: {{ date_locale($task->created_at, 'datetime') }} {{ __('by') }} <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $task->created_by_user_id]) }}">{{ $task->author_name }}</a>
                                    </div>
                                </td>

                                <td>
                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar @if ($task->progress < 50) bg-danger @elseif($task->progress >= 50 && $task->progress < 100) bg-warning @else bg-success @endif" role="progressbar" style="width: {{ (int) $task->progress }}%" aria-valuenow="{{ (int) $task->progress }}"
                                            aria-valuemin="0" aria-valuemax="100"><span class="ms-1">{{ (int)$task->progress }}%</span></div>
                                    </div>

                                    @if($task->updated_at)
                                    <div class="text-muted small">
                                        {{ __('Updated') }}:<br> {{ date_locale($task->updated_at, 'datetime') }}
                                    </div>
                                    @endif
                                </td>

                                <td>
                                    @if ($task->client_user_id)
                                        @if ($task->client_avatar)
                                            <span class="float-left mr-2"><img style="max-width:25px; height:auto;" src="{{ image($task->client_avatar) }}" /></span>
                                        @endif
                                        <h5>{{ $task->client_name }}</h5>
                                        {{ $task->client_email }}
                                    @else
                                        @if ($task->form_data_id)
                                        {{ __('Form sender') }}: <br>
                                        {{ $task->form_sender_name ?? '-' }}<br>
                                        {{ $task->form_sender_email ?? '-' }}
                                        @endif
                                    @endif
                                </td>                               

                                <td>
                                    @if ($task->priority == 3) <button class="btn btn-info btn-sm btn-block">{{ __('Low') }}</button> @endif
                                    @if ($task->priority == 2) <button class="btn btn-warning btn-sm btn-block">{{ __('Normal') }}</button> @endif
                                    @if ($task->priority == 1) <button class="btn btn-danger btn-sm btn-block">{{ __('Urgent') }}</button> @endif

                                    <div class="mb-2"></div>
                                    @if ($task->due_date)
                                        <b>{{ __('Due date') }}</b>:<br>
                                        <small>{{ date_locale($task->due_date) }}</small>
                                    @else
                                        {{ __('no due date') }}
                                    @endif
                                </td>                                
                                
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-primary btn-sm me-3" href="{{ route('admin.tasks.show', ['id' => $task->id]) }}"><i class="bi bi-pencil-square"></i></a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $task->id }}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                        <div class="modal fade confirm-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to delete this task?') }}
														<div class="fw-bold text-danger">{{ __('Warning: All task activity will be permanently deleted') }}</div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.tasks.show', ['id' => $task->id]) }}">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                            <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                 
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $tasks->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_priority' => $search_priority, 'search_source' => $search_source, 'orderBy' => $orderBy])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
