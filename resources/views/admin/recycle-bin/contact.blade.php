<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.recycle_bin') }}">{{ __('Recycle Bin') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Contact messages') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Deleted contact messages') }} ({{ $items->total() ?? 0 }} {{ __('items') }})</h4>
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <a href="#" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#empty-trash"><i class="bi bi-trash"></i> {{ __('Empty this trash') }}</a>
                    @include('admin.recycle-bin.modals.empty-trash')
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
                @if ($message == 'restore')
                    {{ __('Item restored') }}
                @endif

                @if ($message == 'multiple_restore')
                    {{ __('Items restored') }}
                @endif

                @if ($message == 'delete')
                    {{ __('Item permanently deleted') }}
                @endif

                @if ($message == 'multiple_delete')
                    {{ __('Items permanently deleted') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.recycle_bin.module', ['module' => 'contact']) }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

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
                    <a class="btn btn-light  mb-2" href="{{ route('admin.recycle_bin.module', ['module' => 'contact']) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </section>

        <div class="mb-2"></div>

        @if ($items->total() == 0)
            {{ __('No item') }}
        @else
            <form method="POST" action="{{ route('admin.recycle_bin.multiple_action', ['module' => 'contact']) }}">
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
                                <th width="200">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($items as $message)
                                <tr>
                                    <td>
                                        <input name='items_checkbox[]' type='checkbox' id='items_checkbox_{{ $message->id }}[]' value='{{ $message->id }}'>
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
                                                <span class="text-danger">[{{ __('Unread') }}]</span>: <a class="text-bold" href="{{ route('admin.contact.show', ['id' => $message->id]) }}"><b>{{ $message->subject }}
                                                        ({{ $message->name }})
                                                    </b></a>
                                            @else
                                                <a href="{{ route('admin.contact.show', ['id' => $message->id]) }}">{{ $message->name }} ({{ $message->email }})</a>
                                            @endif
                                        </div>

                                        <div class="text-muted small">
                                            {{ date_locale($message->created_at, 'datetime') }}
                                            <br>
                                            IP: {{ $message->ip }}
                                            @if ($message->referer)
                                                <br>{{ __('Referer') }}: <a target="_blank" href="{{ $message->referer }}">{{ $message->referer }}</a>
                                            @endif
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

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'contact', 'id' => $message->id, 'return_to' => 'recycle_bin', 'action' => 'delete']) }}"
                                                class="btn btn-danger btn-sm mb-2">{{ __('Permanently delete') }}</a>

                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'contact', 'id' => $message->id, 'return_to' => 'recycle_bin', 'action' => 'restore']) }}"
                                                class="btn btn-success btn-sm">{{ __('Restore') }}</a>
                                        </div>

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
                                <option value="multiple_delete">{{ __('Permanently delete') }}</option>
                                <option value="multiple_restore">{{ __('Restore') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="return_to" value="recycle_bin">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </div>
            </form>

            {{ $items->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_replied' => $search_replied, 'search_important' => $search_important])->links() }}

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
