<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.recycle_bin') }}">{{ __('Recycle Bin') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Support tickets') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Deleted support tickets') }} ({{ $items->total() ?? 0 }} {{ __('items') }})</h4>
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
            <form action="{{ route('admin.recycle_bin.module', ['module' => 'tickets']) }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="{{ __('Search ticket') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                </div>

                <div class="col-12">
                    <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a class="btn btn-light  mb-2" href="{{ route('admin.recycle_bin.module', ['module' => 'tickets']) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </section>

        <div class="mb-2"></div>

        @if ($items->total() == 0)
            {{ __('No item') }}
        @else
            <form method="POST" action="{{ route('admin.recycle_bin.multiple_action', ['module' => 'tickets']) }}">
                @csrf

                <div class="table-responsive-md">
                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th width="20">
                                    <input type="checkbox" name="select-all" id="select-all" />
                                </th>
                                <th>{{ __('Details') }}</th>
                                <th width="280">{{ __('Client') }}</th>
                                <th width="220">{{ __('Department') }}</th>
                                <th width="200">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($items as $ticket)
                                <tr @if ($ticket->closed_at) class="table-light" @endif>
                                    <td>
                                        <input name='items_checkbox[]' type='checkbox' id='items_checkbox_{{ $ticket->id }}[]' value='{{ $ticket->id }}'>
                                    </td>

                                    <td>
                                        <div class="float-end ms-2">
                                            @if (!$ticket->last_response && !$ticket->closed_at)
                                                <div class="me-1 badge bg-danger fw-normal fs-6"><i class="bi bi-exclamation-triangle"></i> {{ __('New') }}</div>
                                            @endif
                                            @if ($ticket->closed_at)
                                                <div class="me-1 badge bg-dark fw-normal fs-6">{{ __('Closed') }}</div>
                                            @endif
                                            @if ($ticket->last_response == 'client' && !$ticket->closed_at)
                                                <div class="me-1 badge bg-warning fw-normal fs-6"><i class="bi bi-exclamation-triangle"></i>
                                                    {{ __('Waiting your response') }}</div>
                                            @endif
                                            @if ($ticket->last_response == 'operator' && !$ticket->closed_at)
                                                <div class="me-1 badge bg-info fw-normal fs-6"><i class="bi bi-clock"></i>
                                                    {{ __('Waiting client response') }}</div>
                                            @endif
                                        </div>

                                        <div class="@if (!$ticket->closed_at) fw-bold @endif fs-5">
                                            <a href="{{ route('admin.ticket.show', ['code' => $ticket->code]) }}">{{ $ticket->subject }}</a>
                                        </div>

                                        <div class="text-muted small">
                                            @if ($ticket->invoice_id)
                                                <b>{{ __('Ticket automatially created for invoice ') }} #<a
                                                        href="{{ route('admin.cart.invoices.show', ['id' => $ticket->invoice_id]) }}">{{ $ticket->invoice_code }}</a></b><br>
                                            @endif

                                            @if ($ticket->file)
                                                <div class="mt-2"></div>
                                                <a title="{{ $ticket->file }}" target="_blank" href="{{ asset('uploads/' . $ticket->file) }}"><i class="bi bi-file"></i>
                                                    {{ __('View attachment') }}</a>
                                                <div class="mb-2"></div>
                                            @endif
                                            {{ __('Created at') }}: {{ date_locale($ticket->created_at, 'datetime') }}<br>
                                            ID: {{ strtoupper($ticket->code) }}
                                            @if ($ticket->latest_response_created_at)
                                                <br>{{ __('Latest response') }}: {{ date_locale($ticket->latest_response_created_at, 'datetime') }}
                                            @endif
                                            @if ($ticket->closed_at)
                                                <br>{{ __('Closed at') }}: {{ date_locale($ticket->closed_at, 'datetime') }}
                                            @endif
                                            @if ($ticket->closed_by_user_name)
                                                <br>{{ __('Closed by') }}: {{ $ticket->closed_by_user_name }}
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="float-start me-2"><img style="max-width:28px; height:auto;" src="{{ avatar($ticket->client->id) }}" /></div>

                                        <b>{{ $ticket->client->name }}</b>
                                       
                                    </td>

                                    <td>
                                        <b>{{ $ticket->department->title }}</b>
                                    </td>
                                   
                                    <td>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'tickets', 'id' => $ticket->id, 'return_to' => 'recycle_bin', 'action' => 'delete']) }}"
                                                class="btn btn-danger btn-sm mb-2">{{ __('Permanently delete') }}</a>

                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'tickets', 'id' => $ticket->id, 'return_to' => 'recycle_bin', 'action' => 'restore']) }}"
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
