<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.forms') }}">{{ __('Forms') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Forms messages') }} ({{ $count_messages_unread ?? 0 }} {{ __('unread') }}, {{ $messages->total() ?? 0 }} {{ __('total') }})</h4>
                </div>

                <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        @if (logged_user()->role == 'admin')
                            <a href="{{ route('admin.forms.config') }}" class="btn btn-primary me-1 mb-1"><i class="bi bi-gear"></i> {{ __('Manage forms') }}</a>
                        @endif

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
                    @if ($message == 'replied') {{ __('Reply sent') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            <section>
                <form action="{{ route('admin.forms') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="{{ __('Search sender name or email') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                    </div>

                    <div class="col-12">
                        <select name="search_status" class="form-select me-2 mb-2 @if ($search_status) is-valid @endif">
                            <option value="">- {{ __('Any status') }} -</option>
                            <option @if ($search_status == 'unread') selected="selected" @endif value="unread">{{ __('Only unread messages') }}</option>
                            <option @if ($search_status == 'read') selected="selected" @endif value="read">{{ __('Only read messages') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select class="form-select me-2 mb-2 @if ($search_replied) is-valid @endif" name="search_replied">
                            <option name="search_replied" selected="selected" value="">- {{ __('All messages') }} -</option>
                            <option @if ($search_replied == 'no') selected="selected" @endif value="no">{{ __('Only messages without reply') }}</option>
                            <option @if ($search_replied == 'yes') selected="selected" @endif value="yes">{{ __('Only messages with reply') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select class="form-select me-2 mb-2 @if ($search_important) is-valid @endif" name="search_important">
                            <option name="search_important" selected="selected" value="">- {{ __('All messages') }} -</option>
                            <option @if ($search_important == '1') selected="selected" @endif value="1">{{ __('Only important messages') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light  mb-2" href="{{ route('admin.forms') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>

                </form>
            </section>

            <div class="mb-2"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="220">{{ __('Form') }}</th>
                            <th width="320">{{ __('Sender') }}</th>
                            <th width="50"> </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($messages as $message)
                            <tr>

                                <td>
                                    @if ($message->responded_at ?? null) <span class="float-end ms-2 badge bg-success">{{ __('Replied') }}</span> @endif
                                    @if ($message->is_important == 1) <span class="float-end ms-2 badge bg-warning text-white"><i class="bi bi-star-fill text-white"></i> {{ __('Important') }}</span> @endif

                                    <div class="listing">
                                    @if (!$message->read_at)                                        
                                            <span class="text-danger">[{{ __('Unread') }}]</span>: <a class="text-bold"
                                                href="{{ route('admin.forms.show', ['id' => $message->id]) }}"><b>{{ $message->subject }} ({{ $message->name }})</b></a>
                                        
                                    @else <a href="{{ route('admin.forms.show', ['id' => $message->id]) }}">{{ $message->name }} ({{ $message->email }})</a>
                                    @endif
                                    </div>

                                    @if($message->task_id)                                    
                                        @if($message->task_closed_at)<a class="btn btn-success btn-sm mb-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('Task closed') }}</a>
                                        @else<a class="btn btn-danger btn-sm mb-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('Task not closed') }}</a>@endif
                                    @endif

                                    <div class="text-muted small">
                                        {{ date_locale($message->created_at, 'datetime') }}
                                        <br>
                                        IP: {{ $message->ip }}
                                        @if ($message->referer)<br>{{ __('Referer') }}: <a target="_blank" href="{{ $message->referer }}">{{ $message->referer }}</a>@endif
                                    </div>
                                </td>

                                <td>
                                    <b>{{ $message->form_label ?? null }}</b>
                                </td>

                                <td>
                                    @if ($message->name)<b>{{ $message->name }}</b> @else <span class="text-danger">{{ __('No name') }}</span>@endif
                                    <div class="mb-1"></div>
                                    @if ($message->email){{ $message->email }} @else <span class="text-danger">{{ __('No email') }}</span>@endif
                                </td>

                                <td>

                                    <div class="d-grid gap-2">

                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $message->id }}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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

                                    </div>

                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            {{ $messages->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_replied' => $search_replied, 'search_important' => $search_important])->links() }}


        </div>
        <!-- end card-body -->

    </div>

</section>
