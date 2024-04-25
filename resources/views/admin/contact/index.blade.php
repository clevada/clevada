<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.contact') }}">{{ __('Contact messages') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


@if (($config->module_contact ?? null) == 'disabled')
    <div class="alert alert-danger">
        {{ __('Warning. Contact module is disabled.') }} <a href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Manage modules') }}</a>
    </div>
@else
    <div class="card">

        <div class="card-header">

            @if (($config->module_contact ?? null) == 'inactive')
                <div class="alert alert-danger">
                    {{ __('Warning. Contact module is not active. You can manege content, but module is not available on the website') }}. <a
                        href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Manage modules') }}</a>
                </div>
            @endif

            <div class="row">

                <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Contact messages') }} ({{ $count_messages_unread ?? 0 }} {{ __('unread') }}, {{ $messages->total() ?? 0 }} {{ __('total') }})</h4>
                </div>

                <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                    @if (Auth::user()->role == 'admin')
                        <div class="float-end">
                            <a href="{{ route('admin.contact.fields') }}" class="btn btn-primary me-1"><i class="bi bi-input-cursor"></i> {{ __('Manage custom fields') }}</a>
                            <a href="{{ route('admin.template', ['module' => 'contact']) }}" class="btn btn-primary ms-1"><i class="bi bi-laptop"></i> {{ __('Contact page template') }}</a>
                            <a href="{{ route('admin.recycle_bin.module', ['module' => 'contact']) }}" class="btn btn-secondary ms-1"><i class="bi bi-trash"></i> {{ __('Trash') }}</a>
                        </div>
                    @endif
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
                    @if ($message == 'replied')
                        {{ __('Reply sent') }}
                    @endif
                    @if ($message == 'deleted' || $message == 'delete')
                        {{ __('Deleted') }}
                    @endif
                    @if ($message == 'restored' || $message == 'restore')
                        {{ __('Restored') }}
                    @endif
                    @if ($message == 'moved_to_trash')
                        {{ __('Message moved to trash') }}
                    @endif
                    @if ($message == 'updated')
                        {{ __('Updated') }}
                    @endif
                </div>
            @endif

            @if ($config->contact_form_disabled ?? null)
                <div class="alert alert-light text-danger fw-bold mb-3"><i class="bi bi-exclamation-circle"></i> {{ __('Contact form is disabled.') }} <a
                        href="{{ route('admin.template', ['module' => 'contact']) }}">{{ __('Change') }}</a>
                </div>
            @endif

            @if ($messages->total() == 0)
                {{ __('No message') }}
            @else
                <section>
                    <form action="{{ route('admin.contact') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                        <div class="col-12">
                            <input type="text" name="search_terms" placeholder="{{ __('Search sender name or email') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif"
                                value="<?= $search_terms ?>" />
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
                            <a class="btn btn-light  mb-2" href="{{ route('admin.contact') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                        </div>

                    </form>
                </section>

                <div class="mb-2"></div>

                <form method="POST" action="{{ route('admin.contact.multiple_action') }}">
                    @csrf

                    <div class="table-responsive-md">
                        <table class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th width="20">
                                        <input type="checkbox" name="select-all" id="select-all" />
                                    </th>
                                    <th>{{ __('Details') }}</th>
                                    <th width="320">{{ __('Sender') }}</th>
                                    <th width="50"> </th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($messages as $message)
                                    <tr>
                                        <td>
                                            <input name='messages_checkbox[]' type='checkbox' id='messages_checkbox_{{ $message->id }}[]' value='{{ $message->id }}'>
                                        </td>
                                        <td>
                                            @if ($message->responded_at ?? null)
                                                <span class="float-end ms-2 badge bg-success">{{ __('Replied') }}</span>
                                            @endif
                                            @if ($message->is_important == 1)
                                                <span class="float-end ms-2 badge bg-warning"><i class="bi bi-star-fill"></i> {{ __('Important') }}</span>
                                            @endif

                                            <div class="fs-6">
                                                @if (!$message->read_at)
                                                    <span class="text-danger">[{{ __('Unread') }}]</span>: <a class="text-bold"
                                                        href="{{ route('admin.contact.show', ['id' => $message->id]) }}"><b>{{ $message->subject }}
                                                            ({{ $message->name }})
                                                        </b></a>
                                                @else
                                                    <a href="{{ route('admin.contact.show', ['id' => $message->id]) }}">{{ $message->name }} ({{ $message->email }})</a>
                                                @endif
                                            </div>

                                            @if ($message->task_id)
                                                @if ($message->task_closed_at)
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('Task closed') }}</a>
                                                @else<a class="btn btn-danger btn-sm mb-2" href="{{ route('admin.tasks.show', ['id' => $message->task_id]) }}">{{ __('Task not closed') }}</a>
                                                @endif
                                            @endif

                                            <div class="text-muted small">
                                                {{ date_locale($message->created_at, 'datetime') }}
                                                <br>
                                                IP: {{ $message->ip }}
                                            </div>
                                        </td>

                                        <td>
                                            @if ($message->name)
                                                <b>{{ $message->name }}</b>
                                            @else
                                                <span class="text-danger">{{ __('No name') }}</span>
                                            @endif
                                            <div class="mb-1"></div>
                                            @if ($message->email)
                                                {{ $message->email }}
                                            @else
                                                <span class="text-danger">{{ __('No email') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @can('delete', App\Models\Contact::class)
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('admin.contact.to_trash', ['id' => $message->id]) }}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                                </div>
                                            @endcan
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>


                    <div class="row row-cols-lg-auto g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="action" class="form-select" required>
                                    <option value="">- {{ __('With selected:') }} -</option>
                                    <option value="read">{{ __('Mark as read') }}</option>
                                    <option value="unread">{{ __('Mark as unread') }}</option>
                                    @can('delete', App\Models\Contact::class)
                                        <option value="trash">{{ __('Move to Recycle Bin') }}</option>
                                    @endcan
                                    <option value="important">{{ __('Mark as important') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="hidden" name="section" value="messages">
                            <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                        </div>
                    </div>
                </form>

                {{ $messages->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_replied' => $search_replied, 'search_important' => $search_important])->links() }}

            @endif
        </div>
        <!-- end card-body -->

    </div>

    <script language="JavaScript">
        $('#select-all').click(function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>

@endif
