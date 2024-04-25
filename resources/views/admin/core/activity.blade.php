<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-1">
                @include('admin.core.layouts.menu-dashboard')
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
                @if ($message == 'created')
                    {{ __('Created') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif      

        <div class="mb-2 fs-5 fw-bold"><i class="bi bi-activity"></i> {{ __('Activity log') }} ({{ $logs->total() }})</div>
       
        <div class="alert alert-light">
            {{ __('Here you can see all activity from users (internal accounts, registered users)') }}
        </div>

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="380">{{ __('User') }}</th>
                        <th width="200">{{ __('User role') }}</th>
                        <th width="220">{{ __('Date') }}</th>
                        <th width="220">{{ __('Module') }}</th>
                        <th>{{ __('Details') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($logs as $log)
                        @php
                            $data = json_decode($log->data);
                        @endphp
                        <tr>

                            <td>
                                <span class="float-start me-2"><img style="max-width:50px; height:auto;" class="img-fluid rounded-circle" src="{{ avatar($log->user_id) }}" /></span>

                                <a href="{{ route('admin.accounts.show', ['id' => $log->user_id]) }}" class="fw-bold">{{ $log->user->name }}</a>
                                <br>
                                {{ $log->user->email }}
                            </td>

                            <td>
                                @if ($log->user->role == 'admin')
                                    {{ __('Administrator') }}
                                @elseif($log->user->role == 'internal')
                                    {{ __('Internal account') }}
                                @elseif($log->user->role == 'external')
                                    {{ __('External account') }}
                                @elseif($log->user->role == 'user')
                                    {{ __('Registered user') }}
                                @else
                                    {{ $log->user->role }}
                                @endif
                            </td>

                            <td>
                                {{ date_locale($log->created_at, 'datetime') }}
                            </td>

                            <td>
                                @switch($log->module)
                                    @case('tickets')
                                        {{ __('Support tickets') }}
                                    @break

                                    @case('docs')
                                        {{ __('Knowledge Base') }}
                                    @break

                                    @case('forum')
                                        {{ __('Community forum') }}
                                    @break

                                    @case('workspaces')
                                        {{ __('Workspaces') }}
                                    @break

                                    @case('pages')
                                        {{ __('Website page') }}
                                    @break

                                    @case('posts')
                                        {{ __('Website post') }}
                                    @break

                                    @case('vault')
                                        {{ __('Vault') }}
                                    @break

                                    @default
                                        {{ $log->module }}
                                @endswitch
                            </td>

                            <td>
                                @if ($log->module == 'tickets')
                                    @if ($log->activity == 'reply')
                                        <b>{{ __('Reply to ticket') }}</b>
                                    @endif

                                    @if ($log->activity == 'create')
                                        <b>{{ __('Ticket created') }}</b>
                                    @endif

                                    @if ($log->activity == 'update')
                                        <b>{{ __('Ticket updated') }}</b>
                                    @endif

                                    @if ($log->activity == 'close')
                                        <b>{{ __('Ticket closed') }}</b>
                                    @endif

                                    @if ($log->activity == 'open')
                                        <b>{{ __('Ticket reopened') }}</b>
                                    @endif

                                    @if ($log->activity == 'trash')
                                        <b>{{ __('Ticket moved to trash') }}</b>
                                    @endif

                                    @if ($log->activity == 'delete_response')
                                        <b>{{ __('Ticket response deleted') }}</b>
                                    @endif

                                    @if ($log->activity == 'cf_create')
                                        <b>{{ __('Custom field created') }}</b>
                                    @endif

                                    @if ($log->activity == 'cf_update')
                                        <b>{{ __('Custom field updated') }}</b>
                                    @endif

                                    @if ($log->activity == 'cf_delete')
                                        <b>{{ __('Custom field deleted') }}</b>
                                    @endif

                                    @if ($data->ticket_code ?? null)
                                        <br>
                                        <i class="bi bi-box-arrow-right"></i> <a href="{{ route('admin.ticket.show', ['code' => $data->ticket_code]) }}">{{ __('Ticket details') }}</a>
                                    @endif
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $logs->links() }}

    </div>
    <!-- end card-body -->

</div>
